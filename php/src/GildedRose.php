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
        // Constantes de nombres de ítems
        $AGED_BRIE = 'Aged Brie';
        $SULFURAS = 'Sulfuras, Hand of Ragnaros';
        $BACKSTAGE = 'Backstage passes to a TAFKAL80ETC concert';
        $CONJURED_PREFIX = 'Conjured';

        foreach ($this->items as $item) {
            if ($item->name === $SULFURAS) {
                continue; // Sulfuras nunca cambia
            }

            // Determinar acción según nombre
            $action = match (true) {
                $item->name === $AGED_BRIE => function () use ($item): void {
                    $item->quality += ($item->sellIn > 0) ? 1 : 2;
                },
                $item->name === $BACKSTAGE => function () use ($item): void {
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
                },
                str_starts_with($item->name, $CONJURED_PREFIX) => function () use ($item): void {
                    $degrade = 2;
                    if ($item->sellIn <= 0) {
                        $degrade *= 2;
                    }
                    $item->quality -= $degrade;
                },
                default => function () use ($item): void {
                    $degrade = 1;
                    if ($item->sellIn <= 0) {
                        $degrade *= 2;
                    }
                    $item->quality -= $degrade;
                },
            };

            // Ejecutar acción
            $action();

            // Disminuir sellIn
            $item->sellIn -= 1;

            // Aplicar límites de calidad
            if ($item->quality < 0) {
                $item->quality = 0;
            }
            if ($item->quality > 50) {
                // Backstage ya cae a 0 si sellIn < 0, sino limitamos a 50
                $item->quality = min($item->quality, 50);
            }
        }
    }
}
