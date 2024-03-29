<?php

namespace {

    /**
     * Rhyme Contao Backend Theme Bundle
     *
     * Copyright (c) 2024 Rhyme.Digital
     *
     * @license LGPL-3.0+
     */

    use Contao\Input;
    use Rhyme\ContaoBackendThemeBundle\Helper\EnvironmentHelper;
    use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSet;

    // Dynamically add the permission check
    if (Input::get('do') == 'page')
    {
        array_unshift($GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'], array('tl_content', 'checkPermission'));
    }
    if (EnvironmentHelper::isBundleLoaded('Veello\ThemeBundle\VeelloThemeBundle') &&
        Input::get('ptable') === ElementSet::getTable()
    ) {
        $GLOBALS['TL_DCA']['tl_content']['list']['sorting']['headerFields'] = ['name', 'tstamp'];
        unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['vee_set']);
        // Todo: set up permissions check adjustment
    }
}
