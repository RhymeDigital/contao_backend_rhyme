<?php

declare(strict_types=1);

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2020 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

namespace Rhyme\ContaoBackendThemeBundle\Backend\ElementSet;

use Contao\Files;
use Contao\System;
use Contao\Image;
use Contao\StringUtil;
use Contao\Database;
use Contao\FilesModel;
use Contao\DataContainer;
use Rhyme\ContaoBackendThemeBundle\Helper\ElementSetHelper;
use Veello\ThemeBundle\ElementSetManager;
use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSet;

/**
 *
 */
class Callbacks
{

    /**
     * Auto-generate the alias if it has not been set yet
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return string
     *
     * @throws \Exception
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $aliasExists = function (string $alias) use ($dc): bool
        {
            $t = ElementSet::getTable();
            return Database::getInstance()->prepare("SELECT id FROM $t WHERE alias=? AND id!=?")->execute($alias, $dc->id)->numRows > 0;
        };

        // Generate alias if there is none
        if (!$varValue)
        {
            $varValue = System::getContainer()->get('contao.slug')->generate($dc->activeRecord->name, null, $aliasExists);
        }
        elseif (preg_match('/^[1-9]\d*$/', $varValue))
        {
            throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasNumeric'], $varValue));
        }
        elseif ($aliasExists($varValue))
        {
            throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        return $varValue;
    }


    /**
     * @param $varValue
     * @param DataContainer $dc
     * @return mixed
     */
    public function copySingleSRCToVeello($varValue, DataContainer $dc)
    {
        if ($varValue && ($elementSetModel = ElementSet::findByPk($dc->id)) !== null) {
            ElementSetHelper::copyElementSetSingleSRCToVeello($elementSetModel);
        }

        return $varValue;
    }

    /**
     * Generate a product label and return it as HTML string
     *
     * @param array          $row
     * @param string         $label
     * @param DataContainer $dc
     *
     * @return mixed
     */
    public function listLabel($row, $label, $dc)
    {
        $elementSetModel = ElementSet::findByPk($row['id']);

        return static::generateImage($elementSetModel) . ' <div class="element-set-name">' . $row['name'] . '</div>';
    }

    /**
     * Generate image label
     *
     * @param ElementSet $elementSetModel
     *
     * @return string
     */
    public static function generateImage(ElementSet $elementSetModel) : string
    {
        $strImage = ElementSetManager::DUMMY_IMAGE_PATH;

        if ($elementSetModel->singleSRC &&
            ($objFile = FilesModel::findByUuid($elementSetModel->singleSRC)) !== null &&
            \is_file(System::getContainer()->getParameter('kernel.project_dir') . '/' . $objFile->path)
        ) {
            $strImage = $objFile->path;
        }

        /** @noinspection BadExpressionStatementJS */
        /** @noinspection HtmlUnknownTarget */
        return sprintf(
            '<div class="element-set-preview"><img src="%s" alt="%s" width="150" height="75"></div>',
            Image::get($strImage, 150, 75, 'crop'),
            StringUtil::specialchars($elementSetModel->name)
        );
    }
}