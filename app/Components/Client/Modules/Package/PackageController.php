<?php

namespace App\Components\Client\Modules\Package;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Components\Finfo\Modules\Clients\CompanySubscription;
use App\Components\Finfo\Modules\Clients\Billing;
use App\Components\Finfo\Modules\Clients\Company;
use App\Components\Finfo\Modules\Registers\Package;
use App\Components\Finfo\Modules\Currency\Currency;
use App\Components\Client\Modules\Invoice\Invoice;
use App\Components\Finfo\Modules\Registers\RegisterController;
use DateTime;
use Mail;
use Session;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;
use App\Components\Finfo\Modules\SecurePay\SecurePayController;

class PackageController extends Controller
{
    private $payment;
    private $merchantId = "ABC0001";
    private $merchantPassword = "abc123";
    
    public function __construct(){
        $this->middleware('auth');

        if(\Auth::check() && \Auth::user()->user_type_id != 5)
        {
            App::abort(403);
        }
        $this->payment = 'App\Components\Client\Modules\Payment\PaymentController';
    }
    public function getCurrentPackage() {
        
        //return 
    }
	public function getPackage()
	{
        
        $account = \Session::get('account');
        $company = DB::table('company')->join('company_subscription_package as csp','csp.company_id','=','company.id')->select('csp.package_id', 'currency_id')->where('company.finfo_account_name','=',$account)->where('csp.is_current', 1)->first();
        
        if(Input::get('currency') == '') {
            $currency_id = $company->currency_id;
            if($currency_id == 0)
                // $currency_id = 11;
                $currency_id = 2;
        }else{
            $currency_id = Input::get('currency');
        }

        $package_module = Package_module::getPackage();
        $currency = DB::table('currency')->where('id', 11)->lists('name', 'id');

        $exchange = DB::table('currency')->where('id', $currency_id)->select('exchange_rate','symbol')->first();
        Session::put('currency', $currency_id);

        $is_expire = app('App\Components\Client\Modules\Home\HomeController')->checkCompanyIsExpired();
        //$package_module = Package_module::getPackage();

        $history = Company::join('company_subscription_package as csp', 'company.id', '=', 'csp.company_id')
                                ->join('package', 'csp.package_id', '=', 'package.id')
                                ->where('company.id', Auth::user()->company_id)
                                ->orderBy('csp.id', 'DESC')
                                ->get();


		return $this->view('package')->with('package_module', $package_module)->with('currency', $currency)->with('exchange', $exchange)->with('package_id', $company->package_id)
                        ->with('history', $history)->with('is_expire', $is_expire)->with('currency_id', $currency_id);
	}
        public function cancelPackage() {
            $siteUrl =\Config::get('app.protocol')."://".\Config::get('app.base_domain');
            $company = Company::find(Auth::user()->company_id);
            $company->is_active = 0;
            $company->update();
            $com_sub = CompanySubscription::where('company_id', '=', Auth::user()->company_id)
                    ->where('is_cancel', '=', 0)
                    ->where('is_current', '=', 1)
                    ->first();
            if (isset($com_sub) && !empty($com_sub)) {
                $com_sub->updated_at = Carbon::now();
                $com_sub->updated_by = Auth::user()->id;
                $com_sub->is_active = 0;
                $com_sub->is_cancel = 1;
                $com_sub->cancelled_at = Carbon::now();
                $com_sub->save();
                $this->unsubscribedSendEmailToClient();
                $this->unsubscribedSendEmailToAdmin();
                \Auth::logout();
            }
            return redirect()->to($siteUrl);
        }
        private function getCompanyName() {
            $companyInfor = Company::findOrNew(Auth::user()->company_id);
            return $companyInfor->company_name;
        }
                
        private function unsubscribedSendEmailToClient() {
            $values = array(
                            'email' => Auth::user()->email_address,
                            'company_name' => $this->getCompanyName()
                        );
            Mail::queue('app.Components.Client.Modules.Package.views.email.unsubscribe_client', array('companyInfo' => $values), function ($message) use($values) {
                $message->subject("Unsubscribe Request for ".$values['company_name']);
                $message->from('no-reply@finfo.com', 'FINFO Solutions');
                $message->to($values['email']);
            });
        }
        
        private function unsubscribedSendEmailToAdmin() {
            $settings = $this->getSettingJsonFile();
            $values = array(
                            'email' => Auth::user()->email_address,
                            'company_name' => $this->getCompanyName(),
                            'admin_email' => $settings['admin_email_receive_noti']
                        );
            Mail::queue('app.Components.Client.Modules.Package.views.email.unsubscribe_admin', array('companyInfo' => $values), function ($message) use($values) {
                $message->subject("Request to Unsubscribe ".$values['company_name']);
                $message->from($values['email'], $values['company_name']);
                $message->to($values['admin_email']);
            });
        }
        
    public function upgradePackage($id)
    {
        $registerController = new RegisterController();
        $data = array(
                        'package' => DB::table('package')->where('package.id', $id)->get(),
                        'date' => $this->getExpireDate()//Carbon::now()->addMonths(1)->format('m/d/Y'),
                    );
        $price_cur = [];
        if(Session::has('currency')){
            $cur_id = Session::get('currency');
            
        }else{
            $account = \Session::get('account');
            $company = DB::table('company')->join('company_subscription_package as csp','csp.company_id','=','company.id')->select('csp.package_id', 'currency_id')->where('company.finfo_account_name','=',$account)->where('csp.is_current', 1)->first();
            $cur_id = $company->currency_id;
            if($cur_id == 0){
                $cur_id = 2;
            }
        }

        Session::put('currency', $cur_id);
        $cur = Currency::find($cur_id);
        $price_cur['exchange_rate'] = $cur->exchange_rate;
        $price_cur['symbol']   = $cur->symbol;
        //var_dump($exchange_rate);

        return $this->view('checkout')->with('data', $data)->with('price_cur', $price_cur)->with('payment_method', $registerController->getPaymentMethod());;
    }

    public function saveCheckout(Request $request)
    {
        $validate = Validator::make(Input::all(), [
            'card_holder_name'    => 'required|min:6',
            'card_number'     => 'required|numeric',
            'cvv_number' => 'required|numeric',
            'expiry_month'     => 'required|numeric',
            'expiry_year'      => 'required|numeric',
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
        $data = Input::all();
        $package_id = Input::get('package_id');
        if (Input::get('payment_method') == 2) {
             $securePay =  new SecurePayController();
                $securePay->TestMode();
                $securePay->SecurePay($this->merchantId, $this->merchantPassword, true);
                if ($securePay->TestConnection()){
                    $expireDate = $request->input('expiry_month').'/'.$request->input('expiry_year');
                    $invoiceData = Invoice::find(Session::get('data')['invoice_id']);
                    if ($request->input('expiry_month') < 10) {
                        $expireDate = '0'.$request->input('expiry_month')."/".$request->input('expiry_year');
                    }
                    $package = Package::find($package_id);
                    // DO NOT REMOVE 
                    $getLastInvoiceNumber = invoice::orderby('id', 'desc')->limit(1)->first();
                    $generateInvoiveNumberber = $getLastInvoiceNumber->id + 1;
                    // END
                    $invoice_number = Carbon::now()->format('Y-m').'-'.$generateInvoiveNumberber;
                    $getResponseSecurePay = $securePay->Process(
                                                                $package->price, 
                                                                'USD', 
                                                                $request->input('card_number'),
                                                                $expireDate, 
                                                                $request->input('cvv_number'), 
                                                                $invoice_number
                                                            );
                    if ($securePay->ResponseCode != 00) {
                        return redirect()->back()->withInput()->withErrors(array('payment', strtolower("Error: ".$securePay->ResponseCodeText)));
                    }else{    
                        $getData = $this->newInvoice($securePay, $package_id, 2);
                        $securePayEmail['securePayEmail'] = $getData['data']['user']['email_address'];
                        $this->sendConfirmEmailAfterPaymentPaid($securePayEmail, $getData);
                        return Redirect::route('client.admin.package')->with('global', 'Payment success.');
                    }
                }
        }else{
            $response = app($this->payment)->CheckoutTransaction($data);
            if (!$response->getErrors()) {
                $getData = $this->newInvoice($response, $package_id, 1);
                $this->sendConfirmEmailAfterPaymentPaid($response, $getData);
                return Redirect::route('client.admin.package')->with('global', 'Payment success.');
            } else {
                if ($response->getErrors()) {
                    foreach ($response->getErrors() as $error) {
                        return redirect()->back()->withInput()->withErrors(array('payment', strtolower("Error: ".\Eway\Rapid::getMessage($error))));
                    }
                }
            }
        }
        
    }
    
    private function getLastInvoiceByCompanyId(){
        $getCompanySubscriptionData = CompanySubscription::where('company_id', Session::get('company_id'))
                                    ->orderby('id', 'desc')
                                    ->first();
        $datetime = strtotime($getCompanySubscriptionData->expire_date);
        $getDate = date("Y-m-d h:i:s", strtotime("+1 month", $datetime));
        return $getDate;
    }
    public function newInvoice($response, $package_id, $paymenMethod)
    {
        $package = Package::find($package_id);

        $company = CompanySubscription::where('company_id', Session::get('company_id'))
                                    ->where('is_current', 1)
                                    ->first();
        $company->is_current = 0;
        $company->is_active = 0;
        $company->update();
        
        $com_sub = new CompanySubscription;
        $com_sub->package_id = $package_id;
        $com_sub->company_id = Session::get('company_id');
        $com_sub->expire_date = $this->getLastInvoiceByCompanyId();
        $com_sub->start_date = date("Y-m-d h:i:s", strtotime("-1 month", strtotime($this->getLastInvoiceByCompanyId())));
        $com_sub->is_current = 1;
        $com_sub->is_active = 1;
        $com_sub->currency_id = Session::get('currency');
        $com_sub->save();
        
        $invoice = new Invoice;
        $invoice->payment_method_id = $paymenMethod;
        $invoice->company_subscription_package_id = $com_sub->id;
        $invoice->invoice_date = Carbon::now();
        $invoice->due_date = date("Y-m-d h:i:s", strtotime("+1 week", strtotime($com_sub->expire_date)));
        $invoice->amount =   $package->price;
        $invoice->invoice_number = Carbon::now()->format('Y-m');
        if ($response->ResponseCode == '00' || $response->ResponseCode == 00) {
            $invoice->response_code = $response->ResponseCode;
            $invoice->status = 1;
        }else{
            $invoice->status = 0;
        }
        if ($paymenMethod == 1) { // eWay data
            $invoice->transaction_id = $response->TransactionID;
        } else { // SecurePay data
            $invoice->transaction_id = $response->TransactionId;
        }
        $invoice->save();
        
        $invoice_id = $invoice->id;
        $invoice = Invoice::find($invoice_id);
        $invoice->invoice_number = Carbon::now()->format('Y-m').'-'.$invoice_id;
        $invoice->update();

        $currency = Currency::findOrFail(Session::get('currency'));
        $payment_method = DB::table('payment_method')->where('id','=', $paymenMethod)->first();
        $arr['invoice'] = Invoice::findOrFail($invoice_id);
        $arr['csp']     = CompanySubscription::find($arr['invoice']->company_subscription_package_id);
        $arr['company'] = Company::find($arr['csp']->company_id);
        $arr['user']    = User::find($arr['company']['admin_id']);
        $arr['billing'] = Billing::where('company_id', $arr['csp']->company_id)->first();
        $package        = Package::find($arr['csp']->package_id);
        $arr['currency']['code'] = @$currency->code;
        $arr['payment_method'] = $payment_method->name;
        $arr['package'] = [
                            'package_name' => $package->name,
                            'package_price'=> $currency->symbol.' '. number_format( round($invoice->amount * $currency->exchange_rate, 0, PHP_ROUND_HALF_UP), 2),
                            'package_period_title' => '1 Month'
                            ];

        $data['file_name'] = "RECEIPT_".$invoice->invoice_number."_". date('d-m-Y', strtotime(Carbon::now())) .".pdf";
        $data['data']   = $arr;
        $data['invoice_number'] = $invoice->invoice_number;
        // app('App\Components\Client\Modules\Invoice\InvoiceController')->saveInvoicePdf($data);
        app('App\Components\Client\Modules\Invoice\InvoiceController')->saveReceiptPdf($data);

        return $data;
    }
    
    private function sendConfirmEmailAfterPaymentPaid($transaction_infor, $data) {
        $email = array(
                    'email' => isset($transaction_infor->Customer->Email) ? $transaction_infor->Customer->Email : $transaction_infor['securePayEmail'],
                    'pdf_file' => public_path().'/receipt/'.$data['file_name'],
                    'invoice_number' => $data['invoice_number']
                );
        $email['from_email'] = $this->getFromFinfoEmail();
        //$currency_infor = Currency::find(Session::get('data')['package_currency_type']);
        //$data['data']['currencyCode']  = $currency_infor->code;
        $currency = Currency::findOrFail(Session::get('currency'));
        $data['data']['pdf_link']  = $email['pdf_file'];
        $data['data']['currencyCode']  = $currency->code;
        $data['data']['symbol']  = $currency->symbol;
        $data['data']['valid_until']  = Carbon::now()->addMonths(1)->format('m/d/Y');
        $tax = number_format(($data['data']['invoice']['amount'] * 7 / 100) , 2);
        $data['data']['tax']  = $currency->symbol." ".$tax."  ".$currency->code;
        Mail::queue('app.Components.Client.Modules.Package.views.email.payment_notify', array('userData' => $data['data']), function ($message) use($email) {
            $message->subject("PAID - Customer Invoice #".$email['invoice_number']." - Payment Confirmation");
            $message->from($email['from_email'], 'FINFO Solutions');
            $message->to($email['email']);
            $message->attach($email['pdf_file']);
        });

        return true;
    }
    private function getExpireDate(){
        $getCompanyData = CompanySubscription::where('company_id', Session::get('company_id'))->where('is_current',1)->where('is_active', 1)->first();
        return $getCompanyData->expire_date;
    }
}

