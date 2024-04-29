<?php

namespace {

    /**
     * Rhyme Contao Backend Theme Bundle
     *
     * Copyright (c) 2024 Rhyme.Digital
     *
     * @license LGPL-3.0+
     */

    use Contao\DataContainer;
    use Contao\DC_Table;
    use Rhyme\ContaoBackendThemeBundle\Backend\ElementSet\Callbacks;
    use Rhyme\ContaoBackendThemeBundle\Constants\Veello;
    use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSet;
    use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSetGroup;
    use Rhyme\ContaoBackendThemeBundle\Helper\EnvironmentHelper;

    function initRhymeVeelloThemeElementSets() {

        $table = ElementSet::getTable();


        /**
         * Table tl_vee_element_set
         */
        $GLOBALS['TL_DCA'][$table] = [

            // Config
            'config' => [
                'dataContainer' => DC_Table::class,
                'enableVersioning' => true,
                'switchToEdit' => true,
                'ptable' => ElementSetGroup::getTable(),
                'ctable' => ['tl_content'],
                'sql' => [
                    'keys' => [
                        'id' => 'primary',
                        'pid' => 'index',
                        'alias' => 'index',
                    ],
                ],
            ],

            // List
            'list' => [
                'sorting' => [
                    'mode'                    => DataContainer::MODE_PARENT,
                    'fields'                  => array('sorting'),
                    'headerFields'            => array('name', 'tstamp'),
                    'panelLayout'             => 'filter;sort,search,limit',
                ],
                'label' => [
                    'fields'            => ['singleSRC', 'name'],
                    'showColumns'       => true,
                    'label_callback'    => array(Callbacks::class, 'listLabel'),
                ],
                'global_operations' => [
                    'all' => [
                        'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                        'href' => 'act=select',
                        'class' => 'header_edit_all',
                        'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
                    ],
                ],
                'operations' => [
                    'edit' => array
                    (
                        'href'                => 'table='.\Contao\ContentModel::getTable().'&ptable='.ElementSet::getTable(),
                        'icon'                => 'edit.svg'
                    ),
                    'editheader' => array
                    (
                        'href'                => 'act=edit',
                        'icon'                => 'header.svg'
                    ),
                    'copy' => [
                        'href' => 'act=copy',
                        'icon' => 'copy.gif',
                    ],
                    'delete' => [
                        'href' => 'act=delete',
                        'icon' => 'delete.gif',
                        'attributes' => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? '') . '\'))return false;Backend.getScrollOffset()"',
                    ],
                    'show' => [
                        'href' => 'act=show',
                        'icon' => 'show.gif',
                    ],
                ],
            ],

            // Palettes
            'palettes' => [
                '__selector__' => [],
                'default' => '{general_legend},name,alias;{image_legend},singleSRC',
            ],

            // Fields
            'fields' => [
                'id' => [
                    'sql' => ['type' => 'integer', 'unsigned' => true, 'autoincrement' => true],
                ],
                'pid' => [
                    'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
                ],
                'tstamp' => [
                    'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
                ],
                'sorting' => [
                    'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
                ],
                'name' => [
                    'exclude' => true,
                    'search' => true,
                    'inputType' => 'text',
                    'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
                    'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
                ],
                'alias' => [
                    'exclude'                 => true,
                    'search'                  => true,
                    'inputType'               => 'text',
                    'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'unique'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
                    'save_callback' => [
                        array(Callbacks::class, 'generateAlias')
                    ],
                    'sql'                     => "varchar(255) BINARY NOT NULL default ''"
                ],
                'singleSRC' => array
                (
                    'exclude'                 => true,
                    'inputType'               => 'fileTree',
                    'eval'                    => array('filesOnly'=>true, 'fieldType'=>'radio', 'tl_class'=>'clr', 'extensions'=>'svg'),
                    'sql'                     => "binary(16) NULL",
                    'save_callback'           => [
                        [Callbacks::class, 'copySingleSRCToVeello']
                    ],
                ),
            ],
        ];
    }

    if (EnvironmentHelper::isVeelloLoaded()) {
        initRhymeVeelloThemeElementSets();
    }
}