<?php

namespace App\Components\Client\Modules\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use Session;
use Carbon\Carbon;

class UserController extends Controller
{
    	// This function will be used to display a forget password form
    public function getForgetPassword()
    {
        return $this->view('forget-password');
    }

    /* This function will be used to do forgot password
        - params (email)
        - check validation
        - check existing in db
        - insert random number in to database
        - send mail with random number
        - return result
    */
    public function doForgetPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email_address' => 'required|email|min:5|max:50',
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
        $company_id = Session::get('company_id');
        $email      = $request->input('email_address');
        $message    = "We don't have any users registered with that email.";
        $status     = 0;
        $user       = User::where('email_address',$email)->where('company_id', $company_id)->first();
  
        if($user){
            $token                  = $request->input('_token');
            $password_reset_expire  = new DateTime('+1 day');
            $email                          = $user->email_address;
            $name                           = $user->first_name;
            $user->password_reset_token     = $token;
            $user->password_reset_expire    = $password_reset_expire;
            if($user->save())
            {
                $link = route('client.users.reset.password', $token);
                $user_data  = array(
                                    'email' => $email,
                                    'name'  => $name
                                );
                $user_data['from_email'] = $this->getFromFinfoEmail();
                
                Mail::send('app.Components.Client.Modules.Users.views.emails.reset-password', ['client' => $name, 'link' => $link], function($message) use($user_data)
                {
                    $message->subject("Reset Password Link");
                    $message->from($user_data['from_email'], 'FINFO Solutions');
                    $message->to($user_data['email']);
                });

                $message    = "Please check your email to reset password.";
                $status     = 1;
            }
        }
        $data = array(
                    'message'   => $message,
                    'status'    => $status
                );
        return redirect()->back()->with('message', $data);
        
    }

    /* This function will be used get reset password
        - check random existing in db
        - send mail with random number
        - return result
    */
    public function getResetPassword($token)
    {
        $data['password_reset_token']  = $token;
        return $this->view('reset-password')->with('data', $data);
    }

    /* This function will be used with reset password
        - params (password, password_confirmation)
        - check validation
        - Update password
        - return result
    */
    public function doResetPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'password'              => 'required|min:5|max:50|confirmed',
            'password_confirmation' => 'required|min:5|max:50',
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
        $current_date           = new DateTime();
        $password_reset_token   = $request->input('password_reset_token');
        $message                = 'Your reset password code is invalid.';
        $status                 = 0;

        $user   = new User();
        $user   = $user->where('password_reset_token', $password_reset_token)
                       ->where('password_reset_expire', '>=', $current_date)->first();

        if(count($user) > 0)
        {
            $data = [
                        'password'              => Hash::make($request->input('password')),
                        'password_reset_token'  => '',
                        'password_reset_expire' => ''
                    ];

            $user->update($data);
            $message = 'Password has been updated.';
            $status  = 1;
        }
        $data = array(
                    'message'   => $message,
                    'status'    => $status
                );

        return redirect()->back()->with('message', $data);
    }

    // This function will be used to display a success page
    public function getSuccess()
    {
        return $this->view('success');
    }

    public function verify($code)
    {
        if($code){
            $verify = User::where('verify_token','=',$code)->first();
            if($verify)
            {
                $verify->verify_token = '';
                $verify->active_date = Carbon::now();
                $verify->is_active = 1;
                $verify->save();
                
                return redirect('admin/login');
            }else{
                echo 'verify error!';
            }
        }
    }

}
