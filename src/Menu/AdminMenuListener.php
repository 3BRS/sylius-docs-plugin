<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusDocumentationPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class AdminMenuListener implements EventSubscriberInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.menu.admin.main' => 'addDocumentationMenuItem',
        ];
    }

    public function addDocumentationMenuItem(MenuBuilderEvent $event): void
    {
        $event->getMenu()
            ->addChild('threebrs_documentation_plugin', [
                'route' => 'threebrs_admin_documentation_plugin_index',
            ])
            ->setLabel($this->translator->trans('threebrs_documentation_plugin.ui.admin.documentation.menu_title'))
            ->setLabelAttribute('icon', 'tabler:book');
    }
}
