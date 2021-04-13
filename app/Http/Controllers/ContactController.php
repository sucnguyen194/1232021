<?php namespace App\Http\Controllers;

use App\Models\Contact;
use App\Rules\ValidRecapcha;
use Session,Mail;
use Illuminate\Http\Request;

class ContactController extends Controller {

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

	    send_email('contact',$request->data);

        return flash('Gửi thông tin thành công', 1);
	}
}
