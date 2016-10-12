<?php

namespace Miguelv\EasyPaymentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class EasyPaymentExtension
 *
 * @author Miguel Vilata <miguel.vilata@gmail.com>
 */
class EasyPaymentExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // Validate configuration and process it
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        // Configure parameters
        $this->configureParams($config, $container);
    }

    /**
     * @param $config
     * @param ContainerBuilder $container
     */
    protected function configureParams($config, ContainerBuilder $container)
    {
        //Defaults
        $container->setParameter('easy_payment.default.success_path', $config['default']['success_path']);
        $container->setParameter('easy_payment.default.fail_path', $config['default']['fail_path']);
        $container->setParameter('easy_payment.default.currency', $config['default']['currency']);

        //Stripe gateway
        $container->setParameter('easy_payment.stripe.publishable_key', $config['stripe']['publishable_key']);
        $container->setParameter('easy_payment.stripe.apiKey', $config['stripe']['apiKey']);
        $container->setParameter('easy_payment.stripe.manager', $config['stripe']['manager']);
        $container->setParameter('easy_payment.stripe.form.type', $config['stripe']['form']['type']);
        $container->setParameter('easy_payment.stripe.form.validation_groups', $config['stripe']['form']['validation_groups']);
    }
}
