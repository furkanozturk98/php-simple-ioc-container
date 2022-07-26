<?php

namespace App\Container;

use App\Exceptions\NotFoundException;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;

class Container implements ContainerInterface
{
    private array $services = [];

    /**
     * @param string $key
     * @param $value
     *
     * @return $this
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function register(string $key, $value): static
    {
        $this->services[$key] = $this->resolveDependency($value);
        return $this;
    }

    /**
     * @param string $id
     *
     * @return mixed
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    public function get(string $id): mixed
    {
        try {
            if(!isset($this->services[$id])) {
                $this->services[$id] = $this->resolveDependency($id);
            }

            return $this->services[$id];
        } catch (ReflectionException | Exception $ex) {
            throw new NotFoundException($ex->getMessage());
        }
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * @return array
     */
    public function getServices(): array
    {
        return $this->services;
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function resolveDependency($item): object
    {
        // if item a callback
        if(is_callable($item)) {
            return $item();
        }

        // if item a class
        $reflectionItem = new ReflectionClass($item);

        return $this->getInstance($reflectionItem);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getInstance(ReflectionClass $item): object
    {
        $constructor = $item->getConstructor();

        if (is_null($constructor) || $constructor->getNumberOfRequiredParameters() == 0) {
            return $item->newInstance();
        }

        $params = [];

        foreach ($constructor->getParameters() as $param) {
            if ($type = $param->getType()) {
                $params[] = $this->get($type->getName());
            }
        }

        return $item->newInstanceArgs($params);
    }

}