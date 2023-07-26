<?php

/**
 * Copyright (c) 2023 Rhyme Digital LLC (https://rhyme.digital)
 *
 * @license LGPL-3.0-or-later
 */

namespace Rhyme\ContaoBackendThemeBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class LoadElementSetsEvent extends Event
{
    /**
     * @var array
     */
    private array $element_sets = [];

    /**
     * @return array
     */
    public function getElementSets(): array
    {
        return $this->element_sets;
    }

    /**
     * @param array $element_sets
     * @return void
     */
    public function setElementSets(array $element_sets)
    {
        $this->element_sets = $element_sets;
    }
}