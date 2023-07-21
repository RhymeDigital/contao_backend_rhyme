<?php

declare(strict_types=1);

namespace Rhyme\ContaoBackendThemeBundle\Helper;

use Contao\System;

class EnvironmentHelper
{

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
        return array_key_exists($module, static::getContainer()->getParameter('kernel.bundles'));
    }

}