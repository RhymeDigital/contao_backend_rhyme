<?php

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2024 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

namespace Rhyme\ContaoBackendThemeBundle\EventListener;

use Contao\CoreBundle\Event\GenerateSymlinksEvent;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\System;
use Rhyme\ContaoBackendThemeBundle\Helper\ElementSetHelper;
use Rhyme\ContaoBackendThemeBundle\Model\Veello\ElementSet;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Veello\ThemeBundle\ElementSetManager;
use Rhyme\ContaoBackendThemeBundle\Helper\EnvironmentHelper;


/**
 * Class CopyImagesForElementSets
 * @package App\EventListener
 */
class CopyImagesForElementSets
{

    private $framework;
    private $webDir;
    private $filesystem;

    /**
     * @param ContaoFramework $framework
     * @param string $webDir
     * @param Filesystem $filesystem
     */
    public function __construct(ContaoFramework $framework, string $webDir, Filesystem $filesystem)
    {
        $this->framework = $framework;
        $this->webDir = $webDir;
        $this->filesystem = $filesystem;
    }

    /**
     * @param GenerateSymlinksEvent $event
     * @return void
     */
    public function __invoke(GenerateSymlinksEvent $event)
    {
        if ($this->framework->isInitialized() &&
            EnvironmentHelper::isBundleLoaded('Veello\ThemeBundle\VeelloThemeBundle')
        ) {
            $this->copyFilesFromElementSetsConfigFolders();
            $this->copyFilesFromElementSetRecords();
        }
    }

    /**
     * @return void
     */
    protected function copyFilesFromElementSetsConfigFolders()
    {
        $paths = (array)System::getContainer()->getParameter('contao.resources_paths');

        // Remove /contao
        foreach ($paths as $key=>$path) {
            if (\substr($path, -7) === '/contao') {
                $paths[$key] = \substr($path, 0, -7);
            }
        }

        $finder = Finder::create()
            ->path(['element_sets'])
            ->files()
            ->name('*.png')
            ->in($paths)
        ;

        foreach ($finder as $file) {
            $sep = \defined('DIRECTORY_SEPARATOR') ? DIRECTORY_SEPARATOR : '/';
            $fullPath = $file->getPathInfo() . $sep .  $file->getBasename();
            $filename = $file->getBasename();

            // Don't copy Veello images
            if (\strpos($fullPath, 'vendor/veello/theme/src') !== false) {
                continue;
            }

            $this->filesystem->copy($fullPath, $this->webDir . $sep . ElementSetManager::ASSETS_PATH . $sep . $filename, true);
        }
    }

    /**
     * @return void
     */
    protected function copyFilesFromElementSetRecords()
    {
        if (($elementSets = ElementSet::findAll()) !== null) {
            while ($elementSets->next()) {

                /** @var ElementSet $current */
                $current = $elementSets->current();
                ElementSetHelper::copyElementSetSingleSRCToVeello($current);
            }
            $elementSets->reset();
        }
    }
}

