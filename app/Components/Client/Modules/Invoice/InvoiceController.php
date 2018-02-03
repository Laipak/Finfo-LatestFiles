<?php

namespace App\Components\Client\Modules\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use PDF;
use Session;
use Carbon\Carbon;
use Mail;
use App\Components\Finfo\Modules\Clients\Company;
use App\Components\Finfo\Modules\Clients\Billing;
use App\Components\Finfo\Modules\Registers\Package;
use App\Components\Finfo\Modules\Clients\CompanySubscription;
use App\Components\Finfo\Modules\Currency\Currency;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{

    private $payment;
    public function __construct()
    {
        $this->payment = 'App\Components\Client\Modules\Payment\PaymentController';
    }


	public function getViewInvoice()
	{
		return $this->view('view-invoice');
	}

    public function showViewInvoice($data)
	{
		return $this->view('view-invoice')->with('data',$data)->with('controller', $this);
	}

    public function getDLInvoice($data)
    {
        $data['controller'] = $this;
        $pdf = PDF::loadView('app.Components.Client.Modules.Invoice.views.invoice-template', $data);
        return $pdf->download('invoice'.$data['data']['invoice']['invoice_number'].'.pdf');
    }

    public function saveInvoicePdf($data)
    {
        $data['controller'] = $this;
        PDF::loadView('app.Components.Client.Modules.Invoice.views.invoice-template', $data)->save('invoice/'.$data['file_name']);
        return $data['file_name'];
    }

    public function saveReceiptPdf($data)
    {
        $data['controller'] = $this;
        PDF::loadView('app.Components.Client.Modules.Invoice.views.receipt', $data)->save('receipt/'.$data['file_name']);
        return $data['file_name'];
    }

    public function getInovoiceStatus($id)
    {
        if($id == 0 || $id == 2){
            return '<span class="unpaid">Unpaid</span>';
        }elseif($id == 1){
            return '<span class="paid">Paid</span>';
        }else{
            return '<span class="cancelled">Cancelled</span>';
        }
    }
    public function getExchangeRate($ex_id=2){
        $getCurrency = Currency::findOrFail($ex_id);
        $data = array(
            'exchange_rate' => $getCurrency->exchange_rate,
            'symbol' => $getCurrency->symbol,
        );
        return $data;
    }
    
    public function getInvoice($status)
    {
        $invoice_type = 'ever_paid';
        $invoice = new Invoice();
        $curr_id = 2;
        if($status == 0){
            //$sta = [0,2];

            $invoices = $invoice->join('company_subscription_package as csp','invoice.company_subscription_package_id','=','csp.id')
                            ->join('package','csp.package_id', "=", 'package.id')
                            ->where('invoice.status', 1)
                            ->where('company_id', Session::get('company_id'))
                            ->where('is_current', 1)
                            ->orderBy('created_at', 'desc')
                            ->select('invoice.*', 'package.price as amount', 'company_subscription_package_id as csp_id', 'csp.package_id', 'csp.expire_date', 'csp.start_date', 'csp.currency_id as curr_id' )->first();
            
            if(count($invoices) <= 0){
                $invoice_type = 'never_paid';
                $invoices = $invoice->join('company_subscription_package as csp','invoice.company_subscription_package_id','=','csp.id')
                            ->join('package','csp.package_id', "=", 'package.id')
                            ->where('invoice.status', 0)
                            ->where('company_id', Session::get('company_id'))
                            ->where('is_current', 1)
                            ->orderBy('created_at', 'desc')
                            ->select('invoice.*', 'package.price as amount', 'company_subscription_package_id as csp_id', 'csp.package_id', 'csp.expire_date', 'csp.start_date', 'csp.currency_id as curr_id' )->first();
            }

            if(count($invoices) >= 1){
                $curr_id = $invoices->curr_id;
            }

            return $this->view('list-upcoming-invoice')->with('data', $invoices)->with('controller', $this)->with('status', $status)->with('exchage_rate', $this->getExchangeRate($curr_id))->with('invoice_type',$invoice_type);
                          
        }else{
            //$sta = [$status];
            $invoices = $invoice->join('company_subscription_package as csp','invoice.company_subscription_package_id','=','csp.id')
                            ->join('package','csp.package_id', "=", 'package.id')
                            ->where('invoice.status', $status)
                            ->where('company_id', Session::get('company_id'))
                            ->select('invoice.*', 'package.price as amount', 'company_subscription_package_id as csp_id', 'csp.expire_date', 'csp.start_date', 'csp.currency_id as curr_id')->get();
            return $this->view('list')->with('data', $invoices)->with('controller', $this)->with('status', $status)->with('exchage_rate', $this);
        }
    }

    public function getInvoiceCheckout($invoice_id)
    {   
        $invoice = new Invoice();
        $check_invoice = $invoice->where('id', $invoice_id)->where('status', '1')->get();
        if(count($check_invoice) >= 1){
            return Redirect::route('client.invoices.backend', 1);
        }
        $checkout = $invoice->join('company_subscription_package as csp','invoice.company_subscription_package_id','=','csp.id')
                            ->join('package', 'csp.package_id','=', 'package.id')
                            ->where('invoice.id', $invoice_id)
                            ->select('invoice.*', 'package.name as name', 'package.id as package_id')
                            ->first();
        $valid_date = strtotime(date("Y-m-d", strtotime($checkout->due_date)) . " +1 month");
        $valid_date = date('Y-m-d', $valid_date);
        
        return $this->view('checkout')->with('checkout', $checkout)->with('valid_date', $valid_date);

    }

    public function getInvoiceDoCheckout()
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

        $response = app($this->payment)->CheckoutTransaction(Input::all());

        if (!$response->getErrors()) {
            $getData = $this->updateInvoice($response, Input::get('invoice_id'));
            $this->sendConfirmEmailAfterPaymentPaid($response, $getData);
            return Redirect::route('client.invoices.backend', 1)->with('global', 'Invoice paid.');
        } else {
            if ($response->getErrors()) {
                foreach ($response->getErrors() as $error) {
                    return redirect()->back()->withInput()->withErrors(array('payment', strtolower("Error: ".\Eway\Rapid::getMessage($error))));
                }
            }
        }

    }

    private function getContryCodeByContryName($countryName) {
        $countries = array( 'AF'=>'AFGHANISTAN', 'AL'=>'ALBANIA', 'DZ'=>'ALGERIA', 'AS'=>'AMERICAN SAMOA', 'AD'=>'ANDORRA', 'AO'=>'ANGOLA', 'AI'=>'ANGUILLA', 'AQ'=>'ANTARCTICA', 'AG'=>'ANTIGUA AND BARBUDA', 'AR'=>'ARGENTINA', 'AM'=>'ARMENIA', 'AW'=>'ARUBA', 'AU'=>'AUSTRALIA', 'AT'=>'AUSTRIA', 'AZ'=>'AZERBAIJAN', 'BS'=>'BAHAMAS', 'BH'=>'BAHRAIN', 'BD'=>'BANGLADESH', 'BB'=>'BARBADOS', 'BY'=>'BELARUS', 'BE'=>'BELGIUM', 'BZ'=>'BELIZE', 'BJ'=>'BENIN', 'BM'=>'BERMUDA', 'BT'=>'BHUTAN', 'BO'=>'BOLIVIA', 'BA'=>'BOSNIA AND HERZEGOVINA', 'BW'=>'BOTSWANA', 'BV'=>'BOUVET ISLAND', 'BR'=>'BRAZIL', 'IO'=>'BRITISH INDIAN OCEAN TERRITORY', 'BN'=>'BRUNEI DARUSSALAM', 'BG'=>'BULGARIA', 'BF'=>'BURKINA FASO', 'BI'=>'BURUNDI', 'KH'=>'CAMBODIA', 'CM'=>'CAMEROON', 'CA'=>'CANADA', 'CV'=>'CAPE VERDE', 'KY'=>'CAYMAN ISLANDS', 'CF'=>'CENTRAL AFRICAN REPUBLIC', 'TD'=>'CHAD', 'CL'=>'CHILE', 'CN'=>'CHINA', 'CX'=>'CHRISTMAS ISLAND', 'CC'=>'COCOS (KEELING) ISLANDS', 'CO'=>'COLOMBIA', 'KM'=>'COMOROS', 'CG'=>'CONGO', 'CD'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'CK'=>'COOK ISLANDS', 'CR'=>'COSTA RICA', 'CI'=>'COTE D IVOIRE', 'HR'=>'CROATIA', 'CU'=>'CUBA', 'CY'=>'CYPRUS', 'CZ'=>'CZECH REPUBLIC', 'DK'=>'DENMARK', 'DJ'=>'DJIBOUTI', 'DM'=>'DOMINICA', 'DO'=>'DOMINICAN REPUBLIC', 'TP'=>'EAST TIMOR', 'EC'=>'ECUADOR', 'EG'=>'EGYPT', 'SV'=>'EL SALVADOR', 'GQ'=>'EQUATORIAL GUINEA', 'ER'=>'ERITREA', 'EE'=>'ESTONIA', 'ET'=>'ETHIOPIA', 'FK'=>'FALKLAND ISLANDS (MALVINAS)', 'FO'=>'FAROE ISLANDS', 'FJ'=>'FIJI', 'FI'=>'FINLAND', 'FR'=>'FRANCE', 'GF'=>'FRENCH GUIANA', 'PF'=>'FRENCH POLYNESIA', 'TF'=>'FRENCH SOUTHERN TERRITORIES', 'GA'=>'GABON', 'GM'=>'GAMBIA', 'GE'=>'GEORGIA', 'DE'=>'GERMANY', 'GH'=>'GHANA', 'GI'=>'GIBRALTAR', 'GR'=>'GREECE', 'GL'=>'GREENLAND', 'GD'=>'GRENADA', 'GP'=>'GUADELOUPE', 'GU'=>'GUAM', 'GT'=>'GUATEMALA', 'GN'=>'GUINEA', 'GW'=>'GUINEA-BISSAU', 'GY'=>'GUYANA', 'HT'=>'HAITI', 'HM'=>'HEARD ISLAND AND MCDONALD ISLANDS', 'VA'=>'HOLY SEE (VATICAN CITY STATE)', 'HN'=>'HONDURAS', 'HK'=>'HONG KONG', 'HU'=>'HUNGARY', 'IS'=>'ICELAND', 'IN'=>'INDIA', 'ID'=>'INDONESIA', 'IR'=>'IRAN, ISLAMIC REPUBLIC OF', 'IQ'=>'IRAQ', 'IE'=>'IRELAND', 'IL'=>'ISRAEL', 'IT'=>'ITALY', 'JM'=>'JAMAICA', 'JP'=>'JAPAN', 'JO'=>'JORDAN', 'KZ'=>'KAZAKSTAN', 'KE'=>'KENYA', 'KI'=>'KIRIBATI', 'KP'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF', 'KR'=>'KOREA REPUBLIC OF', 'KW'=>'KUWAIT', 'KG'=>'KYRGYZSTAN', 'LA'=>'LAO PEOPLES DEMOCRATIC REPUBLIC', 'LV'=>'LATVIA', 'LB'=>'LEBANON', 'LS'=>'LESOTHO', 'LR'=>'LIBERIA', 'LY'=>'LIBYAN ARAB JAMAHIRIYA', 'LI'=>'LIECHTENSTEIN', 'LT'=>'LITHUANIA', 'LU'=>'LUXEMBOURG', 'MO'=>'MACAU', 'MK'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'MG'=>'MADAGASCAR', 'MW'=>'MALAWI', 'MY'=>'MALAYSIA', 'MV'=>'MALDIVES', 'ML'=>'MALI', 'MT'=>'MALTA', 'MH'=>'MARSHALL ISLANDS', 'MQ'=>'MARTINIQUE', 'MR'=>'MAURITANIA', 'MU'=>'MAURITIUS', 'YT'=>'MAYOTTE', 'MX'=>'MEXICO', 'FM'=>'MICRONESIA, FEDERATED STATES OF', 'MD'=>'MOLDOVA, REPUBLIC OF', 'MC'=>'MONACO', 'MN'=>'MONGOLIA', 'MS'=>'MONTSERRAT', 'MA'=>'MOROCCO', 'MZ'=>'MOZAMBIQUE', 'MM'=>'MYANMAR', 'NA'=>'NAMIBIA', 'NR'=>'NAURU', 'NP'=>'NEPAL', 'NL'=>'NETHERLANDS', 'AN'=>'NETHERLANDS ANTILLES', 'NC'=>'NEW CALEDONIA', 'NZ'=>'NEW ZEALAND', 'NI'=>'NICARAGUA', 'NE'=>'NIGER', 'NG'=>'NIGERIA', 'NU'=>'NIUE', 'NF'=>'NORFOLK ISLAND', 'MP'=>'NORTHERN MARIANA ISLANDS', 'NO'=>'NORWAY', 'OM'=>'OMAN', 'PK'=>'PAKISTAN', 'PW'=>'PALAU', 'PS'=>'PALESTINIAN TERRITORY, OCCUPIED', 'PA'=>'PANAMA', 'PG'=>'PAPUA NEW GUINEA', 'PY'=>'PARAGUAY', 'PE'=>'PERU', 'PH'=>'PHILIPPINES', 'PN'=>'PITCAIRN', 'PL'=>'POLAND', 'PT'=>'PORTUGAL', 'PR'=>'PUERTO RICO', 'QA'=>'QATAR', 'RE'=>'REUNION', 'RO'=>'ROMANIA', 'RU'=>'RUSSIAN FEDERATION', 'RW'=>'RWANDA', 'SH'=>'SAINT HELENA', 'KN'=>'SAINT KITTS AND NEVIS', 'LC'=>'SAINT LUCIA', 'PM'=>'SAINT PIERRE AND MIQUELON', 'VC'=>'SAINT VINCENT AND THE GRENADINES', 'WS'=>'SAMOA', 'SM'=>'SAN MARINO', 'ST'=>'SAO TOME AND PRINCIPE', 'SA'=>'SAUDI ARABIA', 'SN'=>'SENEGAL', 'SC'=>'SEYCHELLES', 'SL'=>'SIERRA LEONE', 'SG'=>'SINGAPORE', 'SK'=>'SLOVAKIA', 'SI'=>'SLOVENIA', 'SB'=>'SOLOMON ISLANDS', 'SO'=>'SOMALIA', 'ZA'=>'SOUTH AFRICA', 'GS'=>'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'ES'=>'SPAIN', 'LK'=>'SRI LANKA', 'SD'=>'SUDAN', 'SR'=>'SURINAME', 'SJ'=>'SVALBARD AND JAN MAYEN', 'SZ'=>'SWAZILAND', 'SE'=>'SWEDEN', 'CH'=>'SWITZERLAND', 'SY'=>'SYRIAN ARAB REPUBLIC', 'TW'=>'TAIWAN, PROVINCE OF CHINA', 'TJ'=>'TAJIKISTAN', 'TZ'=>'TANZANIA, UNITED REPUBLIC OF', 'TH'=>'THAILAND', 'TG'=>'TOGO', 'TK'=>'TOKELAU', 'TO'=>'TONGA', 'TT'=>'TRINIDAD AND TOBAGO', 'TN'=>'TUNISIA', 'TR'=>'TURKEY', 'TM'=>'TURKMENISTAN', 'TC'=>'TURKS AND CAICOS ISLANDS', 'TV'=>'TUVALU', 'UG'=>'UGANDA', 'UA'=>'UKRAINE', 'AE'=>'UNITED ARAB EMIRATES', 'GB'=>'UNITED KINGDOM', 'US'=>'UNITED STATES', 'UM'=>'UNITED STATES MINOR OUTLYING ISLANDS', 'UY'=>'URUGUAY', 'UZ'=>'UZBEKISTAN', 'VU'=>'VANUATU', 'VE'=>'VENEZUELA', 'VN'=>'VIET NAM', 'VG'=>'VIRGIN ISLANDS, BRITISH', 'VI'=>'VIRGIN ISLANDS, U.S.', 'WF'=>'WALLIS AND FUTUNA', 'EH'=>'WESTERN SAHARA', 'YE'=>'YEMEN', 'YU'=>'YUGOSLAVIA', 'ZM'=>'ZAMBIA', 'ZW'=>'ZIMBABWE');
        return array_search(strtoupper($countryName), $countries);        
    }


    public function updateInvoice($response, $invoice_id)
    {
        $invoice = Invoice::find($invoice_id);
        //$invoice->invoice_number = Carbon::now()->format('Y-m-d').'-'.$invoice->id;
        //$invoice->due_date = Carbon::now()->addWeeks(1);
        $invoice->updated_at = Carbon::now();
        $invoice->paid_date = Carbon::now();
        $invoice->transaction_id = $response->TransactionID;
        if ($response->ResponseCode == '00') {
            $invoice->response_code = $response->ResponseCode;
            $invoice->status = 1;
        }else{
            $invoice->status = 0;
        }
        $invoice->update();



        $arr['invoice'] = Invoice::findOrFail($invoice_id);
        $arr['csp']     = CompanySubscription::find($arr['invoice']->company_subscription_package_id);
        $arr['company'] = Company::find($arr['csp']->company_id);
        $arr['user']    = User::find($arr['company']['admin_id']);
        $arr['billing'] = Billing::where('company_id', $arr['csp']->company_id)->first();
        $package        = Package::find($arr['csp']->package_id);
        $arr['package'] = [
                            'package_name' => $package->name,
                            'package_price'=> '$'. number_format(round($invoice->amount), 2),
                            'package_period_title' => '1 Month'
                            ];

        $data['file_name'] = "INVOICE_".$invoice->invoice_number."_". date('d-m-Y', strtotime(Carbon::now())) .".pdf";
        $data['data']   = $arr;
        $data['invoice_number'] = $invoice->invoice_number;
        $this->saveInvoicePdf($data);

        return $data;
    }

    private function sendConfirmEmailAfterPaymentPaid($transaction_infor, $data) {
        $email = array(
                    'email' => $transaction_infor->Customer->Email,
                    'pdf_file' => public_path().'/invoice/'.$data['file_name'],
                    'invoice_number' => $data['invoice_number']
                );
        $email['from_email'] = $this->getFromFinfoEmail();

        //$currency_infor = Currency::find(Session::get('data')['package_currency_type']);
        //$data['data']['currencyCode']  = $currency_infor->code;
        $data['data']['pdf_link']  = $email['pdf_file'];
        $data['data']['currencyCode']  = 'USA';
        $data['data']['valid_until']  = Carbon::now()->addMonths(1)->format('m/d/Y');
        $tax = number_format(round(($data['data']['invoice']['amount'] * 7 / 100)) , 2);
        $data['data']['tax']  = "$ ".$tax."  USA";
        Mail::queue('app.Components.Client.Modules.Package.views.email.payment_notify', array('userData' => $data['data']), function ($message) use($email) {
            $message->subject("PAID - Customer Invoice #".$email['invoice_number']." - Payment Confirmation");
            $message->from($email['from_email'], 'FINFO Solutions');
            $message->to($email['email']);
            $message->attach($email['pdf_file']);
        });

        return true;
    }

    public function getInvoiceDetail($id)
    {
        $data['invoice']    = Invoice::findOrFail($id);
        $data['csp']        = CompanySubscription::findOrFail($data['invoice']->company_subscription_package_id);
        $data['company']    = Company::findOrFail($data['csp']->company_id);
        $data['billing']    = Billing::where('company_id', $data['csp']->company_id)->first();
        $data['user']       = User::findOrFail($data['company']->admin_id);
        $package            = Package::findOrFail($data['csp']->package_id);
        $data['package']    = array(
                                    'package_name' => $package->name,
                                    'package_price'=> $package->price,
                                    'package_period_title' => '1 Month'
                                );

        $data['url_back']   = route('client.invoices.backend', $data['invoice']->status);
        $data['dl_route_name'] = 'client.invoices.backend.download';

        return $this->showViewInvoice($data);
    }

    public function getInvoiceDownload($id)
    {
        $arr['invoice']    = Invoice::findOrFail($id);
        $arr['csp']        = CompanySubscription::findOrFail($arr['invoice']->company_subscription_package_id);
        $arr['company']    = Company::findOrFail($arr['csp']->company_id);
        $arr['billing']    = Billing::where('company_id', $arr['csp']->company_id)->first();
        $arr['user']       = User::findOrFail($arr['company']->admin_id);
        $package            = Package::findOrFail($arr['csp']->package_id);
        $arr['package']    = array(
                                    'package_name' => $package->name,
                                    'package_price'=> '$'. number_format(round($package->price), 2) ,
                                    'package_period_title' => '1 Month'
                                );
        $data['data'] = $arr;

        return $this->getDLInvoice($data);
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
