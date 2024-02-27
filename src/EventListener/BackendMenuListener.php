<?php

declare(strict_types=1);

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2024 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

namespace Rhyme\ContaoBackendThemeBundle\EventListener;

use Contao\CoreBundle\Event\MenuEvent;
use Contao\ManagerBundle\HttpKernel\JwtManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 */
class BackendMenuListener
{
    public function __construct(
        Security $security,
        RouterInterface $router,
        RequestStack $requestStack,
        TranslatorInterface $translator,
        bool $debug,
        ?string $managerPath,
        ?JwtManager $jwtManager
    ) {
    }

    public function __invoke(MenuEvent $event): void
    {
        $this->updateManagerLink($event);
    }

    /**
     * Updates the Contao Manager link in the back end main navigation to open in a new window/tab.
     */
    private function updateManagerLink(MenuEvent $event): void
    {
        try
        {
            if (!$event->getTree() ||
                !$event->getTree()->getChild('system') ||
                !$event->getTree()->getChild('system')->getChild('contao_manager')
            ) {
                return;
            }

            $event->getTree()->getChild('system')->getChild('contao_manager')->setLinkAttribute('target', '_blank');
        }
        catch (\Exception $e) {}
    }

    /**
     * Adds a debug button to the back end header navigation.
     */
    private function addDebugButton(MenuEvent $event): void
    {
        if (null === $this->jwtManager) {
            return;
        }

        $tree = $event->getTree();

        if ('headerMenu' !== $tree->getName()) {
            return;
        }

        if (!$request = $this->requestStack->getCurrentRequest()) {
            throw new \RuntimeException('The request stack did not contain a request');
        }

        $params = [
            'do' => 'debug',
            'key' => $this->debug ? 'disable' : 'enable',
            'referer' => base64_encode($request->server->get('QUERY_STRING', '')),
            'ref' => $request->attributes->get('_contao_referer_id'),
        ];

        $class = 'icon-debug';

        if ($this->debug) {
            $class .= ' hover';
        }

        $debug = $event->getFactory()
            ->createItem('debug')
            ->setLabel('debug_mode')
            ->setUri($this->router->generate('contao_backend', $params))
            ->setLinkAttribute('class', $class)
            ->setLinkAttribute('title', $this->translator->trans('debug_mode', [], 'ContaoManagerBundle'))
            ->setExtra('translation_domain', 'ContaoManagerBundle')
        ;

        $children = [];

        // Try adding the debug button after the alerts button
        foreach ($tree->getChildren() as $name => $item) {
            $children[$name] = $item;

            if ('alerts' === $name) {
                $children['debug'] = $debug;
            }
        }

        // Prepend the debug button if it could not be added above
        if (!isset($children['debug'])) {
            $children = ['debug' => $debug] + $children;
        }

        $tree->setChildren($children);
    }
}
