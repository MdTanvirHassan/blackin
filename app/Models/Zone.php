<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class Zone extends Model
{
    use HasFactory,PreventDemoModeChanges;



    protected $fillable = ['name', 'status', 'parent_zone_id'];

    public function carrier_range_prices(){
    	return $this->hasMany(CarrierRangePrice::class);
    }

    public function divisions()
    {
        return $this->hasMany(Division::class);
    }

    /**
     * Get the parent zone (for sub-zones)
     */
    public function parentZone()
    {
        return $this->belongsTo(Zone::class, 'parent_zone_id');
    }

    /**
     * Get the sub-zones (child zones)
     */
    public function subZones()
    {
        return $this->hasMany(Zone::class, 'parent_zone_id');
    }

    /**
     * Get the areas associated with this zone
     */
    public function areas()
    {
        return $this->belongsToMany(Area::class, 'zone_area');
    }

    /**
     * Check if this zone is a sub-zone
     */
    public function isSubZone()
    {
        return !is_null($this->parent_zone_id);
    }

}
