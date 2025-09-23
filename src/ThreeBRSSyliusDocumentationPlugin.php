<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusDocumentationPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ThreeBRSSyliusDocumentationPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    /**
     * Returns the plugin's container extension.
     *
     * @return ExtensionInterface|null The container extension
     *
     * @throws \LogicException
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->containerExtension) {
            $extension = $this->createContainerExtension();

            if (null !== $extension) {
                /** Removed check for naming convention to allow custom alias,
                 * @see \ThreeBRS\SyliusDocumentationPlugin\DependencyInjection\ThreeBRSSyliusDocumentationExtension::getAlias vs default three_brs_sylius_documentation */
                $this->containerExtension = $extension;
            } else {
                $this->containerExtension = false;
            }
        }

        // @phpstan-ignore-next-line
        return $this->containerExtension ?: null;
    }
}
