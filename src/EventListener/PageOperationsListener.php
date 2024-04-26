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

use Contao\ArrayUtil;
use Contao\CoreBundle\DataContainer\DataContainerOperation;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\DataContainer;
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

    public function adjustArticleGlobalOperations(string $table): void
    {

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

        $dca = &$GLOBALS['TL_DCA']['tl_page'];
        ArrayUtil::arrayInsert($dca['list']['operations'], 1,
            ['editheader' =>  [
                'href' => 'act=edit',
                'icon' => 'header.gif'
            ]]
        );

        $dca['list']['operations']['edit'] = [
            'href'                => 'table=tl_article',
            'icon'                => 'edit.gif'
        ];

        return '';
    }
}