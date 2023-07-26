<?php

declare(strict_types=1);

namespace {

    /**
     * Rhyme Contao Backend Theme Bundle
     *
     * Copyright (c) 2020 Rhyme.Digital
     *
     * @license LGPL-3.0+
     */

    use Contao\ArrayUtil;
    use Rhyme\ContaoBackendThemeBundle\Backend\Navigation\AdjustNavItems;

    //Set global backend theme
    $GLOBALS['TL_CONFIG']['backendTheme']         = 'rhyme';

    /**
     * Back end modules
     */
    AdjustNavItems::pages();
    AdjustNavItems::notificationCenter();

    /**
     * Hooks
     */
    $GLOBALS['TL_HOOKS']['loadDataContainer'][]         = ['Rhyme\ContaoBackendThemeBundle\Hooks\LoadDataContainer\SetTinyMCE', 'run'];
    $GLOBALS['TL_HOOKS']['outputBackendTemplate'][]     = ['Rhyme\ContaoBackendThemeBundle\Hooks\OutputBackendTemplate\AddScripts', 'run'];
    $GLOBALS['TL_HOOKS']['parseFrontendTemplate'][]     = ['Rhyme\ContaoBackendThemeBundle\Hooks\ParseFrontendTemplate\FixFrontendHelperArticle', 'run'];
}
