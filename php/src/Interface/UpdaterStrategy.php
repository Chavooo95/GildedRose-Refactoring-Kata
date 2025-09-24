<?php

declare(strict_types=1);

namespace GildedRose\Interface;

use GildedRose\Item;

interface UpdaterStrategy
{
    public function update(Item $item): void;
}