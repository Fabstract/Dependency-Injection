<?php

namespace Fabs\Component\DependencyInjection;

interface ContainerInterface extends \Psr\Container\ContainerInterface
{
    /**
     * @param Definition $definition
     * @return $this
     */
    function add($definition);
}
