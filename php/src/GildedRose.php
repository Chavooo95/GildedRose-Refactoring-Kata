<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items,
        private ?ItemUpdater $updater = null
    ) {
        $this->updater ??= new ItemUpdater();
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $this->updater->update($item);
        }
    }
}