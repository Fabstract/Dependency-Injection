<?php

namespace Fabs\Component\DependencyInjection;

use Fabs\Component\DependencyInjection\Exception\Exception;

class Injectable extends ContainerAware
{
    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function __get($name)
    {
        $dependency_injector = $this->getContainer();

        if ($dependency_injector === null) {
            throw new Exception('container not injected');
        }

        if ($dependency_injector->has($name)) {
            $this->{$name} = $dependency_injector->get($name);
        }

        return $this->{$name};
    }

    public function __isset($name)
    {
        $container = $this->getContainer();
        return $container !== null && $container->has($name);
    }
}
