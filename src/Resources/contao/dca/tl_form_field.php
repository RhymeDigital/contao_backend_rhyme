<?php

declare(strict_types=1);

namespace {

    /**
     * Rhyme Contao Backend Theme Bundle
     *
     * Copyright (c) 2024 Rhyme.Digital
     *
     * @license LGPL-3.0+
     */

    use Contao\CoreBundle\DataContainer\PaletteManipulator;

    $dca        = &$GLOBALS['TL_DCA']['tl_form_field'];
    $fields     = &$dca['fields'];

    // Set form field "type" to "chosen"
    $fields['type']['eval']['chosen'] = true;
}