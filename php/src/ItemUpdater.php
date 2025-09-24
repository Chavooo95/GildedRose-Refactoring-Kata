<?php

declare(strict_types=1);

namespace GildedRose;

final class ItemUpdater
{
    private const AGED_BRIE = 'Aged Brie';
    private const SULFURAS = 'Sulfuras, Hand of Ragnaros';
    private const BACKSTAGE = 'Backstage passes to a TAFKAL80ETC concert';
    private const CONJURED_PREFIX = 'Conjured';

    public function update(Item $item): void
    {
        // Sulfuras no cambia nunca
        if ($item->name === self::SULFURAS) {
            return;
        }

        // Decidir upgrades/downgrades según nombre usando match
        $op = match (true) {
            $item->name === self::AGED_BRIE => function (Item $it): void {
                $it->quality += ($it->sellIn > 0) ? 1 : 2;
            },
            $item->name === self::BACKSTAGE => function (Item $it): void {
                if ($it->sellIn <= 0) {
                    $it->quality = 0;
                } else {
                    $it->quality += match (true) {
                        $it->sellIn <= 5 => 3,
                        $it->sellIn <= 10 => 2,
                        default => 1,
                    };
                }
            },
            str_starts_with($item->name, self::CONJURED_PREFIX) => function (Item $it): void {
                $step = 2;
                $multiplier = ($it->sellIn <= 0) ? 2 : 1;
                $it->quality -= $step * $multiplier;
            },
            default => function (Item $it): void {
                $step = 1;
                $multiplier = ($it->sellIn <= 0) ? 2 : 1;
                $it->quality -= $step * $multiplier;
            },
        };

        // Ejecutar operación
        $op($item);

        // Decrementa sellIn para todo excepto Sulfuras
        $item->sellIn -= 1;

        // Límites 0..50
        $item->quality = max(0, min(50, $item->quality));
    }
}
