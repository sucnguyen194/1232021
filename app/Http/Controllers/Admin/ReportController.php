<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSession;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        check_admin_systems(SystemsModuleType::REPORT);

        $products = Product::public()->orderByDesc('created_at')->get();
        $sessions = ProductSession::selectRaw('*, SUM(amount) as amount1, SUM(amount_export) as amount2, SUM(amount * price_in - amount_export * price_in) as balance')->with('product')
            ->when(\request()->product,function ($q, $id){
                $q->where('product_id',$id);
            })
            ->whereType('import')
            ->groupBy('product_id')
            ->latest()
            ->orderByDesc('created_at')->get();

        $amount = $sessions->sum('amount1');
        $amount_export = $sessions->sum('amount2');
        $money = $sessions->sum('balance');

       return view('Admin.Report.index',compact('sessions','amount','amount_export','money','products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        check_admin_systems(SystemsModuleType::REPORT);
        $sessions = ProductSession::when(date_range(),function($q, $date){
                $q->whereBetween('created_at', [$date['from']->startOfDay(), $date['to']->endOfDay()]);
            })
            ->whereProductId($id)->whereType('import')->get();

        $product = Product::find($id);
        $amount = $sessions->sum('amount');
        $amount_export = $sessions->sum('amount_export');
        $money = 0;
        foreach($sessions as $session){
            $import = $session->amount * $session->price_in;
            $export = $session->amount_export * $session->price_in;
            $money += $import - $export;
        }
        return view('Admin.Report.show',compact('sessions','amount','amount_export','money','id','product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
