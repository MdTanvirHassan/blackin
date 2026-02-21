<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use App;

class Division extends Model
{
    use HasFactory;
    use PreventDemoModeChanges;


    public function districts() {
        return $this->hasMany(District::class);
    }
    
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function divisions_translations(){
        return $this->hasMany(DivisionTranslation::class);
     }

     public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;
        $division_translation = $this->hasMany(DivisionTranslation::class)->where('lang', $lang)->first();
        return $division_translation != null ? $division_translation->$field : $this->$field;
    }
}
