<?php

namespace Fabs\Component\DependencyInjection;

trait ContainerAwareTrait
{
    /** @var ContainerInterface */
    private $container = null;

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer($container)
    {
        Assert::isType($container, ContainerInterface::class, 'container');
        $this->container = $container;
        return $this;
    }
}
