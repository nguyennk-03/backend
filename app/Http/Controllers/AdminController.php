<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Comment;
use App\Models\User;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Rating;

class AdminController extends Controller
{
    // ====================== 🏠 Dashboard ======================
    public function index()
    {
        return view('admin.dashboard');
    }

    // ====================== 🛒 Quản lý SẢN PHẨM ======================

    public function products()
    {
        $products = Product::paginate(10);
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function productadd(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'img' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product = new Product($request->all());
        if ($request->hasFile('img')) {
            $product->variant_image = $request->file('img')->store('products', 'public');
        }
        $product->save();

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công');
    }

    public function productedit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function productupdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        if ($request->hasFile('img')) {
            $product->variant_image = $request->file('img')->store('products', 'public');
            $product->save();
        }

        return redirect()->route('products')->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function productdelete($id)
    {
        Product::destroy($id);
        return redirect()->route('products')->with('success', 'Xóa sản phẩm thành công');
    }

    // ====================== 📂 Quản lý DANH MỤC ======================

    public function categories()
    {
        $categories = Category::paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function categoryadd(Request $request)
    {
        Category::create($request->all());
        return redirect()->route('categories')->with('success', 'Thêm danh mục thành công');
    }

    public function categoryedit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function categoryupdate(Request $request, $id)
    {
        Category::findOrFail($id)->update($request->all());
        return redirect()->route('categories')->with('success', 'Cập nhật danh mục thành công');
    }

    public function categorydelete($id)
    {
        Category::destroy($id);
        return redirect()->route('categories')->with('success', 'Xóa danh mục thành công');
    }

    // ====================== 🏷️ Quản lý THƯƠNG HIỆU ======================

    public function brands()
    {
        $brands = Brand::paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    public function brandadd(Request $request)
    {
        Brand::create($request->all());
        return redirect()->route('brands')->with('success', 'Thêm thương hiệu thành công');
    }

    public function brandedit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function brandupdate(Request $request, $id)
    {
        Brand::findOrFail($id)->update($request->all());
        return redirect()->route('brands')->with('success', 'Cập nhật thương hiệu thành công');
    }

    public function branddelete($id)
    {
        Brand::destroy($id);
        return redirect()->route('brands')->with('success', 'Xóa thương hiệu thành công');
    }

    // ====================== 👤 Quản lý NGƯỜI DÙNG ======================

    public function users()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function useradd(Request $request)
    {
        User::create($request->all());
        return redirect()->route('users')->with('success', 'Thêm người dùng thành công');
    }

    public function useredit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function userupdate(Request $request, $id)
    {
        User::findOrFail($id)->update($request->all());
        return redirect()->route('users')->with('success', 'Cập nhật người dùng thành công');
    }

    public function userdelete($id)
    {
        User::destroy($id);
        return redirect()->route('users')->with('success', 'Xóa người dùng thành công');
    }

    // ====================== 🎁 Quản lý MÃ GIẢM GIÁ ======================

    public function discounts()
    {
        $discounts = Discount::paginate(10);
        return view('admin.discounts.index', compact('discounts'));
    }

    public function discountadd(Request $request)
    {
        Discount::create($request->all());
        return redirect()->route('discounts')->with('success', 'Thêm mã giảm giá thành công');
    }

    public function discountedit($id)
    {
        $discount = Discount::findOrFail($id);
        return view('admin.discounts.edit', compact('discount'));
    }

    public function discountupdate(Request $request, $id)
    {
        Discount::findOrFail($id)->update($request->all());
        return redirect()->route('discounts')->with('success', 'Cập nhật mã giảm giá thành công');
    }

    public function discountdelete($id)
    {
        Discount::destroy($id);
        return redirect()->route('discounts')->with('success', 'Xóa mã giảm giá thành công');
    }

    // ====================== 📦 Quản lý ĐƠN HÀNG ======================

    public function orders()
    {
        $orders = Order::paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function orderview($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.view', compact('order'));
    }

    public function orderedit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function orderupdate(Request $request, $id)
    {
        Order::findOrFail($id)->update($request->all());
        return redirect()->route('orders')->with('success', 'Cập nhật đơn hàng thành công');
    }

    public function orderdelete($id)
    {
        Order::destroy($id);
        return redirect()->route('orders')->with('success', 'Xóa đơn hàng thành công');
    }
    // ====================== ⭐ Quản lý ĐÁNH GIÁ ======================
    public function ratings()
    {
        $ratings = Rating::with('user', 'product')->paginate(10);
        return view('admin.ratings.index', compact('ratings'));
    }

    public function ratingview($id)
    {
        $rating = Rating::findOrFail($id);
        return view('admin.ratings.view', compact('rating'));
    }

    public function ratingedit($id)
    {
        $rating = Rating::findOrFail($id);
        return view('admin.ratings.edit', compact('rating'));
    }

    public function ratingupdate(Request $request, $id)
    {
        $rating = Rating::findOrFail($id);
        $rating->update($request->all());
        return redirect()->route('ratings')->with('success', 'Cập nhật đánh giá thành công!');
    }

    public function ratingdelete($id)
    {
        Rating::destroy($id);
        return redirect()->route('ratings')->with('success', 'Xóa đánh giá thành công!');
    }


    // ====================== 💬 Quản lý BÌNH LUẬN ======================
    public function comments()
    {
        $comments = Comment::with('user', 'product')->paginate(10);
        return view('admin.comments.index', compact('comments'));
    }

    public function commentview($id)
    {
        $comment = Comment::findOrFail($id);
        return view('admin.comments.view', compact('comment'));
    }

    public function commentedit($id)
    {
        $comment = Comment::findOrFail($id);
        return view('admin.comments.edit', compact('comment'));
    }

    public function commentupdate(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update($request->all());
        return redirect()->route('comments')->with('success', 'Cập nhật bình luận thành công!');
    }

    public function commentdelete($id)
    {
        Comment::destroy($id);
        return redirect()->route('comments')->with('success', 'Xóa bình luận thành công!');
    }
}
