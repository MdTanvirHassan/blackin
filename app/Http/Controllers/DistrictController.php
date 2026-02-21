<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DistrictTranslation;
use App\Models\District;              
use App\Models\Division;              

class DistrictController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:manage_shipping_district'])->only('index','create','destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_district_name = $request->sort_district_name;
        $sort_division_id = $request->sort_division_id;
    
        $districts_queries = District::query();
    
        if ($sort_district_name) {
            $districts_queries->where('name', 'like', "%$sort_district_name%");
        }
    
        if ($sort_division_id) {
            $districts_queries->where('division_id', $sort_division_id);
        }
    
        $districts = $districts_queries->orderBy('name', 'asc')->paginate(15);
    
        // For populating the dropdown list
        $divisions_all = Division::where('status',1)->orderBy('name')->get();
    
        return view('backend.setup_configurations.districts.index', compact(
            'districts',
            'divisions_all',
            'sort_district_name',
            'sort_division_id'
        ));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:districts,name', // Division name must be unique
        ]);
    
        $district = new District;
        $district->name = $request->name;
        $district->postal_code = $request->postal_code;
        $district->division_id = $request->division_id;
        $district->save();
    
        return back()->with('success', translate('District has been inserted successfully'));
    }
    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function edit(Request $request, $id)
     {
         $lang  = $request->lang;
         $district  = District::findOrFail($id);
         $divisions  = Division::where('status',1)->orderBy('name','asc')->get();
         return view('backend.setup_configurations.districts.edit', compact('district', 'lang', 'divisions'));
     }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $district = District::findOrFail($id);
    
        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $district->name = $request->name;
        }
        $district->postal_code = $request->postal_code;
        $district->division_id = $request->division_id;
        
        $district->save();
    
        $district_translation = DistrictTranslation::firstOrNew([
            'lang' => $request->lang,
            'district_id' => $district->id,
        ]);
        $district_translation->name = $request->name;
        $district_translation->save();
    
        flash(translate('Division has been updated successfully'))->success();
        return back();

    }
    
    
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $district = District::findOrFail($id);
    
        if ($district->delete()) {  
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false]);
    }
    
    

    public function updateStatus(Request $request){
        $district = District::findOrFail($request->id);
        $district->status = $request->status;
        $district->save();

        return 1;
    }
}
