<?php

namespace App;

use App\Container\Container;
use App\Exceptions\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class Application extends Container
{
    private array $routes = [];

    /**
     * @param $uri
     * @param $action
     *
     * @return $this
     */
    public function setRoute($uri, $action): static
    {
        $this->routes[$uri] = $action;

        return $this;
    }

    /**
     * @throws ReflectionException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run()
    {
//        $currentUri = $_REQUEST["route"];

        $currentUri = '/products';

        if(array_key_exists($currentUri, $this->routes)) {
            $action = $this->routes[$currentUri];

            if(is_array($action)) {

                $controller = $action[0];
                $method = $action[1];

                $this->register($controller, $controller);
                $instance = $this->get($controller);
                echo $instance->$method();
            } else {
                if(is_callable($action)) {
                    $callbackId = get_class($action) . "_" . uniqid();
                    $this->register($callbackId, $action);
                    echo $this->get($callbackId);
                }
            }

        }
    }
}