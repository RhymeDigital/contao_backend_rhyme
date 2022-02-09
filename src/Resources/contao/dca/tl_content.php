<?php

/**
 * Copyright (C) 2021 Rhyme Digital, LLC.
 *
 * @link		https://rhyme.digital
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

use Contao\Input;

// Dynamically add the permission check
if (Input::get('do') == 'page')
{
    array_unshift($GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'], array('tl_content', 'checkPermission'));
}