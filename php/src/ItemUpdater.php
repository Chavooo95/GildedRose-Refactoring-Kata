<?php

declare(strict_types=1);

namespace GildedRose;

use GildedRose\Class\AgedBrieUpdater;
use GildedRose\Class\BackstageUpdater;
use GildedRose\Class\ConjuredUpdater;
use GildedRose\Class\NormalUpdater;
use GildedRose\Class\SulfurasUpdater;
use GildedRose\Interface\UpdaterStrategy;

final class ItemUpdater
{
    private const AGED_BRIE = 'Aged Brie';
    private const SULFURAS = 'Sulfuras, Hand of Ragnaros';
    private const BACKSTAGE = 'Backstage passes to a TAFKAL80ETC concert';
    private const CONJURED_PREFIX = 'Conjured';

    public function update(Item $item): void
    {
        $this->strategyFor($item)->update($item);
    }

    private function strategyFor(Item $item): UpdaterStrategy
    {
        if ($item->name === self::SULFURAS) {
            return new SulfurasUpdater();
        }
        if ($item->name === self::AGED_BRIE) {
            return new AgedBrieUpdater();
        }
        if ($item->name === self::BACKSTAGE) {
            return new BackstageUpdater();
        }
        if (str_starts_with($item->name, self::CONJURED_PREFIX)) {
            return new ConjuredUpdater();
        }
        return new NormalUpdater();
    }
}
