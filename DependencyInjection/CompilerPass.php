<?php

namespace Miguelv\EasyPaymentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class CompilerPass.
 *
 * @author Miguel Vilata <miguel.vilata@gmail.com>
 */
class CompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->getExtensions() as $name => $extension) {
            switch ($name) {
                case 'payum':
                    $config = [];
                    $definition = $container->findDefinition('payum.builder');
                    $configurationParams = ['username', 'password', 'signature', 'sandbox', 'factory'];

                    foreach ($configurationParams as $key) {
                        $config[$key] = $container->getParameter(sprintf('easy_payment.paypal.express_checkout.%s', $key));
                    }

                    $definition->addMethodCall('addGateway', ['paypal_express_checkout', $config]);
                    break;
            }
        }
    }
}
