<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Enums\SystemsModuleType;
use App\Models\UserDebt;
use Illuminate\Http\Request;

class UserDebtController extends Controller
{
    public $type = SystemsModuleType::DEBT;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        authorize($this->type);

        $users = UserDebt::when(\request()->id,function ($q, $id){
            $q->whereId($id);
        })->orderByDesc('updated_at')->get();

        return view('Admin.Debt.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        authorize($this->type);

        return view('Admin.Debt.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        authorize($this->type);
        $user = new UserDebt();
        $user->forceFill($request->data);
        $user->save();

        return flash('Thêm mới thành công!', 1, route('admin.debts.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(UserDebt $debt)
    {
        authorize($this->type);
        $transactions = $debt->transactions()->when(date_range(),function ($q, $date){
            $q->whereBetween('created_at', [$date['from']->startOfDay(), $date['to']->endOfDay()]);
        })
            ->orderByDesc('created_at')
            ->get();
        return view('Admin.Debt.show',compact('debt','transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, UserDebt $debt)
    {
        authorize($this->type);
        $debt->load('transactions');
        return view('Admin.Debt.edit',compact('debt'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserDebt $debt)
    {
        authorize($this->type);
        $debt->forceFill($request->data);
        $debt->status = $request->has('stauts') ? 1 : 0;
        $debt->save();
        return flash('Cập nhật thành công!', 1);
    }

    public function debts($id,$type, $current){
        authorize($this->type);
        $user = UserDebt::findOrFail($id);
        switch ($type){
            case 'borrow':
                $status = 'Vay';
                $note = 'Vay vốn #';
                break;
            case 'pay':
                $status = 'Trả';
                $current = - $current;
                $note = "Trả tiền #";
                break;
        }
        $user->increaseBalance($current,$note.number_format($current), $user);
        $debt = $user->debt + $current;
        $user->update([
           'debt' => $debt
        ]);
        $users = UserDebt::orderByDesc('updated_at')->get();

        return response()->json($users);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDebt $debt)
    {
        authorize($this->type);
        $debt->transactions()->delete();
        $debt->delete();
        return flash('Xóa thành công!', 1);
    }
}
