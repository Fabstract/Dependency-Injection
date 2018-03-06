<?php

namespace Fabs\Component\DependencyInjection;

interface DefinitionAwareInterface
{
    /**
     * @return Definition
     */
    public function getDefinition();

    /**
     * @param Definition $definition
     * @return $this
     */
    public function setDefinition($definition);
}
