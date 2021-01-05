<?php

require __DIR__ . '/../../vendor/autoload.php';

use DI\Container;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;

use App\Boilerplate\Endpoint;
use App\Boilerplate\GraphQL\Exception\GenericGraphQlException;

// Create Container using PHP-DI
$container = new Container();

// Set container to create App with on AppFactory
AppFactory::setContainer($container);

// Creating the app kernel
$app = AppFactory::create();

// Add Routing Middleware
$app->addRoutingMiddleware();

/**
 * Middleware to handle removing trailing slashes (/) in route URLs
 * @see https://www.slimframework.com/docs/v4/cookbook/route-patterns.html
 */
$app->add(function (Request $request, RequestHandler $handler) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    
    if ($path != '/' && substr($path, -1) == '/') {
        // recursively remove slashes when its more than 1 slash
        $path = rtrim($path, '/');

        // permanently redirect paths with a trailing slash
        // to their non-trailing counterpart
        $uri = $uri->withPath($path);
        
        if ($request->getMethod() == 'GET') {
            $response = new Response();
            return $response
                ->withHeader('Location', (string) $uri)
                ->withStatus(301);
        } else {
            $request = $request->withUri($uri);
        }
    }

    return $handler->handle($request);
});


// Define Custom JSON Error Handler
$graphQlErrorHandler = function (
    Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails,
    ?LoggerInterface $logger = null
) use ($app) {
    if (isset($logger)) {
        $logger->error($exception->getMessage());
    }
    
    $response = $app->getResponseFactory()->createResponse();
    $output = Endpoint::generateOutputError([$exception]);
    $httpCode = 500;
    if ($exception instanceof GenericGraphQlException) {
        $httpCode = $exception->isHttpCode ? $exception->getCode() : $httpCode;
    }
    $response = Endpoint::setupResponse($response, $output, $httpCode);
    return $response;
};

/**
 * The routing middleware should be added earlier than the ErrorMiddleware
 * Otherwise exceptions thrown from it will not be handled by the middleware
 * @see https://www.slimframework.com/docs/v4/middleware/error-handling.html
 * 
 * @param bool $displayErrorDetails -> Should be set to false in production
 * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool $logErrorDetails -> Display error details in error log
 * which can be replaced by a callable of your choice.
 * @param \Psr\Log\LoggerInterface $logger -> Optional PSR-3 logger to receive errors
 * 
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true); // TODO gql format detection
$errorMiddleware->setDefaultErrorHandler($graphQlErrorHandler);
//$errorHandler = $errorMiddleware->getDefaultErrorHandler();
//$errorHandler->forceContentType('application/json');

// Demo index route
$app->redirect('/', '/hello', 301);

// Demo 'hello world' route
$app->any('/hello', function (Request $request, ResponseInterface $response, $args) {
    $nowUTC = new \DateTime('now', new \DateTimeZone('UTC'));
    $nowFR = clone($nowUTC);
    $nowFR->setTimezone(new \DateTimeZone('Europe/Paris'));
    $response->getBody()->write("Hello World !! current DateTime in Europe/Paris(ISO8601): ".$nowFR->format(\DateTimeInterface::ISO8601));
    return $response;
});

// Demo graphql endpoints
$app->map(['GET', 'POST'], '/graphql/blog', \App\Controller\GraphqlController::class.':blogEndpoint');
$app->any('/graphql/refs', \App\Controller\GraphqlController::class.':refsEndpoint');

$app->run();
