<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusDocumentationPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;

final class DocumentationContext implements Context
{
    private string $docsPath;

    public function __construct(string $docsPath)
    {
        $this->docsPath = $docsPath;
    }

    /**
     * @Given there are no documentation files in the documentation directory
     */
    public function thereAreNoDocumentationFilesInTheDocsDirectory(): void
    {
        // Ensure documentation directory exists
        if (!is_dir($this->docsPath)) {
            mkdir($this->docsPath, 0755, true);
        }
        $files = glob($this->docsPath . '/*'); // Get all files in the directory
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Delete the file
            }
        }
    }

    /**
     * @Given there is a :filename documentation file with content:
     */
    public function thereIsADocumentationFileWithContent(string $filename, string $content): void
    {
        // Ensure documentation directory exists
        if (!is_dir($this->docsPath)) {
            mkdir($this->docsPath, 0755, true);
        }

        $filePath = $this->docsPath . '/' . $filename;
        file_put_contents($filePath, trim($content));
    }

    /**
     * @Given there is a :filename documentation file
     */
    public function thereIsADocumentationFile(string $filename): void
    {
        // Ensure documentation directory exists
        if (!is_dir($this->docsPath)) {
            mkdir($this->docsPath, 0755, true);
        }

        $filePath = $this->docsPath . '/' . $filename;
        $defaultContent = "# " . ucfirst(str_replace(['-', '_'], ' ', pathinfo($filename, PATHINFO_FILENAME))) . "\n\nThis is a sample documentation page.";
        file_put_contents($filePath, $defaultContent);
    }

    /**
     * @Given there is no :filename file
     */
    public function thereIsNoFile(string $filename): void
    {
        $filePath = $this->docsPath . '/' . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * @Given there is a :filename file in the documentation directory
     */
    public function thereIsAFileInTheDocsDirectory(string $filename): void
    {
        // Ensure documentation directory exists
        if (!is_dir($this->docsPath)) {
            mkdir($this->docsPath, 0755, true);
        }

        $filePath = $this->docsPath . '/' . $filename;

        // Create a simple image file for testing
        if (pathinfo($filename, PATHINFO_EXTENSION) === 'png') {
            // Create a 1x1 pixel PNG
            $imageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
            file_put_contents($filePath, $imageData);
        } else {
            // Create a simple text file
            file_put_contents($filePath, 'Test file content');
        }
    }
}
