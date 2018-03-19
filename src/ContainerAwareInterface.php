<?php

namespace Fabstract\Component\DependencyInjection;

interface ContainerAwareInterface
{
    /**
     * @return ContainerInterface
     */
    function getContainer();

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    function setContainer($container);
}
