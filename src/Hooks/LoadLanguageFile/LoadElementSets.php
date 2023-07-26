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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Finder\Finder;
use Veello\ThemeBundle\Cache;
use Veello\ThemeBundle\ElementSetManager;
use Rhyme\ContaoBackendThemeBundle\Helper\EnvironmentHelper;
use Rhyme\ContaoBackendThemeBundle\Event\LoadElementSetsEvent;
use Rhyme\ContaoBackendThemeBundle\Constants\Events as EventConstants;


/**
 * Class LoadElementSets
 * @package Rhyme\ContaoBackendThemeBundle\Hooks\LoadLanguageFile
 */
class LoadElementSets
{

    private EventDispatcherInterface $eventDispatcher;
    private array $resourcesPath;


    public function __construct(EventDispatcherInterface $eventDispatcher, array $resourcesPath)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->resourcesPath = $resourcesPath;
    }

    /**
     * @param string $strName
     * @param string $strLanguage
     * @param string $strCacheKey
     * @return void
     */
    public function __invoke(string $strName, string $strLanguage, string $strCacheKey)
    {
        if (EnvironmentHelper::isBundleLoaded('Veello\ThemeBundle\VeelloThemeBundle')) {
            $this->getAllSets();
        }
    }

    /**
     * Get all element sets
     *
     * @return array
     */
    public function getAllSets()
    {
        $cacheKey = 'element-sets';

        if (!Cache::has($cacheKey)) {
            $elements = [];

            try
            {
                $finder = Finder::create()
                    ->path(['config/element_sets'])
                    ->files()
                    ->name('*.php')
                    ->in($this->resourcesPath)
                ;

                if (!empty($finder) && $finder->hasResults())
                {
                    foreach ($finder as $file){
                        $elements[] = include $file;
                    }

                    $elements = \call_user_func_array('array_merge', $elements);
                }
            }
            catch (\InvalidArgumentException $e)
            {
            }

            // Dispatch an event before caching
            $event = new LoadElementSetsEvent();
            $event->setElementSets($elements);
            $this->eventDispatcher->dispatch($event, EventConstants::LOAD_ELEMENT_SETS);

            Cache::set($cacheKey, $elements);
        }

        return Cache::get($cacheKey);
    }
}