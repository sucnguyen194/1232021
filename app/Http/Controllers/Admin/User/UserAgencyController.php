<?php

namespace App\Http\Controllers\Admin\User;

use App\Enums\LeverUser;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Import;
use App\Models\Product;
use App\Models\ProductSession;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserAgency;
use Illuminate\Http\Request;
use phpseclib\System\SSH\Agent;

class UserAgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        check_admin_systems(SystemsModuleType::AGENCY);

        $agencys = UserAgency::when(\request()->id,function ($q, $id){
            $q->whereId($id);
        })->get();

        return view('Admin.User.agency.index',compact('agencys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        check_admin_systems(SystemsModuleType::AGENCY);

        return view('Admin.User.agency.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        check_admin_systems(SystemsModuleType::AGENCY);

        $request->validate([
            'data.name' => 'required',
        ]);

        $agency = new UserAgency();
        $agency->forceFill($request->data);
        $agency->save();
        return redirect()->route('admin.agencys.index')->with(['message' => 'Thêm mới thành công']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(UserAgency $agency){
        check_admin_systems(SystemsModuleType::AGENCY);
        $transaction = Transaction::whereAgencyId($agency->id)
            ->when(date_range(),function ($q, $date){
                $q->whereBetween('created_at', [$date['from']->startOfDay(), $date['to']->endOfDay()]);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('Admin.User.agency.transaction',compact('agency','transaction'));
    }
//    public function show(UserAgency $agency)
//    {
//        check_admin_systems(SystemsModuleType::AGENCY);
//
//        $users = User::whereLever(LeverUser::ADMIN)->get();
//
//        $sessions = $agency->imports()->with(['product','admin'])
//            ->when(\request()->user,function ($q, $user){
//                $q->whereUserCreate($user);
//            })
//            ->when(\request()->product,function ($q, $product){
//                $q->whereProductId($product);
//            })
//            ->when(date_range(),function($q, $date){
//                $q->whereBetween('created_at',[$date['from']->startOfDay(),$date['to']->endOfDay()]);
//            })
//            ->orderByDesc('created_at')
//            ->get();
//        $total = 0;
//        $amount = 0;
//        foreach ($sessions as $session):
//            $total += $session->amount * $session->price_in;
//            $amount += $session->amount;
//        endforeach;
//
//        $products = Product::public()->orderByDesc('created_at')->get();
//
//        return view('Admin.User.agency.show',compact('agency','users','sessions','products','total','amount'));
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UserAgency $agency)
    {
        check_admin_systems(SystemsModuleType::AGENCY);
        return view('Admin.User.agency.edit',compact('agency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserAgency $agency)
    {
        check_admin_systems(SystemsModuleType::AGENCY);

        $request->validate([
            'data.name' => 'required',
        ]);

        $agency->forceFill($request->data);
        $agency->status = $request->input('data.status') ? 1 : 0;
        $agency->save();

        return back()->withInput()->with(['message' => 'Cập nhật thành công!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserAgency $agency)
    {
        check_admin_systems(SystemsModuleType::AGENCY);
        $agency->delete();

        return back()->with(['message' => 'Xóa thành công!']);
    }
}
