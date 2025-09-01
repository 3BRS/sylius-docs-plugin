<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusDocumentationPlugin\Behat\Page\Admin\Documentation;

use Behat\Mink\Element\NodeElement;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class ShowPage extends SymfonyPage implements ShowPageInterface
{
    public function getRouteName(): string
    {
        return 'threebrs_admin_documentation_plugin_show';
    }

    public function hasContent(string $content): bool
    {
        return str_contains($this->getDocument()->getContent(), $content);
    }

    public function hasHeading(string $heading): bool
    {
        $headings = $this->getDocument()->findAll('css', 'h1, h2, h3, h4, h5, h6');
        foreach ($headings as $headingElement) {
            if (str_contains($headingElement->getText(), $heading)) {
                return true;
            }
        }

        return false;
    }

    public function hasCodeBlock(): bool
    {
        return $this->getCodeBlock() !== null;
    }

    public function hasCodeBlockWithContent(string $expectedContent): bool
    {
        $codeBlock = $this->getCodeBlock();
        if ($codeBlock === null) {
            return false;
        }

        return str_contains($codeBlock->getText(), $expectedContent);
    }

    public function getCodeBlock(): ?NodeElement
    {
        return $this->getDocument()->find('css', 'pre code, code')
            ?: null;
    }

    public function hasBulletedList(): bool
    {
        return $this->getDocument()->has('css', 'ul li');
    }

    public function hasImage(): bool
    {
        return $this->getDocument()->has('css', 'img');
    }

    public function clickLink(string $linkText): void
    {
        $this->getDocument()->clickLink($linkText);
    }

    public function hasErrorMessage(string $message): bool
    {
        return $this->hasContent($message);
    }
}
