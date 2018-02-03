<?php

use App\Components\Finfo\Modules\Clients\Company;



$component_path = app_path() . DIRECTORY_SEPARATOR . "Components";
$modules = $component_path . DIRECTORY_SEPARATOR . "Client/Modules";


if (\File::isDirectory($modules)){

    $userDomainName = explode('.', Request::server('HTTP_HOST'));

    $url = 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "" );

		
    $domain = "";
    $pieces = parse_url($url);
		
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        $domain = $regs['domain'];
    }

	
	//end
    if (count($userDomainName) === 3 ) {
		
        $checkExist = Company::where('finfo_account_name','=',$userDomainName[0])
                                ->where('approved_by','!=','0')
                                ->where('is_active','=',1)
                                ->first();
								
				

        if(!$checkExist){
			App::abort(404);
        }

       
        $companyData = Company::join('company_subscription_package as csp','csp.company_id','=','company.id')
                ->select('csp.package_id','company.id','company.company_logo', 'company.favicon', 'company_name')
                ->where('company.finfo_account_name','=', $userDomainName[0])
                ->where('csp.is_current', '=', 1)
                ->first();
       
        Session::put('account_data', $checkExist);
        Session::put('set_package_id', $companyData->package_id);
        
        //put session test
        Session::put('account', $userDomainName[0]);
        

        //Route::group(['domain' => '{account}.'.$domain], function () use ($modules,$userDomainName) {
        $check_do = explode('.', $domain);
		
	
        if (count($check_do) === 3 ) {
            $real_domain = $domain;
		}else{
            $real_domain = Session::get('account').'.'.$domain;
		}
    

        //Route::group(['domain' => Session::get('account').'.'.$domain], function () use ($modules,$userDomainName) {
        Route::group(['domain' => $real_domain], function () use ($modules,$userDomainName) {
            \Session::put('account', $userDomainName[0]);
            $list = \File::directories($modules);
            foreach($list as $module){
                if (\File::isDirectory($module)){
                    if(\File::isFile($module. DIRECTORY_SEPARATOR . "routes.php")){
                        require_once($module. DIRECTORY_SEPARATOR. "routes.php");
                    }
                }
            }
        });
    }
}
