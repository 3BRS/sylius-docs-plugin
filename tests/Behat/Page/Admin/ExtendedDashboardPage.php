<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusDocumentationPlugin\Behat\Page\Admin;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Page\Admin\DashboardPage;
use Sylius\Behat\Service\DriverHelper;

final class ExtendedDashboardPage extends DashboardPage implements ExtendedDashboardPageInterface
{
    public function hasMenuItem(string $menuItem): bool
    {
        return $this->getMenuItem($menuItem) !== null;
    }

    public function getMenuItem(string $menuItem): ?NodeElement
    {
        return $this->getDocument()
                    ->find(
                        'xpath',
                        sprintf('//li[@class="nav-item"]//a[@class="nav-link"][.//span[@class="nav-link-title"][contains(text(), "%s")]]',
                            $menuItem,
                        ),
                    )
            ?: null;
    }

    public function clickMenuItem(string $menuItem): void
    {
        $menuItemElement = $this->getMenuItem($menuItem);

        if ($menuItemElement === null) {
            throw new \RuntimeException(sprintf('Menu item "%s" not found', $menuItem));
        }

        $menuItemElement->click();

        DriverHelper::waitForPageToLoad($this->getSession());
    }
}
