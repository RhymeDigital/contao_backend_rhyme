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

    // Hide the "New element set" button if we're in the element set editor area
    if (EnvironmentHelper::isBundleLoaded('Veello\ThemeBundle\VeelloThemeBundle') &&
        Input::get('ptable') === ElementSet::getTable()
    ) {
        $GLOBALS['TL_DCA']['tl_content']['list']['sorting']['headerFields'] = ['name', 'tstamp'];
        $GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['vee_set'] = [
            'href'          => '',
            'class'         => '',
            'label'         => '',
            'attributes'    => ' style="display:none;"',
        ];
        // Todo: set up permissions check adjustment
    }
}
