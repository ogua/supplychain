<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Company;

class SupplychainController extends Controller
{
    public function order($id)
    {
        $data = Order::where('id',$id)->first();

        //dd($data->items);

        $company = Company::first();

        $packing = false;

        return view('receipt.order-receipt',compact('data','company','packing'));
        
    }

    public function packing($id)
    {
        $data = Order::where('id',$id)->first();

        //dd($data->items);

        $company = Company::first();

        $packing = true;

        return view('receipt.order-receipt',compact('data','company','packing'));
        
    }


    public function report($fromdate,$todate) {

        $data = Order::whereBetween("created_at",[$fromdate,$todate])
        ->get();

        $company = Company::first();

        return view('receipt.order-report',compact('data','company','fromdate','todate'));
    }
}
