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

use Contao\System;
use Rhyme\ContaoBackendThemeBundle\Constants\Veello;

class EnvironmentHelper
{

    /**
     * Return true if Veello is loaded
     *
     * @return bool
     */
    public static function isVeelloLoaded() : bool
    {
        return static::isBundleLoaded(Veello::VEELLO_BUNDLE);
    }

    /**
     * Return true if the bundle is loaded
     *
     * @param string $bundle
     *
     * @return bool
     */
    public static function isBundleLoaded($bundle)
    {
        return in_array($bundle, System::getContainer()->getParameter('kernel.bundles'), true);
    }

    /**
     * Return true if the module is loaded
     *
     * @param string $module
     *
     * @return bool
     */
    public static function isModuleLoaded($module)
    {
        return array_key_exists($module, System::getContainer()->getParameter('kernel.bundles'));
    }

}