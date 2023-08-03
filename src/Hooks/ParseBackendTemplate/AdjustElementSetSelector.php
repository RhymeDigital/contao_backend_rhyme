<?php

declare(strict_types=1);

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2020 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

namespace Rhyme\ContaoBackendThemeBundle\Hooks\ParseBackendTemplate;

use Contao\Controller;
use Contao\RequestToken;
use Contao\System;
use Rhyme\ContaoBackendThemeBundle\Constants\Config;
use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSet;


class AdjustElementSetSelector extends Controller
{
    /**
     *
     * @param string $strBuffer
     * @param string $strTemplate
     */
    public function run($strBuffer, $strTemplate) {

        if ($strTemplate === 'be_vee_element_set' &&
            ($elementSets = ElementSet::findAll()) !== null
        ) {
            $data = [];
            while ($elementSets->next()) {
                $current = $elementSets->current();
                $params = \http_build_query([
                    'do'        => Config::BE_MOD_VLO_ELEMENT_SETS,
                    'id'        => $current->id,
                    'table'     => ElementSet::getTable(),
                    'act'       => 'edit',
                    'popup'     => 1,
                    'rt'        => System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue(),
                ]);
                $data[$current->alias] = [
                    'id'        => $current->id,
                    'pid'       => $current->pid,
                    'edit'      => '/contao?'.$params,
                ];
            }
            $elementSets->reset();
            $strBuffer .= "<script>window.VeeElementSetsFromTable = ".\json_encode($data)."</script>";
        }

        return $strBuffer;
    }
}