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
     * @param string $name
     */
    public function __construct($name = null)
    {
        if ($name !== null) {
            $this->setName($name);
        }
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
        Assert::isNotEmptyString($name, 'name');
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
     * @param string $name
     * @return ServiceDefinition
     */
    public static function create($name = null)
    {
        return new static($name);
    }
}
