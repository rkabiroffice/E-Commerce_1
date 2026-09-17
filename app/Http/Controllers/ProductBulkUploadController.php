<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\ProductsImport;
use App\Models\ProductsExport;
use niklasravnsborg\LaravelPdf\PdfWrapper;
use Illuminate\Support\Facades\Auth;

class ProductBulkUploadController extends Controller
{
    public function __construct() {

        $this->middleware(['permission:product_bulk_import'])->only('index');
        $this->middleware(['permission:product_bulk_export'])->only('export');
    }

    public function index()
    {
        if (Auth::user()->user_type == 'seller') {
            if(Auth::user()->shop->verification_status){
                return view('seller.product_bulk_upload.index');
            }
            else{
                flash('Your shop is not verified yet!')->warning();
                return back();
            }
        }
        elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('backend.product.bulk_upload.index');
        }
    }

    public function export(\Maatwebsite\Excel\Excel $excel){
        return $excel->download(new ProductsExport, 'products.xlsx');
    }

    public function pdf_download_category()
    {
        $categories = Category::all();

        return (new PdfWrapper)->loadView('backend.downloads.category',[
            'categories' => $categories,
        ], [], [])->download('category.pdf');
    }

    public function pdf_download_brand()
    {
        $brands = Brand::all();

        return (new PdfWrapper)->loadView('backend.downloads.brand',[
            'brands' => $brands,
        ], [], [])->download('brands.pdf');
    }

    public function pdf_download_seller()
    {
        $users = User::where('user_type','seller')->get();

        return (new PdfWrapper)->loadView('backend.downloads.user',[
            'users' => $users,
        ], [], [])->download('user.pdf');

    }

    public function bulk_upload(Request $request, \Maatwebsite\Excel\Excel $excel)
    {
        if($request->hasFile('bulk_file')){
            $import = new ProductsImport;
            $excel->import($import, request()->file('bulk_file'));
        }

        return back();
    }

}
