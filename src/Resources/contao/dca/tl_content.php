<?php

namespace {

    /**
     * Copyright (C) 2021 Rhyme Digital, LLC.
     *
     * @link		https://rhyme.digital
     * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
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
