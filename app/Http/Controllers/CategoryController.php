<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\CategoryTranslation;
use App\Models\User;
use App\Models\GroupProductCategoryStandalone;
use App\Models\GroupProductCategoryTranslation;
use App\Utility\CategoryUtility;
use Illuminate\Support\Str;
use Cache;

class CategoryController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_product_categories'])->only('index');
        $this->middleware(['permission:add_product_category'])->only('create');
        $this->middleware(['permission:edit_product_category'])->only('edit');
        $this->middleware(['permission:delete_product_category'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $categories = Category::orderBy('order_level', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        }
        $categories = $categories->paginate(15);
        return view('backend.product.categories.index', compact('categories', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->order_level = 0;
        if($request->order_level != null) {
            $category->order_level = $request->order_level;
        }
        $category->digital = $request->digital;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->cover_image = $request->cover_image;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1 ;
        }

        if ($request->slug != null) {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }
        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        $category->save();

        $category->attributes()->sync($request->filtering_attributes);

        $category_translation = CategoryTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'category_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->save();

        flash(translate('Category has been inserted successfully'))->success();
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $category = Category::findOrFail($id);
        $categories = Category::where('parent_id', 0)
            ->where('digital', $category->digital)
            // ->with('childrenCategories')
            // ->whereNotIn('id', CategoryUtility::children_ids($category->id, true))->where('id', '!=' , $category->id)
            ->with(['childrenCategories' => function ($query) use ($category) {
                $query->whereNotIn('id', CategoryUtility::children_ids($category->id, true))
                      ->where('id', '!=' , $category->id);
            }])
            ->orderBy('name','asc')
            ->get();

        return view('backend.product.categories.edit', compact('category', 'categories', 'lang'));
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
        $category = Category::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $category->name = $request->name;
        }
        if($request->order_level != null) {
            $category->order_level = $request->order_level;
        }
        $category->digital = $request->digital;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->cover_image = $request->cover_image;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;

        $previous_level = $category->level;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1 ;
        }
        else{
            $category->parent_id = 0;
            $category->level = 0;
        }

        // if($category->level > $previous_level){
        //     CategoryUtility::move_level_down($category->id);
        // }
        // elseif ($category->level < $previous_level) {
        //     CategoryUtility::move_level_up($category->id);
        // }

        if ($request->slug != null) {
            $category->slug = strtolower($request->slug);
        }
        else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }


        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        $category->save();

        //Updating childer categories level
        CategoryUtility::update_child_level($category->id);

        $category->attributes()->sync($request->filtering_attributes);

        $category_translation = CategoryTranslation::firstOrNew(['lang' => $request->lang, 'category_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->save();

        Cache::forget('featured_categories');
        flash(translate('Category has been updated successfully'))->success();
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
        $category = Category::findOrFail($id);
        $category->attributes()->detach();

        // Category Translations Delete
        foreach ($category->category_translations as $key => $category_translation) {
            $category_translation->delete();
        }

        foreach (Product::where('category_id', $category->id)->get() as $product) {
            $product->category_id = null;
            $product->save();
        }

        CategoryUtility::delete_category($id);
        Cache::forget('featured_categories');

        flash(translate('Category has been deleted successfully'))->success();
        return redirect()->route('categories.index');
    }

    public function updateFeatured(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->featured = $request->status;
        $category->save();
        Cache::forget('featured_categories');
        return 1;
    }

    public function categoriesByType(Request $request)
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', $request->digital)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.categories.categories_option', compact('categories'));
    }

    public function categoriesWiseProductDiscount(Request $request){
        $sort_search =null;
        $categories = Category::with('sellerDiscounts')->orderBy('order_level', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        }
        $categories = $categories->paginate(15);
        return view('backend.product.category_wise_discount.set_discount', compact('categories', 'sort_search'));
    }
    
    public function categoriesWiseCommission(Request $request){
        $sort_search =null;
        $categories = Category::orderBy('order_level', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        }
        $categories = $categories->paginate(15);
        return view('backend.sellers.category_wise_commission.set_commission', compact('categories', 'sort_search'));
    }

    public function categoriesWiseCommissionUpdate(Request $request)
    {
     
        $categoryId = $request->input('category_id');
        $commissionRate = $request->input('commission');
    
        $category = Category::findOrFail($categoryId);
    
        // Update the main category
        $category->commision_rate = $commissionRate;
        $category->save();
    
        // Recursively update all children
        $this->updateChildrenCommissionRate($category, $commissionRate);
    
       
        return 1;
    }
    
    private function updateChildrenCommissionRate(Category $category, $commissionRate)
    {
        foreach ($category->categories as $child) {
            $child->commision_rate = $commissionRate;
            $child->save();
    
            if ($child->categories->isNotEmpty()) {
                $this->updateChildrenCommissionRate($child, $commissionRate);
            }
        }
    }

    // Group Product Category Methods
    public function groupProductCategoriesIndex(Request $request)
    {
        $sort_search = null;
        $categories = GroupProductCategoryStandalone::orderBy('order_level', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        }
        $categories = $categories->paginate(15);
        return view('backend.product.group_product_categories.index', compact('categories', 'sort_search'));
    }

    public function groupProductCategoriesCreate()
    {
        $categories = GroupProductCategoryStandalone::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.group_product_categories.create', compact('categories'));
    }

    public function groupProductCategoriesStore(Request $request)
    {
        $category = new GroupProductCategoryStandalone;
        $category->name = $request->name;
        $category->order_level = 0;
        if($request->order_level != null) {
            $category->order_level = $request->order_level;
        }
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->cover_image = $request->cover_image;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->featured = $request->has('featured') ? 1 : 0;
        $category->active = $request->has('active') ? 1 : 0;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;
            $parent = GroupProductCategoryStandalone::find($request->parent_id);
            $category->level = $parent->level + 1;
        }

        if ($request->slug != null) {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }

        $category->save();

        $category_translation = GroupProductCategoryTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'group_product_category_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->save();

        flash(translate('Group Product Category has been inserted successfully'))->success();
        return redirect()->route('group-product-categories.index');
    }

    public function groupProductCategoriesEdit(Request $request, $id)
    {
        $lang = $request->lang ?? env('DEFAULT_LANGUAGE');
        $category = GroupProductCategoryStandalone::findOrFail($id);
        $categories = GroupProductCategoryStandalone::where('parent_id', 0)
            ->with(['childrenCategories' => function ($query) use ($category) {
                $query->where('id', '!=', $category->id);
            }])
            ->orderBy('name','asc')
            ->get();

        return view('backend.product.group_product_categories.edit', compact('category', 'categories', 'lang'));
    }

    public function groupProductCategoriesUpdate(Request $request, $id)
    {
        $category = GroupProductCategoryStandalone::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $category->name = $request->name;
        }
        if($request->order_level != null) {
            $category->order_level = $request->order_level;
        }
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->cover_image = $request->cover_image;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->featured = $request->has('featured') ? 1 : 0;
        $category->active = $request->has('active') ? 1 : 0;

        $previous_level = $category->level;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;
            $parent = GroupProductCategoryStandalone::find($request->parent_id);
            $category->level = $parent->level + 1;
        }
        else{
            $category->parent_id = 0;
            $category->level = 0;
        }

        if ($request->slug != null) {
            $category->slug = strtolower($request->slug);
        }
        else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }

        $category->save();

        $category_translation = GroupProductCategoryTranslation::firstOrNew(['lang' => $request->lang, 'group_product_category_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->save();

        flash(translate('Group Product Category has been updated successfully'))->success();
        return back();
    }

    public function groupProductCategoriesDestroy($id)
    {
        $category = GroupProductCategoryStandalone::findOrFail($id);

        // Category Translations Delete
        foreach ($category->group_product_category_translations as $key => $translation) {
            $translation->delete();
        }

        // Delete children recursively
        $this->deleteGroupProductCategoryChildren($id);

        $category->delete();

        flash(translate('Group Product Category has been deleted successfully'))->success();
        return redirect()->route('group-product-categories.index');
    }

    private function deleteGroupProductCategoryChildren($id)
    {
        $children = GroupProductCategoryStandalone::where('parent_id', $id)->get();
        foreach ($children as $child) {
            $this->deleteGroupProductCategoryChildren($child->id);
            foreach ($child->group_product_category_translations as $translation) {
                $translation->delete();
            }
            $child->delete();
        }
    }

    public function groupProductCategoriesUpdateFeatured(Request $request)
    {
        $category = GroupProductCategoryStandalone::findOrFail($request->id);
        $category->featured = $request->status;
        $category->save();
        return 1;
    }

    public function groupProductCategoriesUpdateActive(Request $request)
    {
        $category = GroupProductCategoryStandalone::findOrFail($request->id);
        $category->active = $request->status;
        $category->save();
        return 1;
    }

}
