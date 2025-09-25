<?php

declare(strict_types=1);

namespace GildedRose\Class;

use GildedRose\Interface\UpdaterStrategy;
use GildedRose\Item;

final class SulfurasUpdater implements UpdaterStrategy
{
    public function update(Item $item): void
    {
        // No cambia sellIn ni quality (tests esperan 80 y sellIn constante)
    }
}