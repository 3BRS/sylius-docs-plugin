<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusDocumentationPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Session;
use Sylius\Behat\Service\DriverHelper;
use Symfony\Component\Routing\RouterInterface;
use Tests\ThreeBRS\SyliusDocumentationPlugin\Behat\Page\Admin\ExtendedDashboardPageInterface;
use Tests\ThreeBRS\SyliusDocumentationPlugin\Behat\Page\Admin\Documentation\IndexPageInterface;
use Tests\ThreeBRS\SyliusDocumentationPlugin\Behat\Page\Admin\Documentation\ShowPageInterface;
use Webmozart\Assert\Assert;

final readonly class ManagingDocumentationContext implements Context
{
    public function __construct(
        private ?IndexPageInterface             $indexPage = null,
        private ?ShowPageInterface              $showPage = null,
        private ?ExtendedDashboardPageInterface $dashboardPage = null,
        private ?Session                        $session = null,
        private ?RouterInterface                $router = null,
    ) {
    }

    /**
     * @When I visit the admin dashboard
     */
    public function iVisitTheAdminDashboard(): void
    {
        $this->dashboardPage->open();
    }

    /**
     * @Then I should see :menuItem menu item
     */
    public function iShouldSeeMenuItem(string $menuItem): void
    {
        Assert::true($this->dashboardPage->hasMenuItem($menuItem));
    }

    /**
     * @When I click :menuItem menu item
     */
    public function iClickMenuItem(string $menuItem): void
    {
        $this->dashboardPage->clickMenuItem($menuItem);
    }

    /**
     * @Then I should be redirected to the documentation index page
     */
    public function iShouldBeRedirectedToTheDocumentationIndexPage(): void
    {
        Assert::true(
            $this->indexPage->isOpen(),
            sprintf('Current page is %s', $this->session?->getCurrentUrl() ?? 'unknown'),
        );
    }

    /**
     * @When I go to the documentation index page
     */
    public function iGoToTheDocumentationIndexPage(): void
    {
        $this->indexPage->open();
    }

    /**
     * @When I go to the documentation page for :slug
     */
    public function iGoToTheDocumentationPageFor(string $slug): void
    {
        $this->showPage->open(['slug' => $slug]);
    }

    /**
     * @When I try to access documentation page :slug
     */
    public function iTryToAccessDocumentationPage(string $slug): void
    {
        $this->showPage->tryToOpen(['slug' => $slug]);
    }

    /**
     * @When I try to access documentation image :filename
     */
    public function iTryToAccessDocumentationImage(string $filename): void
    {
        // First ensure we're authenticated by visiting the dashboard
        $this->dashboardPage->open();
        
        // Then generate the proper URL using the router and visit it
        $url = $this->router->generate('threebrs_admin_documentation_plugin_image', ['filename' => $filename]);
        $this->session->visit($url);
    }

    /**
     * @When I access the documentation image :filename
     */
    public function iAccessTheDocumentationImage(string $filename): void
    {
        $this->iTryToAccessDocumentationImage($filename);
    }

    /**
     * @Then I should see :content
     */
    public function iShouldSeeContent(string $content): void
    {
        Assert::true($this->indexPage->hasContent($content) || $this->showPage->hasContent($content));
    }

    /**
     * @Then I should see :content on index page
     */
    public function iShouldSeeContentOnIndexPage(string $content): void
    {
        Assert::true(
            $this->indexPage->hasContent($content),
            sprintf('Content "%s" not found on page %s', $content, $this->session?->getCurrentUrl() ?? 'unknown'),
        );
    }

    /**
     * @Then I should not see any rendered content
     */
    public function iShouldNotSeeAnyRenderedContent(): void
    {
        // Check that there's no main content div with actual markdown content
        Assert::false($this->indexPage->hasContent('<h1>') && $this->indexPage->hasContent('<p>'));
    }

    /**
     * @Then I should see a list of available documentation files:
     */
    public function iShouldSeeAListOfAvailableDocumentationFiles(TableNode $table): void
    {
        $expectedFiles = [];
        foreach ($table->getRows() as $row) {
            $expectedFiles[] = $row[0];
        }

        $actualFiles = $this->indexPage->getDocumentationFiles();

        foreach ($expectedFiles as $expectedFile) {
            Assert::true(
                in_array($expectedFile, $actualFiles),
                sprintf(
                    'Expected file "%s" not found in documentation list %s',
                    $expectedFile,
                    json_encode($actualFiles),
                ),
            );
        }
    }

    /**
     * @Then I should see :heading heading
     */
    public function iShouldSeeHeading(string $heading): void
    {
        Assert::true($this->indexPage->hasHeading($heading) || $this->showPage->hasHeading($heading));
    }

    /**
     * @Then I should see properly formatted markdown content
     */
    public function iShouldSeeProperlyFormattedMarkdownContent(): void
    {
        // Check for bold and italic text formatting
        Assert::true($this->showPage->hasContent('<strong>') || $this->showPage->hasContent('<b>'));
        Assert::true($this->showPage->hasContent('<em>') || $this->showPage->hasContent('<i>'));
    }

    /**
     * @Then I should see a code block with PHP code
     */
    public function iShouldSeeACodeBlockWithPhpCode(): void
    {
        Assert::true($this->showPage->hasCodeBlock(), 'No code block found on the page.');
        Assert::true($this->showPage->hasCodeBlockWithContent('echo "Hello World"'), 'Expected PHP code not found in the code block.');
    }

    /**
     * @Then I should see a bulleted list
     */
    public function iShouldSeeABulletedList(): void
    {
        Assert::true($this->showPage->hasBulletedList());
    }

    /**
     * @Then I should see the image displayed correctly
     */
    public function iShouldSeeTheImageDisplayedCorrectly(): void
    {
        Assert::true($this->showPage->hasImage());
    }

    /**
     * @When I click on :linkText link
     */
    public function iClickOnLink(string $linkText): void
    {
        if ($this->indexPage->isOpen()) {
            $this->indexPage->clickDocumentationLink($linkText);
        } else {
            $this->showPage->clickLink($linkText);
        }

        \assert($this->session !== null);
        DriverHelper::waitForPageToLoad($this->session);
    }

    /**
     * @Then I should be on the documentation page for :slug
     */
    public function iShouldBeOnTheDocumentationPageFor(string $slug): void
    {
        Assert::true($this->showPage->isOpen(['slug' => $slug]));
    }

    /**
     * @Then I should be on the documentation index page
     */
    public function iShouldBeOnTheDocumentationIndexPage(): void
    {
        Assert::true(
            $this->indexPage->isOpen(),
            sprintf('Current page is %s', $this->session?->getCurrentUrl() ?? 'unknown'),
        );
    }

    /**
     * @Then I should see :errorMessage error
     */
    public function iShouldSeeError(string $errorMessage): void
    {
        Assert::true(
            $this->showPage->hasErrorMessage($errorMessage),
            sprintf(
                'Error message "%s" not found on page %s',
                $errorMessage, $this->session?->getCurrentUrl() ?? 'unknown',
            ),
        );
    }

    /**
     * @Then I should see the image content
     */
    public function iShouldSeeTheImageContent(): void
    {
        $contentType = $this->session->getResponseHeader('Content-Type');
        Assert::startsWith(
            $contentType,
            'image/',
            sprintf(
                'Expected image content type. Current URL "%s"',
                $this->session?->getCurrentUrl() ?? 'unknown',
            ),
        );
    }

    /**
     * @Then the response should have proper image content type
     */
    public function theResponseShouldHaveProperImageContentType(): void
    {
        $contentType = $this->session->getResponseHeader('Content-Type');
        Assert::startsWith($contentType, 'image/');
    }
}
