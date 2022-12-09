<?php

declare(strict_types=1);

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2022 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

namespace Rhyme\ContaoBackendThemeBundle\Hooks\ParseFrontendTemplate;

use Contao\Controller;


class FixFrontendHelperArticle extends Controller
{

    /**
     * Fix for RockSolid Frontend helper's article edit button
     * @param string $strBuffer
     * @param string $strTemplateName
     * @param object $objTemplate
     * @return string
     */
    public function run($strBuffer, $strTemplateName, $objTemplate) {
        return \str_replace([
            'contao?do=article'
        ], [
            'contao?do=page'
        ], $strBuffer);
    }
}