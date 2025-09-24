<?php

declare(strict_types=1);

namespace GildedRose\Class;

use GildedRose\Interface\UpdaterStrategy;
use GildedRose\Item;

final class AgedBrieUpdater implements UpdaterStrategy
{
    public function update(Item $item): void
    {
        $item->sellIn -= 1;
        $delta = ($item->sellIn >= 0) ? 1 : 2;
        $item->quality = self::cap($item->quality + $delta);
    }

    private static function cap(int $q): int
    {
        return max(0, min(50, $q));
    }
}