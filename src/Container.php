<?php

namespace Fabs\Component\DependencyInjection;

use Fabs\Component\DependencyInjection\Exception\NotFoundException;

class Container implements ContainerInterface
{
    /**
     * @var Definition[]
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
        Assert::assertNonNull($name, 'name');
        return array_key_exists($name, $this->service_lookup);
    }

    /**
     * @param Definition $definition
     * @return ContainerInterface
     */
    public function add($definition)
    {
        Assert::assertDefinition($definition);

        $this->service_lookup[$definition->getName()] = $definition;
        $definition->setContainer($this);
        return $this;
    }

    /**
     * @param string $name
     * @return Definition
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
