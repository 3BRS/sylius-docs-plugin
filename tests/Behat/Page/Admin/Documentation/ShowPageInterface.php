<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusDocumentationPlugin\Behat\Page\Admin\Documentation;

use FriendsOfBehat\PageObjectExtension\Page\PageInterface;

interface ShowPageInterface extends PageInterface
{
    public function hasContent(string $content): bool;

    public function hasHeading(string $heading): bool;

    public function hasCodeBlock(): bool;

    public function hasCodeBlockWithContent(string $expectedContent): bool;

    public function hasBulletedList(): bool;

    public function hasImage(): bool;

    public function clickLink(string $linkText): void;

    public function hasErrorMessage(string $message): bool;
}
