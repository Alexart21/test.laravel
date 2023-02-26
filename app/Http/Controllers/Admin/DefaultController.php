<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class DefaultController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function api()
    {
        return view('admin.api.index');
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $order = Order::findOrFail($id);
        return view('admin.api.update', compact('order'));
    }

    public function create()
    {
        return view('admin.api.create');
    }
}
