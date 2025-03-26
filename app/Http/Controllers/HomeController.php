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
        // Fetch brands for filter buttons
        $brands = Brand::all();

        // Fetch top-level categories (Men, Women, Kids)
        $categories = Category::whereNull('parent_id')->get();

        // Fetch bestsellers (example: products with high order count or manually flagged)
        $bestsellers = Product::with(['variants.images', 'brand'])
            ->limit(8)
            ->get();

        // Fetch special products (e.g., Nike-specific)
        $specialProducts = Product::with(['variants.images'])
            ->where('brand_id', 4) // Nike brand ID from database
            ->limit(4)
            ->get();

        return view('home', compact('brands', 'categories', 'bestsellers', 'specialProducts'));
    }
}