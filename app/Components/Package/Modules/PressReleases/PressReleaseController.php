<?php

namespace App\Components\Package\Modules\PressReleases;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Components\Client\Modules\Settings\Setting;
use Illuminate\Pagination\Paginator;
use DateTime;
use Hash;
use Mail;
use Auth;
use File;
use Session;
use App\Components\Client\Modules\Home\HomeController;
use DB;
use App\Components\Client\Modules\Webpage\Webpage as Contents;

class PressReleaseController extends Controller
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
        $is_enable = $this->checkSortEnable();
       
        $year = date("Y");
       
        $query  = PressReleases::where('company_id', Session::get('company_id'));
        
                                if(Auth::user())
                                
                 {
                     
                     $query->where('is_active', '!=',0);
                     
                 }
                 
                 
                 else
                 {
                     
                     $query->where('is_active', 1);
                 }
                    $query->where(DB::raw('YEAR(press_date)'), $year);
                    $query->where('is_delete', '==','');
                    $query->orderBy('press_date', Input::get('sort'));
                    $press_release = $query->paginate(5); 
                    
     if ($press_release->isEmpty()) {             
        
         $Current =   DB::table("press_release")
                	    ->where('company_id', Session::get('company_id'))
                	     ->where('is_active', 1)
                        //->where('is_delete', 'NULL')
                        ->orderBy(DB::raw("YEAR(press_date)"), 'desc')
                        ->groupBy(DB::raw("YEAR(press_date)"))
                        ->first();
         
         $now = '';
         if(!empty($Current)){
            $now = $Current->press_date;
         }else{
            $now = '';
         }
                        
      
       $date = strtotime($now);
       $year = date('Y', $date);  
       
   
       
        $query  = PressReleases::where('company_id', Session::get('company_id'));
                     if(Auth::user())
                                
                 {
                     
                    $query->where('is_active', '!=',0);
                     
                 }
                 
                 
                 else
                 {
                     
                    $query->where('is_active', 1);
                 }
                    $query->where(DB::raw('YEAR(press_date)'), $year);
                  //  $query->where('is_delete', '==','');
                   // $query->orderBy('press_date', Input::get('sort'));
                    $press_release = $query->paginate(5); 
                        
                        
     }                    
           
           
     
      $pressdate =   DB::table("press_release")
                	    ->where('company_id', Session::get('company_id'))
                        ->where('is_active', 1)
                      //  ->where('is_delete', '==', '')
                        ->orderBy(DB::raw("YEAR(press_date)"), 'desc')
                        ->groupBy(DB::raw("YEAR(press_date)"))
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
          /* Stock API */
       
    
        return $this->view('frontend.press-releases')
                 ->with(compact('title'))
                 ->with('menuPermissions',$menuPermissions)
                ->with('data', $press_release)
                ->with('years', $pressdate)
                ->with('is_enable', $is_enable)
                ->with('year', $this->getYear())
                ->with('month', $this->getNumberOfMonth())
                ->with('selected_month', null)
                ->with('selected_year',  null)->with('title',$title)
                ->with('result',$result)->with('resultid',$resultid);
    }
    

    public function getTabDataFrontend() {
        $press_release = PressReleases::where('company_id', Session::get('company_id'))->where('is_active', 1)
                                    ->orderBy('press_date', Input::get('sort'))                                    
                                    ->paginate(10);
        return $press_release;
    }
    public function getLatestQuarterFrontend($year = null, $quarter = null) {
        if (empty($year) && empty($quarter)) {
            $press_release = PressReleases::where('company_id', Session::get('company_id'))->where('is_active', 1)
                                    ->orderby('quarter', 'desc')
                                    ->orderby('year', 'desc')
                                    ->limit(1)
                                    ->get();
        }else{
            $press_release = PressReleases::where('company_id', Session::get('company_id'))->where('is_active', 1)
                                    ->where('quarter', $quarter)
                                    ->where('year', $year )
                                    ->orderby('press_date', 'desc')
                                    ->first();  
        }
        return $press_release;
    }
    public function getFilterData(){
        $is_enable = $this->checkSortEnable();
        

       if($_POST['year'] == 'All Year'){
        
        $press_release = PressReleases::where('company_id', Session::get('company_id'))
                                    ->where('is_active', 1)
                                   // ->where(DB::raw('YEAR(press_date)'), '=', $request->input('year'))
                                    //->where(DB::raw('MONTH(press_date)'), '=', $request->input('month'))
                                    ->orderby('press_date', 'desc')
                                    ->paginate(100);

        }else{
        $press_release = PressReleases::where('company_id', Session::get('company_id'))
                                    ->where('is_active', 1)
                                    ->where(DB::raw('YEAR(press_date)'), '=', $_POST['year'])
                                    //->where(DB::raw('MONTH(press_date)'), '=', $request->input('month'))
                                    ->orderby('press_date', 'desc')
                                    ->paginate(100);
        }    
        
        $pressdate =   DB::table("press_release")
                	    ->where('company_id', Session::get('company_id'))
                        ->where('is_active', 1)
                        ->orderBy(DB::raw("YEAR(press_date)"), 'desc')
                        ->groupBy(DB::raw("YEAR(press_date)"))
                        ->get();
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
                        
                        
                        
        return $this->view('frontend.press-releases')
                ->with('data', $press_release)
                ->with('years', $pressdate)
                 ->with('menuPermissions',$menuPermissions)
                ->with('is_enable', $is_enable)
                ->with('year', $this->getYear())
                ->with('month', $this->getNumberOfMonth())
                ->with('filter', true)
                //->with('selected_month', $request->input('month'))
                ->with('selected_year',  null)->with('title',$title)
                ->with('result',$result)->with('resultid',$resultid)
                ->with('selected_year',  $_POST['year']);
    }
    public function getList()
    {
        
        
        
        $is_enable = 0;
        $settng = Setting::where('company_id', Auth::user()->company_id)->first();
        if(count($settng) >= 1){
            $is_enable = $settng->press_release_sort_enable;
        }
        
        $press_release = PressReleases::where('company_id', Auth::user()->company_id)->where('is_active', '!=', 2)->orderby('press_date', 'desc')->get();
        
        
        
       

        return $this->view('backend.list')->with('data', $press_release)->with('is_enable', $is_enable)->with('controller', $this);
    }

    public function getForm($id = '')
    {
        if($id != ''){
            $press_release = PressReleases::findOrFail($id);
            return $this->view('backend.edit')->with('data', $press_release)->with('quarter', $this->getQuarter())->with('year', $this->getYear());
        }
    	return $this->view('backend.create-press-release')->with('quarter', $this->getQuarter())->with('year', $this->getYear());
    }
    
    private function checkExistingPressRelease($quarter, $year) {
        $getPressReleaseData = PressReleases::where('company_id', Auth::user()->company_id )
                            ->where('quarter', $quarter)
                            ->where('year', $year)
                            ->first();
        if (count($getPressReleaseData) > 0) {
            return true;
        }
        return false;
    }
    public function aJaxcheckExistingPressRelease(Request $request) {
        $getPressReleaseData = PressReleases::where('company_id', Auth::user()->company_id )
                            ->where('quarter', $request->input('quarter'))
                            ->where('year', $request->input('year'))
                            ->count();
        if ($getPressReleaseData) {
            echo "false";
        } else {
            echo "true";
        }
    }
    
    public function generateUrlLink($title, $id = null) {
        $title = strtolower(str_replace(' ', "-", trim($title)));
        $getTitle  = preg_replace('/[^A-Za-z0-9\-]/', '', $title);
        if (empty($id)) {
            $getPRData = PressReleases::where('company_id', Auth::user()->company_id )
                        ->where('link', $getTitle )
                        ->count();
        } else{
            $getPRData = PressReleases::where('company_id', Auth::user()->company_id )
                        ->where('link', $getTitle )
                        ->where('id', '<>', $id )
                        ->count();
        }
        
        if ($getPRData > 0) {
            return $getTitle."-".$getPRData;
        }
        return $getTitle;
    }
    public function postSave($id = '')
    {
        
    	$data = Input::all();
     /*   if ($data['editor-option'] == 'wysiwyg') {
            $rules = array(
                'title'     => 'required|min:5|max:100',
                'date'      => 'required|min:1',
            );
        } else {
            $rules = array(
                'title'     => 'required|min:5|max:100',
                'date'      => 'required|min:1',
                'upload'    => 'required',
            );
        } */
        
        $rules = array(
                'title'     => 'required|min:5|max:100',
                'date'      => 'required|min:1',
                'upload'    => 'required',
            );
    	
        $validator = Validator::make($data, $rules);

        if ( $validator->fails() ) {
        	return redirect()->back()->withInput()->withErrors($validator);
          
        }
        
      //  echo'<pre>';
     //   print_r($data);die;
        if ($data['upload'] != '') {
            if(isset($data['myfile'])){
                $data['upload'] = $this->doUploadPdf($data);
                if($id != ''){
                    $pre = PressReleases::findOrFail($id);
                    File::delete($pre->upload);
                }
            }
        }
        
        // REMOVE COMMA FOR DATE FORMAT
        $pressReleaseDate = str_replace(',',"", $data['date']);
        $pressReleasePublishDate = str_replace(',',"", $data['publish_date']);
        if($id == ''){
            $this->getStatus($id);
            
            
            if(Input::get('preview')) 
            
            {
            
                $press_release = new PressReleases();
                $press_release->company_id  = Auth::user()->company_id;
                $press_release->title       = $data['title'];
                $press_release->quarter       = isset($data['quarter'])? $data['quarter'] : null;
                $press_release->year      = isset($data['year'])? $data['year'] : null;
                $press_release->press_date  = date("Y-m-d", strtotime($pressReleaseDate));
                $press_release->publish_date  = date("Y-m-d", strtotime($pressReleasePublishDate));
                    $press_release->upload      = $data['upload'];
                    $press_release->description = $data['body'];
                $press_release->link = $this->generateUrlLink($data['title']);
                $press_release->option_selected = $data['editor-option'];
                $press_release->created_by  = Auth::user()->id;
              /*$press_release->is_active = $data['status'];*/
                
                $press_release->is_active = 2;
                if (isset($data['checkbox']) && !empty($data['checkbox'])) 
                {
                    $press_release->financial_apply   = 1;
                }
                else
                {
                    $press_release->financial_apply   = 1;
                }
            
            /*$press_release->save();*/
            
            }
            
            else
            {
            
                    $getData = PressReleases::where('quarter', '=', Input::get('quarter'))
                    ->where('year', '=', Input::get('year'))
                    ->where('is_active','=',2)
                    ->where('company_id','=', Auth::user()->company_id)
                    ->count();
                    if($getData > 0)
                    {
                         $press = PressReleases::where('quarter', '=', Input::get('year'))
                         ->where('year', '=', Input::get('select_year'))
                        ->where('is_active','=',2)
                        ->where('company_id','=', Auth::user()->company_id)
                        ->first();
                         $press_release = PressReleases::find($press->id);
                     
                    }
                   else
                    {
                          $press_release = new PressReleases;
                    }
                   
                    $press_release->company_id  = Auth::user()->company_id;
                    $press_release->title       = $data['title'];
                    $press_release->quarter       = isset($data['quarter'])? $data['quarter'] : null;
                    $press_release->year      = isset($data['year'])? $data['year'] : null;
                    $press_release->press_date  = date("Y-m-d", strtotime($pressReleaseDate));
                    $press_release->publish_date  = date("Y-m-d", strtotime($pressReleasePublishDate));
                   
                        $press_release->upload      = $data['upload'];
                  
                        $press_release->description = $data['body'];
                    
                    $press_release->link = $this->generateUrlLink($data['title']);
                    $press_release->option_selected = 'pdf';
                    $press_release->created_by  = Auth::user()->id;
                     $press_release->is_active = $data['status'];
                    if (isset($data['checkbox']) && !empty($data['checkbox'])) 
                    {
                        $press_release->financial_apply   = 1;
                    }else
                    {
                        $press_release->financial_apply   = 1;
                    }
            
           
            
            
            }
            
            
             $press_release->save();
             
             $data = Input::all();
						
					if(isset($data['notify_it'])){
					    $notify = $data['notify_it'];
					}else{
						   
					   $notify = '';
					}
			
					$company_id = Auth::user()->company_id;
                    $users = DB::table('email_config')->where('company_id', $company_id)->get();
                    
                    if (!empty($users))
                    {
                    	foreach($users as $users)
                    	{
                    		$send = $users->sender_email;
                    		$reply = $users->reply_name;
                    		$rpy = $users->reply_email;
                    	}
                    }
                    else
                    {
                    	$send = '';
                    	$reply = '';
                    	$rpy = '';
                    }
                    
                    if (!empty($notify))
                    {
                    	if (!empty($send))
                    	{
                    		
                    		$email = DB::table('email_alert')->where('company_id', Auth::user()->company_id)->where('is_active', 1)->lists('email_address');
                    		$email_data = ['email_sl' => $email];
                    		Mail::send([], [],
                    		function ($message) use($email_data)
                    		{
                    		    
                                 $company_id = Auth::user()->company_id;
                                 $users = DB::table('email_config')->where('company_id', $company_id)->get();
                                
                                 if (!empty($users))
                                 {
                                	foreach($users as $users)
                                	{
                                		$send = $users->sender_email;
                                		$reply = $users->reply_name;
                                		$rpy = $users->reply_email;
                                	}
                                 }
                                 else
                                 {
                                	$send = '';
                                	$reply = '';
                                	$rpy = '';
                                 }
                    		    $route_financials = route('package.press-releases');
                    		    $cmpname = Session::get('company_name');
                    		    $send    = $send;
                    		    $reply   = $reply;
                    		    $rpy     = $rpy;
                    		    
                    			$html = "<h2>New Press Release</h2>
                                            <p>To view New Press Release Detail, <a href='{$route_financials}'>click here.</a></p>
                                            Best Regards,<br />
                                            {$cmpname} Team";
                    			$message->subject('<PREVIEW> Press Release');
                    			$message->from($send, $cmpname);
                    			$message->to($email_data['email_sl']);
                    			$message->bcc($rpy);
                    			$message->setBody($html, 'text/html');
                    			
                    		});
                    		
                    	
                    	}
                    }
             
             
            
            
            return Redirect::route('package.admin.press-releases')->with('global', 'Press Releases created.');
            
        }else{
            
            
                     if(Input::get('preview'))
                     
                     {
                                $press_release = new PressReleases;
                                $press_release->company_id  = Auth::user()->company_id;
                                $press_release->title       = $data['title'];
                                $press_release->quarter       = isset($data['quarter'])? $data['quarter'] : null;
                                $press_release->year      = isset($data['year'])? $data['year'] : null;
                                $press_release->press_date  = date("Y-m-d", strtotime($pressReleaseDate));
                                $press_release->publish_date  = date("Y-m-d", strtotime($pressReleasePublishDate));
                             
                                $press_release->upload      = $data['upload'];
                               
                                $press_release->description = $data['body'];
                               
                                $press_release->link = $this->generateUrlLink($data['title']);
                                $press_release->option_selected = $data['editor-option'];
                                $press_release->created_by  = Auth::user()->id;
                                $press_release->is_active = 2;
                                if (isset($data['checkbox']) && !empty($data['checkbox'])) 
                                {
                                    $press_release->financial_apply   = 1;
                                }else
                                {
                                    $press_release->financial_apply   = 1;
                                }
                                 $press_release->save();
                                     
                         
                     }
                     
                     else
                     
                     
                     {
            
                        $press_release = PressReleases::findOrNew($id);
                        
                        
                         
                        $press_release->title       = $data['title'];
                        $press_release->press_date  = date("Y-m-d", strtotime($pressReleaseDate));
                        $press_release->publish_date  = date("Y-m-d", strtotime($pressReleasePublishDate));
                            $press_release->upload      = $data['upload'];    
                       
                            $press_release->description = $data['body'];
                       
                        $press_release->link = $this->generateUrlLink($data['title'], $id );
                        $press_release->option_selected = 'pdf';
                        $press_release->quarter       = isset($data['quarter'])? $data['quarter'] : null;
                        $press_release->year      = isset($data['year'])? $data['year'] : null;
                        $press_release->is_active = $data['status'];
                        if (isset($data['checkbox']) && !empty($data['checkbox'])) {
                            $press_release->financial_apply   = 1;
                        }else{
                            $press_release->financial_apply   = 1;
                        }
                        $press_release->updated_by   = Auth::user()->id;
                         
                        $press_release->update();
                                
                                  $getDels = PressReleases::where('is_active','=',2)->get();
                                        foreach($getDels as $detdel)
                                            {
                       
                                                  $pressdelet= PressReleases::find($detdel->id);
                                                  $pressdelet->delete();
                                            }
                     }
                        
                        return Redirect::route('package.admin.press-releases')->with('global', 'Press Releases updated.');
        }
        
    }


    public function doUploadPdf($data)
    {
        //$data = Input::all();
        $destinationPath = "files/press-release/";
        $file       = $data['myfile'];
        $filename   = str_random(8).$_FILES['myfile']['name'];
        $full_path  = $destinationPath.$filename;
        $file->move($destinationPath, $filename);

        return $full_path;
    }

    public function deletePressRelease($id)
    {
        $delete = PressReleases::findOrFail($id);
        $delete->is_delete     = 1;
        $delete->deleted_by    = Auth::user()->id;
        $delete->update();
        $pdf = $delete->upload;
        if($delete->delete()){
            File::delete($pdf);
            return Redirect::route('package.admin.press-releases')->with('global-danger', 'Press Releases deleted.');
        }
    }

    public function changeEnablePressRelease()
    {
        
        
    
        $data = Input::all();
        $is_enable = Input::get('is_enable');
        $setting = Setting::where('company_id', Auth::user()->company_id)->first();

        if(count($setting) <= 0){
            $setting = new Setting();
            $setting->company_id = Auth::user()->company_id;
            $setting->press_release_sort_enable = $is_enable;
            $setting->save();
        }else{
            $setting->press_release_sort_enable = $is_enable;
            $setting->update();
        }
        
        return $data;
    }

    public function deleteMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {

            $press_release = PressReleases::whereIn('id',$input['check'])->get();

            if(count($press_release)){
                foreach($press_release as $item){
                    $press_release = PressReleases::find($item['id']);
                    $press_release->is_delete   = 1;
                    $press_release->deleted_by  = Auth::user()->id;
                    $press_release->update();
                    $upload = $press_release->upload;
                    File::delete($upload);
                }
            }

            $delete = PressReleases::whereIn('id',$input['check']);
            if($delete->delete())
            {
                return redirect()->route('package.admin.press-releases')->with('global-danger', 'Press Releases(s) deleted.');
            }
        }
    }

    public function publishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $press = PressReleases::whereIn('id',$input['check'])->update(['is_active' => '1']);

            return redirect()->route('package.admin.press-releases')->with('global', 'Press Releases(s) published.');

        }
    }

    public function unPublishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $press = PressReleases::whereIn('id',$input['check'])->update(['is_active' => '0']);

            return redirect()->route('package.admin.press-releases')->with('global', 'Press Releases(s) unpublished.');

        }
    }

    public function checkSortEnable()
    {
        $settng = Setting::where('company_id', Session::get('company_id'))->where('press_release_sort_enable', 1)->first();
        if(count($settng) >= 1){
            return true;
        }else{
            return false;
        }
    }
    public function getFrontendPageDatail($page_title){
        $getDetailPressRelease = PressReleases::where('company_id', Session::get('company_id'))->where('is_active', 1)->where('link', $page_title)->first();
        return $this->view('frontend.slugpage')->with('slugContents', $getDetailPressRelease );
    }
    
    
    
    
    
    
        public function sessiondtata(Request $request) 
        {
      
        $request->session()->put('formdata', $request->all());
      
        $request->session()->save(); //manually save session
        return response()->json(['message' => $request->session()->get('formdata')], 200);
        
        }
    
    
    
    
    
       public function previewpress()
    
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
