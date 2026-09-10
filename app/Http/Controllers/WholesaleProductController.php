<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MehediIitdu\CoreComponentRepository\CoreComponentRepository;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\WholesaleService;

class WholesaleProductController extends Controller
{

    public function all_wholesale_products(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $type = 'All';
        $col_name = null;
        $query = null;
        $sort_search = null;
        $seller_id  = null;

        $products = Product::where('wholesale_product', 1)->orderBy('created_at', 'desc');

        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }

        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null){
            $products = $products
                        ->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }

        $products = $products->paginate(15);

        return view('wholesale.products.index', compact('products','type', 'col_name', 'query', 'sort_search','seller_id'));
    }

    public function in_house_wholesale_products(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $type = 'In House';
        $col_name = null;
        $query = null;
        $sort_search = null;

        $products = Product::where('wholesale_product', 1)->where('added_by','admin')->orderBy('created_at', 'desc');

        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null){
            $products = $products
                        ->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }

        $products = $products->paginate(15);

        return view('wholesale.products.index', compact('products','type', 'col_name', 'query', 'sort_search'));
    }

    public function seller_wholesale_products(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $type = 'Seller';
        $col_name = null;
        $query = null;
        $sort_search = null;
        $seller_id  = null;

        $products = Product::where('wholesale_product', 1)->where('added_by','seller')->orderBy('created_at', 'desc');

        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }

        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null){
            $products = $products
                        ->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }

        $products = $products->paginate(15);

        return view('wholesale.products.index', compact('products','type', 'col_name', 'query', 'sort_search','seller_id'));
    }

    // Wholesale Products list in Seller panel
    public function wholesale_products_list_seller(Request $request)
    {
        $sort_search = null;
        $col_name = null;
        $query = null;
        $products = Product::where('wholesale_product',1)->where('user_id',Auth::user()->id)->orderBy('created_at', 'desc');
        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null){
            $products = $products
                        ->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }

        $products = $products->paginate(15);

        return view('wholesale.frontend.seller_products.index', compact('products', 'sort_search','col_name'));
    }

    public function product_create_admin()
    {
        CoreComponentRepository::initializeCache();

        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('wholesale.products.create', compact('categories'));

    }

    public function product_create_seller()
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        if(get_setting('seller_wholesale_product') == 1){
            if(addon_is_activated('seller_subscription')){
                $user = Auth::user();
                if ($user instanceof User && $user->shop->seller_package != null && $user->shop->seller_package->product_upload_limit > $user->products()->count()) {
                    return view('wholesale.frontend.seller_products.create', compact('categories'));
                }
                else {
                    flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
                    return back();
                }
            }
            else{
                return view('wholesale.frontend.seller_products.create', compact('categories'));
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function product_store_admin(Request $request)
    {
        (new WholesaleService)->store($request);
        return redirect()->route('wholesale_products.in_house');
    }

    public function product_store_seller(Request $request)
    {
        $user = Auth::user();
        if(addon_is_activated('seller_subscription') && $user instanceof User) {
            if($user->shop->seller_package == null || $user->shop->seller_package->product_upload_limit <= $user->products()->count()){
                flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
                return back();
            }
        }

        (new WholesaleService)->store($request);
        return redirect()->route('seller.wholesale_products_list');
    }


    public function product_edit_admin(Request $request, int|string $id)
    {
        CoreComponentRepository::initializeCache();

        $product = Product::findOrFail($id);
        if($product->digital == 1) {
            return redirect('digitalproducts/' . $id . '/edit');
        }

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('wholesale.products.edit', compact('product', 'categories', 'tags','lang'));
    }

    public function product_edit_seller(Request $request, int|string $id)
    {
        $product = Product::findOrFail($id);
        if($product->digital == 1) {
            return redirect('digitalproducts/' . $id . '/edit');
        }

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('wholesale.frontend.seller_products.edit', compact('product', 'categories', 'tags','lang'));
    }


    public function product_update_admin(Request $request, int|string $id)
    {
        (new WholesaleService)->update($request, $id);
        return back();
    }

    public function product_update_seller(Request $request, int|string $id)
    {
        (new WholesaleService)->update($request, $id);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\Response
     */
    public function product_destroy_admin(int|string $id)
    {
        (new WholesaleService)->destroy($id);
        return back();
    }

    public function product_destroy_seller(int|string $id)
    {
        (new WholesaleService)->destroy($id);
        return back();
    }
}
