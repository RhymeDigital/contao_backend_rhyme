<?php

declare(strict_types=1);

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2020 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

namespace Rhyme\ContaoBackendThemeBundle\Hooks\OutputBackendTemplate;

use Contao\Controller;

/**
 * Add scripts/stylesheets to the BE template
 */
class AddScripts extends Controller
{
    /**
     * Run the hook $GLOBALS['TL_HOOKS']['outputBackendTemplate'] in Contao\BackendTemplate
     *
     * @param string $strBuffer
     * @param string $strTemplate
     */
    public function run($strBuffer, $strTemplate) {

        $backendTemplates = array('be_main', 'be_login');

        if(in_array($strTemplate, $backendTemplates)) {

            $scripts = '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700,800,900">';
            $scripts .= "\n";
            $scripts .= '<script src="bundles/rhymecontaobackendtheme/assets/js/datepickerfixes.js"></script>';
            $strBuffer = str_replace('</head>', ($scripts . '</head>'), $strBuffer);
        }

        return $strBuffer;
    }
}