<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Error\DebugFlag;
use GraphQL\Error\FormattedError;

use App\GraphQL\Boilerplate\Endpoint;

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
            ->executeSchema('blog')
        ;
    }

    public function refsEndpoint(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // your code to access items in the container... $this->container->get('');
        return (new Endpoint($response, DebugFlag::INCLUDE_TRACE))
            ->executeSchema('refs')
        ;
    }
}
