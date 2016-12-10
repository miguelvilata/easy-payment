<?php

namespace Miguelv\EasyPaymentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('easy_payment');

        $rootNode
            ->children()

                ->append($this->getStripeNode())

                ->append($this->getPaypalNode())

//               @tood Check that are are valida Classes
//                ->arrayNode('model')->cannotBeEmpty()
//                    ->children()
//                        ->scalarNode('order')
//                            ->cannotBeEmpty()
//                            ->defaultValue('Miguelv\EasyPaymentBundle\Entity\PaypalPaymentDetails')
//                        ->end()
//
//                        ->scalarNode('token')
//                            ->cannotBeEmpty()
//                            ->defaultValue('Miguelv\EasyPaymentBundle\Entity\PaypalPaymentToken')
//                        ->end()
//                    ->end()
//                ->end()

                ->arrayNode('default')->isRequired()
                    ->children()
                        ->scalarNode('success_path')
                            ->cannotBeEmpty()
                            ->isRequired()
                        ->end()

                        ->scalarNode('fail_path')
                            ->cannotBeEmpty()
                            ->isRequired()
                        ->end()

                        ->scalarNode('currency')
                            ->cannotBeEmpty()
                            ->defaultValue('usd')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @return ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    protected function getPaypalNode()
    {
        $builder = new TreeBuilder();

        return $builder->root('paypal')
            ->children()

                ->arrayNode('express_checkout')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('username')
                            ->cannotBeEmpty()
                        ->end()

                        ->scalarNode('password')
                            ->cannotBeEmpty()
                        ->end()

                        ->scalarNode('signature')
                            ->cannotBeEmpty()
                        ->end()

                        ->scalarNode('factory')
                            ->cannotBeEmpty()
                            ->defaultValue('paypal_express_checkout')
                        ->end()

                        ->scalarNode('sandbox')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('form')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('type')
                            ->defaultValue('easy_payment_paypal_type')
                            ->cannotBeEmpty()
                         ->end()

                        ->arrayNode('validation_groups')
                            ->prototype('scalar')->end()
                            ->defaultValue(array('Default'))
                            ->cannotBeEmpty()
                        ->end()

                        ->arrayNode('data')
                            ->info('Use this option to send default values to the form.')
                            ->defaultValue([])
                            ->cannotBeEmpty()
                            ->treatNullLike([])
                            ->requiresAtLeastOneElement()
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @return ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    protected function getStripeNode()
    {
        $builder = new TreeBuilder();

        return $builder->root('stripe')
            ->children()
                ->scalarNode('publishable_key')
                    ->cannotBeEmpty()
                    ->isRequired()
                ->end()

                ->scalarNode('apiKey')
                    ->cannotBeEmpty()
                    ->isRequired()
                ->end()

                ->scalarNode('controller')
                    ->defaultValue('Miguelv\EasyPaymentBundle\Controller\StripePaymentController')
                    ->cannotBeEmpty()
                ->end()

                ->scalarNode('manager')
                    ->defaultValue('Miguelv\EasyPaymentBundle\Gateways\Stripe\StripeManager')
                    ->cannotBeEmpty()
                ->end()

                ->arrayNode('form')->addDefaultsIfNotSet()
                    ->children()

                        ->scalarNode('type')
                            ->defaultValue('easy_payment_stripe_type')
                            ->cannotBeEmpty()
                         ->end()

                        ->arrayNode('validation_groups')
                            ->prototype('scalar')->end()
                            ->defaultValue(array('Default'))
                            ->cannotBeEmpty()
                        ->end()

                        ->arrayNode('data')
                            ->info('Use this option to send default values to the form.')
                            ->defaultValue([])
                            ->cannotBeEmpty()
                            ->treatNullLike([])
                            ->requiresAtLeastOneElement()
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
