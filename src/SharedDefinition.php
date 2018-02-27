<?php

namespace Fabs\Component\DependencyInjection;

use Fabs\Component\DependencyInjection\Exception\Exception;

class SharedDefinition extends Definition
{
    /**
     * @return bool
     */
    public function isShared()
    {
        return true;
    }

    /**
     * @param bool $shared
     * @return $this
     * @throws Exception
     * @deprecated do not use this
     */
    public function setShared($shared = true)
    {
        throw new Exception('cannot call setShared for ' . SharedDefinition::class);
    }
}
