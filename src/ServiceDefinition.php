<?php

namespace Fabstract\Component\DependencyInjection;

class ServiceDefinition extends Definition
{
    /** @var string */
    private $name = null;
    /** @var bool */
    private $shared = false;

    /**
     * ServiceDefinition constructor.
     * @param bool $is_shared
     */
    public function __construct($is_shared = false)
    {
        $this->setShared($is_shared);
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
     */
    public function setShared($shared = true)
    {
        Assert::isBoolean($shared, 'shared');
        $this->shared = $shared;
        return $this;
    }

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
     */
    public function setName($name)
    {
        Assert::isString($name, 'name');
        $this->name = $name;
        return $this;
    }

    public function getInstance()
    {
        if ($this->isShared()) {
            return parent::getInstance();
        }

        return $this->createInstance();
    }

    /**
     * @param bool $is_shared
     * @return ServiceDefinition
     */
    public static function create($is_shared)
    {
        return new static($is_shared);
    }
}
