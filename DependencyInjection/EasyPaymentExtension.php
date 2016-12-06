<?php

namespace Miguelv\EasyPaymentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class EasyPaymentExtension
 *
 * @author Miguel Vilata <miguel.vilata@gmail.com>
 */
class EasyPaymentExtension extends Extension implements PrependExtensionInterface
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
        $container->setParameter('easy_payment.stripe.controller', $config['stripe']['controller']);
        $container->setParameter('easy_payment.stripe.manager', $config['stripe']['manager']);
        $container->setParameter('easy_payment.stripe.form.type', $config['stripe']['form']['type']);
        $container->setParameter('easy_payment.stripe.form.validation_groups', $config['stripe']['form']['validation_groups']);
        $container->setParameter('easy_payment.stripe.form.data', $config['stripe']['form']['data']);

        //Paypal gateway
        $container->setParameter('easy_payment.paypal.form.type', $config['paypal']['form']['type']);
        $container->setParameter('easy_payment.paypal.form.validation_groups', $config['paypal']['form']['validation_groups']);
        $container->setParameter('easy_payment.paypal.form.data', $config['paypal']['form']['data']);

        $container->setParameter('easy_payment.paypal.express_checkout.username', $config['paypal']['express_checkout']['username']);
        $container->setParameter('easy_payment.paypal.express_checkout.password', $config['paypal']['express_checkout']['password']);
        $container->setParameter('easy_payment.paypal.express_checkout.signature', $config['paypal']['express_checkout']['signature']);
        $container->setParameter('easy_payment.paypal.express_checkout.factory', $config['paypal']['express_checkout']['factory']);
        $container->setParameter('easy_payment.paypal.express_checkout.sandbox', $config['paypal']['express_checkout']['sandbox']);
    }

    /**
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        // get all bundles
        $bundles = $container->getParameter('kernel.bundles');

        if (!isset($bundles['PayumBundle'])) {
            return;
        }

        foreach ($container->getExtensions() as $name => $extension) {
            switch ($name) {
                case 'payum':

                    //@discover a way to override this configuration
                    $config = [
                        'storages' => [
                            'Miguelv\EasyPaymentBundle\Entity\PaypalPaymentDetails' => ['doctrine' => 'orm'],
                        ],
                        'security' => [
                            'token_storage' => [
                                'Miguelv\EasyPaymentBundle\Entity\PaypalPaymentToken' => ['doctrine' => 'orm'],
                            ]
                        ],
                        
//                        'gateways' => [
//                            'paypal_express_checkout' => [
//                                'factory' => 'paypal_express_checkout',
//                                'username' => 'username',
//                                'password' => 'password',
//                                'signature' => 'signature',
//                                'sandbox' => false,
//                            ]
//                        ],
                        
                    ];

                    $container->prependExtensionConfig($name, $config);
                    break;
            }
        }
    }
}
