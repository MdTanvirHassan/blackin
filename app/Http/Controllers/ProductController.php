<?php

namespace App\Http\Controllers;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Category;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\ProductCategory;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\User;
use App\Models\GroupProduct;
use App\Models\GroupProductItem;
use App\Models\GroupProductCategory;
use App\Models\GroupProductCategoryStandalone;
use App\Notifications\ShopProductNotification;
use Carbon\Carbon;
use CoreComponentRepository;
use Artisan;
use Cache;
use App\Services\ProductService;
use App\Services\ProductTaxService;
use App\Services\ProductFlashDealService;
use App\Services\ProductStockService;
use App\Services\FrequentlyBoughtProductService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\GroupProductSlot;
use App\Models\GroupProductSlotItem;
use App\Utility\CartUtility;

class ProductController extends Controller
{
    protected $productService;
    protected $productTaxService;
    protected $productFlashDealService;
    protected $productStockService;
    protected $frequentlyBoughtProductService;

    public function __construct(
        ProductService $productService,
        ProductTaxService $productTaxService,
        ProductFlashDealService $productFlashDealService,
        ProductStockService $productStockService,
        FrequentlyBoughtProductService $frequentlyBoughtProductService
    ) {
        $this->productService = $productService;
        $this->productTaxService = $productTaxService;
        $this->productFlashDealService = $productFlashDealService;
        $this->productStockService = $productStockService;
        $this->frequentlyBoughtProductService = $frequentlyBoughtProductService;

        // Staff Permission Check
        $this->middleware(['permission:add_new_product'])->only('create');
        $this->middleware(['permission:show_all_products'])->only('all_products');
        $this->middleware(['permission:show_in_house_products'])->only('admin_products');
        $this->middleware(['permission:show_seller_products'])->only('seller_products');
        $this->middleware(['permission:product_edit'])->only('admin_product_edit', 'seller_product_edit');
        $this->middleware(['permission:product_duplicate'])->only('duplicate');
        $this->middleware(['permission:product_delete'])->only('destroy');
        $this->middleware(['permission:set_category_wise_discount'])->only('categoriesWiseProductDiscount');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_products(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $type = 'In House';
        $col_name = null;
        $query = null;
        $sort_search = null;

        $products = Product::where('added_by', 'admin')->where('auction_product', 0)->where('wholesale_product', 0);

        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }

        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(15);

        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'sort_search'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function seller_products(Request $request, $product_type)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::where('added_by', 'seller')->where('auction_product', 0)->where('wholesale_product', 0);
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $products = $products
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        $products = $product_type == 'physical' ? $products->where('digital', 0) : $products->where('digital', 1);
        $products = $products->orderBy('created_at', 'desc')->paginate(15);
        $type = 'Seller';

        if ($product_type == 'digital') {
            return view('backend.product.digital_products.index', compact('products', 'sort_search', 'type'));
        }
        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }

    public function all_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::where('auction_product', 0)->where('wholesale_product', 0);
        if (get_setting('vendor_system_activation') != 1) {
            $products = $products->where('added_by', 'admin');
        }
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $products = $products->orderBy('created_at', 'desc')->paginate(15);
        $type = 'All';

        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        CoreComponentRepository::initializeCache();

        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.products.create', compact('categories'));
    }

    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = $this->productService->store($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]));
        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->attach($request->category_ids);

        //VAT & Tax
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        //Product Stock
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        // Frequently Bought Products
        $this->frequentlyBoughtProductService->store($request->only([
            'product_id', 'frequently_bought_selection_type', 'fq_bought_product_ids', 'fq_bought_product_category_id'
        ]));
       
        // Product Translations
        $request->merge(['lang' => env('DEFAULT_LANGUAGE')]);
        ProductTranslation::create($request->only([
            'lang', 'name', 'unit', 'description', 'product_id'
        ]));
        
        flash(translate('Product has been inserted successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route('products.admin');
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
    public function admin_product_edit(Request $request, $id)
    {
        CoreComponentRepository::initializeCache();

        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('admin/digitalproducts/' . $id . '/edit');
        }

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function seller_product_edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('digitalproducts/' . $id . '/edit');
        }
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        // $categories = Category::all();
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {

        //Product
        $product = $this->productService->update($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->sync($request->category_ids);


        //Product Stock
        $product->stocks()->delete();
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        //VAT & Tax
        if ($request->tax_id) {
            $product->taxes()->delete();
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        // Frequently Bought Products
        $product->frequently_bought_products()->delete();
        $this->frequentlyBoughtProductService->store($request->only([
            'product_id', 'frequently_bought_selection_type', 'fq_bought_product_ids', 'fq_bought_product_category_id'
        ]));

        // Product Translations
        ProductTranslation::updateOrCreate(
            $request->only([
                'lang', 'product_id'
            ]),
            $request->only([
                'name', 'unit', 'description'
            ])
        );

        flash(translate('Product has been updated successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        if($request->has('tab') && $request->tab != null){
            return Redirect::to(URL::previous() . "#" . $request->tab);
        }
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
       $result=  $this->single_product_delete($id);
        if ($result) {
            flash(translate('Product has been deleted successfully'))->success();
        }
        else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    public function single_product_delete($id)
    {
        $product = Product::findOrFail($id);

        $product->product_translations()->delete();
        $product->categories()->detach();
        $product->stocks()->delete();
        $product->taxes()->delete();
        $product->frequently_bought_products()->delete();
        $product->last_viewed_products()->delete();
        $product->flash_deal_products()->delete();
        deleteProductReview($product);
        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();
            Wishlist::where('product_id', $id)->delete();
            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return 1;
        } else {
            return 0;
        }
    }

    public function bulk_product_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $product_id) {
                $this->single_product_delete($product_id);
            }
        }

        return 1;
    }

    /**
     * Duplicates the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Request $request, $id)
    {
        $product = Product::find($id);

        //Product
        $product_new = $this->productService->product_duplicate_store($product);

        //Product Stock
        $this->productStockService->product_duplicate_store($product->stocks, $product_new);

        //VAT & Tax
        $this->productTaxService->product_duplicate_store($product->taxes, $product_new);
        
        // Product Categories
        foreach($product->product_categories as $product_category){
            ProductCategory::insert([
                'product_id' => $product_new->id,
                'category_id' => $product_category->category_id,
            ]);
        }

        // Frequently Bought Products
        $this->frequentlyBoughtProductService->product_duplicate_store($product->frequently_bought_products, $product_new);

        flash(translate('Product has been duplicated successfully'))->success();
        if ($request->type == 'In House')
            return redirect()->route('products.admin');
        elseif ($request->type == 'Seller')
            return redirect()->route('products.seller');
        elseif ($request->type == 'All')
            return redirect()->route('products.all');
        elseif ($request->type == 'SellerProfile')
             return back();
    }

    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        $product->save();
        Cache::forget('todays_deal_products');
        return 1;
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription') && $request->status == 1) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        $product->save();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }

    public function updateProductApproval(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->approved = $request->approved;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription')) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        $product->save();

        $users                  = User::findMany($product->user_id);
        $data = array();
        $data['product_type']   = $product->digital ==  0 ? 'physical' : 'digital';
        $data['status']         = $request->approved == 1 ? 'approved' : 'rejected';
        $data['product']        = $product;
        $data['notification_type_id'] = get_notification_type('seller_product_approved', 'type')->id;
        Notification::send($users, new ShopProductNotification($data));

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                if (isset($request[$name])) {
                    $data = array();
                    foreach ($request[$name] as $key => $item) {
                        // array_push($data, $item->value);
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }
            }
        }

        $combinations = (new CombinationService())->generate_combination($options);
        return view('backend.product.products.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                if (isset($request[$name])) {
                    $data = array();
                    foreach ($request[$name] as $key => $item) {
                        // array_push($data, $item->value);
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }
            }
        }

        $combinations = (new CombinationService())->generate_combination($options);
        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }

    public function product_search(Request $request)
    {
        $products = $this->productService->product_search($request->except(['_token']));
        return view('partials.product.product_search', compact('products'));
    }

    public function get_selected_products(Request $request){
        $products = product::whereIn('id', $request->product_ids)->get();
        return  view('partials.product.frequently_bought_selected_product', compact('products'));
    }

    public function setProductDiscount(Request $request)
    {
        return $this->productService->setCategoryWiseDiscount($request->except(['_token']));
    }

    // Group Product
    public function group_products_index(Request $request)
    {
        $sort_search = $request->search;
        $categoryFilter = $request->category_id;
        $publishedFilter = $request->published;

        $group_products = GroupProduct::query()
            ->with(['categories'])
            ->withCount([
                'slots as paid_slots_count' => function ($query) {
                    $query->where('is_free', false);
                },
                'slots as free_slots_count' => function ($query) {
                    $query->where('is_free', true);
                },
            ]);

        if ($sort_search) {
            $group_products->where('name', 'like', '%' . $sort_search . '%');
        }

        if ($categoryFilter) {
            $group_products->whereHas('categories', function ($query) use ($categoryFilter) {
                $query->where('group_product_categories.category_id', $categoryFilter);
            });
        }

        if ($publishedFilter !== null && $publishedFilter !== '') {
            $group_products->where('published', (int)$publishedFilter);
        }

        $group_products = $group_products->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());

        $filterCategories = GroupProductCategoryStandalone::orderBy('name', 'asc')->with('childrenCategories')->get();

        return view('backend.product.group_products.index', compact(
            'group_products',
            'sort_search',
            'filterCategories',
            'categoryFilter',
            'publishedFilter'
        ));
    }

    public function group_product_show($identifier)
    {
        $groupProduct = $this->resolveStorefrontGroupProduct($identifier);

        if ($groupProduct->slots->where('is_free', false)->isEmpty()) {
            abort(404);
        }

        $metaDescription = $groupProduct->meta_description ?: Str::limit(strip_tags($groupProduct->description), 150);
        $groupDiscountMeta = [
            'active' => (bool)$groupProduct->has_discount,
            'type' => $groupProduct->discount_type,
            'value' => $groupProduct->has_discount
                ? ($groupProduct->discount_type === 'percentage'
                    ? $groupProduct->discount_amount
                    : convert_price($groupProduct->discount_amount))
                : 0,
        ];

        return view('frontend.group_products.show', [
            'groupProduct' => $groupProduct,
            'paidSlots' => $groupProduct->slots->where('is_free', false)->values(),
            'freeSlots' => $groupProduct->slots->where('is_free', true)->values(),
            'metaTitle' => $groupProduct->meta_title ?? $groupProduct->name,
            'metaDescription' => $metaDescription,
            'currencySymbol' => currency_symbol(),
            'groupDiscountMeta' => $groupDiscountMeta,
        ]);
    }

    public function group_products_by_category(Request $request, $category_slug)
    {
        $category = GroupProductCategoryStandalone::where('slug', $category_slug)
            ->where('active', 1)
            ->with(['childrenCategories', 'coverImage', 'bannerImage', 'parentCategory'])
            ->first();

        if ($category == null) {
            abort(404);
        }

        // Helper function to get all child category IDs recursively from database
        $getAllChildIds = function($parent_id) use (&$getAllChildIds) {
            $ids = [$parent_id];
            $children = GroupProductCategoryStandalone::where('parent_id', $parent_id)
                ->where('active', 1)
                ->pluck('id')
                ->toArray();
            
            foreach ($children as $child_id) {
                $ids = array_merge($ids, $getAllChildIds($child_id));
            }
            
            return $ids;
        };
        
        // Get all child category IDs including the current category
        $category_ids = $getAllChildIds($category->id);
        
        // Get all parent category IDs if exists
        $parent_ids = [];
        $parent = $category->parentCategory;
        while ($parent) {
            $parent_ids[] = $parent->id;
            $parent = $parent->parentCategory;
        }

        $query = $request->keyword;
        $sort_by = $request->sort_by ?? 'newest';

        // Build query for group products
        $group_products = GroupProduct::where('published', 1)
            ->where('active', 1)
            ->whereHas('categories', function ($q) use ($category_ids) {
                $q->whereIn('group_product_categories_standalone.id', $category_ids);
            })
            ->with(['categories', 'thumbnail']);

        // Search filter
        if ($query != null) {
            $group_products->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%');
            });
        }

        // Sort filter
        switch ($sort_by) {
            case 'newest':
                $group_products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $group_products->orderBy('created_at', 'asc');
                break;
            case 'name-asc':
                $group_products->orderBy('name', 'asc');
                break;
            case 'name-desc':
                $group_products->orderBy('name', 'desc');
                break;
            default:
                $group_products->orderBy('created_at', 'desc');
                break;
        }

        // Get all top-level categories for sidebar
        $categories = GroupProductCategoryStandalone::with(['childrenCategories', 'coverImage'])
            ->where('parent_id', 0)
            ->where('active', 1)
            ->orderBy('order_level', 'desc')
            ->get();

        $group_products = $group_products->paginate(24)->appends(request()->query());

        $meta_title = $category->meta_title ?? $category->getTranslation('name');
        $meta_description = $category->meta_description ?? '';

        return view('frontend.group_products.listing', compact(
            'group_products',
            'category',
            'categories',
            'category_ids',
            'parent_ids',
            'query',
            'sort_by',
            'meta_title',
            'meta_description'
        ));
    }

    public function group_product_add_to_cart(Request $request, $identifier)
    {
        try {
            // Resolve group product
            try {
                $groupProduct = $this->resolveStorefrontGroupProduct($identifier);
            } catch (\Exception $e) {
                \Log::error('Error resolving group product: ' . $e->getMessage(), [
                    'identifier' => $identifier,
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withInput()->withErrors([
                    'bundle' => translate('Group product not found.') . ' Error: ' . $e->getMessage(),
                ]);
            }

            // Setup validation rules
            $rules = [
                'bundle_quantity' => ['required', 'integer', 'min:1'],
                'action_type' => ['nullable', 'in:add_to_cart,buy_now'],
            ];
            $attributes = [
                'bundle_quantity' => translate('Bundle quantity'),
            ];

            foreach ($groupProduct->slots as $slot) {
                $rules['slots.' . $slot->id . '.product_id'] = ['required', 'integer'];
                $rules['slots.' . $slot->id . '.variant'] = ['nullable', 'string'];
                $attributes['slots.' . $slot->id . '.product_id'] = $slot->name ?: translate('Slot');
                $attributes['slots.' . $slot->id . '.variant'] = $slot->name ?: translate('Slot');
            }

            // Validate request
            try {
                $validated = $request->validate($rules, [], $attributes);

            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('Validation failed for group product add to cart', [
                    'errors' => $e->errors(),
                    'request_data' => $request->all()
                ]);
                return back()->withInput()->withErrors($e->errors());
            }

            $bundleQuantity = (int)$validated['bundle_quantity'];
            $slotSelections = $validated['slots'];
            $selections = [];


            // Process slot selections
            try {
                foreach ($groupProduct->slots as $slot) {
                    $selectionPayload = $slotSelections[$slot->id] ?? null;
                    $selectedProductId = (int)($selectionPayload['product_id'] ?? 0);
                    $selectedVariant = $selectionPayload['variant'] ?? null;

                    $slotItem = $slot->slotItems->firstWhere('product_id', $selectedProductId);


                    if (!$slotItem || !$slotItem->product) {
                        return back()->withInput()->withErrors([
                            'slots.' . $slot->id => translate('Selected product is not available for this slot.'),
                        ]);
                    }

                    $product = $slotItem->product;

                    // Handle variant selection
                    // For variant products, selectedVariant should not be empty/null
                    // For non-variant products, selectedVariant can be empty string or null
                    if ($product->variant_product) {
                        // Variant product requires a variant selection
                        $selectedVariant = trim($selectedVariant ?? '');
                        if (empty($selectedVariant)) {
                            return back()->withInput()->withErrors([
                                'slots.' . $slot->id => translate('Please choose a variant for this product.'),
                            ]);
                        }
                        $stock = $product->stocks->firstWhere('variant', $selectedVariant);
                    } else {
                        // Non-variant product - use first stock, variant should be empty
                        $stock = $product->stocks->first();
                    }

                    if (!$stock) {
                        return back()->withInput()->withErrors([
                            'slots.' . $slot->id => translate('This product does not have an available stock record.'),
                        ]);
                    }

                    $selections[] = [
                        'slot' => $slot,
                        'product' => $product,
                        'stock' => $stock,
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('Error processing slot selections: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withInput()->withErrors([
                    'bundle' => translate('Error processing selections.') . ' Error: ' . $e->getMessage(),
                ]);
            }

            // Resolve cart context
            try {
                $cartContext = $this->resolveCartContext($request);
            } catch (\Exception $e) {
                \Log::error('Error resolving cart context: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withInput()->withErrors([
                    'bundle' => translate('Error initializing cart.') . ' Error: ' . $e->getMessage(),
                ]);
            }

            if (CartUtility::check_auction_in_cart($cartContext['carts'])) {
                return back()->withInput()->withErrors([
                    'bundle' => translate('Please checkout or clear auction products from your cart before adding bundles.'),
                ]);
            }

            // Generate slot combination hash for this bundle
            // Hash is based on all slot selections: slot_id + product_id + variant
            $slotCombinationData = [];
            foreach ($selections as $selection) {
                $slot = $selection['slot'];
                $product = $selection['product'];
                $variantKey = $product->variant_product ? $selection['stock']->variant : '';
                $slotCombinationData[] = [
                    'slot_id' => $slot->id,
                    'product_id' => $product->id,
                    'variant' => $variantKey ?? ''
                ];
            }
            // Sort by slot_id to ensure consistent hash generation
            usort($slotCombinationData, function($a, $b) {
                return $a['slot_id'] <=> $b['slot_id'];
            });
            $slotCombinationHash = md5(json_encode($slotCombinationData));

            // Check if group product columns exist in database
            $hasGroupProductColumns = Schema::hasColumn('carts', 'group_product_id') 
                && Schema::hasColumn('carts', 'group_product_slot_id');
            $hasCombinationHashColumn = Schema::hasColumn('carts', 'group_product_slot_combination_hash');

            // Check if there's already a bundle with the same group_product_id and same slot combination
            $existingBundle = null;
            if ($hasGroupProductColumns && $hasCombinationHashColumn) {
                $existingBundleItems = Cart::where($cartContext['key'], $cartContext['value'])
                    ->where('group_product_id', $groupProduct->id)
                    ->where('group_product_slot_combination_hash', $slotCombinationHash)
                    ->get();
                
                if ($existingBundleItems->isNotEmpty()) {
                    // Found existing bundle with same slot combination
                    $existingBundle = $existingBundleItems;
                    
                    // Check stock availability before updating quantity
                    $newBundleQuantity = $bundleQuantity;
                    foreach ($existingBundleItems as $existingItem) {
                        $existingSelection = collect($selections)->first(function($sel) use ($existingItem) {
                            return $sel['slot']->id == $existingItem->group_product_slot_id 
                                && $sel['product']->id == $existingItem->product_id
                                && ($sel['product']->variant_product ? $sel['stock']->variant : '') == $existingItem->variation;
                        });
                        
                        if ($existingSelection) {
                            $product = $existingSelection['product'];
                            $stock = $existingSelection['stock'];
                            $newQuantity = $existingItem->quantity + $newBundleQuantity;
                            
                            if ($product->digital == 0 && $stock->qty < $newQuantity) {
                                return back()->withInput()->withErrors([
                                    'bundle' => translate('Insufficient stock for updating this bundle.'),
                                ]);
                            }
                        }
                    }
                }
            }

            // Add items to cart
            try {
                // If existing bundle found, update quantities
                if ($existingBundle && $existingBundle->isNotEmpty()) {
                    foreach ($existingBundle as $existingItem) {
                        $existingSelection = collect($selections)->first(function($sel) use ($existingItem) {
                            return $sel['slot']->id == $existingItem->group_product_slot_id 
                                && $sel['product']->id == $existingItem->product_id
                                && ($sel['product']->variant_product ? $sel['stock']->variant : '') == $existingItem->variation;
                        });
                        
                        if ($existingSelection) {
                            $product = $existingSelection['product'];
                            $stock = $existingSelection['stock'];
                            $slot = $existingSelection['slot'];
                            
                            $newQuantity = $existingItem->quantity + $bundleQuantity;
                            
                            // Get base unit price per item (quantity parameter used only for wholesale pricing tiers)
                            // We pass newQuantity to get correct wholesale price tier if applicable
                            // For group products, skip individual product discount
                            $unitPrice = CartUtility::get_price($product, $stock, $newQuantity, true);
                            
                            // Apply slot discount on top of product discounted price (per unit)
                            if ($slot->discount_type === 'flat' && $slot->discount_value > 0) {
                                $unitPrice = max($unitPrice - $slot->discount_value, 0);
                            } elseif ($slot->discount_type === 'percent' && $slot->discount_value > 0) {
                                $discountPercent = min($slot->discount_value, 100);
                                $unitPrice = max($unitPrice * (100 - $discountPercent) / 100, 0);
                            }
                            
                            // Calculate tax per unit
                            $tax = CartUtility::tax_calculation($product, $unitPrice);
                            
                            // Update quantity and save unit price (cart stores unit price, total = unitPrice * quantity)
                            $existingItem->quantity = $newQuantity;
                            CartUtility::save_cart_data($existingItem, $product, $unitPrice, $tax, $newQuantity);
                        }
                    }
                } else {
                    // Create new bundle with new hash
                    foreach ($selections as $index => $selection) {
                        try {
                            $product = $selection['product'];
                            $stock = $selection['stock'];
                            $slot = $selection['slot'];

                            $variantKey = $product->variant_product ? $stock->variant : null;

                            $cartAttributes = [
                                'product_id' => $product->id,
                                'variation' => $variantKey ?? '',
                                $cartContext['key'] => $cartContext['value'],
                            ];

                            // Only include group product fields if columns exist
                            if ($hasGroupProductColumns) {
                                $cartAttributes['group_product_id'] = $groupProduct->id;
                                $cartAttributes['group_product_slot_id'] = $slot->id;
                                
                                // Include hash in attributes for new bundles
                                if ($hasCombinationHashColumn) {
                                    $cartAttributes['group_product_slot_combination_hash'] = $slotCombinationHash;
                                }
                            }

                            $cart = Cart::firstOrNew($cartAttributes);

                            $newQuantity = ($cart->exists ? $cart->quantity : 0) + $bundleQuantity;

                            if ($product->digital == 0 && $stock->qty < $newQuantity) {
                                return back()->withInput()->withErrors([
                                    'slots.' . $slot->id => translate('Insufficient stock for the selected product.'),
                                ]);
                            }

                            // Get base price with product discount
                            // For group products, skip individual product discount
                            $unitPrice = CartUtility::get_price($product, $stock, $bundleQuantity, true);
                            
                            // Apply slot discount on top of product discounted price
                            if ($slot->discount_type === 'flat' && $slot->discount_value > 0) {
                                $unitPrice = max($unitPrice - $slot->discount_value, 0);
                            } elseif ($slot->discount_type === 'percent' && $slot->discount_value > 0) {
                                $discountPercent = min($slot->discount_value, 100); // Cap at 100%
                                $unitPrice = max($unitPrice * (100 - $discountPercent) / 100, 0);
                            }
                            
                            $tax = CartUtility::tax_calculation($product, $unitPrice);

                            // Only set group product fields if columns exist
                            if ($hasGroupProductColumns) {
                                $cart->group_product_id = $groupProduct->id;
                                $cart->group_product_slot_id = $slot->id;
                                
                                // Set hash for new bundles
                                if ($hasCombinationHashColumn) {
                                    $cart->group_product_slot_combination_hash = $slotCombinationHash;
                                }
                            }
                            
                            CartUtility::save_cart_data($cart, $product, $unitPrice, $tax, $newQuantity);
                        } catch (\Exception $e) {
                            \Log::error('Error adding item to cart (slot index: ' . $index . '): ' . $e->getMessage(), [
                                'product_id' => $product->id ?? null,
                                'slot_id' => $slot->id ?? null,
                                'trace' => $e->getTraceAsString()
                            ]);
                            return back()->withInput()->withErrors([
                                'slots.' . ($slot->id ?? 'unknown') => translate('Error adding product to cart.') . ' Error: ' . $e->getMessage(),
                            ]);
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error in cart addition loop: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withInput()->withErrors([
                    'bundle' => translate('An error occurred while adding the bundle to cart.') . ' Error: ' . $e->getMessage(),
                ]);
            }

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                // Use get_user_cart() for consistency - it handles both logged in and guest users
                $updatedCarts = get_user_cart();
                
                // Get cart items for this bundle - filter by slot combination hash if available
                $hasCombinationHashColumn = Schema::hasColumn('carts', 'group_product_slot_combination_hash');
                $bundleCartItems = $updatedCarts->where('group_product_id', $groupProduct->id);
                
                // If hash column exists and we have a hash, filter by it to get only items from this specific slot combination
                if ($hasCombinationHashColumn && isset($slotCombinationHash)) {
                    $bundleCartItems = $bundleCartItems->filter(function($item) use ($slotCombinationHash) {
                        return $item->group_product_slot_combination_hash === $slotCombinationHash;
                    })->values();
                }
                
                // Calculate total price for the bundle
                $totalPrice = $bundleCartItems->sum(function($cartItem) {
                    return $cartItem->price * $cartItem->quantity;
                });
                
                return response()->json([
                    'success' => true,
                    'message' => translate('Bundle added to cart successfully.'),
                    'cart_count' => count($updatedCarts),
                    // Let the cart partial use get_user_cart() - it will handle conversion and grouping automatically
                    'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
                    'modal_view' => view('frontend.partials.cart.addedGroupProductToCart', [
                        'groupProduct' => $groupProduct,
                        'cartItems' => $bundleCartItems,
                        'totalPrice' => $totalPrice,
                        'quantity' => $bundleQuantity,
                        'slotCombinationHash' => $hasCombinationHashColumn && isset($slotCombinationHash) ? $slotCombinationHash : null,
                    ])->render(),
                    'redirect' => ($validated['action_type'] ?? 'add_to_cart') === 'buy_now' 
                        ? route('checkout.single_page_checkout') 
                        : null
                ]);
            }

            flash(translate('Bundle added to cart successfully.'))->success();

            return ($validated['action_type'] ?? 'add_to_cart') === 'buy_now'
                ? redirect()->route('checkout.single_page_checkout')
                : back();

        } catch (\Exception $e) {
            \Log::error('Unexpected error in group_product_add_to_cart: ' . $e->getMessage(), [
                'identifier' => $identifier,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = translate('An unexpected error occurred. Please check the logs or try again.') . ' Error: ' . $e->getMessage();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }
            
            return back()->withInput()->withErrors([
                'bundle' => $errorMessage,
            ]);
        }
    }

    private function resolveStorefrontGroupProduct($identifier)
    {
        $groupProduct = GroupProduct::where(function ($query) use ($identifier) {
                $query->where('slug', $identifier);
                if (is_numeric($identifier)) {
                    $query->orWhere('id', $identifier);
                }
            })
            ->where('published', 1)
            ->where('active', 1)
            ->with([
                'slots' => function ($slotQuery) {
                    $slotQuery->with([
                        'slotItems' => function ($slotItemQuery) {
                            $slotItemQuery->with(['product' => function ($productQuery) {
                                $productQuery->with(['stocks', 'taxes']);
                            }]);
                        },
                    ])->orderBy('is_free')->orderBy('sort_order');
                },
                'categories',
            ])
            ->firstOrFail();

        $groupProduct->slots->each(function ($slot) {
            $slot->setRelation('slotItems', $slot->slotItems->filter(function ($slotItem) {
                return $slotItem->product
                    && $slotItem->product->published == 1
                    && $slotItem->product->approved == 1;
            })->values());
        });

        return $groupProduct;
    }

    private function resolveCartContext(Request $request)
    {
        if (auth()->check()) {
            $carts = Cart::where('user_id', auth()->id())->get();

            return [
                'key' => 'user_id',
                'value' => auth()->id(),
                'carts' => $carts,
            ];
        }

        $tempUserId = $request->session()->get('temp_user_id');

        if (!$tempUserId) {
            $tempUserId = bin2hex(random_bytes(10));
            $request->session()->put('temp_user_id', $tempUserId);
        }

        $carts = Cart::where('temp_user_id', $tempUserId)->get();

        return [
            'key' => 'temp_user_id',
            'value' => $tempUserId,
            'carts' => $carts,
        ];
    }

    public function group_product_create()
    {
        $categories = GroupProductCategoryStandalone::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();
        
        $products = Product::where('published', 1)
            ->where('approved', 1)
            ->where('digital', 0)
            ->orderBy('name', 'asc')
            ->get();

        return view('backend.product.group_products.create', compact('categories', 'products'));
    }

    public function group_product_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'deal_type' => 'required|in:buy_3_get_1_free,buy_5_get_2_free,signature_polo_bundle,custom',
            'buy_quantity' => 'required|integer|min:1',
            'free_quantity' => 'nullable|integer|min:0',
            'slots_paid' => 'required|array|min:1',
            'slots_paid.*.name' => 'required|string|max:255',
            'slots_paid.*.discount_type' => [
                'required',
                Rule::in(['none', 'flat', 'percent']),
            ],
            'slots_paid.*.discount_value' => 'nullable|numeric|min:0',
            'slots_paid.*.product_ids' => 'required|array|min:1',
            'slots_paid.*.product_ids.*' => 'exists:products,id',
            'slots_free' => 'nullable|array',
            'slots_free.*.name' => 'required_with:slots_free|string|max:255',
            'slots_free.*.discount_type' => [
                'nullable',
                Rule::in(['none', 'flat', 'percent']),
            ],
            'slots_free.*.discount_value' => 'nullable|numeric|min:0',
            'slots_free.*.product_ids' => 'required_with:slots_free|array|min:1',
            'slots_free.*.product_ids.*' => 'exists:products,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:group_product_categories_standalone,id',
        ]);

        $buy_quantity = max((int)$request->buy_quantity, 1);
        $free_quantity = max((int)$request->free_quantity, 0);

        $paidSlots = collect($request->input('slots_paid', []))->values();
        $freeSlots = collect($request->input('slots_free', []))->filter(function ($slot) {
            return is_array($slot);
        })->values();

        if ($paidSlots->count() !== $buy_quantity) {
            return back()->withInput()->withErrors([
                'slots_paid' => translate('Number of paid slots must match buy quantity.'),
            ]);
        }

        if ($free_quantity > 0) {
            if ($freeSlots->count() !== $free_quantity) {
                return back()->withInput()->withErrors([
                    'slots_free' => translate('Number of free slots must match free quantity.'),
                ]);
            }
        } else {
            $freeSlots = collect();
        }

        $groupProduct = GroupProduct::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $request->slug ?: Str::slug($request->name),
            'thumbnail_img' => $request->thumbnail_img,
            'deal_type' => $request->deal_type,
            'buy_quantity' => $buy_quantity,
            'free_quantity' => $free_quantity,
            'random_free_products' => false,
            'has_discount' => $request->has_discount ?? false,
            'discount_amount' => $request->discount_amount,
            'discount_type' => $request->discount_type,
            'bundle_price' => null,
            'refundable' => $request->has('refundable') ? 1 : 0,
            'refund_note_id' => $request->refund_note_id,
            'published' => $request->published ?? true,
            'active' => $request->active ?? true,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        $this->storeSlotsForGroupProduct($groupProduct, $paidSlots, false);
        $this->storeSlotsForGroupProduct($groupProduct, $freeSlots, true);

        // Attach categories
        if ($request->has('category_ids') && is_array($request->category_ids) && !empty($request->category_ids)) {
            $categoryIds = array_filter(array_map('intval', $request->category_ids));
            if (!empty($categoryIds)) {
                $groupProduct->categories()->attach($categoryIds);
            }
        }

        flash(translate('Group Product has been created successfully'))->success();
        return redirect()->route('group_products.index');
    }

    public function group_product_edit($id)
    {
        $groupProduct = GroupProduct::with(['items.product', 'categories', 'slots.slotItems'])->findOrFail($id);
        
        $categories = GroupProductCategoryStandalone::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();
        
        $products = Product::where('published', 1)
            ->where('approved', 1)
            ->where('digital', 0)
            ->orderBy('name', 'asc')
            ->get();

        return view('backend.product.group_products.edit', compact('groupProduct', 'categories', 'products'));
    }

    public function group_product_update(Request $request, $id)
    {
        $groupProduct = GroupProduct::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'deal_type' => 'required|in:buy_3_get_1_free,buy_5_get_2_free,signature_polo_bundle,custom',
            'buy_quantity' => 'required|integer|min:1',
            'free_quantity' => 'nullable|integer|min:0',
            'slots_paid' => 'required|array|min:1',
            'slots_paid.*.name' => 'required|string|max:255',
            'slots_paid.*.discount_type' => [
                'required',
                Rule::in(['none', 'flat', 'percent']),
            ],
            'slots_paid.*.discount_value' => 'nullable|numeric|min:0',
            'slots_paid.*.product_ids' => 'required|array|min:1',
            'slots_paid.*.product_ids.*' => 'exists:products,id',
            'slots_free' => 'nullable|array',
            'slots_free.*.name' => 'required_with:slots_free|string|max:255',
            'slots_free.*.discount_type' => [
                'nullable',
                Rule::in(['none', 'flat', 'percent']),
            ],
            'slots_free.*.discount_value' => 'nullable|numeric|min:0',
            'slots_free.*.product_ids' => 'required_with:slots_free|array|min:1',
            'slots_free.*.product_ids.*' => 'exists:products,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:group_product_categories_standalone,id',
        ]);

        $buy_quantity = max((int)$request->buy_quantity, 1);
        $free_quantity = max((int)$request->free_quantity, 0);

        $paidSlots = collect($request->input('slots_paid', []))->values();
        $freeSlots = collect($request->input('slots_free', []))->filter(function ($slot) {
            return is_array($slot);
        })->values();

        if ($paidSlots->count() !== $buy_quantity) {
            return back()->withInput()->withErrors([
                'slots_paid' => translate('Number of paid slots must match buy quantity.'),
            ]);
        }

        if ($free_quantity > 0) {
            if ($freeSlots->count() !== $free_quantity) {
                return back()->withInput()->withErrors([
                    'slots_free' => translate('Number of free slots must match free quantity.'),
                ]);
            }
        } else {
            $freeSlots = collect();
        }

        $groupProduct->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $request->slug ?: Str::slug($request->name),
            'thumbnail_img' => $request->thumbnail_img,
            'deal_type' => $request->deal_type,
            'buy_quantity' => $buy_quantity,
            'free_quantity' => $free_quantity,
            'random_free_products' => false,
            'has_discount' => $request->has_discount ?? false,
            'discount_amount' => $request->discount_amount,
            'discount_type' => $request->discount_type,
            'bundle_price' => null,
            'refundable' => $request->has('refundable') ? 1 : 0,
            'refund_note_id' => $request->refund_note_id,
            'published' => $request->published ?? true,
            'active' => $request->active ?? true,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        // Refresh slots
        $groupProduct->slots()->delete();
        $this->storeSlotsForGroupProduct($groupProduct, $paidSlots, false);
        $this->storeSlotsForGroupProduct($groupProduct, $freeSlots, true);

        // Update categories
        if ($request->has('category_ids') && is_array($request->category_ids) && !empty($request->category_ids)) {
            $categoryIds = array_filter(array_map('intval', $request->category_ids));
            if (!empty($categoryIds)) {
                $groupProduct->categories()->sync($categoryIds);
            } else {
                $groupProduct->categories()->detach();
            }
        } else {
            $groupProduct->categories()->detach();
        }

        flash(translate('Group Product has been updated successfully'))->success();
        return redirect()->route('group_products.index');
    }

    public function group_product_destroy($id)
    {
        $groupProduct = GroupProduct::findOrFail($id);
        $groupProduct->items()->delete();
        $groupProduct->categories()->detach();
        $groupProduct->delete();

        flash(translate('Group Product has been deleted successfully'))->success();
        return redirect()->route('group_products.index');
    }

    public function group_product_update_published(Request $request)
    {
        $groupProduct = GroupProduct::findOrFail($request->id);
        $groupProduct->published = $request->status;
        $groupProduct->save();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }

    private function storeSlotsForGroupProduct(GroupProduct $groupProduct, $slots, $isFree = false)
    {
        $slots->each(function ($slotData, $index) use ($groupProduct, $isFree) {
            $discountType = $slotData['discount_type'] ?? 'none';
            $discountValue = $slotData['discount_value'] ?? 0;

            if ($discountType === 'percent' && $discountValue > 100) {
                $discountValue = 100;
            }

            $slot = GroupProductSlot::create([
                'group_product_id' => $groupProduct->id,
                'name' => $slotData['name'] ?? translate('New Slot'),
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'is_free' => $isFree,
                'sort_order' => $slotData['sort_order'] ?? $index,
            ]);

            $productIds = $slotData['product_ids'] ?? [];

            foreach ($productIds as $productIndex => $productId) {
                GroupProductSlotItem::create([
                    'group_product_slot_id' => $slot->id,
                    'product_id' => $productId,
                    'sort_order' => $productIndex,
                ]);
            }
        });
    }
}
