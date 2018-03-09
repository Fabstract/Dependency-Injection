<?php

namespace Fabs\Component\DependencyInjection;

use Fabs\Component\DependencyInjection\Exception\Exception;

class Definition extends ContainerAware
{
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
     * @return mixed
     */
    public function getInstance()
    {
        if ($this->instance !== null) {
            return $this->instance;
        }

        $instance = $this->createInstance();
        $this->setInstance($instance);

        return $instance;
    }

    /**
     * @param mixed $instance
     * @return $this
     */
    public function setInstance($instance)
    {
        $assert_type = $this->getAssertTypeInternal();
        if ($assert_type !== null) {
            Assert::isType($instance, $assert_type, 'instance');
        }

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
        Assert::isTypeExists($class_name, 'class_name');
        $assert_type = $this->getAssertTypeInternal();
        if ($assert_type !== null) {
            Assert::isType($class_name, $assert_type, 'class_name');
        }

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
     * @return null|string
     */
    private function getAssertTypeInternal()
    {
        $assert_type = static::getAssertType();
        if ($assert_type !== null) {
            Assert::isTypeExists($assert_type, 'assert type');
        }

        return $assert_type;
    }

    /**
     * @return string|null
     */
    protected function getAssertType()
    {
        return null;
    }

    /**
     * @return mixed|null
     */
    protected function createInstance()
    {
        $instance = null;
        if ($this->class_name !== null) {
            $instance = new $this->class_name(...$this->parameters);
        }

        if ($this->factory_class_name !== null) {
            $factory = $this->getServiceFactory();
            $instance = $factory->create($this->parameters);
        }

        if ($this->creator !== null) {
            $instance = call_user_func_array($this->creator, $this->parameters);
        }

        $assert_type = $this->getAssertTypeInternal();
        if ($assert_type !== null) {
            Assert::isType($instance, $assert_type, 'instance');
        }

        if ($instance instanceof ContainerAwareInterface) {
            $instance->setContainer($this->getContainer());
        }

        if ($instance instanceof DefinitionAwareInterface) {
            $instance->setDefinition($this);
        }

        return $instance;
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
