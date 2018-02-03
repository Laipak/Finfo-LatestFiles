<?php

namespace App\Components\Client\Modules\Sitemap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Validator;
use Mail;
use Session;
use App\Components\Client\Modules\Package\Package_module;
use App\Components\Client\Modules\Company\Company;
use App\Components\Client\Modules\Webpage\Webpage;
use Illuminate\Support\Facades\DB;

use App\Components\Client\Modules\Webpage\Webpage as Contents;

class SitemapController extends Controller 
{
        private function getCompanyInformation(){
            $company = Company::join('company_subscription_package as csp','csp.company_id','=','company.id')
                    ->select('csp.package_id','company.id','company.company_logo', 'company.favicon', 'company_name')
                    ->where('company.finfo_account_name','=', \Session::get('account'))
                    ->where('csp.is_current', '=', 1)
                    ->first();
            return $company;
        }
        private function getPage(){
            $getPageDatas = Webpage::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.*', 'company.company_name') 
                        ->where('content.is_delete', '=', 0)
                        ->where('company.id','=', \Session::get('company_id'))
                        ->orderby('content.ordering', 'asc')
                        //->limit(3)
                        ->get();
            return $getPageDatas;
        }
        private function getModuleBaseOnCompany($company) {
            $modules = Package_module::join('module','package_module.module_id'      ,'=','module.id')
                     ->select('module.name', 'module.route_name','module.route_frontend','module.id', 'module.css_class')
                     ->where('package_module.package_id','=',$company->package_id)
                     ->where('module.id','!=','7')
                     ->orderby('module.id','asc')  
                     ->get();
            return $modules;
        }
        
	public function index()
	{
            $dataSiteMap =  array();
            $getPages = $this->getPage();
            $modules = DB::table('module')->get();
            $dataSiteMap['pages'] = $getPages;
            //$dataSiteMap['modules'] = $modules;
            // UNCOMMAN WHEN MENU UPDATE BASE ON COMPANY PACKAGE
            $company = $this->getCompanyInformation();
            $dataSiteMap['modules'] = $this->getModuleBaseOnCompany($company);
            
              $title = Contents::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.name','content.title', 'content.content_description', 'content.created_at', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                        ->where('company.id','=', Session::get('company_id'))
                        ->where('content.is_delete', '=', 0 )
                        ->where('content.is_active', '=', 1)
                        ->orderBy('content.ordering', 'asc')
                        ->get();    
                      
             $menuPermissions = DB::table('menu_permissions')
                        ->join('module', 'module.id', '=', 'menu_permissions.menus_id')
                        ->select('module.*')
                        ->where('menu_permissions.company_id',Session::get('company_id'))
                        ->orderBy('ordering', 'asc')
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
            
            return $this->view('sitemap')->with('sitemap', $dataSiteMap)->with('controller', $this)->with('title',$title)->with(compact('menuPermissions'))->with('result',$result)->with('resultid',$resultid);
	}
        public function getMenuNavigation($menuRouteName){
            //return $menuRouteName;
            $navigation = null;
            switch ($menuRouteName){
                case 'package.annual-report' :
                    $navigation = 'Annual Reports';
                    break;
                case 'package.financial-result' :
                     $navigation  = 'Financials';
                    break;
                case 'package.press-releases' :
                     $navigation = 'Press Releases';
                    break;
                case 'package.stock-information' :
                     $navigation  = 'Stock Info';
                    break;
                case 'package.announcements' :
                     $navigation = 'Announcements';
                    break;
                case 'package.investor-relations-calendar' :
                     $navigation = 'Events';
                    break;
                case 'package.email-alerts' :
                     $navigation  = 'Email Alerts';
                    break;
                case 'package.media-access' :
                    $navigation  = 'Media Access';
                    break;
            }
            return $navigation;  
        }
}
