<?php

namespace App\Components\Client\Modules\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use Session;
use Auth;
use Image;
use File;


class ProfileController extends Controller
{
	public function getProfile()
	{

        $id = Auth::user()->id;
        $user = User::FindOrFail($id);
        
		return $this->view('profile')->with('data', $user);
	}

	public function doProfileUpdate()
	{
        $id = Auth::user()->id;
		$validate = Validator::make(Input::all(), [
            'first_name'    => 'required|min:2|max:100',
            'last_name'     => 'required|min:2|max:50',
            'phone'		    => 'min:6|max:20',
            'email_address' => 'required|min:7|max:50|email'
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }

        $data = Input::all();

        $user = User::FindOrFail($id);
        //upload profile picture
        $destinationPath    = "img/logo/";

        $pic       = $data['profile_picture'];
        if($pic != ""){
            $old_pic   = $user->profile_picture;
            $obj_image  = Image::make($pic);
            $mime       = $obj_image->mime();

            if ($mime == 'image/png')
                $extension = 'png';
            else
                $extension = 'jpg';

            $filename              = str_random(8).'.'.$extension;
            $data['profile_picture']  = $destinationPath.$filename;
            rename($pic, $data['profile_picture']);
            File::delete($old_pic);
        }

        
        $user->update($data);

        return redirect('admin/profile')->with('global', 'Profile updated');

        
	}

    public function doProfileUpdatePassword()
    {
        $id = Auth::user()->id;
        $validate = Validator::make(Input::all(), [
            'current_password'          => 'required',
            'new_password'              => 'required|confirmed',
            'new_password_confirmation' => 'required',
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
        $user = User::FindOrFail($id);
        $password = $user->password;

        if (Hash::check(Input::get('current_password'), $password))
        { 
            $user->password = Hash::make(Input::get('new_password'));

            if($user->save())
                return redirect('admin/profile')->with('global', 'Password updated');
        }else{
            return redirect()->back()->with('message', 'Incorrect current password');
        }
    }

    public function postCheckExitEmail()
    {
        $id = Auth::user()->id;
        $company_id = Session::get('company_id');

        $email = User::Where('email_address', Input::get('email_address'))->where('company_id', $company_id)->whereNotIn('id', [$id])->first();

        if( count($email) >= 1 ){
            echo 'false';
        }
        else{
            echo 'true';
        }
    }

    public function doProfileUploadPicture()
    {
        $data = Input::all();
        $destinationPath = "files/temp/";
        $file       = $data['profile_pic'];
        $filename   = $_FILES['profile_pic']['name'];
        $full_path  = $destinationPath.$filename;
        $file->move($destinationPath, $filename);
        return $full_path;
    }
}