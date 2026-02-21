<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class GroupProductItem extends Model
{
    use PreventDemoModeChanges;

    protected $fillable = [
        'group_product_id',
        'product_id',
        'is_free',
        'sort_order',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function groupProduct()
    {
        return $this->belongsTo(GroupProduct::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

