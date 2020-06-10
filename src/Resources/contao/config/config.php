<?php

declare(strict_types=1);

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2020 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

//Set global backend theme
$GLOBALS['TL_CONFIG']['backendTheme']         = 'rhyme';

//Hooks
$GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('Rhyme\ContaoBackendThemeBundle\Hooks\OutputBackendTemplate\AddScripts', 'run');

