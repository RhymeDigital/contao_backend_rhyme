<?php

/**
 * Copyright (C) 2023 Rhyme Digital, LLC.
 *
 * @link       https://rhyme.digital
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Rhyme\ContaoBackendThemeBundle\Hooks\LoadLanguageFile;

use Contao\ContentModel;
use Contao\Controller;
use Contao\Input;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Finder\Finder;
use Veello\ThemeBundle\Cache;
use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSet;
use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSetGroup;
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
        $contexts = ['page', 'article'];
        if (\in_array(Input::get('do'), $contexts) && EnvironmentHelper::isBundleLoaded('Veello\ThemeBundle\VeelloThemeBundle')) {
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
            Controller::loadLanguageFile('veetheme');

            $this->loadSetsFromFiles($elements);
            $this->loadSetsFromTables($elements);
            $this->dispatchEvent($elements);

            Cache::set($cacheKey, $elements);
        }

        return Cache::get($cacheKey);
    }


    /**
     * Load element sets from config files
     * @param array $elements
     * @return void
     */
    protected function loadSetsFromFiles(array &$elements)
    {
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
    }


    /**
     * Load element sets from DCA/tables too
     * @param array $elements
     * @return void
     */
    protected function loadSetsFromTables(array &$elements)
    {
        // Load from DCA too - Todo: move to new function
        if (($groups = ElementSetGroup::findAll()) !== null)
        {
            while ($groups->next())
            {
                $currentGroup = $groups->current();
                if (!$currentGroup->alias) continue;

                if (($sets = ElementSet::findByPid($currentGroup->id, ['order'=>ElementSet::getTable().".sorting"])) !== null)
                {
                    while ($sets->next())
                    {
                        $currentSet = $sets->current();
                        if (!$currentSet->alias) continue;

                        if (($contents = ContentModel::findPublishedByPidAndTable($currentSet->id, ElementSet::getTable())) !== null)
                        {
                            $elements[$currentGroup->alias] = $elements[$currentGroup->alias] ?? [];
                            $elements[$currentGroup->alias][$currentSet->alias] = $elements[$currentGroup->alias][$currentSet->alias] ?? [];

                            // Load the language entries too
                            $GLOBALS['TL_LANG']['VEE']['element_sets']['group_'.$currentGroup->alias] = $currentGroup->name;
                            $GLOBALS['TL_LANG']['VEE']['element_sets'][$currentSet->alias] = $currentSet->name;

                            while ($contents->next())
                            {
                                $currentEl = $contents->current();
                                $data = $currentEl->row();
                                unset($data['id']);
                                unset($data['pid']);
                                unset($data['ptable']);
                                $elements[$currentGroup->alias][$currentSet->alias][] = $data;
                            }
                            $contents->reset();
                        }
                    }
                    $sets->reset();
                }
            }
            $groups->reset();
        }
    }


    /**
     * Dispatch an event before caching
     * @param array $elements
     * @return void
     */
    protected function dispatchEvent(array &$elements)
    {
        $event = new LoadElementSetsEvent();
        $event->setElementSets($elements);
        $event = $this->eventDispatcher->dispatch($event, EventConstants::LOAD_ELEMENT_SETS);
        $elements = $event->getElementSets();
    }
}