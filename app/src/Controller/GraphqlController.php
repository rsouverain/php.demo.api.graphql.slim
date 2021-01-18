<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Error\DebugFlag;
use GraphQL\Error\FormattedError;

use App\Boilerplate\GraphQL\Endpoint;

/**
 * Class GraphqlController
 * @package App\Controller
 */
class GraphqlController
{
    /** @var ContainerInterface  */
    private $container;

    /**
     * GraphqlController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        \GraphQL\Error\FormattedError::setInternalErrorMessage('An error occurred while resolving your query');
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws \App\Boilerplate\GraphQL\Exception\PersistedQueryNotFoundException
     * @throws \App\Boilerplate\GraphQL\Exception\PersistedQueryNotSupportedException
     */
    public function demoEndpoint(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return (new Endpoint($request, $response, DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE))
            ->executeSchema([
                'schemaFilePath' => __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'GraphQL'.DIRECTORY_SEPARATOR.'Schema'.DIRECTORY_SEPARATOR.'demo'.DIRECTORY_SEPARATOR.'Demo.schema.php',
            ])
        ;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws \App\Boilerplate\GraphQL\Exception\PersistedQueryNotFoundException
     * @throws \App\Boilerplate\GraphQL\Exception\PersistedQueryNotSupportedException
     */
    public function blogEndpoint(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return (new Endpoint($request, $response, DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE))
            ->executeSchema([
                'schemaFilePath' => __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'GraphQL'.DIRECTORY_SEPARATOR.'Schema'.DIRECTORY_SEPARATOR.'blog'.DIRECTORY_SEPARATOR.'Blog.schema.php',
            ])
        ;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws \App\Boilerplate\GraphQL\Exception\PersistedQueryNotFoundException
     * @throws \App\Boilerplate\GraphQL\Exception\PersistedQueryNotSupportedException
     */
    public function refsEndpoint(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        throw new \Exception('Not Implemented Yet');
        // your code to access items in the container... $this->container->get('');
        return (new Endpoint($request, $response, DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE))
            ->executeSchema('refs')
        ;
    }
}
