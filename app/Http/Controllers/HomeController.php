<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $brands = Brand::all();

        $categories = Category::whereNull('parent_id')->get();

        $bestsellers = Product::with(['variants.images', 'brand'])
            ->limit(8)
            ->get();

        $specialProducts = Product::with(['variants.images'])
            ->where('brand_id', 4)
            ->limit(4)
            ->get();

        return view('home', compact('brands', 'categories', 'bestsellers', 'specialProducts'));
    }
}