<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZoneRequest;
use App\Models\Area;
use App\Models\Country;
use App\Models\Division;
use App\Models\Zone;


class ZoneController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:manage_zones'])->only('index', 'create', 'edit', 'destroy');
    }

    public function index()
    {
        $zones = Zone::with(['parentZone', 'divisions', 'areas.city'])->latest()->paginate(10);
        return view('backend.setup_configurations.zones.index', compact('zones'));
    }


    public function create()
    {
        $divisions = Division::where('status', 1)->get();
        $parentZones = Zone::whereNull('parent_zone_id')->where('status', 1)->get();
        $areas = Area::with('city')->where('status', 1)->get();
        return view('backend.setup_configurations.zones.create', compact('divisions', 'parentZones', 'areas'));
    }


    public function store(ZoneRequest $request)
    {
        $zoneData = $request->only(['name', 'status', 'parent_zone_id']);
        
        // Only assign divisions if this is not a sub-zone
        if (empty($request->parent_zone_id)) {
            $zoneData['parent_zone_id'] = null;
            $zone = Zone::create($zoneData);
            
            if ($request->has('division_id')) {
                foreach ($request->division_id as $val) {
                    Division::where('id', $val)->update(['zone_id' => $zone->id]);
                }
            }
        } else {
            // This is a sub-zone, assign areas instead
            $zone = Zone::create($zoneData);
            
            if ($request->has('area_id')) {
                $zone->areas()->sync($request->area_id);
            }
        }
    
        flash(translate('Zone has been created successfully'))->success();
        return redirect()->route('zones.index');
    }
    

    public function edit($id)
    {
        $zone = Zone::with('areas')->findOrFail($id);
        $divisions = Division::where('status', 1)->get();
        $parentZones = Zone::whereNull('parent_zone_id')->where('status', 1)->where('id', '!=', $id)->get();
        $areas = Area::with('city')->where('status', 1)->get();

        $selectedDivisionIds = Division::where('zone_id', $zone->id)->pluck('id')->toArray();
        $selectedAreaIds = $zone->areas->pluck('id')->toArray();

        return view('backend.setup_configurations.zones.edit', compact('zone', 'divisions', 'selectedDivisionIds', 'parentZones', 'areas', 'selectedAreaIds'));
    }



    public function update(ZoneRequest $request, $id)
    {
        $zone = Zone::findOrFail($id);
    
        $updateData = [
            'name' => $request->name,
            'status' => $request->status ?? 1,
            'parent_zone_id' => $request->parent_zone_id ?? null,
        ];
        
        $zone->update($updateData);
    
        // If this is a sub-zone, manage areas
        if (!empty($request->parent_zone_id)) {
            // Clear division assignments for sub-zones
            Division::where('zone_id', $zone->id)->update(['zone_id' => null]);
            
            // Sync areas
            if ($request->has('area_id')) {
                $zone->areas()->sync($request->area_id);
            } else {
                $zone->areas()->sync([]);
            }
        } else {
            // Regular zone, manage divisions
            // Clear area assignments for regular zones
            $zone->areas()->sync([]);
            
            // Clear all division assignments first
            Division::where('zone_id', $zone->id)->update(['zone_id' => null]);
            
            // Assign new divisions
            if ($request->has('division_id')) {
                foreach ($request->division_id as $divisionId) {
                    Division::where('id', $divisionId)->update(['zone_id' => $zone->id]);
                }
            }
        }
    
        flash(translate('Zone has been updated successfully'))->success();
        return redirect()->route('zones.index');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $zone = Zone::findOrFail($id);

        Country::where('zone_id', $zone->id)->update(['zone_id' => 0]);
        
        // Clear divisions and areas before deleting
        Division::where('zone_id', $zone->id)->update(['zone_id' => null]);
        $zone->areas()->detach();
        
        // Delete sub-zones first (cascade)
        $zone->subZones()->delete();

        Zone::destroy($id);

        flash(translate('Zone has been deleted successfully'))->success();
        return redirect()->route('zones.index');
    }
}
