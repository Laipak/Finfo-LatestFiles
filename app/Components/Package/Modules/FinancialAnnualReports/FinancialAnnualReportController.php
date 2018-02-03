<?php

namespace App\Components\Package\Modules\FinancialAnnualReports;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use Auth;
use Session;
use Illuminate\Support\Facades\DB;
use App\Components\Client\Modules\Home\HomeController;
use App\Components\Client\Modules\Webpage\Webpage as Contents;

class FinancialAnnualReportController extends Controller
{
    protected $companyExpire = false;
    public function __construct(){
        $homeController = new HomeController();
        if ($homeController->checkCompanyIsExpired() == true) {
            $this->companyExpire = true;
        }
    }
    //this function use to display client frontend page financial results
    public function getAnnualReport()
    { 
        if ($this->companyExpire == true) {
            return redirect::to('/');
        }
        
    	$query = AnnualReports::where('company_id', Session::get('company_id'));
    	    
    	    if(!Auth::user())
                {
                  $query->where('is_active', 1);
                } 
    	
    	
    	$query->orderBy('financial_year', 'desc');
    	
    	  $report = $query->get();
    	
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
      }else{
        $result ='';
        $resultid ='';  
      }  
          /* Stock API */
                            
                        
        return $this->view('frontend.annual-report')->with(compact('title'))->with('menuPermissions',$menuPermissions)->with('data', $report)->with('result',$result)->with('resultid',$resultid);
    }

}
