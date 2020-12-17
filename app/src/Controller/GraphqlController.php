<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GraphqlController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function mainEndpoint(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      // your code to access items in the container... $this->container->get('');
      
      
      return $response;
    }

    public function refsEndpoint(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      // your code to access items in the container... $this->container->get('');
      
      
      return $response;
    }
}