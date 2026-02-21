<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class DivisionTranslation extends Model
{
  use PreventDemoModeChanges;

  protected $fillable = ['name', 'lang', 'division_id'];

  public function division(){
    return $this->belongsTo(Division::class);
  }
}
