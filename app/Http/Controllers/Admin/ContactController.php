<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        authorize(SystemsModuleType::CONTACT);

        $contact = Contact::when(request()->status,function($q){
            $status = request()->status == 'true' ? 1 : 0 ;
            $q->where('status',$status);
            })
            ->when(request()->user,function($q, $user){
                $q->where('user_id',$user);
            })
            ->whereRepId(0)
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
        authorize(SystemsModuleType::CONTACT);

        $replys = Contact::whereRepId($contact->id)->get();

        if($contact->status == 0)
            $contact->update(['status' => 1,'user_edit' => \Auth::id()]);

        return view('Admin.Contact.edit',compact('contact','replys'));
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
    public function update(Request $request, Contact $contact)
    {
        authorize(SystemsModuleType::CONTACT);

        Validator::make($request->data,[
            'note' => 'required'
        ])->validate();
        $post = new Contact();
        $post->forceFill($request->data);
        $post->user_id = auth()->id();
        $post->rep_id = $contact->id;
        $post->save();

        send_email('reply',$request->data,$contact->email);

        return flash('Gửi phản hồi thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($id){
        authorize(SystemsModuleType::CONTACT);
        $contact = Contact::find($id);
        $contact->delete();
        return flash('Xóa thành công', 1);
    }
    public function delete(Request $request)
    {
        authorize(SystemsModuleType::CONTACT);

        if($request->destroy == 'delete'){
            $count = count($request->check_del);
            for($i=0;$i<$count;$i++){
                $id = $request->check_del[$i];
                $contact = Contact::find($id);
                $contact->delete();
            }
            return flash('Xóa thành công', 1);
        }
    }

    public function destroy($id)
    {

    }
}
