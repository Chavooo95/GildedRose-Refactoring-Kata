<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testKeepsItemName(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        $this->assertSame('foo', $items[0]->name);
    }

    // ... existing code ...

    public function testNormalItemDegradesQualityAndSellInEachDay(): void
    {
        $items = [new Item('Normal Item', 10, 20)];
        $app = new GildedRose($items);

        $app->updateQuality();

        $this->assertSame(9, $items[0]->sellIn);
        $this->assertSame(19, $items[0]->quality);
    }

    public function testNormalItemDegradesTwiceAsFastAfterSellDate(): void
    {
        $items = [new Item('Normal Item', 0, 10)];
        $app = new GildedRose($items);

        $app->updateQuality();

        $this->assertSame(-1, $items[0]->sellIn);
        $this->assertSame(8, $items[0]->quality);
    }

    public function testQualityNeverNegative(): void
    {
        $items = [new Item('Normal Item', 5, 0)];
        $app = new GildedRose($items);

        $app->updateQuality();

        $this->assertSame(4, $items[0]->sellIn);
        $this->assertSame(0, $items[0]->quality);
    }

    public function testAgedBrieIncreasesQualityByOneBeforeSellDate(): void
    {
        $items = [new Item('Aged Brie', 5, 10)];
        $app = new GildedRose($items);

        $app->updateQuality();

        $this->assertSame(4, $items[0]->sellIn);
        $this->assertSame(11, $items[0]->quality);
    }

    public function testAgedBrieIncreasesQualityTwiceAsFastAfterSellDate(): void
    {
        $items = [new Item('Aged Brie', 0, 10)];
        $app = new GildedRose($items);

        $app->updateQuality();

        $this->assertSame(-1, $items[0]->sellIn);
        $this->assertSame(12, $items[0]->quality);
    }

    public function testQualityNeverExceeds50ForAgedBrie(): void
    {
        $items = [new Item('Aged Brie', 10, 50)];
        $app = new GildedRose($items);

        $app->updateQuality();

        $this->assertSame(9, $items[0]->sellIn);
        $this->assertSame(50, $items[0]->quality);
    }

    public function testBackstageIncreasesBy1WhenMoreThan10Days(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20)];
        $app = new GildedRose($items);

        $app->updateQuality();

        $this->assertSame(14, $items[0]->sellIn);
        $this->assertSame(21, $items[0]->quality);
    }

    public function testBackstageIncreasesBy2When10DaysOrLess(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', 10, 20)];
        $app = new GildedRose($items);

        $app->updateQuality();

        $this->assertSame(9, $items[0]->sellIn);
        $this->assertSame(22, $items[0]->quality);
    }

    public function testBackstageIncreasesBy3When5DaysOrLess(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', 5, 20)];
        $app = new GildedRose($items);

        $app->updateQuality();

        $this->assertSame(4, $items[0]->sellIn);
        $this->assertSame(23, $items[0]->quality);
    }

    public function testBackstageQualityDropsToZeroAfterConcert(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', 0, 20)];
        $app = new GildedRose($items);

        $app->updateQuality();

        $this->assertSame(-1, $items[0]->sellIn);
        $this->assertSame(0, $items[0]->quality);
    }

    public function testBackstageQualityCappedAt50(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49)];
        $app = new GildedRose($items);

        $app->updateQuality();

        $this->assertSame(4, $items[0]->sellIn);
        $this->assertSame(50, $items[0]->quality);
    }

    public function testSulfurasNeverSoldNorDecreasesInQuality(): void
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', 0, 80)];
        $app = new GildedRose($items);

        $app->updateQuality();

        $this->assertSame(0, $items[0]->sellIn);
        $this->assertSame(80, $items[0]->quality);
    }

    public function testConjuredItemsDegradeTwiceAsFastBeforeSellDate(): void
    {
        $items = [new Item('Conjured Mana Cake', 3, 6)];
        $app = new GildedRose($items);

        $app->updateQuality();

        // Debería bajar 2 en calidad (el doble que un normal)
        $this->assertSame(2, $items[0]->sellIn);
        $this->assertSame(4, $items[0]->quality);
    }

    public function testConjuredItemsDegradeFourTimesAsFastAfterSellDate(): void
    {
        $items = [new Item('Conjured Mana Cake', 0, 8)];
        $app = new GildedRose($items);

        $app->updateQuality();

        // Antes del update era sellIn 0 (fecha), luego -1 y calidad -4 en total
        $this->assertSame(-1, $items[0]->sellIn);
        $this->assertSame(4, $items[0]->quality);
    }

    public function testConjuredQualityNeverNegative(): void
    {
        $items = [new Item('Conjured Something', 0, 3)];
        $app = new GildedRose($items);

        $app->updateQuality();

        // Caería 4, pero debe quedarse en 0
        $this->assertSame(-1, $items[0]->sellIn);
        $this->assertSame(0, $items[0]->quality);
    }
}
