<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Error\DebugFlag;
use GraphQL\Error\FormattedError;

use App\Boilerplate\Endpoint;

class GraphqlController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function blogEndpoint(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // $this->container->get('');
        return (new Endpoint($response, DebugFlag::INCLUDE_TRACE))
            ->executeSchema([
                'lookupDirectories' => [
                    __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'GraphQL'.DIRECTORY_SEPARATOR.'Schema'.DIRECTORY_SEPARATOR.'blog',
                ],
                'lookupExtensions'=> ['php'],
                'isLookupRecursive'=> true,
                'lookupExcludePaths' => [
                    __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'GraphQL'.DIRECTORY_SEPARATOR.'Schema'.DIRECTORY_SEPARATOR.'blog'.DIRECTORY_SEPARATOR.'Data',
                ],
            ])
        ;
    }

    public function refsEndpoint(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        throw new \Exception('Not Implemented Yet');
        // your code to access items in the container... $this->container->get('');
        return (new Endpoint($response, DebugFlag::INCLUDE_TRACE))
            ->executeSchema('refs')
        ;
    }
}
