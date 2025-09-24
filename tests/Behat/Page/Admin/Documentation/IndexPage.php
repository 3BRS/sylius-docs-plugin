<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusDocumentationPlugin\Behat\Page\Admin\Documentation;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class IndexPage extends SymfonyPage implements IndexPageInterface
{
    public function getRouteName(): string
    {
        return 'threebrs_sylius_documentation_admin_index';
    }

    public function hasContent(string $content): bool
    {
        return str_contains($this->getDocument()->getContent(), $content);
    }

    public function hasDocumentationFile(string $slug): bool
    {
        return $this->getDocument()->hasLink($slug);
    }

    public function getDocumentationFiles(): array
    {
        $links = $this->getDocument()->findAll('css', 'a[href*="/documentation/"]');
        $files = [];
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (preg_match('/\/documentation\/([^\/]+)$/', $href, $matches)) {
                $files[] = $matches[1];
            }
        }
        return $files;
    }

    public function clickDocumentationLink(string $slug): void
    {
        $this->getDocument()->clickLink($slug);
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
        return $this->getDocument()->has('css', 'pre code, code');
    }

    public function hasBulletedList(): bool
    {
        return $this->getDocument()->has('css', 'ul li');
    }

    public function hasImage(): bool
    {
        return $this->getDocument()->has('css', 'img');
    }
}
