<?php

namespace App\Components\Finfo\Modules\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Validator;
use Mail;

class ContactController extends Controller 
{
	public function index()
	{
            return $this->view('contact');
	}
        public function save(Request $request) {
            $validator = Validator::make($request->all(), 
                            array(
                                'name'=> 'required|min:2',
                                'subject'=> 'required|min:2',
                                'message'=> 'required|min:2',
                                'email'=> 'required|email',
                            ));      
            if ( $validator->fails() ) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            $this->storeContactInfor($request->all());
            return redirect()->back()->with('successSendToAdmin', trans('crud.success.contact_us.email_sent'));
        }
        
        private function storeContactInfor($data) {
            $contact = new Contact();
            $contact->name = $data['name'];
            $contact->email = $data['email'];
            $contact->subject = $data['subject'];
            $contact->message = $data['message'];
            $contact->save();
            $this->sendConfirmEmailAfterUserRegisterToAdmin($data);
        }
        
        private function sendConfirmEmailAfterUserRegisterToAdmin($data)
        {
            $settings = $this->getSettingJsonFile();
            $value['admin_email_receive_noti'] = $settings['admin_email_receive_noti'];
            $value['from_name'] = $data['name'];
            $value['from_email'] = $data['email'];
            $admin = 'Admin';
            Mail::queue('app.Components.Finfo.Modules.Contact.views.emails.notification_admin', array('value' => $value, 'userData' => $data), 
                function ($message) use($value) {
                    $message->subject("Contact us form");
                    $message->from($value['from_email'],  $value['from_name'] );
                    $message->to($value['admin_email_receive_noti']);
                    //$message->to('adam.chhorn@pathmazing.com');
                }
            );
        }
}
