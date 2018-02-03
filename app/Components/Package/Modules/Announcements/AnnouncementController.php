<?php

namespace App\Components\Package\Modules\Announcements;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Hash;
use Auth;
use Mail;
use Session;
use App\Components\Client\Modules\Home\HomeController;
use App\Components\Client\Modules\Webpage\Webpage as Contents;

class AnnouncementController extends Controller
{
    protected $companyExpire = false;
    
    public function __construct(){
        $homeController = new HomeController();
        if ($homeController->checkCompanyIsExpired() == true) {
            $this->companyExpire = true;
        }   
    }
    public function getFrontend()
    {
        if ($this->companyExpire == true) {
            return redirect::to('/');
        }
     
     $year = date("Y");
    

     $query= Announcement::where('company_id', Session::get('company_id'));
    	                        if(!Auth::user())
                                {
                            $query->where('status', 0);
                                } 
                                $query->where(DB::raw('YEAR(announce_at)'), $year);
    							$query->where('is_deleted', 0);
    							$query->orderBy('announce_at', 'DESC');

    $Announcement = $query->paginate(5);


   if ($Announcement->isEmpty()) { 
       
        $Current =   DB::table("announcements")
                	    ->where('company_id', Session::get('company_id'))
                	    ->where('is_deleted', 0)
                        ->orderBy(DB::raw("YEAR(announce_at)"), 'desc')
                        ->groupBy(DB::raw("YEAR(announce_at)"))
                        ->first();
       
        $now = '';
        if(!empty($Current)){
            $now = $Current->announce_at;
        }else{
            $now = '';
        }
                        
       //$now = $Current->announce_at;
       $date = strtotime($now);
       $year = date('Y', $date); 
       
       
       $query= Announcement::where('company_id', Session::get('company_id'));
    	                        if(!Auth::user())
                                {
                            $query->where('status', 0);
                                } 
                                $query->where(DB::raw('YEAR(announce_at)'), $year);
    							$query->where('is_deleted', 0);
    							$query->orderBy('announce_at', 'DESC');

      $Announcement = $query->paginate(5);
    } 
    
    
    $Announc =   DB::table("announcements")
                	    ->where('company_id', Session::get('company_id'))
                	    ->where('is_deleted', 0)
                        ->orderBy(DB::raw("YEAR(announce_at)"), 'desc')
                        ->groupBy(DB::raw("YEAR(announce_at)"))
                        ->get();
                        
                        
                        
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

       return $this->view('frontend.announcements')->with('data', $Announcement)
                ->with('announc', $Announc)
                ->with('year', $this->getYear())
                ->with('month', $this->getNumberOfMonth())
                ->with('filter', true)
                ->with('selected_month', null)
                ->with('selected_year',  null)->with('title',$title)->with('menuPermissions',$menuPermissions)->with('result',$result)->with('resultid',$resultid);
    }
    public function getTabDataFrontend() {
        $announcement = Announcement::where('company_id', Session::get('company_id'))->where('status', 0)
    								->where('is_deleted', 0)
    								->orderBy('announce_at', Input::get('sort'))
                                    ->paginate(10);
                                    
        return $announcement;
    }
    public function getLatestQuarterFrontend($year = null, $quarter = null) {
        if (empty($year) && empty($quarter)) {
            $announcement = Announcement::where('company_id', Session::get('company_id'))->where('status', 0)
    								->where('is_deleted', 0)
    								->orderby('quarter', 'desc')
                                                                ->orderby('year', 'desc')
                                                                ->limit(1)
                                                                ->get();
        }else{
           
            $announcement = Announcement::where('company_id', Session::get('company_id'))->where('status', 0)
    								->where('is_deleted', 0)
                                                                ->where('year', $year)
                                                                ->where('quarter', $quarter)
                                                                ->orderby('announce_at', 'desc')
                                                                ->first();
        }
        return $announcement;
    }
    public function getSortByYear($sort) {
        $announcement = Announcement::where('company_id', Session::get('company_id'))->where('status', 0)
                                    ->where('is_deleted', 0)
                                    ->where(DB::raw('YEAR(announce_at)'), $sort)
                                    ->paginate(10);
        return $announcement;
    }
    public function getFrontendPageDatail($page_title){
        $getDetailPressRelease = Announcement::where('company_id', Session::get('company_id'))->where('status', 0)->where('link', $page_title)->first();
        return $this->view('frontend.slugpage')->with('slugContents', $getDetailPressRelease );
    }
    public function getFilterData(Request $request){
        
       if($_POST['year'] == 'All Year'){
        $press_release = Announcement::where('company_id', Session::get('company_id'))
                                    ->where('status', 0)
                                 //  ->where(DB::raw('YEAR(announce_at)'), '=', $request->input('year'))
                                   // ->where(DB::raw('MONTH(announce_at)'), '=', $request->input('month'))
                                    ->where('is_deleted', 0)
                                    ->orderBy('announce_at', 'desc')
                                    ->paginate(100);
                                    
       }else{
         $press_release = Announcement::where('company_id', Session::get('company_id'))
                                    ->where('status', 0)
                                    ->where(DB::raw('YEAR(announce_at)'), '=', $request->input('year'))
                                   // ->where(DB::raw('MONTH(announce_at)'), '=', $request->input('month'))
                                    ->where('is_deleted', 0)
                                    ->orderBy('announce_at', 'desc')
                                    ->paginate(100);  
           
           
       }                            
         
     $menuPermissions = DB::table('menu_permissions')
                        ->join('module', 'module.id', '=', 'menu_permissions.menus_id')
                        ->select('module.*')
                        ->where('menu_permissions.company_id',Session::get('company_id'))
                        ->get();     
         
    $title = Contents::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.name','content.title', 'content.content_description', 'content.created_at', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                        ->where('company.id','=', Session::get('company_id'))
                        ->where('content.is_delete', '=', 0 )
                        ->where('content.is_active', '=', 1)
                        ->where('content.ordering', '!=', 0)
                        ->orderBy('content.ordering', 'asc')
                        ->get();   
                        
                        
    $Announc =   DB::table("announcements")
                	    ->where('company_id', Session::get('company_id'))
                	    ->where('is_deleted', 0)
                        ->orderBy(DB::raw("YEAR(announce_at)"), 'desc')
                        ->groupBy(DB::raw("YEAR(announce_at)"))
                        ->get();
                                
        return $this->view('frontend.announcements')
                ->with('data', $press_release)
                ->with('title',$title)
                ->with('menuPermissions',$menuPermissions)
                ->with('announc', $Announc)
                ->with('year', $this->getYear())
                ->with('month', $this->getNumberOfMonth())
                ->with('filter', true)
                ->with('selected_month', $request->input('month'))
                ->with('selected_year',  $request->input('year'));
    }
    
    
    
    
    /*session data frontent*/
    
    
    
    
    public function previewannouncements()
    
    {
        
        $name = Session::get('formdata');
        
           $title = Contents::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.name','content.title', 'content.content_description', 'content.created_at', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                        ->where('company.id','=', Session::get('company_id'))
                        ->where('content.is_delete', '=', 0 )
                        ->where('content.is_active', '=', 1)
                        ->orderBy('content.ordering', 'asc')
                        ->get();    
        
		return $this->view('frontend.data')->with('data',$name)->with('title',$title);
        
    
       
    }
    
    
    
    
    
    
    
    
}