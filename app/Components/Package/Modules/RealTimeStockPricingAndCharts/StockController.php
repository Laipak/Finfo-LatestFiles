<?php

namespace App\Components\Package\Modules\RealTimeStockPricingAndCharts;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use App\Components\Client\Modules\Home\HomeController;
use Session;
use App\Components\Client\Modules\Webpage\Webpage as Contents;


class StockController extends Controller
{
        protected $companyExpire = false;

        public function __construct(){
            $homeController = new HomeController();
            if ($homeController->checkCompanyIsExpired() == true) {
                $this->companyExpire = true;
            }   
        }
        
	public function getStockInformation()
	{
            if ($this->companyExpire == true) {
                return redirect::to('/');
            }
            $symbol = '';
		$setting = app('App\Components\Package\Modules\RealTimeStockPricingAndCharts\StockBackendController')->checkStockRecord();
		$api_url = $setting->stock_url;
		
		if($api_url != ''){
			$full_symbol = strstr($api_url, 'symbol=');
			$symbol = str_replace('symbol=', '', $full_symbol);
		}


    $stockid = $setting->stockid;
    
      
     $menuPermissions = DB::table('menu_permissions')
                        ->join('module', 'module.id', '=', 'menu_permissions.menus_id')
                        ->select('module.*')
                        ->where('menu_permissions.company_id',Session::get('company_id'))
                        ->orderBy('ordering', 'asc')
                        ->get();
						
       $title = Contents::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.name','content.title', 'content.content_description', 'content.created_at', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                        ->where('company.id','=', Session::get('company_id'))
                        ->where('content.is_delete', '=', 0 )
                        ->where('content.is_active', '=', 1)
                        ->where('content.ordering', '!=', 0)
                        ->orderBy('content.ordering', 'asc')
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
         
    
      $stockURL = DB::table("stock_pricing_and_chart")
                	    ->where('company_id', Session::get('company_id'))
                	    ->get();                    
    
      
		return $this->view('frontend.stock-information')->with('stock', $this->getStock())->with('chart_setting', $setting)->with('stockURL', $stockURL)->with('symbol', $symbol)->with('stockid',$stockid)->with('title',$title)->with('menuPermissions',$menuPermissions)->with('result',$result)->with('resultid',$resultid)->with('high',$high)->with('low',$low)->with('total',$total)->with('lastupdate',$lastupdate)->with('changes',$changes);
	}

	public function getStock()
	{
		$stock = app('App\Components\Package\Modules\RealTimeStockPricingAndCharts\StockBackendController')->APIConnectDataStockPriceAPIRequest();
		return $stock;
	}

}