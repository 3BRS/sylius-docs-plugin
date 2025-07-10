<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusDocsPlugin\Controller;

use League\CommonMark\CommonMarkConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment as TwigEnvironment;

class DocsController
{
    private string $projectDir;

    public function __construct(
        private TwigEnvironment $twig,
        private CommonMarkConverter $converter,
        private AuthorizationCheckerInterface $authorizationChecker,
        private KernelInterface $kernel,
    ) {
        $this->projectDir = $this->kernel->getProjectDir();
    }

    public function index(): Response
    {
        if (!$this->authorizationChecker->isGranted('ROLE_ADMINISTRATION_ACCESS')) {
            throw new AccessDeniedHttpException('Only admin users can access documentation.');
        }

        $docsDir = $this->projectDir . '/docs';
        $indexPath = realpath($docsDir . '/index.md');
        $docsRealPath = realpath($docsDir);

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

            $renderedContent = $this->converter->convert($markdown);
            $html = (string) $renderedContent;
            $html = preg_replace('/href="([^"\/]+)"/', 'href="/admin/docs/$1"', $html);
            $indexExists = true;
        }

        $files = is_dir($docsDir)
            ? array_filter(scandir($docsDir), fn ($file) => pathinfo($file, \PATHINFO_EXTENSION) === 'md')
            : [];

        $slugs = array_map(fn ($file) => pathinfo($file, \PATHINFO_FILENAME), $files);

        return new Response($this->twig->render('@ThreeBRSSyliusDocsPlugin/admin/docs/index.html.twig', [
            'html' => $html,
            'slugs' => $slugs,
            'index_exists' => $indexExists,
        ]));
    }

    public function show(Request $request, string $slug = 'index'): Response
    {
        if (!$this->authorizationChecker->isGranted('ROLE_ADMINISTRATION_ACCESS')) {
            throw new AccessDeniedHttpException('Only admin users can access documentation.');
        }

        if (str_contains($slug, '..') || str_contains($slug, '/')) {
            throw new NotFoundHttpException('Invalid documentation slug.');
        }

        $docsDir = $this->projectDir . '/docs';
        $docsRealPath = realpath($docsDir);
        $expectedPath = $docsDir . '/' . $slug . '.md';
        $path = realpath($expectedPath);

        $html = null;
        $indexExists = true;

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

            $renderedContent = $this->converter->convert($markdown);
            $html = (string) $renderedContent;
            $html = preg_replace('/href="([^"\/]+)"/', 'href="/admin/docs/$1"', $html);
        }

        $files = is_dir($docsDir)
            ? array_filter(scandir($docsDir), fn ($file) => pathinfo($file, \PATHINFO_EXTENSION) === 'md')
            : [];

        $slugs = array_map(fn ($file) => pathinfo($file, \PATHINFO_FILENAME), $files);

        return new Response($this->twig->render('@ThreeBRSSyliusDocsPlugin/admin/docs/show.html.twig', [
            'html' => $html,
            'slug' => $slug,
            'slugs' => $slugs,
            'index_exists' => $indexExists,
        ]));
    }
}
