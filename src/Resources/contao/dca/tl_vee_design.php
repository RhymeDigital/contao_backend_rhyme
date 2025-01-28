<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace {

	use Contao\CoreBundle\DataContainer\PaletteManipulator;

	/**
	 * Table tl_vee_design
	 */
	$table = 'tl_vee_design';
	
	if ($GLOBALS['TL_DCA'][$table])
	{
		$dca    = &$GLOBALS['TL_DCA'][$table];
		$fields = &$dca['fields'];
		$palettes = &$dca['palettes'];

		/**
		 * Palettes
		 */
		PaletteManipulator::create()
			->addField('fourthColor', 'secondaryColor')
			->addField('thirdColor', 'secondaryColor')
			->applyToPalette('default', $table)
		;

		/**
		 * Fields
		 */
		$fields['thirdColor'] = [
			'label' => &$GLOBALS['TL_LANG'][$table]['thirdColor'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => [
				'mandatory' => false,
				'maxlength' => 6,
				'colorpicker' => true,
				'isHexColor' => true,
				'tl_class' => 'w50 wizard clr',
			],
			'vee_helpWizardGroup' => 'color',
			'vee_scss' => ['name' => 'thirdColor', 'format' => \Veello\ThemeBundle\Style\Property\ColorProperty::IDENTIFIER],
			'sql' => ['type' => 'string', 'length' => 6, 'default' => ''],
		];
		$fields['fourthColor'] = [
			'label' => &$GLOBALS['TL_LANG'][$table]['fourthColor'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => [
				'maxlength' => 6,
				'colorpicker' => true,
				'isHexColor' => true,
				'tl_class' => 'w50 wizard',
			],
			'vee_helpWizardGroup' => 'color',
			'vee_scss' => ['name' => 'fourthColor', 'format' => \Veello\ThemeBundle\Style\Property\ColorProperty::IDENTIFIER],
			'sql' => ['type' => 'string', 'length' => 6, 'default' => ''],
		];
	}
}