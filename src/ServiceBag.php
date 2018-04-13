<?php

namespace Fabstract\Component\DependencyInjection;

class ServiceBag
{
    /**
     * @var ServiceDefinition[]
     */
    private $service_definition_list = [];

    /**
     * @param ServiceDefinition $service_definition
     * @return ServiceBag
     */
    public function addServiceDefinition($service_definition)
    {
        Assert::isType($service_definition, ServiceDefinition::class, 'service definition');
        $this->service_definition_list[] = $service_definition;
        return $this;
    }

    /**
     * @param string $name
     * @param string $class_name
     * @param bool $shared
     * @return ServiceDefinition
     */
    public function create($name, $class_name, $shared = true)
    {
        $service_definition = new ServiceDefinition($shared);
        $service_definition
            ->setName($name)
            ->setClassName($class_name);

        $this->addServiceDefinition($service_definition);
        return $service_definition;
    }

    /**
     * @return ServiceDefinition[]
     */
    public function getAll()
    {
        return $this->service_definition_list;
    }
}