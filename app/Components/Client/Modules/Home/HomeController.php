<?php

namespace App\Components\Client\Modules\Home;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Components\Client\Modules\Company\Company;
use Illuminate\Http\Request;
use App\Components\Package\Modules\Announcements\Announcement;
use App\Components\Package\Modules\InvestorRelationsCalendar\InvestorRelationsCalendar;
use DateTime;
use DateTimeZone;
use Hash;
use Mail;
use PDF;
use Session;
use Illuminate\Support\Facades\DB;
use App\Components\Client\Modules\Webpage\Webpage as Contents;
use Auth;
use App\Components\Package\Modules\PressReleases\PressReleases as PressReleases;

class HomeController extends Controller
{
	public function getHome()
	{
               //$company = DB::table('company')->get();

              $menu = DB::table('menu_permissions')->where('company_id',Session::get('company_id'))->get();
                if($this->checkCompanyIsExpired() == true) {
                    return $this->view('home_expired');
                }else{
                    $settings 		= Setting::all();
                    $announcement 	= $this->getAnnouncement();
                    $events			= $this->getEvent();
                    $stock 			= $this->getStock();
                    $stock_setting  = app('App\Components\Package\Modules\RealTimeStockPricingAndCharts\StockBackendController')->checkStockRecord();
                  
                  
                  
                $menuPermissions = DB::table('menu_permissions')
                        ->join('module', 'module.id', '=', 'menu_permissions.menus_id')
                        ->select('module.*')
                        ->where('menu_permissions.company_id',Session::get('company_id'))
                       ->orderBy('ordering', 'asc')
                        ->get();
                        
                        
               $menuLink = DB::table('menu_permissions')
                        ->join('module', 'module.id', '=', 'menu_permissions.menus_id')
                        ->select('module.*')
                        ->where('menu_permissions.company_id',Session::get('company_id'))
                        ->whereIn('menu_permissions.menus_id', [1, 2, 3, 9])
                        ->count();        
               
                  
                 /* Company Info*/
                
                    $contents = Contents::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.title', 'content.content_description', 'content.created_at', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                        ->where('company.id','=', Session::get('company_id'))
                        ->where('content.is_delete', '=', 0 )
                        ->where('content.is_active', '=', 1)
                        ->where('content.ordering', '=', 0)
                        ->orderBy('content.ordering', 'asc')
                        ->first();
                        
                        
                    $contentsTitle = Contents::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.name','content.title', 'content.content_description', 'content.created_at', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                        ->where('company.id','=', Session::get('company_id'))
                        ->where('content.is_delete', '=', 0 )
                        ->where('content.is_active', '=', 1)
                        ->where('content.ordering', '!=', 0)
                        ->orderBy('content.ordering', 'asc')
                        ->first();    
                        
                        
                         
                    $title = Contents::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.name','content.title', 'content.content_description', 'content.created_at', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                        ->where('company.id','=', Session::get('company_id'))
                        ->where('content.is_delete', '=', 0 )
                        ->where('content.is_active', '=', 1)
                        ->where('content.ordering', '!=', 0)
                        ->orderBy('content.ordering', 'asc')
                        ->get();    
                      
                     
                        
        /* PressReleases */
                
                 $query  = PressReleases::where('company_id', Session::get('company_id'));
                        if(Auth::user())
                      {
                         $query->where('is_active', '!=',0);
                      }
                  else
                     {
                         $query->where('is_active', 1);
                     }
                  $query->orderBy('press_date', Input::get('sort'));
                  $press_release = $query->paginate(3); 
                  
         /* PressReleases */
         
         
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
        
        $lastupdate = '';
        $high = '';
        $total = '';
        
      }else{
        $result ='';
        $resultid =''; 
        $lastupdate = '';
        $high = '';
        $total = '';
      }  
          /* Stock API */
         
         
         
         
         
         $sliders = DB::table('slider_images')->where('company_id',Session::get('company_id'))->orderBy('slide_order', 'asc')->get();
         
                  
                      return $this->view('home')->with(compact('settings'))->with(compact('title'))->with(compact('menu'))->with(compact('announcement'))->with(compact('events'))->with('stock', $stock)->with('stock_setting', $stock_setting)->with(compact('press_release'))->with('dataContents', compact('contents'))->with('dataContentsTitle', compact('contentsTitle'))->with('sliders',$sliders)->with('menuPermissions',$menuPermissions)->with('menuLink',$menuLink)->with('result',$result)->with('resultid',$resultid);
                }
	}



public function Email()
	{
	    
	   
	    $id   = Session::get('company_id');
	     if(isset($_POST["formData"]))
	    {
	        
	        
	        $is_exit = DB::table('email_alert')->where('email_address',$_POST['formData'])->where('company_id',$id)->first();
	        
	        if(count($is_exit) == 1){
	            
	            echo "0";
	            
	        }else{
	            
	            
	       $insert = array
			(
			   	'company_id' => $id,
			    'email_address' => $_POST["formData"],
				'is_active' => '1',
				'created_at' => date("Y-m-d H:i:s"),
				
			); 
			
	    	$result = 	DB::table('email_alert')->insertGetId($insert);
	    	
	    	
	    	for($i=1;$i<=10;$i++){
	    	    
	    	    
	    	    $datas = array('email_alert_id' => $result, 'email_alert_category_id' => $i, 'created_at' =>date("Y-m-d H:i:s"));
	    	    $email_alerts = DB::table('email_alert_items')->insert($datas);
	    	    
	    	    
	    	}
	    
	         echo "1";
	            
	        }
	        
    	  
        }
        
	}

	



	public function getAnnouncement()
	{
		$announcement = Announcement::where('company_id', Session::get('company_id'))
									->where('status', 0)
									->where('is_deleted', 0)
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(3);

                                    
       	return $announcement;
	}

	public function getEvent()
	{
		$event = InvestorRelationsCalendar::where('company_id', Session::get('company_id'))
									->where('is_active', 1)
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(3);
        return $event;
	}

	public function getStock()
	{
		$stock = app('App\Components\Package\Modules\RealTimeStockPricingAndCharts\StockBackendController')->APIConnectDataStockPriceAPIRequest();
		return $stock;
	}
  
	public function convertTimewithTimezone()
	{
		$time 	= Input::get('time');
		$tz 	= new DateTimeZone(Input::get('tz'));
		$schedule_date = new DateTime($time);
		$schedule_date->setTimeZone(new DateTimeZone(Input::get('tz')));
		$time =  $schedule_date->format('Y-m-d h:i:s a');
		//$time->setTimeZone($tz);
		$data = [
					'default' => Input::get('time'),
					'timezone'=> $tz,
					'time'=> $time,

				];
		return $data;
	}
        
    public function checkCompanyIsExpired(){

        $company = DB::table('company')->where('finfo_account_name','=', Session::get('account'))->first();
        if(count($company) >= 1){
        	Session::put('company_id', $company->id);
        }

        $getCompanyDataTrail = Company::join('company_subscription_package as csp', 'company.id', '=','csp.company_id')
                ->select('csp.expire_date', 'company.company_name', 'company.email_address')
                ->where('csp.is_active', 1)
                ->where('company.is_delete', 0)
                ->where('csp.is_trail', 1)
                ->where('company.finfo_account_name', Session::get('account'))
                ->first();

        if (isset($getCompanyDataTrail->expire_date) && !empty($getCompanyDataTrail->expire_date)) {
            if (strtotime(date('Y-m-d h:i:s')) < strtotime($getCompanyDataTrail->expire_date)) {
                return false;
            }
        }

        $getCompanyData = Company::join('company_subscription_package as csp', 'company.id', '=','csp.company_id')
                ->join('invoice', 'invoice.company_subscription_package_id', '=', 'csp.id')
                ->select('csp.expire_date', 'company.company_name', 'company.email_address')
                ->where('csp.is_active', 1)
                ->where('csp.is_current', 1)
                ->where('company.is_delete', 0)
                ->where('invoice.status', '=', 1)
                ->where('company.finfo_account_name', Session::get('account'))
                ->first();
        if (isset($getCompanyData->expire_date) && !empty($getCompanyData->expire_date)) {
            if (strtotime(date('Y-m-d h:i:s')) > strtotime($getCompanyData->expire_date)) {
                return true;
            }
            return false;
        }
        return true;
    }
}