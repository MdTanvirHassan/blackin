<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use App;

class District extends Model
{
    use HasFactory;

    public function division() {
        return $this->belongsTo(Division::class, 'division_id'); // Add quotes around 'division_id'
    }
    

    public function districts_translations(){
        return $this->hasMany(DistrictTranslation::class);
     }

     public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;
        $district_translation = $this->hasMany(DistrictTranslation::class)->where('lang', $lang)->first();
        return $district_translation != null ? $district_translation->$field : $this->$field;
    }


}
