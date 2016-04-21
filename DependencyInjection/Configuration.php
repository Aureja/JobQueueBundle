<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\JobQueueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('aureja_job_queue');

        $rootNode
            ->children()
                ->scalarNode('db_driver')->cannotBeOverwritten()->isRequired()->end()
                ->scalarNode('job_configuration_manager')
                    ->defaultValue('aureja_job_queue.manager.job_configuration.default')->cannotBeEmpty()->end()
                ->scalarNode('job_report_manager')
                    ->defaultValue('aureja_job_queue.manager.job_report.default')->cannotBeEmpty()->end()
                ->arrayNode('class')->isRequired()
                    ->children()
                        ->arrayNode('model')->isRequired()
                            ->children()
                                ->scalarNode('job_configuration')->isRequired()->end()
                                ->scalarNode('job_report')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('queues')
                    ->beforeNormalization()
                        ->ifString()
                        ->then(
                            function ($value) {
                                return preg_split('/\s*,\s*/', $value);
                            }
                        )
                        ->end()
                    ->requiresAtLeastOneElement()->prototype('scalar')->end()
                ->end()

                ->arrayNode('reset')->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('timeout')->min(60)->defaultValue(600)
                    ->end()
                ->end()

                ->scalarNode('console_dir')->defaultValue('%kernel.root_dir%')->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}
