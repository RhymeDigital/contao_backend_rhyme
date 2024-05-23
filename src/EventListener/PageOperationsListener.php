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

use Contao\CoreBundle\DataContainer\DataContainerOperation;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Security;


class PageOperationsListener
{
    private ContaoFramework $framework;
    private Security $security;
    private Connection $connection;

    public function __construct(
        ContaoFramework $framework,
        Security $security,
        Connection $connection
    ) {
        $this->connection = $connection;
        $this->security = $security;
        $this->framework = $framework;
    }

    public function adjustArticleGlobalOperations(string $table): void
    {
        if($table === 'tl_page') {
            unset($GLOBALS['TL_DCA']['tl_page']['list']['operations']['articles']);
        }
    }

    public function adjustPageOperations(
        array $row,
        ?string $href,
        string $label,
        string $title,
        ?string $icon,
        string $attributes,
        string $table,
        array $rootRecordIds,
        ?array $childRecordIds,
        bool $circularReference,
        ?string $previous,
        ?string $next,
        DataContainer $dc
    ): string
    {
        unset($GLOBALS['TL_DCA']['tl_page']['list']['operations']['articles']);
        return '';
    }
}