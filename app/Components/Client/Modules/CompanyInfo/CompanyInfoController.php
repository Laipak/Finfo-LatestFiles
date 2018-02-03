<?php

namespace App\Components\Client\Modules\CompanyInfo;

use App\Http\Controllers\Controller;
use App\Components\Client\Modules\Webpage\Webpage as Contents;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use\Auth;
use Hash;
use Mail;
use Illuminate\Support\Facades\DB;
use App\Components\Finfo\Modules\Clients\Company;
use App\Components\Client\Modules\Home\HomeController;
use Session;


class CompanyInfoController extends Controller
{   
    protected $companyExpire = false;
    
    public function __construct() {
        $homeController = new HomeController();
        if ($homeController->checkCompanyIsExpired() == true) {
            $this->companyExpire = true;
        }   
    }
    public function getCompanyInfo()
    {  
        if ($this->companyExpire == true) {
            return redirect::to('/');
        }
        
         $menuPermissions = DB::table('menu_permissions')
                        ->join('module', 'module.id', '=', 'menu_permissions.menus_id')
                        ->select('module.*')
                        ->where('menu_permissions.company_id',Session::get('company_id'))
                        ->orderBy('ordering', 'asc')
                        ->get();
        
        $contents = Contents::join('company', 'content.company_id','=', 'company.id')
                ->select('content.title', 'content.content_description', 'content.created_at', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                ->where('company.id','=', $this->getCompanyId())
                ->where('content.is_delete', '=', 0 )
                ->where('content.is_active', '=', 1)
                ->where('content.name', '=', 'company-info')
                ->orderBy('content.ordering', 'asc')
                ->first();
                
          $title = Contents::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.name','content.title', 'content.content_description', 'content.created_at', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                        ->where('company.id','=', Session::get('company_id'))
                        ->where('content.is_delete', '=', 0 )
                        ->where('content.is_active', '=', 1)
                        ->orderBy('content.ordering', 'asc')
                        ->get();    
                
        return $this->view('company_info')->with('dataContents', compact('contents'))->with('title',$title)->with('menuPermissions',$menuPermissions);        
    }
    private function getCompanyId(){
        $request = new Request();
        $currentUrl = explode('.', $_SERVER['HTTP_HOST']);
        $companyInfo = Company::where('finfo_account_name', '=',  $currentUrl[0])->get()->first();
        return $companyInfo->id;
    }

    public function getSlugContents($subpage) 
    {
        if ($this->companyExpire == true) {
            return redirect::to('/');
        }
       $query = Contents::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.name', 'content.title', 'content.content_description', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                        ->where('company.id','=', $this->getCompanyId())
                        ->where('content.is_delete', '=', 0 );
                       if(Auth::user())
                         {
                       $query->orderBy('content.id', 'desc');
                        }
                        else
                        {
                            
                            $query->where('content.is_active', '=', 1);
                          
                            
                            
                        }
                        $query->where('content.name', '=', $subpage);
                        $query->orderBy('ordering', 'asc');
                         $contents = $query->get()->first();
        
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
                        
        return $this->view('slugpage')->with(compact('title'))->with(compact('menuPermissions'))->with('slugContents', compact('contents'))->with('result',$result)->with('resultid',$resultid);
    }
    
    
    
        
    public function previewannouncements()
    
    {
        $name = Session::get('formdata');
        
   

		  return $this->view('data')->with('data',$name);
        
    
       
    }
    
    
    
    
}