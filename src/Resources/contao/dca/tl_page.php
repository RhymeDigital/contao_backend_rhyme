<?php

namespace {

    /**
     * Copyright (C) 2021 Rhyme Digital, LLC.
     *
     * @link		https://rhyme.digital
     * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
     */

    use Contao\ArrayUtil;
    use Rhyme\ContaoBackendThemeBundle\Backend\Page\Callbacks;
    use Contao\CoreBundle\DataContainer\PaletteManipulator;

    $dca = &$GLOBALS['TL_DCA']['tl_page'];

    /**
     * Operations
     */
    ArrayUtil::arrayInsert($dca['list']['operations'], 1,
        ['editheader' => array_merge($dca['list']['operations']['edit'], ['icon'=>'header.gif'])]
    );

    $dca['list']['operations']['edit'] = [
        'href'                => 'table=tl_article',
        'icon'                => 'edit.gif'
    ];

    unset($dca['list']['operations']['articles']);


    /**
     * Fields
     */
    $dca['fields']['layout']['wizard'] = $dca['fields']['layout']['wizard'] ?? [
        [Callbacks::class, 'editLayout']
    ];
    $dca['fields']['subpageLayout']['wizard'] = $dca['fields']['subpageLayout']['wizard'] ?? [
            [Callbacks::class, 'editLayout']
        ];


}