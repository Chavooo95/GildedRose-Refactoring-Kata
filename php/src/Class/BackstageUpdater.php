<?php

declare(strict_types=1);

namespace GildedRose\Class;

use GildedRose\Interface\UpdaterStrategy;
use GildedRose\Item;

final class BackstageUpdater implements UpdaterStrategy
{
    public function update(Item $item): void
    {
        $item->sellIn -= 1;

        if ($item->sellIn < 0) {
            $item->quality = 0;
            return;
        }

        $inc = 1;
        if ($item->sellIn < 5) {
            $inc = 3;
        } elseif ($item->sellIn < 10) {
            $inc = 2;
        }

        $item->quality = self::cap($item->quality + $inc);
    }

    private static function cap(int $q): int
    {
        return max(0, min(50, $q));
    }
}