<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusDocsPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use ThreeBRS\SyliusDocsPlugin\DependencyInjection\ThreeBRSSyliusDocsPluginExtension;

class ThreeBRSSyliusDocsPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new ThreeBRSSyliusDocsPluginExtension();
    }
}
