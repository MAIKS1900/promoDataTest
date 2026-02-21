<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $product_id
 * @property string $product_name Наименование товара
 * @property int $category_id Id категории товара
 * @property int $manufacturer_id Id производителя
 * @property-read \App\Models\Manufacturer $manufacturer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Price> $prices
 * @property-read int|null $prices_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static Builder<static>|Product newModelQuery()
 * @method static Builder<static>|Product newQuery()
 * @method static Builder<static>|Product query()
 * @method static Builder<static>|Product whereCategoryId($value)
 * @method static Builder<static>|Product whereManufacturerId($value)
 * @method static Builder<static>|Product whereProductId($value)
 * @method static Builder<static>|Product whereProductName($value)
 * @mixin Eloquent
 */
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /** @inheritdoc */
    protected $primaryKey = 'product_id';

    /** @inheritdoc */
    protected $fillable = [
        'product_name',
        'category_id',
        'manufacturer_id'
    ];

    /** @inheritdoc */
    public $timestamps = false;

    /**
     * Производитель товара
     *
     * @return BelongsTo
     */
    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id', 'manufacturer_id');
    }

    /**
     * Цены на товар
     *
     * @return HasMany
     */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class, 'product_id', 'product_id');
    }
}
