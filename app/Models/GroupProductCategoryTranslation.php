<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupProductCategoryTranslation extends Model
{
    protected $table = 'group_product_category_translations';

    protected $fillable = [
        'group_product_category_id',
        'name',
        'lang',
    ];

    public function groupProductCategoryStandalone()
    {
        return $this->belongsTo(GroupProductCategoryStandalone::class, 'group_product_category_id');
    }
}

