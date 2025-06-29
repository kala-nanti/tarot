<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $total_food = Product::where
        ('category_id', 1)->count();
        
        $total_drink = Product::where
        ('category_id', 2)->count();
        
        $foods = Product::where('category_id', 1)->get();
        $drinks = Product::where('category_id', 2)->get();

        return view('welcome',
        compact('total_food','total_drink','foods','drinks')
    );
    }
}
