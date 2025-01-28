<?php

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2024 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

declare(strict_types=1);

namespace Rhyme\ContaoBackendThemeBundle\Hooks\LoadDataContainer;

use Contao\ArrayUtil;
use Contao\System;

class AdjustOperationEditIcons
{

	/**
	 * @param $strTable
	 */
	public function run($strTable)
	{
		if (empty($GLOBALS['TL_DCA'][$strTable]['list']['operations'])) {
			return;
		}

		$ops = &$GLOBALS['TL_DCA'][$strTable]['list']['operations'];

		$request = System::getContainer()->get('request_stack')->getCurrentRequest();
		if (!$request
			|| !System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)
		) {
			return;
		}

		// See if the edit header icon is first, and if the children icon is second, move it to the right
		if (\array_key_first($ops) === 'edit'
			&& \array_search('children', \array_keys($ops)) === 1
		) {
			$children = $ops['children'];
			unset($ops['children']);
			ArrayUtil::arrayInsert($ops, 0, [
				'children' => $children
			]);
		}
	}

}