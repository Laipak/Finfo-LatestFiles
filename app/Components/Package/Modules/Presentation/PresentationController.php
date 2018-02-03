<?php

namespace App\Components\Package\Modules\Presentation;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use Session;

class PresentationController extends Controller
{
	public function getTabDataFrontend() {
        $presentation = Presentation::where('company_id', Session::get('company_id'))->where('is_active', 1)    								
                                    ->paginate(10);
        return $presentation;
    }
    public function getLatestQuarterFrontend($year = null, $quarter = null) {
        if (empty($year) && empty($quarter)) {
            $presentation = Presentation::where('company_id', Session::get('company_id'))->where('is_active', 1)    								
                                    ->orderby('quarter', 'desc')
                                    ->orderby('year', 'desc')
                                    ->limit(1)
                                    ->get();
        }else{
            $presentation = Presentation::where('company_id', Session::get('company_id'))->where('is_active', 1)    								
                                    ->where('quarter', $quarter)
                                    ->where('year', $year )
                                    ->first();
        }
        
        
        return $presentation;
    }
}
