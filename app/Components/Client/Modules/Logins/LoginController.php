<?php

namespace App\Components\Client\Modules\Logins;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use App\Components\Finfo\Modules\Clients\Company;

class LoginController extends Controller
{
    
    public function index()
    {
        
        
        if (!\Auth::check()) {
            return $this->view('login');
        } else{
            return redirect('/admin/dashboard');
        }
    }

    public function doLogin()
    {
    	$data = Input::all();
		
		
        $rules = array(
            'email' => 'required|email|min:5|max:50',
            'password' => 'required|min:6',
        );
    
        $validator = Validator::make($data, $rules);

        if ( $validator->fails() ) {
          return redirect('/admin/login')->withInput()->withErrors($validator);
        }

        $account = \Session::get('account_data');

         if(\Auth::attempt(['email_address' => Input::get('email'), 'password' => Input::get('password'), 'user_type_id' => '5', 'is_active' => '1', 'company_id' => $account->id]) || \Auth::attempt(['email_address' => Input::get('email'), 'password' => Input::get('password'), 'user_type_id' => '6', 'is_active' => '1', 'company_id' => $account->id]))
         {
             \Auth::user()->last_login = new DateTime();
             \Auth::user()->save();

             return redirect()->intended('/admin/dashboard');
         }
         else
         {
             return redirect('/admin/login')->withInput()->withErrors(['messages' => 'Invalid Username or Password']);
         }
    }

    public function logout()
    {
    	\Auth::logout();
        return redirect()->route('client.login');
    }
}
