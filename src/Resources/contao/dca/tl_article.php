<?php

/**
 * Copyright (C) 2021 Rhyme Digital, LLC.
 *
 * @link		https://rhyme.digital
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

use Contao\Input;
use Contao\CoreBundle\DataContainer\PaletteManipulator;

/**
 * List
 */

if (!Input::get('picker') && !Input::get('popup') && Input::get('do') !== 'group') {
    $GLOBALS['TL_DCA']['tl_article']['list']['sorting'] = array
    (
        'mode'                    => 4,
        'fields'                  => array('sorting'),
        'format'                  => '%s <span style="color:#999;padding-left:3px">[%s]</span>',
        'panelLayout'             => 'filter;search,limit',
        'headerFields'            => array('title', 'alias', 'type', 'published'),
        'child_record_callback'   => function($arrRow){
            return sprintf('<div class="tl_content_left">%s<span style="color:#999;padding-left:3px;font-size:12px;">[%s]</span></div>',
                $arrRow['title'],
                $arrRow['inColumn']
            );
        }
    );
}
unset($GLOBALS['TL_DCA']['tl_article']['list']['global_operations']['toggleNodes']);
