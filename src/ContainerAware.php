<?php

namespace Fabs\Component\DependencyInjection;

class ContainerAware implements ContainerAwareInterface
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
        Assert::assertType($container, ContainerInterface::class, 'container');
        $this->container = $container;
        return $this;
    }
}
