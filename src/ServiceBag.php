<?php

namespace Fabstract\Component\DependencyInjection;

class ServiceBag
{
    /** @var ServiceDefinition[] */
    private $service_definition_list = [];
    /** @var array */
    private $sub_container_name_class_or_creator_lookup = [];

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
        $service_definition = new ServiceDefinition();
        $service_definition
            ->setName($name)
            ->setShared($shared)
            ->setClassName($class_name);

        $this->addServiceDefinition($service_definition);
        return $service_definition;
    }

    /**
     * @return ServiceDefinition[]
     */
    public function getServiceDefinitionList()
    {
        return $this->service_definition_list;
    }

    /**
     * @param string $name
     * @param ServiceProviderInterface|string|callable $service_provider_or_creator
     * @return ServiceBag
     */
    public function createSubContainer($name, $service_provider_or_creator)
    {
        Assert::isNotEmptyString($name, 'name');
        if (is_callable($service_provider_or_creator) !== true) {
            Assert::isType(
                $service_provider_or_creator,
                ServiceProviderInterface::class,
                'service_provider_or_creator'
            );
        }

        $this->sub_container_name_class_or_creator_lookup[$name] = $service_provider_or_creator;
        return $this;
    }

    /**
     * @return array
     */
    public function getSubContainerList()
    {
        return $this->sub_container_name_class_or_creator_lookup;
    }
}
