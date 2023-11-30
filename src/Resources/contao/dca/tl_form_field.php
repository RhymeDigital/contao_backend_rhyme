<?php

declare(strict_types=1);

namespace {

    /**
     * Copyright (C) 2023 Rhyme Digital, LLC.
     *
     * @link		https://rhyme.digital
     * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
     */

    use Contao\CoreBundle\DataContainer\PaletteManipulator;

    $dca        = &$GLOBALS['TL_DCA']['tl_form_field'];
    $fields     = &$dca['fields'];

    // Set form field "type" to "chosen"
    $fields['type']['eval']['chosen'] = true;
}