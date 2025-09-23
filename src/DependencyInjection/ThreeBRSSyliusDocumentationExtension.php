<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusDocumentationPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ThreeBRSSyliusDocumentationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $docsPath = $config['docs_path'] ?? null;
        $docsPath = assert(is_string($docsPath));
        $container->setParameter('threebrs_sylius_documentation_plugin.docs_path', $docsPath);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');
    }

    // @phpstan-ignore-next-line
    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }

    public function getAlias(): string
    {
        return 'threebrs_sylius_documentation';
    }
}
