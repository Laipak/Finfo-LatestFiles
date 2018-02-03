<?php

namespace App\Components\Client\Modules\Leaderships;

use App\Http\Controllers\Controller;
use App\Components\Client\Modules\Webpage\Webpage as Contents;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use App\Components\Finfo\Modules\Clients\Company;

class LeadershipController extends Controller
{   
	public function getLeadership()
	{
		$contents = Contents::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.title', 'content.feature_image', 'content.content_description', 'company.company_name') 
                        ->where('company.id','=', $this->getCompanyId())
                        ->where('content.is_delete', '=', 0 )
                        ->where('content.is_active', '=', 1)
                        ->get();
		return $this->view('leadership')->with(compact('contents'));
	}
        private function getCompanyId(){
            $request = new Request();
            $currentUrl = explode('.', $_SERVER['HTTP_HOST']);
            $companyInfo = Company::where('finfo_account_name', '=',  $currentUrl[0])->get()->first();
            return $companyInfo->id;
        }

}