<?php

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2024 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

namespace Rhyme\ContaoBackendThemeBundle\Hooks\LoadDataContainer;

use Contao\Config;
use Contao\Input;
use Contao\Controller;
use Contao\System;


/**
 * Class FixDateSpaces
 * @package Rhyme\ContaoBackendThemeBundle\Hooks\LoadDataContainer
 */
class FixDateSpaces extends Controller
{

    /**
     * @param $strTable
     */
	public function run($strTable)
	{
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();
        if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)) {

		    if (!\is_array($GLOBALS['TL_DCA'][$strTable]['fields']))
            {
                return;
            }

		    foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $key=>$data)
            {
                // The "g" format is our main problem here (MooTools)
                if (isset($data['eval']['datepicker']) && \strpos(Config::get(strval($data['eval']['rgxp']).'Format'), 'g') !== false)
                {
                    if (($strPostValue = \strval(Input::post($key))) && \strpos($strPostValue, '  ') !== false)
                    {
                        Input::setPost($key, \str_replace('  ', ' ', $strPostValue));
                    }
                }
            }
		}
	}

} 