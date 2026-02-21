<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class GroupProductSlot extends Model
{
    use PreventDemoModeChanges;

    protected $fillable = [
        'group_product_id',
        'name',
        'discount_type',
        'discount_value',
        'is_free',
        'sort_order',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'is_free' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function groupProduct()
    {
        return $this->belongsTo(GroupProduct::class);
    }

    public function slotItems()
    {
        return $this->hasMany(GroupProductSlotItem::class)
            ->orderBy('sort_order');
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'group_product_slot_items',
            'group_product_slot_id',
            'product_id'
        )->withPivot('sort_order')
         ->orderBy('group_product_slot_items.sort_order');
    }
}


