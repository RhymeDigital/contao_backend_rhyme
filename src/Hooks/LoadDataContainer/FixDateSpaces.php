<?php

/**
 * Copyright (C) 2023 Rhyme Digital, LLC.
 * 
 * @author		Blair Winans <blair@rhyme.digital>
 * @author		Adam Fisher <adam@rhyme.digital>
 * @link		https://rhyme.digital
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Rhyme\ContaoBackendThemeBundle\Hooks\LoadDataContainer;

use Contao\Config;
use Contao\Input;
use Contao\Controller;


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
		if (TL_MODE === 'BE')
		{
		    if (!\is_array($GLOBALS['TL_DCA'][$strTable]['fields']))
            {
                return;
            }

		    foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $key=>$data)
            {
                // The "g" format is our main problem here (MooTools)
                if ($data['eval']['datepicker'] && \strpos(Config::get(strval($data['eval']['rgxp']).'Format'), 'g') !== false)
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