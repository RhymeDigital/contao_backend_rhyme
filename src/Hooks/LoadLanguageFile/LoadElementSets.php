<?php

/**
 * Copyright (C) 2023 Rhyme Digital, LLC.
 *
 * @link       https://rhyme.digital
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Rhyme\ContaoBackendThemeBundle\Hooks\LoadLanguageFile;

use Contao\ArrayUtil;
use Contao\Folder;
use Contao\System;
use Contao\Widget;
use Contao\Frontend;
use Contao\StringUtil;
use Symfony\Component\Finder\Finder;
use Veello\ThemeBundle\Cache;
use Veello\ThemeBundle\ElementSetManager;


/**
 * Class LoadElementSets
 * @package Rhyme\ContaoBackendThemeBundle\Hooks\LoadLanguageFile
 */
class LoadElementSets
{

    /**
     * @param string $strName
     * @param string $strLanguage
     * @param string $strCacheKey
     * @return void
     */
    public static function run(string $strName, string $strLanguage, string $strCacheKey)
    {
        //static::getAllSets();
    }



    /**
     * Get all element sets
     *
     * @return array
     */
    public static function getAllSets()
    {
        $cacheKey = 'element-sets';

        if (!Cache::has($cacheKey)) {
            $elements = ElementSetManager::getAllSets();
            $elements2 = [];

            try
            {
                $finder = Finder::create()
                    ->path(['config/element_sets'])
                    ->files()
                    ->name('*.php')
                    ->in(System::getContainer()->getParameter('contao.resources_paths'))
                ;

                if (!empty($finder) && $finder->hasResults())
                {
                    foreach ($finder as $file){
                        $elements2[] = include $file;
                    }

                    $elements2 = call_user_func_array('array_merge', $elements2);
                }
            }
            catch (\InvalidArgumentException $e)
            {
            }

            // Merge our element sets with the original
            $allElements = array_merge($elements, $elements2);

            // Insert our elements sets as the first groups
            $i = 0;
            foreach ($elements2 as $key=>$element) {
                unset($allElements[$key]);
                ArrayUtil::arrayInsert($allElements, $i++, [$key=>$element]);
            }

            Cache::set($cacheKey, $allElements);
        }

        return Cache::get($cacheKey);
    }
}