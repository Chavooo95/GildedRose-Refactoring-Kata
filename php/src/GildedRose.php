<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            // Sulfuras no cambia nunca
            if ($item->name === 'Sulfuras, Hand of Ragnaros') {
                continue;
            }

            $isBackstage = $item->name === 'Backstage passes to a TAFKAL80ETC concert';
            $isBrie = $item->name === 'Aged Brie';
            $isConjured = str_starts_with($item->name, 'Conjured');

            // Actualiza calidad según tipo
            if ($isBrie) {
                // Brie sube 1, y tras expirar sube 2
                $item->quality += ($item->sellIn > 0) ? 1 : 2;
            } elseif ($isBackstage) {
                // Backstage sube 1/2/3 y tras concierto cae a 0
                if ($item->sellIn <= 0) {
                    $item->quality = 0;
                } else {
                    $item->quality += 1;
                    if ($item->sellIn <= 10) {
                        $item->quality += 1;
                    }
                    if ($item->sellIn <= 5) {
                        $item->quality += 1;
                    }
                }
            } else {
                // Normal/Conjured: baja 1 o 2, tras expirar el doble
                $degrade = $isConjured ? 2 : 1;
                if ($item->sellIn <= 0) {
                    $degrade *= 2;
                }
                $item->quality -= $degrade;
            }

            // Decrementa sellIn para todo excepto Sulfuras (ya hemos hecho continue antes)
            $item->sellIn -= 1;

            // Reglas de límites generales
            if ($item->name !== 'Sulfuras, Hand of Ragnaros') {
                if ($item->quality < 0) {
                    $item->quality = 0;
                }
                if ($item->quality > 50 && !$isBackstage) {
                    // Backstage también está limitado a 50 antes del concierto,
                    // pero si ya cayó a 0 no lo subimos.
                    $item->quality = 50;
                }
                if ($isBackstage && $item->sellIn >= 0 && $item->quality > 50) {
                    $item->quality = 50;
                }
            }
        }
    }
}
