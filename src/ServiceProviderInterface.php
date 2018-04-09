<?php

namespace Fabstract\Component\DependencyInjection;

interface ServiceProviderInterface
{
    /**
     * @param ServiceBag $service_bag
     * @return void
     */
    public function configureServiceBag($service_bag);
}
