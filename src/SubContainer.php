<?php

namespace Fabstract\Component\DependencyInjection;

class SubContainer extends Container implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param \Fabstract\Component\DependencyInjection\ServiceDefinition $definition
     * @return $this
     */
    public function add($definition)
    {
        parent::add($definition);
        $definition->setContainer($this->getContainer());
        return $this;
    }
}
