<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\FContactModel;
use App\Models\SiteSetting;
use App\Rules\ValidRecapcha;
use Session,DB,Mail;
use Illuminate\Http\Request;

class ContactController extends Controller {

    public function setting(){
        return SiteSetting::langs()->first();
    }

	public function index(){
		return view('Contact.index');
	}
	public function store(Request $request){

	    $request->validate([
	        'data.name' => 'required',
	        'data.phone' => 'numeric',
            'data.email' => 'email',
            'g-recaptcha-response' => ['required', new  ValidRecapcha()]
        ]);

        $contact = new Contact();

	    $contact->forceFill($request->data);
	    $contact->save();
        $setting = $this->setting();
        Mail::send('Emails.contact',$request->data,function($msg) use ($setting){
            $msg->from(env('MAIL_USERNAME'),'Thông báo');
            $msg->to($setting->email)->subject($setting->name);
        });
        return redirect()->back()->with(['message' => 'Gửi thông tin thành công!']);
	}
}
