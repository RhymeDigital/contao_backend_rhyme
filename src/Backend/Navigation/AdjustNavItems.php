<?php

declare(strict_types=1);

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2020 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

namespace Rhyme\ContaoBackendThemeBundle\Backend\Navigation;

use Contao\Controller;
use Contao\ArrayUtil;
use Contao\Input;

/**
 * Add scripts/stylesheets to the BE template
 */
class AdjustNavItems extends Controller
{

    /**
     * Move "Site Structure" to "Content" group and merge them all into one called "Pages"
     */
    public static function pages()
    {
        $arrArticle = $GLOBALS['BE_MOD']['content']['article'];

        // Add tl_page as first table
        ArrayUtil::arrayInsert($arrArticle['tables'], 0, ['tl_page']);

        // Insert new array as first nav item
        ArrayUtil::arrayInsert($GLOBALS['BE_MOD']['content'], 0, [
            'page' => $arrArticle,
        ]);

        // Remove the old items
        unset($GLOBALS['BE_MOD']['design']['page']);

        if (!Input::get('picker') && !Input::get('popup')) {
            unset($GLOBALS['BE_MOD']['content']['article']);
        }
    }

    /**
     * Move "Notification Center" after "Layout"
     */
    public static function notificationCenter()
    {
        if (!isset($GLOBALS['BE_MOD']['notification_center'])) {
            return;
        }

        $varLayoutIndex = array_search('design', array_keys($GLOBALS['BE_MOD']));
        if ($varLayoutIndex === false) {
            return;
        }

        $arrNotCtr = $GLOBALS['BE_MOD']['notification_center'];
        unset($GLOBALS['BE_MOD']['notification_center']);

        ArrayUtil::arrayInsert($GLOBALS['BE_MOD'], $varLayoutIndex + 1, array
        (
            'notification_center' => $arrNotCtr
        ));
    }
}