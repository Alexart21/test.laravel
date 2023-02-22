<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ProductFormRequest;

class ProductsController extends Controller
{
    const PAGE_SIZE = 10;

    public function index()
    {
        $products = Product::orderByDesc('title')->paginate(self::PAGE_SIZE);
        $count = $products->count();
        $total = $products->total();
        return view('admin.products.index', compact('products', 'count', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductFormRequest $request)
    {
        $data = $request->validated();
        $product =  Product::create($data);
        return redirect()->route('products.show', [$product->id]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductFormRequest $request, string $id)
    {
        $data = $request->validated();
        $product = Product::findOrFail($id);
        $product->update($data);
        flash('Обновлено !')->success();

        return redirect()->route('products.show', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index');
    }
}
