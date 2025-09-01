<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusDocumentationPlugin\Controller;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\CommonMarkException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as TwigEnvironment;

readonly class DocumentationController
{
    public function __construct(
        private TwigEnvironment $twig,
        private CommonMarkConverter $converter,
        private string $docsPath,
        private RouterInterface $router,
    ) {
    }

    public function index(): Response
    {
        $indexPath = realpath($this->docsPath . '/index.md');
        $docsRealPath = realpath($this->docsPath);

        $html = null;
        $indexExists = false;

        if (
            $indexPath !== false &&
            is_file($indexPath) &&
            $docsRealPath !== false &&
            str_starts_with($indexPath, $docsRealPath)
        ) {
            $markdown = file_get_contents($indexPath);
            if ($markdown === false) {
                throw new \RuntimeException('Failed to read index.md');
            }

            try {
                $renderedContent = $this->converter->convert($markdown);
                $html = $this->replaceMarkdownLinks((string) $renderedContent);
            } catch (CommonMarkException $e) {
                throw new \RuntimeException('Failed to convert index.md to HTML.', 0, $e);
            }

            $indexExists = true;
        }

        $files = is_dir($this->docsPath)
            ? array_filter(scandir($this->docsPath), fn ($file) => pathinfo($file, \PATHINFO_EXTENSION) === 'md')
            : [];

        $slugs = array_map(fn ($file) => pathinfo($file, \PATHINFO_FILENAME), $files);

        return new Response($this->twig->render('@ThreeBRSSyliusDocumentationPlugin/admin/docs/index.html.twig', [
            'html' => $html,
            'slugs' => $slugs,
            'index_exists' => $indexExists,
        ]));
    }

    public function show(string $slug = 'index'): Response
    {
        if (str_contains($slug, '..') || str_contains($slug, '/')) {
            throw new NotFoundHttpException('Invalid documentation slug.');
        }

        $docsRealPath = realpath($this->docsPath);
        $expectedPath = $this->docsPath . '/' . $slug . '.md';
        $path = realpath($expectedPath);

        $html = null;

        if ($path === false) {
            if ($slug !== 'index') {
                throw new NotFoundHttpException(sprintf('Documentation page "%s" not found.', $slug));
            }
        } else {
            if ($docsRealPath === false || !str_starts_with($path, $docsRealPath)) {
                throw new NotFoundHttpException('Access outside docs directory is not allowed.');
            }

            if (!is_file($path)) {
                throw new NotFoundHttpException(sprintf('Documentation page "%s" not found.', $slug));
            }

            $markdown = file_get_contents($path);
            if ($markdown === false) {
                throw new \RuntimeException(sprintf('Failed to read file: %s', $path));
            }

            try {
                $renderedContent = $this->converter->convert($markdown);
                $html = $this->replaceMarkdownLinks((string) $renderedContent);
            } catch (CommonMarkException $e) {
                throw new \RuntimeException(sprintf('Failed to convert markdown for "%s".', $slug), 0, $e);
            }
        }

        $files = is_dir($this->docsPath)
            ? array_filter(scandir($this->docsPath), fn ($file) => pathinfo($file, \PATHINFO_EXTENSION) === 'md')
            : [];

        $slugs = array_map(fn ($file) => pathinfo($file, \PATHINFO_FILENAME), $files);

        return new Response($this->twig->render('@ThreeBRSSyliusDocumentationPlugin/admin/docs/show.html.twig', [
            'html' => $html,
            'slug' => $slug,
            'slugs' => $slugs,
        ]));
    }

    public function image(string $filename): Response
    {
        if (str_contains($filename, '..') || str_contains($filename, '/')) {
            throw new NotFoundHttpException('Invalid image filename.');
        }

        $imagePath = realpath($this->docsPath . '/' . $filename);
        $basePath = realpath($this->docsPath);

        if (
            $imagePath === false ||
            $basePath === false ||
            !str_starts_with($imagePath, $basePath) ||
            !is_file($imagePath)
        ) {
            throw new NotFoundHttpException(sprintf('Image "%s" not found.', $filename));
        }

        $imageContent = file_get_contents($imagePath);
        if ($imageContent === false) {
            throw new \RuntimeException(sprintf('Failed to read image file: %s', $imagePath));
        }

        return new Response($imageContent, 200, [
            'Content-Type' => mime_content_type($imagePath),
            'Content-Disposition' => 'inline',
        ]);
    }

    private function replaceMarkdownLinks(string $html): string
    {
        $html = preg_replace_callback('/href="([^"\/]+)"/', function (array $matches): string {
            $slug = pathinfo($matches[1], \PATHINFO_FILENAME);

            if ($slug === 'index') {
                return sprintf('href="%s"', $this->router->generate('threebrs_admin_documentation_plugin_index'));
            }

            return sprintf('href="%s"', $this->router->generate('threebrs_admin_documentation_plugin_show', ['slug' => $slug]));
        }, $html) ?? $html;

        $html = preg_replace_callback('/<img\s+[^>]*src="([^"\/]+)"[^>]*>/i', function (array $matches): string {
            $original = $matches[0];
            $filename = $matches[1];

            $imagePath = realpath($this->docsPath . '/' . $filename);
            $basePath = realpath($this->docsPath);

            if (
                $imagePath !== false &&
                $basePath !== false &&
                str_starts_with($imagePath, $basePath) &&
                is_file($imagePath)
            ) {
                $imageUrl = $this->router->generate('threebrs_admin_documentation_plugin_image', ['filename' => $filename]);

                return preg_replace('/src="[^"]+"/', 'src="' . $imageUrl . '"', $original) ?? $original;
            }

            return $original;
        }, $html) ?? $html;

        return $html;
    }
}
