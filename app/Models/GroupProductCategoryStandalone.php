<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use App;

class GroupProductCategoryStandalone extends Model
{
    use PreventDemoModeChanges;

    protected $table = 'group_product_categories_standalone';

    protected $fillable = [
        'name',
        'parent_id',
        'level',
        'order_level',
        'banner',
        'icon',
        'cover_image',
        'slug',
        'meta_title',
        'meta_description',
        'featured',
        'active',
    ];

    protected $with = ['group_product_category_translations'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $translation = $this->group_product_category_translations->where('lang', $lang)->first();
        return $translation != null ? $translation->$field : $this->$field;
    }

    public function group_product_category_translations()
    {
        return $this->hasMany(GroupProductCategoryTranslation::class, 'group_product_category_id');
    }

    public function coverImage()
    {
        return $this->belongsTo(Upload::class, 'cover_image');
    }

    public function catIcon()
    {
        return $this->belongsTo(Upload::class, 'icon');
    }

    public function bannerImage()
    {
        return $this->belongsTo(Upload::class, 'banner');
    }

    public function categories()
    {
        return $this->hasMany(GroupProductCategoryStandalone::class, 'parent_id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(GroupProductCategoryStandalone::class, 'parent_id')->with('categories');
    }

    public function parentCategory()
    {
        return $this->belongsTo(GroupProductCategoryStandalone::class, 'parent_id');
    }

    public function groupProducts()
    {
        return $this->belongsToMany(GroupProduct::class, 'group_product_categories', 'category_id', 'group_product_id');
    }
}

