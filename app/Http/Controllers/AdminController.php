<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class AdminController extends Controller
{
    public function index()
    {
        return view('fileupload');
    }
    public function assignedProducts()
    {
        return view('assigned-products');
    }
    public function products()
    {
        $products = Product::with('user')->get();
        return view('product',compact('products'));
    }

}
