<?php

declare(strict_types=1);

namespace GildedRose\Class;

use GildedRose\Interface\UpdaterStrategy;
use GildedRose\Item;

final class ConjuredUpdater implements UpdaterStrategy
{
    public function update(Item $item): void
    {
        $item->sellIn -= 1;
        $step = 2;
        $multiplier = ($item->sellIn < 0) ? 2 : 1;
        $item->quality = self::cap($item->quality - ($step * $multiplier));
    }

    private static function cap(int $q): int
    {
        return max(0, min(50, $q));
    }
}