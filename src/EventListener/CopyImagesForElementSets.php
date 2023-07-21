<?php

namespace Rhyme\ContaoBackendThemeBundle\EventListener;

use Contao\CoreBundle\Event\GenerateSymlinksEvent;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\System;
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


    public function __construct(ContaoFramework $framework, string $webDir, Filesystem $filesystem)
    {
        $this->framework = $framework;
        $this->webDir = $webDir;
        $this->filesystem = $filesystem;
    }

    public function __invoke(GenerateSymlinksEvent $event)
    {
        if ($this->framework->isInitialized() &&
            EnvironmentHelper::isBundleLoaded('Veello\ThemeBundle\VeelloThemeBundle')
        ) {
            $paths = (array)System::getContainer()->getParameter('contao.resources_paths');
            foreach ($paths as $key=>$path)
            {
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
                $fullPath = $file->getPathInfo() . DIRECTORY_SEPARATOR .  $file->getBasename();
                $filename = $file->getRelativePath();
                $this->filesystem->copy($fullPath, $this->webDir . '/' . ElementSetManager::ASSETS_PATH.'/'.$filename, true);
            }
        }
    }
}

