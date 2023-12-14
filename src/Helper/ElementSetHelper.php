<?php

declare(strict_types=1);

namespace Rhyme\ContaoBackendThemeBundle\Helper;

use Contao\Files;
use Contao\FilesModel;
use Contao\System;
use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSet;
use Veello\ThemeBundle\ElementSetManager;

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
            $target = \str_replace($projectDir . $sep, '', $webDir . $sep . ElementSetManager::ASSETS_PATH . $sep . ($elementSet->alias ?: 'rhyme_set_' . $elementSet->id) . '.png');

            Files::getInstance()->copy($objFile->path, $target);
        }
    }

}