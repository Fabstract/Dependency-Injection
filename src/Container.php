<?php

namespace Fabstract\Component\DependencyInjection;

use Fabstract\Component\DependencyInjection\Exception\NotFoundException;

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
     * @throws NotFoundException
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
     * @param string $service_name
     * @param string $definition_class
     * @return $this
     */
    public function addServiceDefinitionConstraint($service_name, $definition_class)
    {
        Assert::isNotNullOrWhiteSpace($service_name, 'service_name');
        Assert::isClassExists($definition_class, 'constraint_class');

        $this->constraint_lookup[$service_name] = $definition_class;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws NotFoundException
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function __get($name)
    {
        if ($this->has($name)) {
            $this->{$name} = $this->get($name);
        }

        return $this->{$name};
    }

    /**
     * @param ServiceProviderInterface $service_provider
     * @return $this
     */
    public function importFromServiceProvider($service_provider)
    {
        Assert::isType($service_provider, ServiceProviderInterface::class, 'service_provider');
        $service_bag = new ServiceBag();
        $service_provider->configureServiceBag($service_bag);
        $service_definition_list = $service_bag->getServiceDefinitionList();

        foreach ($service_definition_list as $service_definition) {
            $this->add($service_definition);
        }

        $sub_container_list = $service_bag->getSubContainerList();
        foreach ($sub_container_list as $sub_container_name => $service_provider_or_creator) {
            $this->addSubContainer($sub_container_name, $service_provider_or_creator);
        }

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
            throw new NotFoundException("${name} is not injected to container");
        }

        return $this->service_lookup[$name];
    }

    /**
     * @param string $name
     * @param ServiceProviderInterface|string|callable $service_provider_or_creator
     */
    public function addSubContainer($name, $service_provider_or_creator)
    {
        $sub_container_service_definition = new ServiceDefinition();
        $sub_container_service_definition->setName($name);
        $sub_container_service_definition->setShared(true);
        $sub_container_service_definition->setCreator(function () use ($service_provider_or_creator) {
            if ($service_provider_or_creator !== null) {
                if (is_callable($service_provider_or_creator)) {
                    $service_provider_or_creator = $service_provider_or_creator();
                }

                Assert::isType(
                    $service_provider_or_creator,
                    ServiceProviderInterface::class,
                    'service provider'
                );

                if (is_string($service_provider_or_creator)) {
                    /** @var ServiceProviderInterface $service_provider_or_creator */
                    $service_provider_or_creator = new $service_provider_or_creator();
                }

                $sub_container = new SubContainer();
                $sub_container->setContainer($this);
                $sub_container->importFromServiceProvider($service_provider_or_creator);
                return $sub_container;
            }
            return null;
        });

        $this->add($sub_container_service_definition);
    }
}
