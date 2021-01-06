<?php

/**
 * Copyright (C) 2021 Rhyme Digital, LLC.
 *
 * @link		https://rhyme.digital
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

use Contao\ArrayUtil;
use Contao\CoreBundle\DataContainer\PaletteManipulator;


ArrayUtil::arrayInsert($GLOBALS['TL_DCA']['tl_page']['list']['operations'], 1,
    ['editheader' => array_merge($GLOBALS['TL_DCA']['tl_page']['list']['operations']['edit'], ['icon'=>'header.gif'])]
);

$GLOBALS['TL_DCA']['tl_page']['list']['operations']['edit'] = [
    'href'                => 'table=tl_article',
    'icon'                => 'edit.gif'
];

unset($GLOBALS['TL_DCA']['tl_page']['list']['operations']['articles']);
