<?php

namespace App\Components\Package\Modules\EmailAlerts;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Session;
use Mail;
use DB;
use App\Components\Client\Modules\Home\HomeController;
use App\Components\Client\Modules\Webpage\Webpage as Contents;

class EmailAlertController extends Controller
{
    private $backendcotroller;
    protected $companyExpire = false;
    
    public function __construct(){
        $homeController = new HomeController();
        if ($homeController->checkCompanyIsExpired() == true) {
            $this->companyExpire = true;
        }
        $this->backendcotroller = 'App\Components\Package\Modules\EmailAlerts\EmailAlertBackendController';
    }

    public function getFrontend()
    {
        if ($this->companyExpire == true) {
            return redirect::to('/');
        }
        $category = EmailAlertsCategory::get();
        return $this->view('frontend.email-alert')->with('category', $category)->with('controller', $this);
    }
    public function getNavigationByRouteName($adminRouteName, $catName){
        $navigation = null;
        switch ($adminRouteName){
            case 'package.admin.financial-annual-reports' :
                $navigation = 'Annual Reports';
                break;
            case 'package.admin.latest-financial-highlights' :
                 $navigation  = 'Financials';
                break;
            case 'package.admin.press-releases' :
                 $navigation = 'Press Releases';
                break;
            case 'package.admin.stock' :
                 $navigation  = 'Stock Info';
                break;
            case 'package.admin.announcements' :
                 $navigation = 'Announcements';
                break;
            case 'package.admin.investor-relations-calendar' :
                 $navigation = 'Events';
                break;
            case 'package.admin.email-alerts' :
                 $navigation  = 'Email Alerts';
                break;
            case 'package.admin.media-access' :
                $navigation  = 'Media Access';
                break;
            default :
                $navigation  = null;
                break;
        }
        if (!empty($navigation)) {
            return $navigation;  
        }
        return $catName;
    }

    public function postEmilAlerts()
    {
    	$validate = Validator::make(Input::all(), [
            'name'	                => 'required',
            'email'                 => 'required|email',
            'category'              => 'required',
            'g-recaptcha-response'  => 'required',

        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
        $data = Input::all();

        $privatekey = '6Lf82hITAAAAAC_igYld6oIm8guG0FkTgRhm6a-n';
        $captcha = $data['g-recaptcha-response'];
        $response= json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$privatekey."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
     
        if($response['success'] == false){
        	return redirect()->back()->with('error_recaptcha', 'Have something wrong with captcha')->withInput();
        }else{
        	$check_email = EmailAlerts::where('company_id', Session::get('company_id'))->where('email_address', $data['email'])->first();
        	if(count($check_email) >= 1){
        		$check_email->name = $data['name'];
        		//$check_email->categories = implode(",",$data['category']);
        		$check_email->update();

                app($this->backendcotroller)->deleteEmailAlertItem($check_email->id);
                app($this->backendcotroller)->storeEmailAlertItem($check_email->id, $data['category']);
        	}else{
        		$email_alert = new EmailAlerts();
	        	$email_alert->company_id    = Session::get('company_id');
	        	$email_alert->name          = $data['name'];
	        	$email_alert->email_address = $data['email'];
                $email_alert->is_active     = 1;
	        	//$email_alert->categories = implode(",",$data['category']);
	        	$email_alert->save();

                app($this->backendcotroller)->storeEmailAlertItem($email_alert->id, $data['category']);
        	}
        	
        	return Redirect::route('package.email-alerts')->with('global', 'Email subscribed');
        }
    }

    public function getUnsubscribe()
    {
        if ($this->companyExpire == true) {
            return redirect::to('/');
        }
        
        $category = EmailAlertsCategory::get();
         
         
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
         
        return $this->view('frontend.unsubscribe')->with('category', $category)->with('controller', $this)->with('selected_year',  null)->with('title',$title)->with('menuPermissions',$menuPermissions)->with('result',$result)->with('resultid',$resultid);
    }

    public function doUnsubscribe()
    {
        $data = Input::all();



        $check_email = EmailAlerts::where('company_id', Input::get('cmpid'))->where('email_address', Input::get('email'))->first();


        if(count($check_email) >= 1){
            $email_alert_id = $check_email->id;

            $email_item = EmailAlertsItem::where('email_alert_id', $email_alert_id)->whereIn('email_alert_category_id', Input::get('category'))->delete();

            $check_item = EmailAlertsItem::where('email_alert_id', $email_alert_id)->get();

            if(count($check_item) <= 0){
                $email_alert = EmailAlerts::findOrFail($email_alert_id);
                $email_alert->is_delete = 1;
                $email_alert->update();
                $email_alert->delete();
            }
            
            
          /*  $update = DB::table('email_alert')
                    ->where('email_address', Input::get('email'))
                    ->update(['is_active' => 0]);
                    
          */          
            $delete = DB::table('email_alert')
                    ->where('email_address', Input::get('email'))
                    ->where('company_id', Input::get('cmpid'))
                    ->delete();        

           // return Redirect::route('package.post.email-alerts')->with('global', 'Email unsubscribed');
           
           return redirect::to('/');

        }else{
            return redirect()->back()->withInput()->with('email_erorr', "We don't have any subscriber with this email.");
        }

    }
}

