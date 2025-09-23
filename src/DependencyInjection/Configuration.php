<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusDocumentationPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('threebrs_sylius_documentation');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('docs_path')
                    ->defaultValue('%kernel.project_dir%/documentation')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
