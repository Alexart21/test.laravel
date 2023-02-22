<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DefaultController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function api()
    {
        return view('admin.api');
    }
}
