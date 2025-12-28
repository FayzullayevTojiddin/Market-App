<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function show(Product $product, $count)
    {
        $count = max(1, min(100, (int)$count)); // xavfsizlik
        return view('labels.single', compact('product', 'count'));
    }
}