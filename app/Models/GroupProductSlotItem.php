<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class GroupProductSlotItem extends Model
{
    use PreventDemoModeChanges;

    protected $fillable = [
        'group_product_slot_id',
        'product_id',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function slot()
    {
        return $this->belongsTo(GroupProductSlot::class, 'group_product_slot_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}


