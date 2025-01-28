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

    use Contao\ContentModel;
    use Rhyme\ContaoBackendThemeBundle\Constants\Veello;
    use Rhyme\ContaoBackendThemeBundle\Model;
	use Rhyme\ContaoBackendThemeBundle\Hooks;
    use Rhyme\ContaoBackendThemeBundle\Constants\Config;
    use Rhyme\ContaoBackendThemeBundle\Helper\EnvironmentHelper;
    use Rhyme\ContaoBackendThemeBundle\Backend\Navigation\AdjustNavItems;

    $blnVloLoaded = EnvironmentHelper::isVeelloLoaded();

    //Set global backend theme
    $GLOBALS['TL_CONFIG']['backendTheme']         = 'rhyme_contao_backend_theme';

    /**
     * Back end modules
     */
    AdjustNavItems::pages();
    AdjustNavItems::notificationCenter();

    // Add to Veello BE_MOD
    if ($blnVloLoaded) {
        $GLOBALS['BE_MOD']['vee'][Config::BE_MOD_VLO_ELEMENT_SETS] = [
            'tables' => [
                Model\Veello\ElementSetGroup::getTable(),
                Model\Veello\ElementSet::getTable(),
                ContentModel::getTable(),
            ],
        ];
    }

    /**
     * Hooks
     */
    $GLOBALS['TL_HOOKS']['loadDataContainer'][]         = [Hooks\LoadDataContainer\SetTinyMCE::class, 'run'];
	$GLOBALS['TL_HOOKS']['loadDataContainer'][]         = [Hooks\LoadDataContainer\FixDateSpaces::class, 'run'];
	$GLOBALS['TL_HOOKS']['loadDataContainer'][]         = [Hooks\LoadDataContainer\AdjustOperationEditIcons::class, 'run'];
    $GLOBALS['TL_HOOKS']['outputBackendTemplate'][]     = [Hooks\OutputBackendTemplate\AddScripts::class, 'run'];
    $GLOBALS['TL_HOOKS']['parseBackendTemplate'][]      = [Hooks\ParseBackendTemplate\AdjustElementSetSelector::class, 'run'];
    $GLOBALS['TL_HOOKS']['parseFrontendTemplate'][]     = [Hooks\ParseFrontendTemplate\FixFrontendHelperArticle::class, 'run'];


    /**
     * Models
     */
    if ($blnVloLoaded) {
        $GLOBALS['TL_MODELS'][Model\Veello\ElementSetGroup::getTable()]         = Model\Veello\ElementSetGroup::class;
        $GLOBALS['TL_MODELS'][Model\Veello\ElementSet::getTable()]              = Model\Veello\ElementSet::class;
    }
}
