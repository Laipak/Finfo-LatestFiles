<?php

namespace App\Components\Finfo\Modules\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use File;
use Illuminate\Support\Facades\DB;
use App\Components\Finfo\Modules\Menus\Menu;
use App\Components\Client\Modules\Company\Company;
use App\Components\Client\Modules\Invoice\Invoice;
use App\Components\Client\Modules\Invoice\InvoiceController;
use App\Components\Client\Modules\Users\User;
use Carbon\Carbon;
use Auth;
class AdminController extends Controller
{
    
    // Get admin login page
    public function index()
    {
        return $this->view('login')->with('menus', $this->getMenus());
    }

    /* This function will be used to login to FINFO admin
        - check validation
        - if true then redirect to FINFO dashboard
        - if false then redirect back to login page
    */
    public function doLogin()
    {
        $data = Input::all();
		
		$rules = array(
            'email' => 'required|email|min:5|max:50',
            'password' => 'required|min:6|max:15',
        );
    
        $validator = Validator::make($data, $rules);

        if ( $validator->fails() ) {
          return redirect('admin/login')->withInput()->withErrors($validator);
        }

         if(\Auth::attempt(['email_address' => Input::get('email'), 'password' => Input::get('password'), 'user_type_id' => '3', 'is_active' => '1']) || \Auth::attempt(['email_address' => Input::get('email'), 'password' => Input::get('password'), 'user_type_id' => '4', 'is_active' => '1']))
         {
             \Auth::user()->last_login = new DateTime();
             \Auth::user()->save();

             return redirect()->intended('admin/dashboard');
         }
         else
         {
             return redirect('admin/login')->withInput()->withErrors(['messages' => 'Invalid Username or Password']);
         }
    }
    
    // this funciton will be used to change setting in admin
    public function getSetting()
    {
        $currency = DB::table('currency')->get();
        $getPackageData = $this->getPackageData();
 
        $phone = DB::table('stockids')->first();
  
        return $this->view('setting')->with($this->getSettingJsonFile())->with('currency', $currency)->with('getPackageData', $getPackageData)->with('phone', $phone);
    }
	
    public function doUpdatePackagePrice(){
        DB::table('package')->where('id', Input::get('pacakge-price-id'))
                ->update(
                    array(
                        'title' => Input::get('package_title'),
                        'price' => Input::get('package_price'),
                    )
                );
        return redirect()->back()->with('package-message', 'Package prices was updated.');
    }
    
    public function getPackageDataById(){
        $packageData= DB::table('package')->where('id', Input::get('id'))->first();
        $data = array(
            'title' => $packageData->title,
            'price' => $packageData->price,
            );
        return $data;
    }
    
    
    
    
    
    
    	public function phone(Request $request)

     {
      
        $phone = DB::table('stockids')->update(['phone' => $request->phone]);
        
          return redirect()->back()->with('global', 'Pricing and chart updated.');
	}
    
    
    
    
    
    
    
    
    
    
    public function getPackageData(){
        $packageData = DB::table('package')->get();
        return $packageData;
    }

    // this function will be used to logout finfo user
    public function logout()
    {
        \Auth::logout();
        return redirect()->route('finfo.admin.login');
    }

    // This function will be used to display a forget password form
    public function getForgetPassword()
    {
        return $this->view('forget-password')->with('menus', $this->getMenus());
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

        $email      = $request->input('email_address');
		
        $message    = "We don't have any users registered with that email.";
        $status     = 0;
        $user       = User::where('email_address','=',$email)->where('company_id', 1)->first();

        if($user){
            $token                  = $request->input('_token');
            $password_reset_expire  = new DateTime('+1 day');

            $email                          = $user->email_address;
            $name                           = $user->first_name;
            $user->password_reset_token     = $token;
            $user->password_reset_expire    = $password_reset_expire;
            if($user->save())
            {
                $link = route('finfo.admin.reset.password', $token);
                $user_data  = array(
                                    'email' => $email,
                                    'name'  => $name
                                );
                $user_data['from_email'] = $this->getFromFinfoEmail();
                
                Mail::queue('app.Components.Finfo.Modules.Admin.views.emails.reset-password', ['admin' => $name, 'link' => $link], function($message) use($user_data)
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
        return redirect()->back()->with('message', $data)->with('menus', $this->getMenus());
        
    }

    /* This function will be used get reset password
        - check random existing in db
        - send mail with random number
        - return result
    */
    public function getResetPassword($token)
    {
        $data['password_reset_token']  = $token;
        return $this->view('reset-password')->with('data', $data)->with('menus', $this->getMenus());
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
        $message                = 'Your reset password code has expired.';
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
            
            return Redirect::route('finfo.admin.login')->with('global', 'Password has been updated.');

        }
        $data = array(
                    'message'   => $message,
                    'status'    => $status
                );

        return redirect()->back()->with('message', $data);
    }

    /*this function will show 
        - chart
        - upcoming invoice
        - new client register
        - recent client activity
    */
    public function getDashboard()
    {
        $invoiceController = new InvoiceController();
        return $this->view('dashboard')->with('clients', $this->getPeddingClient())
                        ->with('clientBackendController', app('App\Components\Finfo\Modules\Clients\ClientBackendController'))
                        ->with('invoices', $this->getInvoice())
                        ->with('invoicecontroller', app('App\Components\Client\Modules\Invoice\InvoiceController'))
                        ->with('client_access', $this->getClientAccess())
                        ->with('exchage_rate', $invoiceController->getExchangeRate())
                        ->with('controller', $this);
    }
    public function doSetting(Request $request) {
        $data = array( 
                    "sub_domain_exclusive" => $request->input('sub_domain'),
                    "admin_email_receive_noti" => $request->input('finfo_email'),
                    "broadcasts_per_year" => $request->input('broadcasts_per_year'),
                    "recipients_per_year" => $request->input('recipients_per_year'),
                    "market" => $request->input('lunch_market'),
                    "admin_from_email" => $request->input('from_email'),
                    "admin_phone" => $request->input('phone'),
                );
        $filesStatus = File::put($this->settingJsonFilePath(), json_encode($data));
        if ($filesStatus == false) {
            return redirect()->back()->with('msg_error', trans('admin-message.msg_success.msg_errors'));
        }else{
            return redirect()->back()->with('msg_success', trans('admin-message.msg_success.msg_do_setting'));
        }
    }

    public function doEditCurrency()
    {
        $id = Input::get('id');
        $currency = DB::table('currency')->where('id', $id)->first();
        $data = array(
            'name' => $currency->name,
            'symbol' => $currency->symbol,
            'exchange_rate'=> $currency->exchange_rate,
            'code'=> $currency->code
            );
        return $data;
    }

    public function doDoCurrency()
    {
        $id = Input::get('cur_id');
        if($id == ''){
            DB::table('currency')->insert(
                array(
                    'name' => Input::get('name'),
                    'symbol' => Input::get('symbol'),
                    'exchange_rate' => Input::get('exchange_rate'),
                    'code' => Input::get('code')
                    )
            );

            return redirect()->back()->with('message', 'Currency created.');
        }else{
            DB::table('currency')
                ->where('id', $id)
                ->update(
                    array(
                        'name' => Input::get('name'),
                        'symbol' => Input::get('symbol'),
                        'exchange_rate' => Input::get('exchange_rate'),
                        'code' => Input::get('code')
                    )
                );
            return redirect()->back()->with('message', 'Currency updated.');
        }
    }

    public function doDodelete($id)
    {
        DB::table('currency')->where('id', $id)->delete();

        return redirect()->route('finfo.admin.setting')->with('message', 'Currency deleted.');
    }
    public function successVerify() {
        return $this->view('sucess-verify')->with('menus', $this->getMenus());
    }

    public function getPeddingClient()
    {
        $client = Company::join('user','user.id','=','company.admin_id')
                                    ->join('company_subscription_package as csp', 'csp.company_id','=','company.id')
                                    ->join('package','package.id','=','csp.package_id')
                                    ->select('company.*', 'user.first_name', 'user.last_name', 'user.email_address', 'csp.package_id', 'package.title')
                                    ->where('company.id','!=',1)
                                    ->where('csp.is_current', 1)
                                    ->where('csp.is_cancel','=', 0)
                                    ->where('company.rejected_by', 0)
                                    ->where('company.is_active', '!=', 1)
                                    ->orderBy('company.id','DESC')
                                    ->get();
        return $client;
    }

    public function getInvoice()
    {
        $invoice = new Invoice();
        $invoices = $invoice->join('company_subscription_package as csp','invoice.company_subscription_package_id','=','csp.id')
                            ->join('company', 'company.id', '=', 'csp.company_id')
                            ->where('is_current', 1)
                            ->select('invoice.*', 'company_name', 'csp.expire_date', 'csp.start_date', 'currency_id')
                            ->orderBy('csp.created_at', 'desc')
                            ->limit(20)->get();
        return $invoices;
    }

    public function getClientAccess()
    {
        $client_access = User::join('company', 'user.id', '=', 'company.admin_id')
                                ->where('company_id', '!=', 1)
                                ->where('last_login', '!=', ' 0000-00-00 00:00:00')
                                ->orderBy('last_login', 'desc')
                                ->take('20')
                                ->get();
        return $client_access;
    }

    public function ajaxGetNewClient()
    {

        $company = new Company();
        $client =   $company->distinct('created_at')
                            ->select(DB::raw('DATE(created_at) as date'), 
                                    DB::raw('count(created_at)AS total'))
                            ->where('created_at', '!=', '0000-00-00 00:00:00')
                            ->groupBy('date')
                            ->get();
        $client_chart = [];
        if($client){
            foreach ($client as $value) {
                $arr = array('x' => date('M/d/Y', strtotime($value->date)), 'y' => (int)$value->total);
                array_push($client_chart, $arr);
            }
        }
        return $client_chart;
    }

    public function PriceWithEx($price='', $cur_id='')
    {
        $amount = '$ '.number_format(round($price), 2 , ".", ",");
        if($cur_id != 0 ){
            $currency = DB::table('currency')->where('id', '=', $cur_id)->first();
            if(count($currency)){
                $symbol = @$currency->symbol;
                $exchange = $currency->exchange_rate;
                $amount = $symbol.' '.number_format(round($price * $exchange), 2 , ".", ",");
            }
        }
        
        return $amount;
    }
}
