<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function targetBySales()
    {
        return view('admin.targetbysales');
    }
    
    public function achievmentArea(Request $request)
    {
        return view('admin.achievment.area'); 
    }
    
    public function achievmentSupplier(Request $request)
    {
        return view('admin.achievment.supplier'); 
    }
    
    public function achievmentDepo(Request $request)
    {
        return view('admin.achievment.depo'); 
    }
    
    public function achievmentPrinciple(Request $request)
    {
        return view('admin.achievment.principle'); 
    }

    public function historyMovement()
    {
        return view('admin.historymovement');
    }

    public function serviceLevel()
    {
        return view('admin.servicelevel');
    }

    public function leadTime()
    {
        return view('admin.leadtime');
    }
}