<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\DivisionTranslation;
use App\Models\District;              

class DivisionController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:manage_shipping_division'])->only('index','create','destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_division_name = $request->sort_division_name;
        $sort_division_id = $request->sort_division_id;
    
        $divisions_queries = Division::query();
    
        if ($sort_division_name) {
            $divisions_queries->where('name', 'like', "%$sort_division_name%");
        }
    
        if ($sort_division_id) {
            $divisions_queries->where('id', $sort_division_id);
        }
    
        $divisions = $divisions_queries->orderBy('status', 'desc')->paginate(15);
    
        // For populating the dropdown list
        $divisions_all = Division::orderBy('name')->get();
    
        return view('backend.setup_configurations.divisions.index', compact(
            'divisions',
            'divisions_all',
            'sort_division_name',
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
            'name' => 'required|string|max:255|unique:divisions,name', // Division name must be unique
        ]);
    
        $division = new Division;
        $division->name = $request->name;
        $division->save();
    
        return back()->with('success', translate('Division has been inserted successfully'));
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
         $division  = Division::findOrFail($id);
         return view('backend.setup_configurations.divisions.edit', compact('division', 'lang'));
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
        $division = Division::findOrFail($id);
    
        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $division->name = $request->name;
        }
    
        $division->save();
    
        $division_translation = DivisionTranslation::firstOrNew([
            'lang' => $request->lang,
            'division_id' => $division->id,
        ]);
        $division_translation->name = $request->name;
        $division_translation->save();
    
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
        $division = Division::findOrFail($id);
    
        if ($division->delete()) {  
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false]);
    }
    
    

    public function updateStatus(Request $request){
        $division = Division::findOrFail($request->id);
        $division->status = $request->status;
        $division->save();

        return 1;
    }
}
