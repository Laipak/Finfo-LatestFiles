<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use File;
use\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use App\Components\Finfo\Modules\Menus\Menu;
use App\Components\Client\Modules\Webpage\Webpage as Contents;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $account;

    public function __construct()
    {
        if (\Session::has('account'))
        {
           $this->account = \Session::get('account');
        }
    }

    public function view($path)

    {
    
        $d_path = substr(get_class($this), 0, strrpos(get_class($this), '\\'));
        $d_path = lcfirst(str_replace("\\",".",$d_path));
        $modules = array();
        $company_logo   = "";
        $favicon        = "";
        $setting = array();
        $contents = array();
        $menu = array();
        $menu_pers = array();
        $companySettings = null;
        if (\Session::has('account'))
        {
            $account = \Session::get('account');
            $company = DB::table('company')->join('company_subscription_package as csp','csp.company_id','=','company.id')->select('csp.package_id','company.id','company.company_logo', 'company.favicon', 'company_name')->where('company.finfo_account_name','=',$account)->where('csp.is_current', '=', 1)->first();

            $modules = DB::table('package_module')->join('module','package_module.module_id','=','module.id')
                                                  ->select('module.name', 'module.route_name','module.id', 'module.css_class', 'module.route_frontend', 'module.css_id')
                                                  ->where('package_module.package_id','=',$company->package_id)
                                                  ->orderby('module.ordering','asc')  
                                                  ->get();

            $setting = DB::table('setting')->join('company','company.id','=','setting.company_id')                                              
                                              ->where('setting.company_id','=',$company->id)
                                              ->first();

            $package = DB::table('package')->where('id', $company->package_id)->first();
      
       $menu = DB::table('menu_permissions')->where('company_id',$company->id)->get();
            //var_dump($menu); 
       $menu_pers = DB::table('menu_permissions')->where('company_id',$company->id)->lists('menus_id');


            $getSkipPageId = $this->getSkipCompanyInfo($company->id);
            $contents = null;
            if (!empty($getSkipPageId)) {
                $query = DB::table('content')->join('company', 'company.id','=', 'content.company_id')
                    ->select('content.name', 'content.title', 'content.ordering', 'content.content_description', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                    ->where('company.id','=', $company->id)
                    //->where('content.id','!=', $getSkipPageId)
                    ->where('content.is_delete', '=', 0 );
                    if(Auth::user())
                         {
                        
                        $query->orderBy('content.ordering', 'asc');
                        }
                        else
                        {
                            $query->where('content.is_active', '=', 1);
                             $query->orderBy('content.ordering', 'asc')->limit(6);
                        }
                   
                    $contents = $query->get();
                    
          
                    
            }
            $company_logo   = $company->company_logo;
            $favicon        = $company->favicon;
            Session::put('company_id', $company->id);
            Session::put('company_name', $company->company_name);
            Session::put('package_name', $package->title);
            Session::put('theme_color', $setting->theme_color);
            Session::put('package_id', $this->getPackageId());
            Session::put('frontend_menus',$this->getModuleNavigationName($modules));
            Session::put('company_logo', $company_logo);
            
            if (!empty($setting)) {
                Session::put('container_color', $setting->container_color);
                Session::put('background_color', $setting->background_color);
                Session::put('font_family', $setting->font_family);
                Session::put('font_color', $setting->font_color);
            }
            $companySettings = DB::table('setting')->where('company_id', Session::get('company_id'))->first();
        }


        return view($d_path . '.views.'.$path)
                    ->with('account', $this->account)
                    ->with('app_template',$this->getAppTemplate())
                    ->with('modules', $modules)
                    ->with('company_logo', $company_logo)
                    ->with('favicon', $favicon)
                    ->with('setting', $setting)
                    ->with('contents', $contents)
                    ->with('companySetting', $companySettings)
                    ->with('active_theme', $this->checkActiveClientTheme())
                    ->with('frontend_menus', $this->getModuleNavigationName($modules))
                    ->with('menu',$menu)
                    ->with('menu_pers',$menu_pers);

    }
    private function getPackageId(){
        $package = DB::table('package')->where('name', Session::get('package_name'))->first();
        return $package->id;
    }
    private function checkActiveClientTheme(){
        $getThemeData = DB::table('themes')->where('status', 1)->first();
        if (!empty($getThemeData)) {
            return $getThemeData->theme_path;
        }
        return "default";
    }
    public function getSkipCompanyInfo($companyId) {
        $contents = Contents::join('company', 'content.company_id','=', 'company.id')
                ->select('content.id') 
                ->where('company.id','=', $companyId)
                ->where('content.is_delete', '=', 0 )
                ->where('content.is_active', '=', 1)
                //->where('content.ordering', '=', 1)
                ->orderBy('content.created_at', 'desc')
                ->get()->first();
        if (!empty($contents)) {
            return $contents->id;    
        }
        return null;
    }
    public function getModuleNavigationName($menus){
        foreach($menus as $menu) {
            switch ($menu->route_name){
                case 'package.admin.financial-annual-reports' :
                    $menu->nav_frontend = 'Annual Reports';
                    break;
                case 'package.admin.latest-financial-highlights' :
                     $menu->nav_frontend  = 'Financials';
                    break;
                case 'package.admin.press-releases' :
                     $menu->nav_frontend = 'Press Releases';
                    break;
                case 'package.admin.stock' :
                     $menu->nav_frontend  = 'Stock Info';
                    break;
                case 'package.admin.announcements' :
                     $menu->nav_frontend = 'Announcements';
                    break;
                case 'package.admin.investor-relations-calendar' :
                     $menu->nav_frontend  = 'Events';
                    break;
                case 'package.admin.email-alerts' :
                     $menu->nav_frontend  = 'Email Alerts';
                    break;
                case 'package.admin.media-access' :
                    $menu->nav_frontend  = 'Media Access';
                    break;
                default :
                    $menu->nav_frontend  = null;
                    break;
            }
        }
        return $menus ;
    }
    public function getAppTemplate()
    {
        $getThemeData = DB::table('themes')->where('status', 1)->first();
        $defaultTheme = "resources.views.templates.client.template2.frontend";
        if (!empty($getThemeData)) {
            $activeTheme = trim($getThemeData->theme_path);
            $defaultTheme = "resources.views.templates.client.$activeTheme.frontend";    
        }
        return array(
                    'frontend' => "resources.views.templates.finfo.frontend",
                    'backend' => "resources.views.templates.finfo.backend",
                    'client.frontend' => $defaultTheme,
                    'client.frontend_expired' => "resources.views.templates.client.template1.frontend_expired",
                    'client.backend' => "resources.views.templates.client.template1.backend",
            );
    }
    
    public function settingJsonFilePath() {
        $saveJsonFilesPath = public_path().'/setting.json';
        return $saveJsonFilesPath;
    }
    
    public function getSettingJsonFile() {
        $getSettings = json_decode(File::get($this->settingJsonFilePath()));
		
		$data = array(
            'sub_domain_exclusive' => $getSettings->sub_domain_exclusive,
            'admin_email_receive_noti' => $getSettings->admin_email_receive_noti,
            'broadcasts_per_year' => $getSettings->broadcasts_per_year,
            'recipients_per_year' => $getSettings->recipients_per_year,
            'lunch_market' => $getSettings->market,
            'admin_from_email' => $getSettings->admin_from_email
        );
        return $data;
    }
    public function setCurrencyFormat($exchange_rate, $packages_price) {
        return number_format(round((isset($exchange_rate)) ? $packages_price * $exchange_rate : $packages_price), 2, ".", ",");
    }
    public function getMenus(){
        $getMenus = Menu::where('status', '=', 1 )->orderBy('ordering', 'asc')->get();
        return $getMenus;
    }
    public function getYear() {
        $year = array();
        for($index = 10; $index >= 0; $index--) {
            $year[date('Y') - $index] = date('Y') - $index;
        }
        krsort($year);
        return $year;
    }
    public function getNumberOfMonth(){
        $month = array();
        for($index = 1; $index < 13; $index++) {
            if ($index < 10) {
                $index = '0'.$index;
            }
            
            $month[$index] = date('F', mktime(0, 0, 0, $index, 10));
        }
        return $month;
    }
    public function getQuarter() {
        $quarter = array('1' => 'Jan - Mar', '2' => 'Apr - Jun', '3' => 'Jul - Sep', '4' => 'Oct - Dec');
        return $quarter;
    }
    public function getQuarterName() {
        $quarter = array('1' => 'Quarter 1', '2' => 'Quarter 2', '3' => 'Quarter 3', '4' => 'Quarter 4');
        return $quarter;
    }
    public function getQuarterMonthById($quarterId) {
        switch ($quarterId) {
            case 1 : return 'Jan - Mar';
            case 2 : return 'Apr - Jun';
            case 3 : return 'Jul - Sep';
            case 4 : return 'Oct - Dec';
        }
    }
    public function getQuarterNameById($quarterId) {
        switch ($quarterId) {
            case 1 : return 'Quarter 1';
            case 2 : return 'Quarter 2';
            case 3 : return 'Quarter 3';
            case 4 : return 'Quarter 4';
        }
    }
    public function getQuarterNameByName($quarterName) {
        switch ($quarterName) {
            case 'first-quarter' : return 1;
            case 'second-quarter' : return 2;
            case 'third-quarter' : return 3;
            case 'fourth-quarter' : return 4;
            default : return null;
        }
    }

    public function getStatus($id)
    {
        if($id == 1){
            return 'Publish';
        }else{
            return 'Unpublish';
        }
    }

    
    public function getTemplate() {
        $getSettings = json_decode(File::get(public_path().'/template/newsletter_template.json'));
        return $getSettings->template1;
    }
    public function getUserAccessWebsiteInfo(){
        $details = json_decode(file_get_contents("http://ipinfo.io/{$_SERVER['REMOTE_ADDR']}"));
        return $details;
    }

    public function getFromFinfoEmail(){
        $arr_json = $this->getSettingJsonFile();
        if($arr_json['admin_from_email'] == ''){
            return 'no-reply@finfo.com';
        }else{
            return $arr_json['admin_from_email'];
        }
        
    }
}
