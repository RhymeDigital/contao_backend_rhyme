<?php

namespace {

    use Contao\DataContainer;
    use Rhyme\ContaoBackendThemeBundle\Backend\ElementSetGroup\Callbacks;
    use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSet;
    use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSetGroup;
    use Rhyme\ContaoBackendThemeBundle\Helper\EnvironmentHelper;

    function initRhymeVeelloThemeElementSetGroups() {

        $table = ElementSetGroup::getTable();


        /**
         * Table tl_vee_element_set_group
         */
        $GLOBALS['TL_DCA'][$table] = [

            // Config
            'config' => [
                'dataContainer' => 'Table',
                'enableVersioning' => true,
                'switchToEdit' => true,
                'ctable' => [ElementSet::getTable()],
                'sql' => [
                    'keys' => [
                        'id' => 'primary',
                        'alias' => 'index',
                    ],
                ],
            ],

            // List
            'list' => [
                'sorting' => array
                (
                    'mode'                    => DataContainer::MODE_SORTED,
                    'fields'                  => array('name'),
                    'flag'                    => DataContainer::SORT_INITIAL_LETTER_ASC,
                    'panelLayout'             => 'filter;search,limit'
                ),
                'label' => [
                    'fields' => ['name'],
                    'showColumns' => true,
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
                        'href'                => 'table='.ElementSet::getTable(),
                        'icon'                => 'edit.svg'
                    ),
                    'editheader' => array
                    (
                        'href'                => 'act=edit',
                        'icon'                => 'header.svg',
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
                'default' => '{general_legend},name,alias',
            ],

            // Fields
            'fields' => [
                'id' => [
                    'sql' => ['type' => 'integer', 'unsigned' => true, 'autoincrement' => true],
                ],
                'tstamp' => [
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
            ],
        ];
    }

    if (EnvironmentHelper::isBundleLoaded('Veello\ThemeBundle\VeelloThemeBundle')) {
        initRhymeVeelloThemeElementSetGroups();
    }
}