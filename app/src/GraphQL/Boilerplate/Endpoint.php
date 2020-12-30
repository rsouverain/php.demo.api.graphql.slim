<?php

namespace App\GraphQL\Boilerplate;

use Psr\Http\Message\ResponseInterface;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use GraphQL\Error\DebugFlag;

use App\GraphQL\Boilerplate\SchemaLoader;
use App\GraphQL\Boilerplate\AutomaticPersistedQueries;
use App\GraphQL\Exception\GenericGraphQlException;
use App\GraphQL\Exception\ExtensionException;

class Endpoint
{
    protected $response;
    protected $isDebugMode = false;
    protected static $debugFlag = 0;
    
    public function __construct($response = null, int $debugFlag = 0)
    {
        $this->response = $response;
        $this->isDebugMode = (bool) ($debugFlag > 0);
        self::$debugFlag = $debugFlag;
        $this->APQ = new AutomaticPersistedQueries(300);
    }

    /**
     * Setup the response object or string with your graphql result output.
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
     * errorFormatter is responsible for converting instances of `GraphQL\Error\Error` to an array.
     * 
     * @see https://webonyx.github.io/graphql-php/error-handling/
     */
    protected function errorFormatter (Error $error) {
        return FormattedError::createFromException($error, self::$debugFlag);
    }
    
    /**
     * errorHandler is useful for error filtering and logging.
     * 
     * @see https://webonyx.github.io/graphql-php/error-handling/
     */
    protected function errorHandler (array $errors, callable $formatter) {
        return array_map($formatter, $errors);
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

    protected function getSchema (array $lookupSchemaOptions = [])
    {
        // @TODO refactor use of schemaloader to be outside of Endpoint (Controller?)
        $schemaLoader = new SchemaLoader(
            isset($lookupSchemaOptions['lookupDirectories']) ? (array) $lookupSchemaOptions['lookupDirectories'] : null,
            isset($lookupSchemaOptions['lookupExtensions']) ? (array) $lookupSchemaOptions['lookupExtensions'] : ['php'],
            isset($lookupSchemaOptions['isLookupRecursive']) ? (bool) $lookupSchemaOptions['isLookupRecursive'] : true
        );
        if (
            isset($lookupSchemaOptions['lookupExcludePaths'])
            && is_array($lookupSchemaOptions['lookupExcludePaths'])
            && count($lookupSchemaOptions['lookupExcludePaths']) > 0
        ) {
            $schemaLoader
                ->addLookupExclusions([
                    //__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Schema/blog/Type/StoryType.php',
                    __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Schema/blog/Data',
                ])
            ;
        }
        return $schemaLoader->lookup();
    }

    /**
     * Executing the stuff required when the endpoint is reached
     * @param array|null lookupSchemaOptions
     * @see https://webonyx.github.io/graphql-php/reference/#graphqlserveroperationparams
     */
    public function executeSchema(array $lookupSchemaOptions)
    {
        $data = $this->getInputData();

        $persistedQuery = $this->APQ->onRequestRecieved(
            (string) $data['query'],
            (array) $data['extensions'],
            (array) $data['variables'],
        );

        try {
            $output = GraphQL
                ::executeQuery(
                    $this->getSchema($lookupSchemaOptions),
                    gettype($persistedQuery) === 'string' ? $persistedQuery : $data['query'],
                    $rootValue,
                    $contextValue,
                    (array) $data['variables']
                )
                ->setErrorFormatter($this->errorFormatter)
                ->setErrorsHandler($this->errorHandler)
                ->toArray()
            ;
            $httpCode = 200;
        }
        catch (\GenericGraphQlException $ex) {
            $httpCode = $ex->isHttpCode ? $ex->getCode() : 500;
            $output = self::generateOutputError([$ex]);
        }
        catch (\Exception $ex) {
            $httpCode = ($ex->getCode() >= 100) ? $ex->getCode() : 500;
            $output = self::generateOutputError([$ex]);
        }
        $this->response = self::setupResponse($this->response, $output, $httpCode);
        return $this->response;
    }
}
