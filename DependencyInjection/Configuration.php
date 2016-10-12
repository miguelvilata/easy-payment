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

                ->arrayNode('stripe')->isRequired()
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
                                    ->defaultValue([])
                                    ->cannotBeEmpty()
                                    ->treatNullLike(array())
                                    ->requiresAtLeastOneElement()
                                    ->useAttributeAsKey('name')
                                    ->prototype('scalar')->end()
                                ->end()
        
            
                            ->end()
                        ->end()


                    ->end()
                ->end()

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
        ;

        return $treeBuilder;
    }

}
