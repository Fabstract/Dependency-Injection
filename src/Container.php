<?php

namespace Fabs\Component\DependencyInjection;

use Fabs\Component\DependencyInjection\Exception\NotFoundException;

class Container implements ContainerInterface
{
    /**
     * @var ServiceDefinition[]
     */
    protected $service_lookup = [];

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->getDefinition($name)->getInstance();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        Assert::isNotNull($name, 'name');
        return array_key_exists($name, $this->service_lookup);
    }

    /**
     * @param ServiceDefinition $definition
     * @return ContainerInterface
     */
    public function add($definition)
    {
        Assert::isDefinition($definition);

        $this->service_lookup[$definition->getName()] = $definition;
        $definition->setContainer($this);
        return $this;
    }

    /**
     * @param string $name
     * @return ServiceDefinition
     * @throws NotFoundException
     */
    protected function getDefinition($name)
    {
        if (!$this->has($name)) {
            throw new NotFoundException();
        }

        return $this->service_lookup[$name];
    }
}
