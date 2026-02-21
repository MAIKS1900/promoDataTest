<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /** @var int Количество производителей для генерации */
    private const MANUFACTURERS_COUNT = 10;

    /** @var int Количество товаров у каждого производителя для генерации */
    private const PRODUCTS_PER_MANUFACTURER_COUNT = 20;

    /** @var int Количество дней, на которые есть цены по товарам */
    private const PRICES_PER_PRODUCT_COUNT = 14;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $startDate = Date::parse('2026-02-01');

        $pricesSequences = [];
        for ($i = 0; $i < self::PRICES_PER_PRODUCT_COUNT; $i++) {
            $pricesSequences[] = ['price_date' => $startDate->addDays($i)->toDateString()];
        }

        Manufacturer::factory()
            ->count(self::MANUFACTURERS_COUNT)
            ->has(
                Product::factory()
                    ->count(self::PRODUCTS_PER_MANUFACTURER_COUNT)
                    ->has(Price::factory()->count(self::PRICES_PER_PRODUCT_COUNT)->sequence(...$pricesSequences))
            )
            ->create();

    }
}
