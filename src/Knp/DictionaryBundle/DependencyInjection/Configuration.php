<?php

namespace Knp\DictionaryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('knp_dictionary');

        if (method_exists($builder, 'getRootNode')) {
            $rootNode = $builder->getRootNode();
        } else {
            // BC for symfony/config < 4.2
            $rootNode = $builder->root('knp_dictionary');
        }

        $rootNode
            ->children()
            ->arrayNode('dictionaries')
                ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->always()
                            ->then(function ($values) {
                                if (false === \array_key_exists('type', $values)) {
                                    if (false === \array_key_exists('content', $values)) {
                                        return ['type' => 'value', 'content' => $values];
                                    }

                                    return \array_merge($values, ['type' => 'value']);
                                }

                                return $values;
                            })
                        ->end()
                        ->children()
                            ->scalarNode('type')->defaultValue('value')->end()
                            ->scalarNode('category')->defaultNull()->end()
                            ->arrayNode('content')
                                ->prototype('scalar')->end()
                            ->end()
                            ->scalarNode('service')->end()
                            ->scalarNode('method')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
