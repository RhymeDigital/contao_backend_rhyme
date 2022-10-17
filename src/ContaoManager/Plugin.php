<?php

declare(strict_types=1);

/**
 * Rhyme Contao Backend Theme Bundle
 *
 * Copyright (c) 2020 Rhyme.Digital
 *
 * @license LGPL-3.0+
 */

namespace Rhyme\ContaoBackendThemeBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Rhyme\ContaoBackendThemeBundle\RhymeContaoBackendThemeBundle;

final class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        $loadAfter = [
            ContaoCoreBundle::class,
            'notification_center',
        ];

        if (\class_exists('Veello\ThemeBundle\VeelloThemeBundle')) {
            $loadAfter[] = \Veello\ThemeBundle\VeelloThemeBundle::class;
        }

        return [
            BundleConfig::create(RhymeContaoBackendThemeBundle::class)
                ->setLoadAfter($loadAfter),
        ];
    }
}