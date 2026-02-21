<?php

namespace App\Models;

use Database\Factories\ManufacturerFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $manufacturer_id
 * @property string $manufacturer_name Наименование производителя
 * @property-read Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\ManufacturerFactory factory($count = null, $state = [])
 * @method static Builder<static>|Manufacturer newModelQuery()
 * @method static Builder<static>|Manufacturer newQuery()
 * @method static Builder<static>|Manufacturer query()
 * @method static Builder<static>|Manufacturer whereManufacturerId($value)
 * @method static Builder<static>|Manufacturer whereManufacturerName($value)
 * @mixin Eloquent
 */
class Manufacturer extends Model
{
    /** @use HasFactory<ManufacturerFactory> */
    use HasFactory;

    /** @inheritdoc */
    protected $primaryKey = 'manufacturer_id';

    /** @inheritdoc */
    protected $fillable = [
        'manufacturer_name',
    ];

    /** @inheritdoc */
    public $timestamps = false;

    /**
     * Товары производителя
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'manufacturer_id', 'manufacturer_id');
    }
}
