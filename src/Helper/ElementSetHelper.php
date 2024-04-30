<?php

declare(strict_types=1);

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2024 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

namespace Rhyme\ContaoBackendThemeBundle\Helper;

use Contao\Dbafs;
use Contao\Files;
use Contao\FilesModel;
use Contao\System;
use Rhyme\ContaoBackendThemeBundle\Constants\Veello;
use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSet;

class ElementSetHelper
{

    /**
     * @param ElementSet $elementSet
     */
    public static function copyElementSetSingleSRCToVeello(ElementSet $elementSet)
    {
        if ($elementSet->singleSRC != '' &&
            ($objFile = FilesModel::findByUuid($elementSet->singleSRC)) !== null &&
            \is_file(System::getContainer()->getParameter('kernel.project_dir') . '/' . $objFile->path)
        ) {
            $projectDir = System::getContainer()->getParameter('kernel.project_dir');
            $webDir = System::getContainer()->getParameter('contao.web_dir');

            $sep = \defined('DIRECTORY_SEPARATOR') ? DIRECTORY_SEPARATOR : '/';
            $target = \str_replace($projectDir . $sep, '', $webDir . $sep . Veello::ELEMENT_SET_MANAGER_ASSETS_PATH . $sep . ($elementSet->alias ?: 'rhyme_set_' . $elementSet->id) . '.svg');
            $targetDark = \str_replace($projectDir . $sep, '', $webDir . $sep . Veello::ELEMENT_SET_MANAGER_ASSETS_PATH . $sep . ($elementSet->alias ?: 'rhyme_set_' . $elementSet->id) . '--dark.svg');

            Files::getInstance()->copy($objFile->path, $target);
            Files::getInstance()->copy($objFile->path, $targetDark);
        }
    }

}