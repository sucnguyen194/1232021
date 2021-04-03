<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeverUser;
use App\Enums\ProductSessionType;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Import;
use App\Models\Product;
use App\Models\ProductSession;
use App\Models\User;
use App\Models\UserAgency;
use Cassandra\Type\UserType;
use Illuminate\Http\Request;
use Cart,Session;
use Illuminate\Support\Facades\Auth;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        check_admin_systems(SystemsModuleType::HISTORY_IMPORT);

        $products = Product::selectRaw('id,name')->public()->get();
        $agencys = UserAgency::selectRaw('id,name')->status()->get();
        $imports = Import::with(['user','agency','sessions'])
            ->when(\request()->user,function($q, $user){
                $q->where('user_id',$user);
            })
            ->when(\request()->agency,function($q, $agency){
                $q->where('agency_id',$agency);
            })
            ->when(date_range(), function ($q, $range){
                $q->whereBetween('created_at', [$range['from']->startOfDay(), $range['to']->endOfDay()]);
            })
            ->get();
        $users = User::selectRaw('id,name')->where('lever','>',LeverUser::SUPPERADMIN)->get();

        return view('Admin.Import.index',compact('products','agencys','imports','users'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        check_admin_systems(SystemsModuleType::IMPORT);

        $products = Product::selectRaw('id,name,amount')->public()->orderByDesc('id')->get();
        $agencys = UserAgency::selectRaw('id,name')->status()->orderByDesc('id')->get();

        $agency = Session::has('agency') ? Session::get('agency') : 0;

        return view('Admin.Import.create',compact('products','agencys','agency'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        check_admin_systems(SystemsModuleType::IMPORT);

        if(!Cart::instance('import')->count())
            return flash('Đơn hàng trống!', 3);

        switch ($request->send){
            case "cancel":
                Cart::instance('import')->destroy();
                Session::forget('agency');
                return flash('Hủy đơn hàng thành công!', 1);
                break;

            case "save":
                $request->validate([
                    'checkout' => 'integer',
                    'agency' => 'required|min:1',
                ]);

                $total = str_replace(',','',Cart::instance('import')->subtotal(0));
                $checkout = $request->checkout;
                $import = Import::create([
                    'agency_id' => $request->agency,
                    'user_id' => Auth::id(),
                    'total' => $total,
                    'checkout' => $checkout,
                    'debt' => $total - $checkout,
                    'note' => $request->note,
                ]);
                foreach(Cart::instance('import')->content() as $cart):

                    $session = ProductSession::create([
                        'import_id' => $import->id,
                        'agency_id' => $import->agency_id,
                        'user_create' => Auth::id(),
                        'product_id' => $cart->id,
                        'amount' => $cart->qty,
                        'price_in' => $cart->price,
                        'type' => ProductSessionType::getKey(ProductSessionType::import)
                    ]);

                //update số lượng sản phẩm được nhập
                $amount = $session->product->amount;
                $amount = $amount + $cart->qty;
                $session->product()->update(['amount' => $amount]);
                endforeach;

                //update công nợ của nhà cung cấp
                $debt = $import->agency->debt;
                $debt = $debt + $import->debt;
                $import->agency->increaseBalance($import->debt,'Tạo mới nhập hàng #'.$import->id, $import);
                $import->agency()->update(['debt' => $debt]);
                Session::forget('agency');
                Cart::instance('import')->destroy();
                return flash('Nhập kho thành công!', 1, route('admin.imports.index'));
                break;
                default;
                    return flash('Lỗi', 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Import $import)
    {
        check_admin_systems(SystemsModuleType::HISTORY_IMPORT);

        $users = User::whereLever(LeverUser::ADMIN)->get();
        $agencys = UserAgency::status()->get();
        $import->load('sessions');

        return view('Admin.Import.show',compact('import','users','agencys'));
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
    public function update(Request $request, Import $import)
    {
        check_admin_systems(SystemsModuleType::HISTORY_IMPORT);

        switch ($request->send){
            case 'user':
                $request->validate([
                    'user' => 'integer|required|min:1',
                    'agency' => 'integer|required|min:1',
                ]);

                $agency = UserAgency::find($request->agency);
                $user = User::find($request->user);
                if(!$agency || !$user)  return flash('Thông tin chưa được cập nhật!', 3);
                    if($agency->id <> $import->agency->id){
                        $agency->increaseBalance($import->debt,'Thay đổi thông tin nhập hàng #'.$import->id, $import);
                        $agency->update([
                            'debt' => $agency->debt + $import->debt
                        ]);
                        $import->agency->increaseBalance(-$import->debt,'Thay đổi thông tin nhập hàng #'.$import->id, $import);
                        $import->agency()->update([
                            'debt' => $import->agency->debt - $import->debt,
                        ]);
                    }

                $import->sessions()->update([
                    'user_create' => $request->user,
                    'agency_id' => $request->agency
                ]);
                $import->update([
                    'user_id' => $request->user,
                    'agency_id' => $request->agency,
                    'note' => $request->note
                ]);
                break;
            case 'checkout':
                $request->validate([
                    'checkout' => 'integer|required|min:1',
                ]);

                $checkout = $request->checkout;
                $debt = $import->total - $checkout;

                $agency_debt = $import->agency->debt - $import->debt + $debt;
                $import->agency->increaseBalance($debt - $import->debt,'Cập nhật thông tin nhập hàng #'.$import->id, $import);
                $import->agency()->update([
                    'debt' => $agency_debt
                ]);
                $import->update([
                   'checkout' => $request->checkout,
                   'debt' => $debt,
                ]);

                break;
        }
        return flash('Cập nhật thành công!', 1);
    }

    public function updateSession(Request $request, $id){

        check_admin_systems(SystemsModuleType::HISTORY_IMPORT);

        $request->validate([
            'amount' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
        ]);

        $session = ProductSession::find($id);

        $total = $session->import->total + ( ($request->amount * $request->price ) - ( $session->amount * $session->price_in) );
        $debt = $total - $session->import->checkout;
        $debt_agency = $session->agency->debt - $session->import->debt + $debt;
        //update tổng tiền + công nợ trong imports
        $session->import()->update([
            'total' => $total,
            'debt' => $debt,
        ]);
        //update công nợ NCC
        $session->agency->increaseBalance($debt - $session->import->debt,'Sửa sản phẩm #'.$session->product->id.' - thông tin nhập hàng #'.$session->import->id, $session->import);
        $session->agency()->update([
           'debt' => $debt_agency
        ]);

        //update số lượng trong products
        $amount = $session->product->amount + ($request->amount - $session->amount);
        $session->product()->update([
            'amount' => $amount
        ]);

        //update số lượng + giá nhập trong product_sessions
        $session->update([
            'amount' => $request->amount,
            'price_in' => $request->price,
        ]);
        return flash('Cập nhật thành công!', 1);
    }

    public function destroySession($id){
        check_admin_systems(SystemsModuleType::HISTORY_IMPORT);

        $session = ProductSession::find($id);
        if($session->product){
            $session->product()->update([
                'amount' => $session->product->amount - $session->amount
            ]);
        }

        $total = $session->import->total - ($session->amount * $session->price_in);
        $debt = $total - $session->import->checkout;

        $debt_agency = $session->agency->debt - $session->import->debt + $debt;
        if($session->agency){
            $session->agency->increaseBalance($debt - $session->import->debt,'Hủy sản phẩm #'.$session->product_id.' - thông tin nhập hàng #'.$session->import->id, $session->import);
            $session->agency()->update([
                'debt' => $debt_agency
            ]);
        }
        if($session->import){
            $session->import()->update([
                'total' => $total,
                'debt' => $debt,
            ]);
        }

        $session->delete();
        return flash('Xóa thành công!', 1);
    }
    public function ajax($id){
        check_admin_systems(SystemsModuleType::HISTORY_IMPORT);
        $session = ProductSession::find($id);
        return response()->json($session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Import $import)
    {
        check_admin_systems(SystemsModuleType::IMPORT);
        if(!$import->sessions->count())
            $import->delete();
        return flash('Xóa thành công!', 1);
    }
}
