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
use Doctrine\DBAL\Connection;
use Symfony\Bundle\SecurityBundle\Security;


class PageOperationsListener
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly Security $security,
        private readonly Connection $connection,
    ) {
    }

    #[AsHook('loadDataContainer', priority: 400)]
    public function adjustArticleGlobalOperations(string $table): void
    {
        if($table === 'tl_article') {
            $dca = &$GLOBALS['TL_DCA']['tl_article'];
            $dca['list']['global_operations'] = [
                'all',
            ];
        }

        if($table === 'tl_page') {
            unset($GLOBALS['TL_DCA']['tl_page']['list']['operations']['articles']);
        }
    }

    #[AsCallback(table: 'tl_page', target: 'list.operations.articles.button', priority: 400)]
    public function adjustPageOperations(DataContainerOperation $operation): void
    {
        $operation->disable();
        unset($GLOBALS['TL_DCA']['tl_page']['list']['operations']['articles']);
    }
}