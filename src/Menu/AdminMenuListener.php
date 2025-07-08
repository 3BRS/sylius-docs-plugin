<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusDocsPlugin\Menu;

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
            ->addChild('docs', [
                'route' => 'threebrs_admin_docs_index',
            ])
            ->setLabel($this->translator->trans('threebrs.ui.docs_plugin.docs_menu_title'))
            ->setLabelAttribute('icon', 'tabler:book');
    }
}
