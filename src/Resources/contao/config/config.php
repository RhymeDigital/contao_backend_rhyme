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
    use Rhyme\ContaoBackendThemeBundle\Model;
    use Rhyme\ContaoBackendThemeBundle\Constants\Config;
    use Rhyme\ContaoBackendThemeBundle\Helper\EnvironmentHelper;
    use Rhyme\ContaoBackendThemeBundle\Backend\Navigation\AdjustNavItems;

    $blnVeelloLoaded = EnvironmentHelper::isBundleLoaded('Veello\ThemeBundle\VeelloThemeBundle');

    //Set global backend theme
    $GLOBALS['TL_CONFIG']['backendTheme']         = 'rhyme';

    /**
     * Back end modules
     */
    AdjustNavItems::pages();
    AdjustNavItems::notificationCenter();

    // Add to Veello BE_MOD
    if ($blnVeelloLoaded) {
        $GLOBALS['BE_MOD']['vee'][Config::BE_MOD_VLO_ELEMENT_SETS] = [
            'tables' => [
                Model\Veello\ElementSetGroup::getTable(),
                Model\Veello\ElementSet::getTable(),
                \Contao\ContentModel::getTable(),
            ],
        ];
    }

    /**
     * Hooks
     */
    $GLOBALS['TL_HOOKS']['loadDataContainer'][]         = ['Rhyme\ContaoBackendThemeBundle\Hooks\LoadDataContainer\SetTinyMCE', 'run'];
    $GLOBALS['TL_HOOKS']['outputBackendTemplate'][]     = ['Rhyme\ContaoBackendThemeBundle\Hooks\OutputBackendTemplate\AddScripts', 'run'];
    $GLOBALS['TL_HOOKS']['parseBackendTemplate'][]             = ['Rhyme\ContaoBackendThemeBundle\Hooks\ParseBackendTemplate\AdjustElementSetSelector', 'run'];
    $GLOBALS['TL_HOOKS']['parseFrontendTemplate'][]     = ['Rhyme\ContaoBackendThemeBundle\Hooks\ParseFrontendTemplate\FixFrontendHelperArticle', 'run'];


    /**
     * Models
     */
    if ($blnVeelloLoaded) {
        $GLOBALS['TL_MODELS'][Model\Veello\ElementSetGroup::getTable()]         = Model\Veello\ElementSetGroup::class;
        $GLOBALS['TL_MODELS'][Model\Veello\ElementSet::getTable()]              = Model\Veello\ElementSet::class;
    }
}
