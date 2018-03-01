<?php

namespace Fabs\Component\DependencyInjection;

use Fabs\Component\DependencyInjection\Exception\Exception;

class Definition extends ContainerAware
{
    /** @var string */
    private $name = null;
    /** @var bool */
    private $shared = false;
    /** @var mixed */
    private $instance = null;

    /** @var string */
    private $class_name = null;
    /** @var string */
    private $factory_class_name = null;
    /** @var callable */
    private $creator = null;

    /** @var mixed[] */
    private $parameters = [];

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     * @throws Exception
     */
    public function setName($name)
    {
        Assert::isString($name, 'name');
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShared()
    {
        return $this->shared;
    }

    /**
     * @param bool $shared
     * @return $this
     * @throws Exception
     */
    public function setShared($shared = true)
    {
        Assert::isBoolean($shared, 'shared');
        $this->shared = $shared;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstance()
    {
        if ($this->isShared()) {
            if ($this->instance !== null) {
                return $this->instance;
            }
        }

        $instance = $this->createInstance();

        if ($instance instanceof ContainerAwareInterface) {
            $instance->setContainer($this->getContainer());
        }

        if ($this->isShared()) {
            $this->setInstance($instance);
        }

        return $instance;
    }

    /**
     * @param mixed $instance
     * @return $this
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed[] $parameters
     * @return $this
     * @throws Exception
     */
    public function setParameters($parameters)
    {
        Assert::isArray($parameters, 'parameters');
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->class_name;
    }

    /**
     * @param string $class_name
     * @return $this
     */
    public function setClassName($class_name)
    {
        Assert::isString($class_name, 'class_name');
        Assert::isClassExists($class_name, 'class_name');
        $this->class_name = $class_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getFactoryClassName()
    {
        return $this->factory_class_name;
    }

    /**
     * @param string $factory_class_name
     * @return $this
     */
    public function setFactoryClassName($factory_class_name)
    {
        Assert::isString($factory_class_name, 'factory_class_name');
        Assert::isClassExists($factory_class_name, 'factory_class_name');
        $this->factory_class_name = $factory_class_name;
        return $this;
    }

    /**
     * @return callable
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param callable $creator
     * @return $this
     */
    public function setCreator($creator)
    {
        Assert::isCallable($creator, 'creator');
        $this->creator = $creator;
        return $this;
    }

    /**
     * @return mixed|null
     */
    protected function createInstance()
    {
        if ($this->class_name !== null) {
            return new $this->class_name(...$this->parameters);
        }

        if ($this->factory_class_name !== null) {
            $factory = $this->getServiceFactory();
            return $factory->create($this->parameters);
        }

        if ($this->creator !== null) {
            return call_user_func_array($this->creator, $this->parameters);
        }

        return null;
    }

    /**
     * @return ServiceFactoryInterface
     */
    private function getServiceFactory()
    {
        static $factory_lookup = [];

        if (array_key_exists($this->factory_class_name, $factory_lookup) === true) {
            return $factory_lookup[$this->factory_class_name];
        }

        /** @var ServiceFactoryInterface $factory */
        $factory = new $this->factory_class_name();

        Assert::isType($factory, ServiceFactoryInterface::class, 'factory');

        $factory_lookup[$this->factory_class_name] = $factory;

        if ($factory instanceof ContainerAwareInterface) {
            $factory->setContainer($this->getContainer());
        }

        return $factory;
    }
}
