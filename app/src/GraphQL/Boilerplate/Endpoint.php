<?php

namespace App\GraphQL\Boilerplate;

use Psr\Http\Message\ResponseInterface;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use GraphQL\Error\DebugFlag;

use App\GraphQL\Boilerplate\SchemaLoader;
use App\GraphQL\Exception\GenericGraphQlException;

class Endpoint
{
    protected $response;
    protected $isDebugMode = false;
    
    public function __construct($response = null, int $debugFlag = 0)
    {
        $this->response = $response;
        $this->isDebugMode = (bool) ($debugFlag > 0);
        $this->debugFlag = $debugFlag;
    }

    /**
     * Setup the response object or string with your graphql result output.
     */
    protected function setupResponse ($output, int $httpCode = 200)
    {
        if ($this->response instanceof ResponseInterface) {

            $this->response->getBody()->write(json_encode($output));
            $this->response = $this->response
                ->withHeader('Content-type', 'application/json')
                ->withStatus($httpCode);
            ;
        } else {
            header('Content-Type: application/json');
            $this->response = json_encode($output);
        }
        return $this;
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
        ];
        return $data;
    }

    /**
     * errorFormatter is responsible for converting instances of `GraphQL\Error\Error` to an array.
     * 
     * @see https://webonyx.github.io/graphql-php/error-handling/
     */
    protected function errorFormatter (Error $error) {
        return FormattedError::createFromException($error, $this->debugFlag);
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
    protected function generateOutputError (array $exceptionList)
    {
        $output = [
            'errors' => [],
        ];
        foreach ($exceptionList as $ex) {
            $error = null;

            if ($ex instanceof GenericGraphQlException) {
                $error = FormattedError::createFromException($ex, $this->debugFlag);
                $error['message'] = $ex->getMessage();
                if (isset($error['trace'])) {
                    $error['debug'] = (string) $ex;
                }
                $error['class'] = get_class($ex);
            }
            elseif ($ex instanceof \Exception) {
                $error = FormattedError::createFromException($ex, $this->debugFlag);
            } elseif (is_string($ex)) {
                $error = [
                    'message' => $ex->getMessage(),
                    'trace' => null,
                ];
            }

            if (isset($error)) {
                $output['errors'][] = $error;
            }
        }
        return $output;
    }

    protected function getSchema (string $schemaName)
    {
        // @TODO refactor use of schemaloader to be outside of Endpoint (Controller?)
        $schemaLoader = new SchemaLoader(
            [
                __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Schema'.DIRECTORY_SEPARATOR.'blog',
            ],
            [
                //'gql.php',
                'php',
            ]
        );
        $schemaLoader
            ->addLookupExclusions([
                //__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Schema/blog/Type/StoryType.php',
                __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Schema/blog/Data',
            ])
            ->lookup()
        ;
    }

    /**
     * Executing the stuff required when the endpoint is reached
     * @param string schemaName name of the GraphQL schema directory to load
     */
    public function executeSchema(string $loadSchemaName)
    {
        $data = $this->getInputData();

        try {
            $output = GraphQL::executeQuery($this->getSchema($loadSchemaName), $data['query'], $rootValue, $contextValue, (array) $data['variables'])
                ->setErrorFormatter($this->errorFormatter)
                ->setErrorsHandler($this->errorHandler)
                ->toArray()
            ;
            $httpCode = 200;
        } catch (\Exception $ex) {
            // handle custom code exceptions 404, 403, etc.
            $httpCode = ($ex->getCode() >= 100) ? $ex->getCode() : 500;
            $output = $this->generateOutputError([$ex]);
        }
        $this->setupResponse($output, $httpCode);
        return $this->response;
    }
}
