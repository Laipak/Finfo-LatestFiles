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
use Auth;
use Carbon\Carbon;

class WebcastBackendController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
	//this function used to view page webcast list
	public function getlist()
	{

		$webcast = Webcast::where('company_id', Auth::user()->company_id)->get();

		return $this->view('backend.list')->with('data', $webcast)->with('controller', $this);
	}

	//this function used to view page add new webcast
	public function getForm($id = '')
	{

        if($id != ''){
        	$webcast = Webcast::findOrFail($id);
        	return $this->view('backend.edit')->with('year', $this->getYear())->with('quarter', $this->getQuarter())->with('data', $webcast);
        }

		return $this->view('backend.form')->with('year', $this->getYear())->with('quarter', $this->getQuarter());
	}
        private function checkExistingWebcast($quarter, $year ){
            $getWebcast = Webcast::where('company_id', Auth::user()->company_id )
                            ->where('quarter', $quarter)
                            ->where('year', $year)
                            ->count();
        if ($getWebcast > 0) {
            return true;
        }
        return false;
        }
	public function postSave($id = '')
	{
            $validate = Validator::make(Input::all(), [
            'url'	=> 'required|min:5|max:250|url',
            'caption' 	=> 'required|min:5|max:100',
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
        $data = Input::all();
        if (empty($id)) {
            if ($this->checkExistingWebcast($data['quarter'], $data['year']) == true) {
                $validate->errors()->add('quarter', 'Quarter already existing with year.');
                return redirect()->back()->withInput()->withErrors($validate);
            }
        }
        
        if($id == ''){
        	$webcast = new Webcast();
	        $webcast->company_id 	= Auth::user()->company_id;
	        $webcast->quarter 		= $data['quarter'];
	        $webcast->year 			= $data['year'];
	        $webcast->url 			= $data['url'];
	        $webcast->caption 		= $data['caption'];
            $webcast->publish_date       = date('Y-m-d', strtotime($data['publish_date'])) ;
	        $webcast->created_by	= Auth::user()->id;
                $webcast->is_active     = 1;
	        $webcast->save();

	        return Redirect::route('package.admin.webcast')->with('global', 'Webcast created.');
        }else{
        	$webcast = Webcast::findOrFail($id);
	        $webcast->url 			= $data['url'];
	        $webcast->caption 		= $data['caption'];
	        $webcast->updated_by	= Auth::user()->id;
            $webcast->publish_date       = date('Y-m-d', strtotime($data['publish_date'])) ;
	        $webcast->update();

	        return Redirect::route('package.admin.webcast')->with('global', 'Webcast updated.');
        }
        
	}

	public function getDelete($id)
	{
		$webcast = Webcast::findOrFail($id);
		$webcast->is_delete 	= 1;
		$webcast->deleted_by 	= Auth::user()->id;
		$webcast->update();
		$webcast->delete();
		return Redirect::route('package.admin.webcast')->with('global-danger', 'Webcast deleted.');
	}

	public function deleteMulti()
	{
		$input = Input::all();
        if(is_array($input['check']))
        {

            $delete = Webcast::whereIn('id',$input['check'])->get();

            if(count($delete)){
                foreach($delete as $item){
                    $delete = Webcast::find($item['id']);
                    $delete->is_delete   = 1;
                    $delete->deleted_by  = Auth::user()->id;
                    $delete->update();
                }
            }

            if($delete->delete())
            {
                return redirect()->route('package.admin.webcast')->with('global-danger', 'Webcast(s) deleted.');
            }
        }
	}

	public function publishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $webcast = Webcast::whereIn('id',$input['check'])->update(['is_active' => '1']);

            return redirect()->route('package.admin.webcast')->with('global', 'Webcast(s) published.');

        }
    }

    public function unPublishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $webcast = Webcast::whereIn('id',$input['check'])->update(['is_active' => '0']);

            return redirect()->route('package.admin.webcast')->with('global', 'Webcast(s) unpublished.');

        }
    }
}
