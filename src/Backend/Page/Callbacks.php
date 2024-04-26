<?php

declare(strict_types=1);

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2024 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

namespace Rhyme\ContaoBackendThemeBundle\Backend\Page;

use Contao\Controller;
use Contao\System;
use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;

/**
 *
 */
class Callbacks extends Controller
{


    /**
     * Return the edit layout wizard
     *
     * @param DataContainer $dc
     *
     * @return string
     */
    public static function editLayout(DataContainer $dc)
    {
        if ($dc->value < 1)
        {
            return '';
        }

        $title = sprintf($GLOBALS['TL_LANG']['MSC']['editRelatedLayout'], $dc->value);
        $requestToken = System::getContainer()
            ->get('contao.csrf.token_manager')
            ->getDefaultTokenValue()
        ;

        return ' <a href="contao/main.php?do=themes&amp;table=tl_layout&amp;act=edit&amp;id=' . $dc->value . '&amp;popup=1&amp;nb=1&amp;rt=' . $requestToken . '" title="' . StringUtil::specialchars($title) . '" onclick="Backend.openModalIframe({\'title\':\'' . StringUtil::specialchars(str_replace("'", "\\'", $title)) . '\',\'url\':this.href});return false">' . Image::getHtml('alias.svg', $title) . '</a>';
    }
}