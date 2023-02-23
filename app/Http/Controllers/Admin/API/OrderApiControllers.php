<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderApiControllers extends Controller
{
    public function __construct()
    {
        auth()->setDefaultDriver('api'); // ВОТ без этой строчки не работала api&&web аутентификация !!!!
        $this->middleware('auth:api', ['except' => ['login', 'registration']]);
    }

    public function orders(Request $request)
    {
        $page_size = $request->page_size;
        $orders = Order::orderByDesc('date')->paginate($page_size);
        $count = $orders->count();
        $total = $orders->total();
        return response()->json([
            'orders'=> $orders,
            'page_size' => $page_size
        ]);
    }

    public function page(Request $request)
    {
        $pageNum = $request->pageNum;
        $page_size = $request->page_size;
        // получаем данные по номеру страницы
        $orders = Order::orderByDesc('date')->paginate($page_size, ['*'], 'page', $pageNum);
        return response()->json([
            'orders'=> $orders,
        ]);
    }

}
