<?php

namespace App\Components\Finfo\Modules\Registers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Components\Finfo\Modules\Registers\Register;
use App\Components\Finfo\Modules\Clients\Company;
use App\Components\Finfo\Modules\Clients\CompanySubscription;
use App\Components\Finfo\Modules\Clients\Billing;
use App\Components\Finfo\Modules\Clients\Invoice;
use App\Components\Finfo\Modules\Registers\Package;
use App\Components\Finfo\Modules\Currency\Currency as Currency;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Cartalyst\Stripe\Stripe;
use Illuminate\Support\Facades\DB;
use App\Components\Client\Modules\Invoice\InvoiceController;
use App\Components\Finfo\Modules\Menus\Menu;
use App\Components\Finfo\Modules\SecurePay\SecurePayController;
use App\Components\Finfo\Modules\Payment\PaymentController as Payment;
use Config;
use App\User;
use Auth;
error_reporting(E_ALL);
class RegisterController extends Controller
{
    private $payment;
    private $merchantId = "ABC0001";
    private $merchantPassword = "abc123";
    
    public function __construct()
    {
        $this->payment = 'App\Components\Finfo\Modules\Payment\PaymentController';
    }
    
   // List all Packages and its Modules
    public function index()
    {   

        if(Input::get('currency') == '') {
            $currency_id = 2;
        }else{
            $currency_id = Input::get('currency');
        }

        $package_module = Package_module::getPackage();
        $currency = DB::table('currency')->lists('name', 'id');

        $exchange = DB::table('currency')->where('id', $currency_id)->select('exchange_rate','symbol', 'id')->first();
        
        return $this->view('subscriptions')
                ->with(compact('package_module'))
                ->with('currencyData', $currency)
                ->with('exchange', $exchange)
                ->with('menus', $this->getMenus())
                ->with('country', $this->getCountry() )
                ->with('currentCountry', $this->getCurrentContryAccess() )
                ->with('market', $this->getMarket() );
    }

    /* This function will be used with register
        - params (id)
        - return one result
    */
    public function getRegister($name, $currency_type)
    {

        $getPackage = Package::all();
        $packages = array();
        foreach($getPackage as $package) {
            $packages[$package->id] = ucfirst($package->name);
        }
        $package_subscribed = Package::where('name', '=', $name)->first();
				
        $package_subscribed->currencyType = $currency_type; 
		
		/* New Changes 11-10-2017 */
		
		if($currency_type == 2){
			
			$currency_val = "sg";
			
		}else if($currency_type == 11){
			
			$currency_val = "au";
			
		}else if($currency_type == 14){
			
			$currency_val = "my";
			
		}else{
			
			$currency_val = "";
			
		}
		
		
		/* End of New Changes 11-10-2017 */
		
        return $this->view('register')->with('package_subscribed', $package_subscribed)
                ->with('menus',  $this->getMenus())
                ->with('package', $packages)
                ->with('country', $this->getCountry() )
                ->with('currentCountry', $this->getCurrentContryAccess() )
				->with('currency_val',$currency_val)
                ->with('market', $this->getMarket() );
    }

    private function storeCompanyInformation($request)
    {
        $payment = new Payment();
        //$market = strtolower($payment->getContryCodeByContryName($market));
        $company = new Company;
        $company->finfo_account_name1 = strtolower($request->input('account_name1')."-".$request->input('market').".".$request->input('host_name'));
       // $company->finfo_account_name2 = strtolower($request->input('account_name2')."-".$request->input('market'));
        $company->company_name = $request->input('company_name');
        $company->phone = $request->input('phone_number');
        $company->email_address = $request->input('company_email');
        $company->website = $request->input('company_website');
        $company->address = $request->input('company_address');
        $company->country = $request->input('country');
        $company->market = $request->input('market');
        $company->save();

        //$package_id = \Crypt::decrypt($request->input('h_package'));

        $package = Package::find($request->input('package_id'));

        $com_sub = new CompanySubscription;
        $com_sub->package_id = $request->input('package_id');
        $com_sub->company_id = $company->id;
        $com_sub->start_date = Carbon::now();
        $com_sub->expire_date = Carbon::now()->addMonths(1);
        $com_sub->is_current = 1;
        $com_sub->is_active = 1;
        $com_sub->currency_id = $request->input('h_package_currency_type');
        $com_sub->save();
       
        $invoice = new Invoice;
        $invoice->payment_method_id = 1;
        $invoice->company_subscription_package_id = $com_sub->id;
        $invoice->invoice_date = Carbon::now();
        $invoice->due_date = Carbon::now()->addWeeks(1);
        $invoice->amount =   $package->price;
        $invoice->invoice_number = Carbon::now()->format('Y-m');
        $invoice->transaction_id = 00;        
        $invoice->status = 0;
        $invoice->save();
        $invoice->invoice_number = Carbon::now()->format('Y-m').'-'.$invoice->id;
        $invoice->save();
        
        
        
        /*For Social Media Group*/
        
        $get_toolkit_details = DB::table('Media_tool_settings')->first();
        
        $access_token = $get_toolkit_details->oauth_token;
        $organization_id = $get_toolkit_details->organization_id;
            
        $group_name = $company->company_name;
            
        $company_id = $company->id;
        
    $data = array("access_token" => $access_token, "name" => $group_name, "public" => "true");                                                                    

    $data_string = json_encode($data);                                                         
    $group_url = 'https://api.mediatoolkit.com/organizations/'.$organization_id.'/groups';

    $ch = curl_init($group_url);                                                                      

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          

    'Content-Type: application/json',                                                                                

    'Content-Length: ' . strlen($data_string))                                                                       

    );                                                                                                                   

    $result = curl_exec($ch);

    $socialData = json_decode($result);

    $group_id = $socialData->data->id;


    $insert =array('company_id' => $company_id,
                    'group_name' => $group_name,
                    'group_id' => $group_id
                 );
                              
        $insert_info = DB::table('feed_group_details')->insert($insert);

        /*End of Social Media Group*/
        
        return array('company_id' => $company->id, 'csp_id' => $com_sub->id, 'invoice_id' => $invoice->id );
    }

    private function storeUserInformation($companyId, $activation_code, $request )
    {
        $user = new User;
        $user->user_type_id = 5;
        $user->company_id = $companyId;
        $user->first_name = $request->input('first_name');
        $user->last_name =$request->input('last_name');
        $user->phone =$request->input('phone');
        $user->email_address = $request->input('email');
        $user->password =  Hash::make($request->input('password'));
        $user->lunch_country =  $request->input('country');
        $user->lunch_market =  $request->input('market');
        $user->verify_token = $activation_code;
        $user->save();
        return $user->id;
    }

    private function updateAdminIdOfCompanyInformatonOn($companyId, $userId)
    {
        $com = Company::find($companyId);
        $com->admin_id = $userId;
        $com->save();
    }

    private function sendEmailVerifyToClient($activation_code, $values)
    {
        $csp_id = Session::get('data')['csp_id'];
     	
        $link_payment = route('finfo.registers.checkout', $csp_id);
				
        $values['from_email'] = $this->getFromFinfoEmail();
		
	    $link = route('finfo.user.frontend.verify', $activation_code);
	
	
	  Mail::queue('resources.views.emails.verify_client', array('value' => $values, 'link' => $link, 'link_payment' => $link_payment), function ($message) use($values) {
            $message->subject("Hello from the FINFO Founders");
            $message->from($values['from_email'], 'FINFO Solutions');
            $message->to($values['email']);
        });
        return true;
    }

    private function sendConfirmEmailAfterUserRegisterToAdmin($companyId, $value)
    {
        $settings = $this->getSettingJsonFile();
        $value['from_email'] = $this->getFromFinfoEmail();
        $getAdminEmail = explode(',', $settings['admin_email_receive_noti']);
        foreach($getAdminEmail as $adminEmail) {
            $value['admin_email_receive_noti'] = $adminEmail;
            $admin = 'Admin';
            $client_link = route('finfo.admin.clients.detail', $companyId);
            Mail::queue('resources.views.emails.notify_finfo', array('value' => $value, 'admin' => $admin, 'link' => $client_link), function ($message) use($value) {
                $message->subject("New Client Registration Notification");
                $message->from($value['from_email'], 'FINFO Solutions');
                $message->to($value['admin_email_receive_noti']);
            });
        }
        return true;
    }
    private function validateCheckoutProcess($request) {
        $rules = array(
            'street' => 'required|min:2',
            'city' => 'required|min:2|max:50',
            'zip_code' => 'required|numeric|min:2',
            'country' => 'required',
            'state' => 'required',
            'phone' => 'required|regex:/^\+?[^a-zA-Z]{5,}$/',
            'card_holder_name' => 'required|min:6',
            'card_number' => 'required|numeric',
            'cvv_number' => 'required|numeric',
            'expiry_month' => 'required|numeric',
            'expiry_year' => 'required|numeric',
        );
        $validator = Validator::make($request->all(), $rules);
        if ( $validator->fails() ) {
            return $validator->errors();
        }
        return false;
    }
    
    private function checkDefaultValidation($request) {
        $rules = array(
            'account_name1' => 'required|min:6|max:20|unique:company,finfo_account_name1',
            //'account_name2' => 'required|min:6|max:20|unique:company,finfo_account_name2',
            'first_name' => 'required|min:2|max:50',
            'last_name' => 'required|min:2|max:50',
            'company_email' => 'required|email|min:5|max:50',
            'company_address' => 'required|',
            'email' => 'required|email|min:5|max:50',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
            'checkbox' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ( $validator->fails() ) {
            return $validator->errors();
        }
        return false;
    }
    /* This function will be used with register
        - params (id)
        - set rules
        - check validation
        - check email exist
        - insert user info
        - insert token into database
        - send verify email with token
    */
    public function doRegister(Request $request)
    { //echo 5;exit;
        $checkDefualtValidation = $this->checkDefaultValidation($request);
        if ($checkDefualtValidation == false) {
            $activation_code = md5(time().rand());
            // SAVE COMPANAY INFORMATION
            $company = $this->storeCompanyInformation($request);
            $package = Package::find($request->input('package_id'));

            $data = array('company_id' => $company['company_id'],
                          'csp_id' => $company['csp_id'],
                          'package_name' => $package->name,
                          'package_currency_type' => $request->input('h_package_currency_type'),
                          'package_id' => $request->input('package_id'),
                          'package_period' => $package->period,
                          'package_period_title' => $package->period_title,
                          'valid_until' => Carbon::now()->addMonths(1)->format('m/d/Y'),
                          'invoice_id' => $company['invoice_id'],
                          'country' => $request->input('country'),
                          'market' => $request->input('market'),
                          'email' => $request->input('email'),
                         );

            Session::put('data', $data);
			
			/* For Activate all Menus 07-11-2017` */
			
				for($i=1;$i<=4;$i++)
                {
					DB::table('menu_permissions')->insert(
							['menus_id' => $i, 'company_id' => $company['company_id']]
                                                        );
                }
				
				for($i=6;$i<=12;$i++)
                {
					DB::table('menu_permissions')->insert(
							['menus_id' => $i, 'company_id' => $company['company_id']]
                                                        );
                }
				
				
				//DB::table('menu_permissions')->where('menus_id',5)->where('company_id',$company['company_id'])->delete();
			
			/* End of Menus Active 07-11-2017 */


            // SAVE USER INFORMATION
            $userId = $this->storeUserInformation($company['company_id'], $activation_code, $request);

            // SAVE COMPANY SUBSCRIBER
            $this->updateAdminIdOfCompanyInformatonOn($company['company_id'], $userId);

            // SEND EMAIL TO CLIENT
            $this->sendEmailVerifyToClient($activation_code, $request->all());

            // SEND EMAIL TO ADMIN USER
            $this->sendConfirmEmailAfterUserRegisterToAdmin($company['company_id'], $request->all());

      return redirect()->route('finfo.registers.checkout')->with('menus', $this->getMenus());
        
              //02-12-17    return redirect()->route('finfo.registers.success')->with('menus', $this->getMenus());
        
        }else{
            return redirect()->back()->withInput()->withErrors($checkDefualtValidation);
        }
    }
    public function getCheckout($scp_id='')
    {   
        
        if($scp_id != ''){
            $com_sub = CompanySubscription::find($scp_id);


            if($com_sub){
                $company = Company::find($com_sub->company_id);
                $check_company = Company::join('company_subscription_package as csp', 'csp.company_id','=','company.id')
                            ->join('invoice', 'invoice.company_subscription_package_id', '=', 'csp.id')
                            ->where('company.id', '=', $com_sub->company_id)
                            ->where('csp.is_current', '=', 1)
                            ->where('csp.is_active', '=', 1)
                            ->where('invoice.status', '=', 1)
                            ->first();
                if(count($check_company) >= 1){
                    return redirect('/');
                }

                $invoice = Invoice::where('company_subscription_package_id' , '=', $scp_id)->first();
                if($invoice->status == 1){
                    return redirect('/');
                }
                $package = Package::find($com_sub->package_id);

                $data = array('company_id' => $company->id,
                              'csp_id' => $scp_id,
                              'package_name' => $package->name,
                              'package_currency_type' => $com_sub->currency_id,
                              'package_id' => $com_sub->package_id,
                              'package_period' => '',
                              'package_period_title' => '',
                              'valid_until' => $com_sub->expire_date,
                              'invoice_id' => $invoice->id,
                              'country' => $company->country,
                              'market' => $company->market,
                             );
                Session::put('data', $data);
                $package_subscribed = Package::find($data['package_id']);
                $currency_infor = Currency::find($data['package_currency_type']); 

                if($currency_infor->id == 11){
                    $package_subscribed->pakage_price = $currency_infor->symbol." ". number_format(round($package_subscribed->price_aud), 2 , ".", "," );
                }else{
                    $package_subscribed->pakage_price = $currency_infor->symbol." ". $this->setCurrencyFormat($currency_infor->exchange_rate, $package_subscribed->price );
                }
                
                
                return $this->view('checkout')->with('package_subscribed', $package_subscribed)->with('menus', $this->getMenus())
                        ->with('country', $this->getMenus())
                        ->with('market', $this->getMenus())
                        ->with('payment_method', $this->getPaymentMethod());
            }else{
                return redirect('/');
            }
            
        }elseif(session::has('data')) {
            $package_subscribed = Package::find(Session::get('data')['package_id']);

            $emaildata = Session::get('data');
            $emaildata =  $emaildata['email'];
            $currency_infor = Currency::find(Session::get('data')['package_currency_type']);

            if($currency_infor->id == 11){
                $package_subscribed->pakage_price = $currency_infor->symbol." ". number_format(round($package_subscribed->price_aud), 2 , ".", "," );
            }else{
                $package_subscribed->pakage_price = $currency_infor->symbol." ". $this->setCurrencyFormat($currency_infor->exchange_rate, $package_subscribed->price );
            }

            return $this->view('checkout')->with('package_subscribed', $package_subscribed)->with('menus', $this->getMenus())
                    ->with('country', $this->getMenus())
                    ->with('market', $this->getMenus())
                    ->with('email',$emaildata)
                    ->with('payment_method', $this->getPaymentMethod());
        }else{
            return redirect('/');
        }
    }
    
    private function sendConfirmEmailAfterPaymentPaid($transaction_infor, $data) {

        $email = array(
                    'email' => isset($transaction_infor->Customer->Email) ? $transaction_infor->Customer->Email : $transaction_infor['securePayEmail']  ,
                    'pdf_file' => public_path().'/receipt/'.$data['file_name'],
                    'invoice_number' => $data['invoice_number']
                );
        $email['from_email'] = $this->getFromFinfoEmail();
        $currency_infor = Currency::find(Session::get('data')['package_currency_type']);

        $data['data']['currencyCode']  = $currency_infor->code;

        $data['data']['pdf_link']  = $email['pdf_file'];
        $data['data']['valid_until']  = Carbon::now()->addMonths(1)->format('m/d/Y');
        $tax = number_format(round(($data['data']['invoice']['amount'] * 7 / 100) * $currency_infor->exchange_rate), 2,'.','');
        $data['data']['tax']  = $currency_infor->symbol." ".$tax." ".$currency_infor->code;
        Mail::queue('app.Components.Finfo.Modules.Registers.views.emails.payment_notify', array('userData' => $data['data']), function ($message) use($email) {
            $message->subject("PAID - Customer Invoice #".$email['invoice_number']." - Payment Confirmation");
            $message->from($email['from_email'], 'FINFO Solutions');
            $message->to($email['email']);
            $message->attach($email['pdf_file']);
        });
        \Session::flush();
        return true;
    }
    public function saveCheckout(Request $request)
    {


        $getValidation = $this->validateCheckoutProcess($request);
        if ($getValidation == true ) {
            return redirect()->back()->withInput()->withErrors($getValidation);
        }else{
            
                $invoiceData = Invoice::find(Session::get('data')['invoice_id']);
            

           

            $stripe = Stripe::make('sk_test_9O4hvGw8bi5dtLvV6PF9IcPo');          
            $customer = $stripe->customers()->create([
                'email' =>$request->input('email')
            ]);
            try{


            $token = $stripe->tokens()->create([
                'card' => [
                    'number'    => $request->input('card_number'),
                    'exp_month' => $request->input('expiry_month'),
                    'cvc'       => $request->input('cvv_number'),
                    'exp_year'  => $request->input('expiry_year')
                ]
            ]);



            if(!$token)
            throw (new Cartalyst\Stripe\Exception\CardErrorException);

        }

        catch(Exception $e){
         
            return redirect()->back()->withInput()->withErrors(array('payment', strtolower("Error: ".$e->getMessage())));
        }



        $card = $stripe->cards()->create($customer['id'], $token['id']);
     

        $charge = $stripe->charges()->create([
            'customer' => $customer['id'],
            'currency' => 'USD',
            'amount'   => $invoiceData->amount, 
        ]);

        
        $securePay = array_merge($charge, $request->all());

     

        $getData = $this->storeCheckoutUserInformation($securePay);
       
        $securePayEmail['securePayEmail'] = $getData['data']['user']['email_address'];
      
        $this->sendConfirmEmailAfterPaymentPaid($securePayEmail, $getData);
                    return $this->view('success')->with('menus', $this->getMenus());
           
          



        }
    }






    
    private function storeCheckoutUserInformation($getResponseOfTransaction) {

        // BILLING TABLE
        $input = Input::all();
        
        $billing = new Billing;
        $billing->company_id = $input['hide_company'];
        $billing->street = $input['street'];
        $billing->city = $input['city'];
        $billing->zip_code = $input['zip_code'];
        $billing->country = $input['country'];
        $billing->state = $input['state'];
        $billing->phone = $input['phone'];
        $billing->save();

        // INVOICE TABLE
        $invoice = Invoice::find(Session::get('data')['invoice_id']);
        $invoice->payment_method_id = 1;
        $invoice->due_date = Carbon::now()->addWeeks(1);
        $invoice->updated_at = Carbon::now();
        
        if ($getResponseOfTransaction['failure_code'] == null) {
            $invoice->response_code = 0;
            $invoice->status = 1;
        } else {
            $invoice->status = 0;
        }

       
        
        $invoice->transaction_id = $getResponseOfTransaction['balance_transaction'];
       
        $invoice->update(); 
        
        //PASS DATA TO PDF FILE
        $payment_method = DB::table('payment_method')->where('id','=', 1)->first();
        $currency_infor = Currency::find(Session::get('data')['package_currency_type']); 
        $arr['company'] = Company::find($input['hide_company']);
        $arr['user'] = User::find($arr['company']['admin_id']);
        $arr['csp'] = CompanySubscription::find($input['hide_csp']);
        $arr['invoice'] = $invoice;
        $arr['payment_method'] = $payment_method->name;
        $arr['billing'] =$billing;
        $arr['currency']['code'] = @$currency_infor->code;
        $arr['package'] = \Session::get('data');
        if(@$currency_infor->id == 11){
            $overide_package = Package::find(Session::get('data')['package_id']);
            $arr['package']['package_price'] = $currency_infor->symbol." ".$overide_package->price_aud;
        }else{
            $arr['package']['package_price'] = $currency_infor->symbol." ".$this->setCurrencyFormat($currency_infor->exchange_rate, $invoice->amount);

        }
        
        $data['file_name'] = "RECEIPT_".$invoice->invoice_number."_". date('d-m-Y', strtotime(Carbon::now())) .".pdf";
        $data['data'] = $arr;
        $data['invoice_number'] = $invoice->invoice_number;
        // app('App\Components\Client\Modules\Invoice\InvoiceController')->saveInvoicePdf($data);
        app('App\Components\Client\Modules\Invoice\InvoiceController')->saveReceiptPdf($data);
        return $data;
    }
    
    public function getToken($token)
    {
        if(!$token) {

            return 'Not allowed';
        }

        $user = User::whereVerify_token($token)->first();

        if (!$user)
        {
            return 'Your activated key has been expired!';
        }

        $user->verify_token = null;
        $user->is_active = 1;
        $user->save();

        return redirect('register/success')->with('menus', $this->getMenus());

    }

    public function getRegisterSuccess()
    {
        return $this->view('success')->with('menus', $this->getMenus());
    }

    public function validateDomainNameByEmail(Request $request )
    {
        $checkAccountName = Company::where('finfo_account_name', '=', $request->input('name'))
                                    ->count();
        $checkAccountName1 = Company::where('finfo_account_name1', '=', $request->input('name'))
                                    ->count();
        $checkAccountName2 = Company::where('finfo_account_name2', '=', $request->input('name'))
                                    ->count();
        $total = $checkAccountName +  $checkAccountName1 + $checkAccountName2;
        if ($total >= 1) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    public function getCountry() {
        $countries = array( 'AF'=>'AFGHANISTAN', 'AL'=>'ALBANIA', 'DZ'=>'ALGERIA', 'AS'=>'AMERICAN SAMOA', 'AD'=>'ANDORRA', 'AO'=>'ANGOLA', 'AI'=>'ANGUILLA', 'AQ'=>'ANTARCTICA', 'AG'=>'ANTIGUA AND BARBUDA', 'AR'=>'ARGENTINA', 'AM'=>'ARMENIA', 'AW'=>'ARUBA', 'AU'=>'AUSTRALIA', 'AT'=>'AUSTRIA', 'AZ'=>'AZERBAIJAN', 'BS'=>'BAHAMAS', 'BH'=>'BAHRAIN', 'BD'=>'BANGLADESH', 'BB'=>'BARBADOS', 'BY'=>'BELARUS', 'BE'=>'BELGIUM', 'BZ'=>'BELIZE', 'BJ'=>'BENIN', 'BM'=>'BERMUDA', 'BT'=>'BHUTAN', 'BO'=>'BOLIVIA', 'BA'=>'BOSNIA AND HERZEGOVINA', 'BW'=>'BOTSWANA', 'BV'=>'BOUVET ISLAND', 'BR'=>'BRAZIL', 'IO'=>'BRITISH INDIAN OCEAN TERRITORY', 'BN'=>'BRUNEI DARUSSALAM', 'BG'=>'BULGARIA', 'BF'=>'BURKINA FASO', 'BI'=>'BURUNDI', 'KH'=>'CAMBODIA', 'CM'=>'CAMEROON', 'CA'=>'CANADA', 'CV'=>'CAPE VERDE', 'KY'=>'CAYMAN ISLANDS', 'CF'=>'CENTRAL AFRICAN REPUBLIC', 'TD'=>'CHAD', 'CL'=>'CHILE', 'CN'=>'CHINA', 'CX'=>'CHRISTMAS ISLAND', 'CC'=>'COCOS (KEELING) ISLANDS', 'CO'=>'COLOMBIA', 'KM'=>'COMOROS', 'CG'=>'CONGO', 'CD'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'CK'=>'COOK ISLANDS', 'CR'=>'COSTA RICA', 'CI'=>'COTE D IVOIRE', 'HR'=>'CROATIA', 'CU'=>'CUBA', 'CY'=>'CYPRUS', 'CZ'=>'CZECH REPUBLIC', 'DK'=>'DENMARK', 'DJ'=>'DJIBOUTI', 'DM'=>'DOMINICA', 'DO'=>'DOMINICAN REPUBLIC', 'TP'=>'EAST TIMOR', 'EC'=>'ECUADOR', 'EG'=>'EGYPT', 'SV'=>'EL SALVADOR', 'GQ'=>'EQUATORIAL GUINEA', 'ER'=>'ERITREA', 'EE'=>'ESTONIA', 'ET'=>'ETHIOPIA', 'FK'=>'FALKLAND ISLANDS (MALVINAS)', 'FO'=>'FAROE ISLANDS', 'FJ'=>'FIJI', 'FI'=>'FINLAND', 'FR'=>'FRANCE', 'GF'=>'FRENCH GUIANA', 'PF'=>'FRENCH POLYNESIA', 'TF'=>'FRENCH SOUTHERN TERRITORIES', 'GA'=>'GABON', 'GM'=>'GAMBIA', 'GE'=>'GEORGIA', 'DE'=>'GERMANY', 'GH'=>'GHANA', 'GI'=>'GIBRALTAR', 'GR'=>'GREECE', 'GL'=>'GREENLAND', 'GD'=>'GRENADA', 'GP'=>'GUADELOUPE', 'GU'=>'GUAM', 'GT'=>'GUATEMALA', 'GN'=>'GUINEA', 'GW'=>'GUINEA-BISSAU', 'GY'=>'GUYANA', 'HT'=>'HAITI', 'HM'=>'HEARD ISLAND AND MCDONALD ISLANDS', 'VA'=>'HOLY SEE (VATICAN CITY STATE)', 'HN'=>'HONDURAS', 'HK'=>'HONG KONG', 'HU'=>'HUNGARY', 'IS'=>'ICELAND', 'IN'=>'INDIA', 'ID'=>'INDONESIA', 'IR'=>'IRAN, ISLAMIC REPUBLIC OF', 'IQ'=>'IRAQ', 'IE'=>'IRELAND', 'IL'=>'ISRAEL', 'IT'=>'ITALY', 'JM'=>'JAMAICA', 'JP'=>'JAPAN', 'JO'=>'JORDAN', 'KZ'=>'KAZAKSTAN', 'KE'=>'KENYA', 'KI'=>'KIRIBATI', 'KP'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF', 'KR'=>'KOREA REPUBLIC OF', 'KW'=>'KUWAIT', 'KG'=>'KYRGYZSTAN', 'LA'=>'LAO PEOPLES DEMOCRATIC REPUBLIC', 'LV'=>'LATVIA', 'LB'=>'LEBANON', 'LS'=>'LESOTHO', 'LR'=>'LIBERIA', 'LY'=>'LIBYAN ARAB JAMAHIRIYA', 'LI'=>'LIECHTENSTEIN', 'LT'=>'LITHUANIA', 'LU'=>'LUXEMBOURG', 'MO'=>'MACAU', 'MK'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'MG'=>'MADAGASCAR', 'MW'=>'MALAWI', 'MY'=>'MALAYSIA', 'MV'=>'MALDIVES', 'ML'=>'MALI', 'MT'=>'MALTA', 'MH'=>'MARSHALL ISLANDS', 'MQ'=>'MARTINIQUE', 'MR'=>'MAURITANIA', 'MU'=>'MAURITIUS', 'YT'=>'MAYOTTE', 'MX'=>'MEXICO', 'FM'=>'MICRONESIA, FEDERATED STATES OF', 'MD'=>'MOLDOVA, REPUBLIC OF', 'MC'=>'MONACO', 'MN'=>'MONGOLIA', 'MS'=>'MONTSERRAT', 'MA'=>'MOROCCO', 'MZ'=>'MOZAMBIQUE', 'MM'=>'MYANMAR', 'NA'=>'NAMIBIA', 'NR'=>'NAURU', 'NP'=>'NEPAL', 'NL'=>'NETHERLANDS', 'AN'=>'NETHERLANDS ANTILLES', 'NC'=>'NEW CALEDONIA', 'NZ'=>'NEW ZEALAND', 'NI'=>'NICARAGUA', 'NE'=>'NIGER', 'NG'=>'NIGERIA', 'NU'=>'NIUE', 'NF'=>'NORFOLK ISLAND', 'MP'=>'NORTHERN MARIANA ISLANDS', 'NO'=>'NORWAY', 'OM'=>'OMAN', 'PK'=>'PAKISTAN', 'PW'=>'PALAU', 'PS'=>'PALESTINIAN TERRITORY, OCCUPIED', 'PA'=>'PANAMA', 'PG'=>'PAPUA NEW GUINEA', 'PY'=>'PARAGUAY', 'PE'=>'PERU', 'PH'=>'PHILIPPINES', 'PN'=>'PITCAIRN', 'PL'=>'POLAND', 'PT'=>'PORTUGAL', 'PR'=>'PUERTO RICO', 'QA'=>'QATAR', 'RE'=>'REUNION', 'RO'=>'ROMANIA', 'RU'=>'RUSSIAN FEDERATION', 'RW'=>'RWANDA', 'SH'=>'SAINT HELENA', 'KN'=>'SAINT KITTS AND NEVIS', 'LC'=>'SAINT LUCIA', 'PM'=>'SAINT PIERRE AND MIQUELON', 'VC'=>'SAINT VINCENT AND THE GRENADINES', 'WS'=>'SAMOA', 'SM'=>'SAN MARINO', 'ST'=>'SAO TOME AND PRINCIPE', 'SA'=>'SAUDI ARABIA', 'SN'=>'SENEGAL', 'SC'=>'SEYCHELLES', 'SL'=>'SIERRA LEONE', 'SG'=>'SINGAPORE', 'SK'=>'SLOVAKIA', 'SI'=>'SLOVENIA', 'SB'=>'SOLOMON ISLANDS', 'SO'=>'SOMALIA', 'ZA'=>'SOUTH AFRICA', 'GS'=>'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'ES'=>'SPAIN', 'LK'=>'SRI LANKA', 'SD'=>'SUDAN', 'SR'=>'SURINAME', 'SJ'=>'SVALBARD AND JAN MAYEN', 'SZ'=>'SWAZILAND', 'SE'=>'SWEDEN', 'CH'=>'SWITZERLAND', 'SY'=>'SYRIAN ARAB REPUBLIC', 'TW'=>'TAIWAN, PROVINCE OF CHINA', 'TJ'=>'TAJIKISTAN', 'TZ'=>'TANZANIA, UNITED REPUBLIC OF', 'TH'=>'THAILAND', 'TG'=>'TOGO', 'TK'=>'TOKELAU', 'TO'=>'TONGA', 'TT'=>'TRINIDAD AND TOBAGO', 'TN'=>'TUNISIA', 'TR'=>'TURKEY', 'TM'=>'TURKMENISTAN', 'TC'=>'TURKS AND CAICOS ISLANDS', 'TV'=>'TUVALU', 'UG'=>'UGANDA', 'UA'=>'UKRAINE', 'AE'=>'UNITED ARAB EMIRATES', 'GB'=>'UNITED KINGDOM', 'US'=>'UNITED STATES', 'UM'=>'UNITED STATES MINOR OUTLYING ISLANDS', 'UY'=>'URUGUAY', 'UZ'=>'UZBEKISTAN', 'VU'=>'VANUATU', 'VE'=>'VENEZUELA', 'VN'=>'VIET NAM', 'VG'=>'VIRGIN ISLANDS, BRITISH', 'VI'=>'VIRGIN ISLANDS, U.S.', 'WF'=>'WALLIS AND FUTUNA', 'EH'=>'WESTERN SAHARA', 'YE'=>'YEMEN', 'YU'=>'YUGOSLAVIA', 'ZM'=>'ZAMBIA', 'ZW'=>'ZIMBABWE');
        return $countries;
    }
    public function getCurrentContryAccess() {
        $getCountry = $this->getUserAccessWebsiteInfo();
        if (isset($getCountry->country)) {
            return $getCountry->country;
        }
    }
    public function getMarket(){
        $lunch_markets[''] = 'Please select a market';
        $getSettings = $this->getSettingJsonFile();
        $markets = explode(',', $getSettings['lunch_market']);
        foreach($markets as $market) {
            $key = array_search(strtoupper($market), $this->getCountry());
            $lunch_markets[strtolower($key)] = $market;
        }
        return $lunch_markets;
    }
    public function doSecurePay() {
        $securePay =  new SecurePayController();
        $securePay->TestMode();
        $securePay->SecurePay('ABC0001', 'abc123', true);
        if ($securePay->TestConnection()){
            echo "<br/>it connection";
        }else{
            echo "<br/>not connection";
        }
        //$securePay->Process(123, 'USD', '462834666666', '07/19', '321', 'ORD34234', TRUE);
    }
    public function getPaymentMethod() {
        $getPaymentMethodData = DB::table('payment_method')->orderby('id', 'asc')->get();
        return $getPaymentMethodData;
    }
}
