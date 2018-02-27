<?php

namespace Fabs\Component\DependencyInjection;

interface ServiceFactoryInterface
{
    /**
     * @param mixed[] $parameters
     * @return mixed
     */
    function create($parameters = []);
}
