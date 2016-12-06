<?php

namespace Miguelv\EasyPaymentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Miguelv\EasyPaymentBundle\DependencyInjection\CompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

/**
 * Class EasyPaymentBundle
 */
class EasyPaymentBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CompilerPass(), PassConfig::TYPE_AFTER_REMOVING);

        parent::build($container);
    }    
}
