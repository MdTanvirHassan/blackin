<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class GroupProductCategory extends Model
{
    use PreventDemoModeChanges;

    protected $fillable = [
        'group_product_id',
        'category_id',
    ];

    public function groupProduct()
    {
        return $this->belongsTo(GroupProduct::class);
    }

    public function category()
    {
        return $this->belongsTo(GroupProductCategoryStandalone::class, 'category_id');
    }
}

