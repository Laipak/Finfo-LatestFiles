<?php

namespace App\Components\Finfo\Modules\Users;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class UserController extends Controller
{
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
                if ($verify->user_type_id != 3 && $verify->user_type_id != 4)  {
                    return redirect('admin/verified');
                }else{
                    return redirect('admin/login');
                }
            } else {
                return redirect('/');
            }
        }
    }
}
