<?php

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2024 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

namespace Rhyme\ContaoBackendThemeBundle\EventListener;

use Contao\ContentModel;
use Contao\Controller;
use Contao\Database;
use Contao\System;
use Contao\CoreBundle\Framework\ContaoFramework;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSet;
use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSetGroup;
use Rhyme\ContaoBackendThemeBundle\Helper\EnvironmentHelper;
use Rhyme\ContaoBackendThemeBundle\Event\LoadElementSetsEvent;
use Rhyme\ContaoBackendThemeBundle\Constants\Events as EventConstants;
use Veello\ThemeBundle\Cache\Serializer\CacheWarmer;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;


/**
 * Class LoadElementSets
 * @package Rhyme\ContaoBackendThemeBundle\Hooks\LoadLanguageFile
 */
class LoadElementSets
{

    protected EventDispatcherInterface $eventDispatcher;
    protected array $resourcesPath;
    private ContaoFramework $framework;
    protected $cacheWarmer;
    private const CACHE_PATH = 'element_sets.php';
    private Filesystem $filesystem;


    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        array $resourcesPath,
        ContaoFramework $framework,
        Filesystem $filesystem
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->resourcesPath = $resourcesPath;
        $this->framework = $framework;
        $this->filesystem = $filesystem;
    }

    /**
     * @return void
     */
    public function __invoke(RequestEvent $event)
    {
        if (!$this->framework->isInitialized()) {
            $this->framework->initialize();
        }

        if (EnvironmentHelper::isBundleLoaded('Veello\ThemeBundle\VeelloThemeBundle')) {
            $this->getAllSets();
        }
    }

    /**
     * Get all element sets
     *
     * @return void
     */
    public function getAllSets()
    {
        $elements = [];
        Controller::loadLanguageFile('veetheme');
        $this->loadSetsFromFiles($elements);
        $this->loadSetsFromTables($elements);
        $this->dispatchEvent($elements);

        $this->cacheWarmer = new CacheWarmer($this->filesystem, self::CACHE_PATH);
        $this->cacheWarmer->dumpFile(self::CACHE_PATH, $elements);
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
        $groupTable = ElementSetGroup::getTable();
        $setTable   = ElementSet::getTable();

        // Load from DCA too - Todo: move to new function
        if (Database::getInstance()->tableExists($groupTable) &&
            Database::getInstance()->tableExists($setTable) &&
            ($groups = ElementSetGroup::findAll()) !== null
        ){
            while ($groups->next())
            {
                $currentGroup = $groups->current();
                if (!$currentGroup->alias) continue;

                if (($sets = ElementSet::findByPid($currentGroup->id, ['order'=>$setTable.".sorting"])) !== null)
                {
                    while ($sets->next())
                    {
                        $currentSet = $sets->current();
                        if (!$currentSet->alias) continue;

                        if (($contents = ContentModel::findPublishedByPidAndTable($currentSet->id, $setTable)) !== null)
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