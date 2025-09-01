<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusDocumentationPlugin\Behat\Page\Admin;

use Sylius\Behat\Page\Admin\DashboardPageInterface;

interface ExtendedDashboardPageInterface extends DashboardPageInterface
{
    public function hasMenuItem(string $menuItem): bool;
    
    public function clickMenuItem(string $menuItem): void;
}
