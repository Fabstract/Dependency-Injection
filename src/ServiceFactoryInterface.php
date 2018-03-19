<?php

namespace Fabstract\Component\DependencyInjection;

interface ServiceFactoryInterface
{
    /**
     * @param mixed[] $parameters
     * @return mixed
     */
    function create($parameters = []);
}
