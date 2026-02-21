<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class GroupProduct extends Model
{
    use PreventDemoModeChanges;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'thumbnail_img',
        'deal_type',
        'buy_quantity',
        'free_quantity',
        'random_free_products',
        'has_discount',
        'discount_amount',
        'discount_type',
        'bundle_price',
        'refundable',
        'refund_note_id',
        'published',
        'active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'random_free_products' => 'boolean',
        'has_discount' => 'boolean',
        'refundable' => 'boolean',
        'published' => 'boolean',
        'active' => 'boolean',
        'buy_quantity' => 'integer',
        'free_quantity' => 'integer',
        'discount_amount' => 'decimal:2',
        'bundle_price' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(GroupProductItem::class)->orderBy('sort_order');
    }

    public function paidItems()
    {
        return $this->hasMany(GroupProductItem::class)->where('is_free', false)->orderBy('sort_order');
    }

    public function freeItems()
    {
        return $this->hasMany(GroupProductItem::class)->where('is_free', true)->orderBy('sort_order');
    }

    public function slots()
    {
        return $this->hasMany(GroupProductSlot::class)->orderBy('sort_order');
    }

    public function categories()
    {
        return $this->belongsToMany(GroupProductCategoryStandalone::class, 'group_product_categories', 'group_product_id', 'category_id');
    }

    public function group_product_categories()
    {
        return $this->hasMany(GroupProductCategory::class);
    }

    public function thumbnail()
    {
        return $this->belongsTo(Upload::class, 'thumbnail_img');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'group_product_items', 'group_product_id', 'product_id')
                    ->withPivot('is_free', 'sort_order')
                    ->orderBy('group_product_items.sort_order');
    }

    public function refundNote()
    {
        return $this->belongsTo(Note::class, 'refund_note_id');
    }
}

