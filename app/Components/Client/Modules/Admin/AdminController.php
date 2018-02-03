<?php

namespace App\Components\Client\Modules\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Components\Client\Modules\Invoice\Invoice;
use App\Components\Package\Modules\NewsletterAndBroadcast\Newsletter;
use App\Components\Package\Modules\NewsletterAndBroadcast\Broadcast;
use App\Components\Package\Modules\EmailAlerts\EmailAlerts;
use Session;
use DateTime;
use Hash;
use Mail;
use Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
	public function getDashboard()
	{
		$package = DB::table('package')->where('name', Session::get('package_name'))->first();
              
		$is_expire = app('App\Components\Client\Modules\Home\HomeController')->checkCompanyIsExpired();
		$broadcastc_controller = app('App\Components\Package\Modules\NewsletterAndBroadcast\NewsletterAndBroadcastBackendController');

		$setting = app('App\Components\Package\Modules\RealTimeStockPricingAndCharts\StockBackendController')->checkStockRecord();
		$stock_value = app('App\Components\Package\Modules\RealTimeStockPricingAndCharts\StockBackendController')->APIConnectDataStockPriceAPIRequest();
		$upcoming_invoice = $this->getUpcomingInvoice();
		$broadcast = $this->getLastBroadcast();
		$subscribe = $this->getSubscriber();
		$companySettings = DB::table('setting')->where('company_id', Session::get('company_id'))->first();
		
		
		
		$stockURL = DB::table("stock_pricing_and_chart")
                	    ->where('company_id', Session::get('company_id'))
                	    ->get();                    
    
		
		
		
		/* Stock API */
         $stock = DB::table('stock_pricing_and_chart')->where('company_id',Session::get('company_id'))->get();
         foreach($stock as $stock){
             
             $stk = $stock->api_url;
         }
         
    if(!empty($stk)){     
       $curl = curl_init();

         curl_setopt_array($curl, array(
          CURLOPT_URL => $stk,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: 4e36495b-68d5-7322-d88e-a79514dc5628"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        
        $Filedata = $response;
        
        $xml = simplexml_load_string($Filedata);
        
         
        $result = (string) $xml->snap->equityDomainGroup->equityDomain->tradePrice->last;
        $resultid = (string) $xml->snap->equityDomainGroup->equityDomain->instrumentIdentifier->code;
        
        
        $high = (string) $xml->snap->equityDomainGroup->equityDomain->tradePrice->high;
        $low = (string) $xml->snap->equityDomainGroup->equityDomain->tradePrice->low;
        $total = (string) $xml->snap->equityDomainGroup->equityDomain->tradeVolume->totalVolume;
       
        $lastupdate = (string) $xml->header->dataDateTime;
        $changes = (string) $xml->snap->equityDomainGroup->equityDomain->tradePrice->change;
        
      }else{
        $result ='';
        $resultid =''; 
        $lastupdate = '';
        $high = '';
        $low = '';
        $total = '';
        $lastupdate ='';
        $changes ='';
        
        
      }  
          /* Stock API */
		
		
		
		
		
		
		return $this->view('dashboard')->with('stock', $this->getStock())
										->with('setting', $setting)
										->with('companySetting', $companySettings)
										->with('stockURL', $stockURL)
										->with('upcoming_invoice', $upcoming_invoice)
										->with('broadcast', $broadcast)
										->with('subscribe', $subscribe)
										->with('broadcastc_controller', $broadcastc_controller)
										->with('is_expire', $is_expire)
										->with('controller', $this)
										->with('package_id', $package->id)
										->with('result',$result)->with('resultid',$resultid)->with('high',$high)->with('low',$low)->with('total',$total)->with('lastupdate',$lastupdate)->with('changes',$changes);
	}

	public function getStock()
	{
            //$this->checkAuthClient();
            $stock = app('App\Components\Package\Modules\RealTimeStockPricingAndCharts\StockBackendController')->APIConnectDataStockPriceAPIRequest();
            return $stock;
	}
        

	public function getUpcomingInvoice()
	{
		$invoice = new Invoice();
		$invoices = $invoice->join('company_subscription_package as csp','invoice.company_subscription_package_id','=','csp.id')
                            ->where('invoice.status', 1)
                            ->where('company_id', Session::get('company_id'))
                            ->where('is_current', 1)
                            ->orderBy('created_at', 'desc')
                            ->select('invoice.*', 'company_subscription_package_id as csp_id', 'expire_date', 'csp.currency_id as curr_id')->first();
        return $invoices;
	}

	public function getApi()
	{

		$response= file_get_contents("https://www.googleapis.com/analytics/v3/data?ids=ga:71994063");
		//var_dump($response);
		exit;
	}

	public function getLastBroadcast()
	{
		$newsletter = new Newsletter();
		$broadcast = $newsletter->join('broadcast_setting as bs', 'newsletter.id', '=', 'bs.newsletter_id')
								->where('company_id', Auth::user()->company_id)
								->where('bs.is_delete', 0)
								->orderBy('broadcast_date', 'desc')
								->orderBy('broadcast_time', 'desc')
								->get();
		return $broadcast;
	}

	public function getSubscriber()
	{
		$date = Carbon::now();
		$current_date = date("d", strtotime($date));

		$data['all_subscribe'] = EmailAlerts::where('company_id', Auth::user()->company_id)->where('is_active', 1)->count();
		$data['daily_subscribe'] = EmailAlerts::where('company_id', Auth::user()->company_id)
										->where('is_active', 1)
										->where(DB::raw('DAY(created_at)'), $current_date)
										->count();
		return $data;
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