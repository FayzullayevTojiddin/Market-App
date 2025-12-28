<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function show(Product $product)
    {
        return view('labels.single', compact('product'));
    }
}