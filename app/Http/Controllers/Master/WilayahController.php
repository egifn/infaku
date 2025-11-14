<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WilayahController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function masterWilayah()
    {
        return view('admin.master.wilayah');
    }
}
