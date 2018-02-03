<?php

namespace App\Components\Package\Modules\Webcast;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use Session;

class WebcastController extends Controller
{
	public function getTabDataFrontend() {
        $webcast = Webcast::where('company_id', Session::get('company_id'))
    								->where('is_active', 1)    								
                                    ->paginate(10);
        return $webcast;
    }
    public function getLastQuarterFrontend($year = null, $quarter = null) {
        if (empty($year) && empty($quarter)) {
            $webcast = Webcast::where('company_id', Session::get('company_id'))
                        ->where('is_active', 1)  
                        ->orderby('quarter', 'desc')
                        ->orderby('year', 'desc')
                        ->limit(1)
                        ->first();
        }else{
            $webcast = Webcast::where('company_id', Session::get('company_id'))
                        ->where('is_active', 1)  
                        ->where('quarter', $quarter )
                        ->where('year', $year)
                        ->first();
        }
        
        return $webcast;
    }

}