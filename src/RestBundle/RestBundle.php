<?php

namespace RestBundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RestBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $loader = new YamlFileLoader($container, new FileLocator(
            [__DIR__.'/Resources/config/']
        ));

        $loader->load('services.yml');
    }
}
