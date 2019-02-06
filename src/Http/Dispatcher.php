<?php

namespace Tunacan\Http;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Tunacan\Core\RequestHandlerInterface;
use Tunacan\Route\RouterInterface;
use Tunacan\Util\PageResolver;

class Dispatcher implements RequestHandlerInterface
{
    /**
     * @var ContainerInterface
     */
    private $delegateContainer;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /** @var PageResolver $resolver */
    private $resolver;

    public function __construct(
        ContainerInterface $container,
        RouterInterface $router,
        PageResolver $resolver,
        LoggerInterface $logger = null
    ) {
        $this->delegateContainer = $container;
        $this->router = $router;
        $this->resolver = $resolver;
        $this->logger = $logger;
    }

    public function handle(Request $request, Response $response)
    {
        $route = $this->router->getRoute($request);
        if ($route->isRedirect()) {
            header("Location: {$route->getRedirect()}");
            exit;
        } else {
            $controller = $this->delegateContainer->get($route->getControllerFqn());
            $method = $route->getMethod();
            $request->addUriArgumentsList($route->getArguments());
            $page = $controller->$method();
            $response->send();
            echo $this->resolver->resolve($page, $response);
        }
    }
}
