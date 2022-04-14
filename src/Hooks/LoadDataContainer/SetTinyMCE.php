<?php

/**
 * Copyright (c) 2020 Rhyme Digital LLC (https://rhyme.digital)
 *
 * @license LGPL-3.0-or-later
 */

namespace Rhyme\ContaoBackendThemeBundle\Hooks\LoadDataContainer;

use Contao\Backend;

/**
 * Class SetTinyMCE
 * @package Rhyme\ContaoBackendThemeBundle\Hooks\LoadDataContainer
 */
class SetTinyMCE extends Backend
{

    /**
     * Load our custom tinyMCE template so clients have more options
     * @param string $strTable
     */
    public function run($strTable)
    {
        $arrFields = &$GLOBALS['TL_DCA'][$strTable]['fields'];
        if (empty($arrFields)) return;

        foreach ($arrFields as $key=>$data)
        {
            if (!empty($data) && isset($data['eval']) && $data['eval']['rte'] === 'tinyMCE') {
                $arrFields[$key]['eval']['rte'] = 'tinyMCE_rhyme';
            }
        }
    }
}