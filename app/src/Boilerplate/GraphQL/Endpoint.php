<?php

namespace App\Boilerplate\GraphQL;

use App\Boilerplate\AppContext;
use App\GraphQL\Schema\_common\Data\UserAccount\Repository\UserAccountRepository;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Executor\Promise\Adapter\SyncPromiseAdapter;
use GraphQL\Executor\Promise\Promise;
use Overblog\PromiseAdapter\Adapter\WebonyxGraphQLSyncPromiseAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use GraphQL\Error\DebugFlag;

use App\Boilerplate\GraphQL\Exception\GenericGraphQlException;
use App\Boilerplate\GraphQL\Exception\ExtensionException;
use App\Boilerplate\GraphQL\Exception\PersistedQueryNotFoundException;
use App\Boilerplate\GraphQL\Exception\PersistedQueryNotSupportedException;


/**
 * Class Endpoint
 * @package App\Boilerplate\GraphQL
 */
class Endpoint
{
    /** @var ServerRequestInterface  */
    protected $request;

    /** @var null  */
    protected $response;

    /** @var bool  */
    protected $isDebugMode = false;

    /** @var int  */
    protected static $debugFlag = 0;

    /** @var WebonyxGraphQLSyncPromiseAdapter  */
    public static $promiseAdapter;

    /**
     * Endpoint constructor.
     * @param ServerRequestInterface $request
     * @param null $response
     * @param int $debugFlag
     */
    public function __construct(ServerRequestInterface $request, $response = null, int $debugFlag = 0)
    {
        $this->request = $request;
        $this->response = $response;
        $this->isDebugMode = (bool) ($debugFlag > 0);
        self::$debugFlag = $debugFlag;
        $this->APQ = new AutomaticPersistedQueries();

        if (!(self::$promiseAdapter instanceof WebonyxGraphQLSyncPromiseAdapter)) {
            $graphQLPromiseAdapter = new SyncPromiseAdapter();
            self::$promiseAdapter = new WebonyxGraphQLSyncPromiseAdapter($graphQLPromiseAdapter);
            GraphQL::setPromiseAdapter($graphQLPromiseAdapter);
        }
    }

    /**
     * Setup the response object or string with your graphql result output.
     *
     * @param ResponseInterface|null $response
     * @param mixed $output
     * @param int $httpCode
     * @return false|ResponseInterface|string
     */
    public static function setupResponse ($response, $output, int $httpCode = 200)
    {
        if ($response instanceof ResponseInterface) {

            $response->getBody()->write(json_encode($output));
            $response = $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus($httpCode);
            ;
        } else {
            header('Content-Type: application/json');
            $response = json_encode($output);
        }
        return $response;
    }

    /**
     * Retrieve input datas to determine the graphql query or mutation employed.
     *
     * @return array
     */
    protected function getInputData ()
    {
        if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            $data = json_decode((file_get_contents('php://input') ?: ''), true) ?: [];
        } else {
            $data = $_REQUEST;
        }

        $data += [
            'query' => null,
            'variables' => null,
            'extensions' => [],
        ];
        return $data;
    }


    /**
     * Utility to generate graphql-compatible formated output errors
     *
     * @see https://webonyx.github.io/graphql-php/error-handling/#errors-in-graphql
     */
    public static function generateOutputError (array $exceptionList, array $extensions = [])
    {
        $output = [
            'errors' => [],
        ];
        foreach ($exceptionList as $ex) {
            $error = null;
            
            if ($ex instanceof ExtensionException) {
                $error = FormattedError::createFromException($ex, self::$debugFlag);
                $extensions = array_merge($extensions, $ex->getExtensions());
            }
            elseif ($ex instanceof GenericGraphQlException) {
                $error = FormattedError::createFromException($ex, self::$debugFlag);
            }
            elseif ($ex instanceof \Exception) {
                $error = FormattedError::createFromException($ex, self::$debugFlag);
            }
            elseif ($ex instanceof \Error) {
                $error = FormattedError::createFromException($ex, self::$debugFlag);
            }
            
            if (is_string($ex)) {
                $error = [
                    'message' => $ex->getMessage(),
                    'trace' => null,
                ];
            }
            else {
                $error['message'] = $ex->getMessage();
                if (isset($error['trace'])) {
                    $error['debug'] = (string) $ex;
                }
                $error['class'] = get_class($ex);
    
            }
            
            if (count($extensions) > 0) {
                $error['extensions'] = array_merge(
                    isset($error['extensions']) ? $error['extensions'] : [],
                    $extensions
                );
            }

            if (isset($error)) {
                $output['errors'][] = $error;
            }
            
        }
        return $output;
    }

    /**
     * @param Schema|array $lookupSchemaOptions
     * @return Schema|null
     * @throws Exception\FileNotFoundException
     * @TODO fix return type to load Schema
     */
    protected function getSchema ($lookupSchemaOptions)
    {
        $schemaLoader = null;

        if (isset($lookupSchemaOptions['schemaFilePath'])) {
            return require_once($lookupSchemaOptions['schemaFilePath']);
        }
        elseif (isset($lookupSchemaOptions['lookupDirectories']) || isset($lookupSchemaOptions['lookupExtensions']) || isset($lookupSchemaOptions['isLookupRecursive'])) {
            $schemaLoader = new SchemaLoader(
                isset($lookupSchemaOptions['lookupDirectories']) ? (array) $lookupSchemaOptions['lookupDirectories'] : null,
                isset($lookupSchemaOptions['lookupExtensions']) ? (array) $lookupSchemaOptions['lookupExtensions'] : ['schema.php'],
                isset($lookupSchemaOptions['isLookupRecursive']) ? (bool) $lookupSchemaOptions['isLookupRecursive'] : false
            );
            if (
                isset($lookupSchemaOptions['lookupExcludePaths'])
                && is_array($lookupSchemaOptions['lookupExcludePaths'])
                && count($lookupSchemaOptions['lookupExcludePaths']) > 0
            ) {
                $schemaLoader->addLookupExclusions($lookupSchemaOptions['lookupExcludePaths']);
            }
            return $schemaLoader->load();
        }

        return null;
    }

    /**
     * Executing the stuff required when the endpoint is reached
     *
     * @see https://webonyx.github.io/graphql-php/reference/#graphqlserveroperationparams
     *
     * @param array|null $lookupSchemaOptions
     * @return false|ResponseInterface|string|null
     * @throws PersistedQueryNotFoundException
     * @throws PersistedQueryNotSupportedException
     *
     * @return ResponseInterface
     */
    public function executeSchema(array $lookupSchemaOptions)
    {
        $data = $this->getInputData();

        $persistedQuery = $this->APQ->onRequestRecieved(
            (string) $data['query'],
            (array) $data['extensions'],
            (array) $data['variables'],
        );

        $output = null;
        try {
            $rootValue = null;
            $contextValue = AppContext::getInstance();
            $contextValue->setRequest($this->request);
            $this->hydrateContextWithAuthenticatedUser();

            // $debug = DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE;
            $httpCode = 200;
            $execResult = GraphQL::executeQuery(
//                new SyncPromiseAdapter(),
                $this->getSchema($lookupSchemaOptions),
                gettype($persistedQuery) === 'string' ? $persistedQuery : $data['query'],
                $rootValue,
                $contextValue,
                (array) $data['variables']
            );
//            $promise->then(function (ExecutionResult $execResult) use (&$httpCode) {
                $output = $execResult
                    ->setErrorFormatter(function (Error $error) use (&$httpCode) {
                        // @see https://webonyx.github.io/graphql-php/error-handling/
                        if ($error->getPrevious() instanceof GenericGraphQlException && $error->getPrevious()->isHttpCode) {
                            $httpCode = $error->getPrevious()->getCode();
                        }
                        return FormattedError::createFromException($error, self::$debugFlag);
                    })
                    ->setErrorsHandler(function (array $errors, callable $formatter) {
                        return array_map($formatter, $errors);
                    })
                    ->toArray(self::$debugFlag)
                ;
//                return $output;
//            });
//            $output = self::$promiseAdapter->await($promise, true);
        }
        catch (GenericGraphQlException $ex) {
            $httpCode = $ex->isHttpCode ? $ex->getCode() : 500;
            $output = self::generateOutputError([$ex]);
        }
        catch (\Exception $ex) {
            $httpCode = ($ex->getCode() >= 100) ? $ex->getCode() : 500;
            $output = self::generateOutputError([$ex]);
        }

        if ($output !== null) {
            $this->response = self::setupResponse($this->response, $output, $httpCode);
        }
        return $this->response;
    }

    protected function hydrateContextWithAuthenticatedUser ()
    {
        $token = null;
        $user = null;
        if ($this->request instanceof ServerRequestInterface) {
            $lookupCookie = 'token';
            $lookupHeader = 'Authorization';
            $headerRegexp = "/Bearer\s+(.*)$/i";
            $scheme = $this->request->getUri()->getScheme();
            $host = $this->request->getUri()->getHost();
            $header = $this->request->getHeaderLine($lookupHeader);

            $matches = null;
            if (false === empty($header)) {
                if (preg_match($headerRegexp, $header, $matches)) {
//                        $this->log(LogLevel::DEBUG, "Using token from request header");
                    $token = $matches[1];
                }
            }

            /* Token not found in header try a cookie. */
            $matches = null;
            if ($token === null) {
                $cookieParams = $this->request->getCookieParams();
                if (isset($cookieParams[$lookupCookie])) {
//                        $this->log(LogLevel::DEBUG, "Using token from cookie");
                    if (preg_match($headerRegexp, $cookieParams[$lookupCookie], $matches)) {
                        $token = $matches[1];
                    }
                }
            }

            if ($token !== null) {
                // don't do this with base 64 in real life
                $userId = base64_decode($token);
                $user = UserAccountRepository::getInstance()->fetchByProperty($userId);
                AppContext::getInstance()->setAuthenticatedUserAccount($user);
            }

        }
        return $this;
    }
}
