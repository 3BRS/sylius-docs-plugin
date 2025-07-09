<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusDocsPlugin\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment as TwigEnvironment;
use League\CommonMark\CommonMarkConverter;
use Symfony\Component\HttpKernel\KernelInterface;

class DocsController
{
    private string $projectDir;

    public function __construct(
        private TwigEnvironment $twig,
        private CommonMarkConverter $converter,
        private AuthorizationCheckerInterface $authorizationChecker,
        private KernelInterface $kernel
    ) {
        $this->projectDir = $this->kernel->getProjectDir();
    }

    public function show(Request $request, string $slug = 'index'): Response
    {
        // 🔐 Admin access protection
        if (!$this->authorizationChecker->isGranted('ROLE_ADMINISTRATION_ACCESS')) {
            throw new AccessDeniedHttpException('Only admin users can access documentation.');
        }

        // 🧱 Directory traversal protection
        if (str_contains($slug, '..') || str_contains($slug, '/')) {
            throw new NotFoundHttpException('Invalid documentation slug.');
        }

        $docsDir = $this->projectDir . '/docs';
        $expectedPath = $docsDir . '/' . $slug . '.md';
        $path = realpath($expectedPath);

        $html = null;

        if ($path === false) {
            if ($slug !== 'index') {
                throw new NotFoundHttpException(sprintf('Documentation page "%s" not found.', $slug));
            }
            // slug === 'index' but file doesn't exist → show warning in Twig
        } else {
            // 🧱 Security: make sure the file is actually inside /docs
            if (!str_starts_with($path, realpath($docsDir))) {
                throw new NotFoundHttpException('Access outside docs directory is not allowed.');
            }

            if (!is_file($path)) {
                throw new NotFoundHttpException(sprintf('Documentation page "%s" not found.', $slug));
            }

            // ✅ File exists, convert markdown to HTML
            $markdown = file_get_contents($path);
            $html = $this->converter->convert($markdown);
        }

        // 📋 Build list of available .md files (for TOC)
        $files = is_dir($docsDir)
            ? array_filter(scandir($docsDir), fn($file) => pathinfo($file, PATHINFO_EXTENSION) === 'md')
            : [];

        $slugs = array_map(fn($file) => pathinfo($file, PATHINFO_FILENAME), $files);

        return new Response($this->twig->render('@ThreeBRSSyliusDocsPlugin/admin/docs/show.html.twig', [
            'html' => $html,
            'slug' => $slug,
            'slugs' => $slugs,
        ]));
    }
}
