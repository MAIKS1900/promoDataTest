<?php

namespace App\Models;

use Database\Factories\PriceFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $price_id
 * @property int $product_id Id товара
 * @property numeric $price Цена товара
 * @property \Carbon\CarbonImmutable $price_date Дата цены
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Database\Factories\PriceFactory factory($count = null, $state = [])
 * @method static Builder<static>|Price newModelQuery()
 * @method static Builder<static>|Price newQuery()
 * @method static Builder<static>|Price query()
 * @method static Builder<static>|Price whereCreatedAt($value)
 * @method static Builder<static>|Price wherePrice($value)
 * @method static Builder<static>|Price wherePriceDate($value)
 * @method static Builder<static>|Price wherePriceId($value)
 * @method static Builder<static>|Price whereProductId($value)
 * @method static Builder<static>|Price whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Price extends Model
{
    /** @use HasFactory<PriceFactory> */
    use HasFactory;

    /** @inheritdoc */
    protected $primaryKey = 'price_id';

    /** @inheritdoc */
    protected $fillable = [
        'product_id',
        'price',
        'price_date'
    ];

    /** @inheritdoc */
    protected $casts = [
        'price_date' => 'date',
    ];

    /** @inheritdoc */
    public $timestamps = false;

    /**
     * Товар, к которому относится цена
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
