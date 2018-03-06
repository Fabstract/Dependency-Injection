<?php

namespace Fabs\Component\DependencyInjection;

use Fabs\Component\DependencyInjection\Exception\NotFoundException;

class Container implements ContainerInterface
{
    /**
     * @var ServiceDefinition[]
     */
    protected $service_lookup = [];
    /** @var string[] */
    protected $constraint_lookup = [];

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

        $service_name = $definition->getName();
        if (array_key_exists($service_name, $this->constraint_lookup) === true) {
            $type = $this->constraint_lookup[$service_name];
            Assert::isType($definition, $type, 'definition');
        }

        $this->service_lookup[$service_name] = $definition;
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

    /**
     * @param string $service_name
     * @param string $constraint_class
     * @return $this
     */
    public function addServiceDefinitionConstraint($service_name, $constraint_class)
    {
        Assert::isNotNullOrWhiteSpace($service_name, 'service_name');
        Assert::isClassExists($constraint_class, 'constraint_class');

        $this->constraint_lookup[$service_name] = $constraint_class;
        return $this;
    }
}
