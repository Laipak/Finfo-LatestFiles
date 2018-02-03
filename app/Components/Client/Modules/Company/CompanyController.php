<?php

namespace App\Components\Client\Modules\Company;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use Image;
use Session;
use File;

class CompanyController extends Controller
{
	public function getCompany()
	{
//            if(!\Auth::check()){
//                return redirect('admin/login');
//            }
            if(Session::has('account')){
                $user       = new User();
                $companyId  = $user->getCompanyID(Session::get('account'));

                $company = Company::findOrFail($companyId);
            }
            return $this->view('company')->with('data', $company);
	}

	public function postUpdate()
	{
		$validate = Validator::make(Input::all(), [
            'company_name'          => 'required|min:2|max:100',
            'finfo_account_name'    => 'required|min:2|max:50',
            'phone'		            => 'numeric',
            'address'               => 'required',
            'website'               => 'min:5|max:50',
            //'established'           => 'min:5|max:5',
            'number_of_employee'    => 'min:1|max:4',
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
        

        if(Session::has('account')){
            $user       = new User();
            $companyId  = $user->getCompanyID(Session::get('account'));
            $company    = Company::findOrFail($companyId);
            $data       = Input::all();
           
            if (!empty($data['established'])) {
                $data['established_at'] = date("Y-m-d", strtotime($data['established']));
            } else{
                $data['established_at'] = '0000-00-00';
            }
            
            $company->update($data);

            if($user){
                return Redirect::route('client.admin.company')->with('global', 'Company Updated');
            }

        }
	}

    public function doCompanyUploadLogo()
    {
        $data = Input::all();
        $destinationPath = "files/temp/";
        $file       = $data['logo'];
        $filename   = $_FILES['logo']['name'];
        $full_path  = $destinationPath.$filename;
        $file->move($destinationPath, $filename);
        return $full_path;
    }

    public function doCompanyUploadFavicon()
    {
        $data = Input::all();
        $newFileName = str_random(8);
        $destinationPath = "files/temp/".Session::get('account').'/';
        $file       = $data['file_favicon'];
        $filename   = $_FILES['file_favicon']['name'];
        $full_path  = $destinationPath.$filename;
        $file->move($destinationPath, $filename);
        if ($_FILES['file_favicon']['type'] != 'image/x-icon') {
            $icon   = Image::make($full_path);
            $icon->resize(32, 32)->save();
            $new_path = $destinationPath.$newFileName.'.ico';
            rename($full_path, $new_path);
            return $new_path;
        }
        return $destinationPath.$filename;
    }
}
