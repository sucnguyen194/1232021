<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!check_admin_systems(SystemsModuleType::CONTACT))
            return redirect()->back()->withErrors(['message'=>'Errors']);

        $contact = Contact::when(request()->status,function($q){
            $status = request()->status == 'true' ? 1 : 0 ;
            $q->where('status',$status);
            })
            ->when(request()->user,function($q, $user){
                $q->where('user_id',$user);
            })
            ->orderByDesc('id')->get();

        $user = User::where('lever','>=',\Auth::user()->lever)->get();

        return view('Admin.Contact.list',compact('contact','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        if(!check_admin_systems(SystemsModuleType::CONTACT))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        if($contact->status == 0)
            $contact->update(['status' => 1,'user_edit' => \Auth::id()]);

        return view('Admin.Contact.edit',compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        return abort(404);
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
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!check_admin_systems(SystemsModuleType::CONTACT))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $contact = Contact::find($id);

        $contact->delete();

        return redirect()->route('admin.contact.index')->with(['messsage' => 'Xóa thành công']);

    }

    public function delMulti(Request $request)
    {
        if(!check_admin_systems(SystemsModuleType::PAGES))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);
        if($request->delall == 'delete'){

            $count = count($request->check_del);
            for($i=0;$i<$count;$i++){
                $id = $request->check_del[$i];

                $contact = Contact::find($id);

                $contact->delete();

            }
            return redirect()->route('admin.contact.index')->with(['message'=>'Xóa thành công']);
        }
        return redirect()->route('admin.contact.index')->withErrors(['message'=>'LỖi']);
    }
}
