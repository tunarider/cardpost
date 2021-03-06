<?php
namespace Tunacan\Route;

use Psr\Container\ContainerInterface;
use Tunacan\Http\Request;
use Tunacan\Core\InterceptorInterface;
use Tunacan\MVC\ControllerInterface;

class Route implements RouteInterface
{
    private $path;
    private $controller;
    private $method;
    private $options;
    private $arguments;
    private $redirect;
    private $interceptor;

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path = null): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getController(ContainerInterface $c): ControllerInterface
    {
        return $c->get($this->controller);
    }

    /**
     * @param string $controller
     */
    public function setController(string $controller = null): void
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method = null): void
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = null): void
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return ($this->arguments) ?: [];
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments = null): void
    {
        $this->arguments = $arguments;
    }

    public function isRedirect(): bool
    {
        return (!is_null($this->redirect));
    }

    /**
     * @return mixed
     */
    public function getRedirect(): string
    {
        return $this->redirect;
    }

    /**
     * @param mixed $redirect
     */
    public function setRedirect($redirect = null): void
    {
        $this->redirect = $redirect;
    }

    public function hasInterceptor(): bool
    {
        return is_callable($this->interceptor);
    }

    public function getInterceptor(ContainerInterface $c): InterceptorInterface
    {
        $callback = $this->interceptor;
        return $callback($c);
    }

    public function setInterceptor(callable $interceptor): void
    {
        $this->interceptor = $interceptor;
    }

    public function match(Request $request)
    {
        $requestURI = $request->getServerInfo('REQUEST_URI');
        $requestURIList = explode(
            '/',
            preg_replace('/[\/]+/', '/', $requestURI)
        );
        $matchFactor = explode('/', explode('?', $this->path)[0]);
        array_shift($requestURIList);
        array_shift($matchFactor);
        if ($this->compare($matchFactor, $requestURIList)) {
            preg_match_all('/[\/\?]?:?([a-zA-Z0-9]+)/', $this->path, $matches);
            for ($i = 0; $i < sizeof($matches[0]); $i++) {
                if (strpos($matches[0][$i], ':') === false) {
                    continue;
                } else {
                    $this->arguments[$matches[1][$i]] = $requestURIList[$i];
                }
            }
            return true;
        } else {
            return false;
        }
    }

    private function compare(array $matchFactor, array $requestURIList)
    {
        if (sizeof($matchFactor) > sizeof($requestURIList)) {
            return false;
        }
        for ($i = 0; $i < sizeof($matchFactor); $i++) {
            if (preg_match('/:[a-zA-Z0-9]+/', $matchFactor[$i]) === 0
                && $matchFactor[$i] !== $requestURIList[$i]
            ) {
                return false;
            }
        }
        return true;
    }
}
