<?php

namespace App\Components\Package\Modules\InvestorRelationsCalendar;

use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Input;
use App\Components\Client\Modules\Home\HomeController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use\Auth;
use App\Components\Client\Modules\Webpage\Webpage as Contents;

class InvestorRelationsCalendarController extends Controller
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
        if($this->companyExpire == true) {
             return redirect::to('/');
        }
        $data = '';
       // if(Input::has('view') && Input::get('view') == 'listing'){
            $query = InvestorRelationsCalendar::where('company_id', Session::get('company_id'));
                                    if(!Auth::user())
                                {
                                $query->where('is_active', 1);
                                } 
                                
                                    $query->orderBy('event_datetime', 'desc');
                                      $data = $query->paginate(10); 
     //   }
        
        
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
       
        return $this->view('frontend.investor-relations-calendar')->with(compact('title'))->with('menuPermissions',$menuPermissions)->with('event', $this->getData())->with('data', $data)->with('result',$result)->with('resultid',$resultid);
    }

    public function getData()
    {
    	$query = InvestorRelationsCalendar::where('company_id', Session::get('company_id'));
                     if(!Auth::user())
                                {
                                $query->where('is_active', 1);
                                } 
    				$query->select('id', 'event_title as title', 'event_datetime as start');
                     $events = $query->get();
    	return $events;
    }

    public function ajaxGetCalendaList()
    {
        $month = Input::get('month');
        $year = Input::get('year');
        $filter = "desc";
        if (Input::get('filter')) {
            $filter = Input::get('filter');
        }
        $data = InvestorRelationsCalendar::where('company_id', Session::get('company_id'))
                                    ->where('is_active', 1)
                                    ->where(DB::raw('MONTH(event_datetime)'), $month)
                                    ->where(DB::raw('YEAR(event_datetime)'), $year)
                                    ->orderBy('event_datetime', $filter)
                                    ->get();
        $content = '<div class="row title-row">
                        <div class="col-sm-2 col-md-2  col-xs-2 left-td-col">
                            Date
                        </div>
                        <div class="col-sm-8 col-md-8  col-xs-8 right-td-col">
                            New Event
                        </div>
                    </div>';

        if(count($data) >= 1){
            foreach($data as $even)
            {
                $content .= '<div class="row event-row">
                                <div class="col-sm-2 col-md-2 col-xs-2 left-td-col">
                                    '.date("Y-m-d", strtotime($even->event_datetime)).'
                                </div>
                                <div class="col-sm-8 col-md-8 col-xs-8 right-td-col">
                                    <a href="/'.$even->upload.'" target="_brank">'.$even->event_title.'</a>
                                </div>
                            </div>';
            }
        }
        
        return $content;
    }
}