<?php

namespace App\Components\Client\Modules\Google;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Session;


class GoogleController extends Controller
{
	public function showGoogleReportView() {
		$setData = null;
		$settings = DB::table('setting')->where('company_id', Session::get('company_id'))->first();
		if (isset($settings->google_client_id)){
			$setData  = $settings->google_client_id;
		}
        return $this->view('report-view')->with('data', $setData);
	}
	public function saveClientId(Request $request){
		$settings = DB::table('setting')->where('company_id', Session::get('company_id'));
		$settings->update(array('google_client_id'=> $request->get('client-id')));
		return $this->view('report-view')->with('data', $request->get('client-id'));	
	}
}