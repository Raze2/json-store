<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = collect(json_decode(file_get_contents(base_path('resources/json/products.json'))))->sortBy('created_at');
        $total_sum = number_format($products->sum('total'),2, '.', '');
        return view('products.index', compact('products', 'total_sum'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {

        $product = collect($request->validated());
        $product["price"] = number_format($request->price, 2, '.', '');
        $product->put('total', number_format((float) $request->quantity * $request->price, 2, '.', ''));
        $product->put('created_at',Carbon::now()->format('d/m/Y h:i'));

        $products = collect(json_decode(file_get_contents(base_path('resources/json/products.json')), 1));
        $products->push($product);
        
        file_put_contents(base_path('resources/json/products.json'), json_encode($products));

        return json_decode($product);
    }

}
