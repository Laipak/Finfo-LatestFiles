<?php

namespace App\Components\Package\Modules\LatestFinancialHighlight;

use App\Http\Controllers\Controller;
use App\Components\Package\Modules\LatestFinancialHighlight\LatestFinancialHighlight;
use App\Components\Package\Modules\LatestFinancialHighlight\LatestFinancialItems;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Input;
use DateTime;
use File;
use Carbon\Carbon;
use DB;
use Session;
use Mail;




class LatestFinancialHighlightBackendController extends Controller 
{
    public function __construct()
    {
        $this->middleware('auth');
        if(\Auth::check() && \Auth::user()->user_type_id != 5)
        {
            App::abort(403);
        }
    }
    
    public function index()
    {
        
        
                
        $financialHighlights = LatestFinancialItems::join('latest_financial_highlights as lfh', 'lfh.id', '=', 'latest_financial_highlights_items.latest_financial_highlights_id')
                            ->where('is_deleted', '=', 0)
                            ->where('is_archive', '=', 0)
                            ->where('status', '!=', 2)
                            ->where('company_id', '=', Auth::user()->company_id)
                            ->orderBy('lfh.year', 'desc')
                            ->orderBy('lfh.quarter', 'desc')
                            ->groupBy('lfh.id')
                            ->get();
                            
                            
                      //      return $financialHighlights;
        $index = 0;
        foreach($financialHighlights as $data) {
            $financialHighlights[$index]->quarterName = $this->getQuarterMonthById($data->quarter);
            $index++;
        }
        return $this->view('backend.list')->with('financialHighlights', $financialHighlights);
    }
    public function listArchive() {
        $financialHighlights = LatestFinancialItems::join('latest_financial_highlights as lfh', 'lfh.id', '=', 'latest_financial_highlights_items.latest_financial_highlights_id')
                            ->where('is_deleted', '=', 0)
                            ->where('is_archive', '=', 1)
                            ->where('company_id', '=', Auth::user()->company_id)
                            ->orderBy('lfh.year', 'desc')
                            ->orderBy('lfh.quarter', 'desc')
                            ->groupBy('lfh.id')
                            ->get();
        $index = 0;
        foreach($financialHighlights as $data) {
            $financialHighlights[$index]->quarterName = $this->getQuarterMonthById($data->quarter);
            $index++;
        }
        return $this->view('backend.list-archive')->with('financialHighlights', $financialHighlights);
    }
    public function getCreate()
    {
        $getData = LatestFinancialHighlight::where('is_deleted','!=', 1)
                ->select('year')
                ->where('status','!=',2)
                ->where('company_id','=', Auth::user()->company_id)
                ->groupBy('year')
                ->get();
        
                $year = array();
                foreach ($getData as $key => $value) {
                $year[]=$value['year'];
                }
                $year_list = "";
                foreach ($year as $key => $value) {
                $year_list .="'".$value."',";
                }

      	return $this->view('backend.create')->with('loadyear', $getData)->with('year_list', $year_list)->with('year', $this->getYear())->with('quarter',  $this->getQuarter() );
    }

    private function checkValidationForm($data) {
        if (isset($data['values'][0]) && empty($data['values'][0])) {
            return true;
        }
        if (isset($data['titles'][0]) && empty($data['titles'][0])) {
            return true;
        }
        return false;
    }
    private function checkExistQuarterAndYear($quarter, $year) {
        $getData = LatestFinancialHighlight::where('quarter', '=', $quarter)
                ->where('year', '=', $year)
                ->where('is_deleted','!=', 1)
                ->where('status','!=',2)
                ->where('company_id','=', Auth::user()->company_id)
                ->get()
                ->first();
        if (!empty($getData)) {
            return $getData;
        }
        return false;
    }
    public function getLastActiveFinancial($quarter){
        $getData = LatestFinancialHighlight::where('is_deleted','=',0)
                ->where('is_archive','=',0)
                ->where('company_id','=', Auth::user()->company_id)
                ->where('quarter', $quarter)
                ->orderby('year', 'desc')
                ->get()
                ->first();
        return $getData;
    }
    public function postSave()
    {
        
       $data = Input::all();
       
       if(isset($data['notify_it'])){
           $notify = $data['notify_it'];
       }else{
           
            $notify = '';
       }   
       

     if(!empty($data['webcas'])){
      if((preg_match("@^https://@i",$data['webcas'])) || (preg_match("@^http://@i",$data['webcas']))){
            $String = preg_replace("@(http://)+@i",'http://',$data['webcas']);
        }else{
            $String = 'https://'.$data['webcas'];
        }    
     }else{
          
          $String = '';
     } 
      
     
      
       $data['webcas'] = $String;
       
  
        if(isset($data['select_year'])){
        if (!preg_match('/^[0-9]*$/', $data['select_year']))
        {
        
         return redirect()->back()->with('error', 'Please enter correct year');
        
        }
        else
        {
        	if (strlen($data['select_year']) == 4)
        	{
        	    
        	   if(isset($data['myfile'])){
          	     
        		         
        $validate = Validator::make(Input::all(), [
           //dec4 'select_quarter'     => 'required',
            //'select_year'    => 'required',
            'financial_highlight_title' => 'required',
            'titles'    => 'required',
            'myfile' => 'max:500000'
        ]);
        
         
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        } else {
            $getCheckResult = $this->checkValidationForm(Input::all());
            if ($getCheckResult == true) {
                $validate->errors()->add('titles', 'The title is required');
                $validate->errors()->add('values', 'The value is required');
                return redirect()->back()->withErrors($validate->errors())->withInput();
            }else{
                $titles = Input::get('titles');
                $values = Input::get('values');
               /*dec4 $getFinancialData = $this->checkExistQuarterAndYear(Input::get('select_quarter'), Input::get('select_year'));
             
                if (isset($getFinancialData->year) && (Input::get('select_year') == $getFinancialData->year) 
                    && isset($getFinancialData->select_quarter) ==  (Input::get('select_quarter') == $getFinancialData->select_quarter)) 
                {
                    $validate->errors()->add('select_quarter', trans('crud.errors.lastestfinancialhighlight.quarter_year_exist'));
                    return redirect()->back()->withErrors($validate->errors())->withInput();
                }
                // CHECK RECORD TO MADE ARCHIVED STATUS
                $getLastActiveFinancial = $this->getLastActiveFinancial(Input::get('select_quarter'));
              
                // UPDATE PREVIOUS RECORD
                if (isset($getLastActiveFinancial->year) && ($getLastActiveFinancial->year < Input::get('select_year'))
                ) {
                    $archiveLatestFinancialHighlight = LatestFinancialHighlight::findOrNew($getLastActiveFinancial->id);
                    $archiveLatestFinancialHighlight->is_archive = 1;
                    $archiveLatestFinancialHighlight->update();
                }
                
                dec4*/
                // SAVE NEW RECORD
                
                  
              
                
                if(Input::get('preview')) 
                {
                    $getDels = LatestFinancialHighlight::where('status','=',2)->get();
                     foreach($getDels as $detdel)
                     {
                       
                          $checjuu= LatestFinancialHighlight::find($detdel->id);
                          $checjuu->delete();
                     }
                
                    $financial = new LatestFinancialHighlight;
                    // CHECKING
                    if(isset($getLastActiveFinancial->year) && ($getLastActiveFinancial->year > Input::get('select_year'))) {
                        $financial->is_archive = 1;
                    }  
                    $financial->company_id = Auth::user()->company_id;
                    $financial->quarter = 1;
                    $financial->year = Input::get('select_year');                        
                    $financial->created_at = Carbon::now();
                    $financial->created_by = Auth::user()->id;
                    $financial->title = Input::get('financial_highlight_title');
                    $financial->publish_date = date('y-m-d', strtotime(Input::get('publish_date')));
                    $financial->status = 2;
                }
                
                else
                {
                    $getData = LatestFinancialHighlight::/*dec4where('quarter', '=', Input::get('select_quarter'))
                    ->*/where('year', '=', Input::get('select_year'))
                    ->where('is_deleted','=',0)
                    ->where('status','=',2)
                    ->where('company_id','=', Auth::user()->company_id)
                    ->count();
                    if($getData > 0)
                    {
                     
                        $one = LatestFinancialHighlight::/*dec4where('quarter', '=', Input::get('select_quarter'))
                        ->*/where('year', '=', Input::get('select_year'))
                        ->where('is_deleted','=',0)
                        ->where('status','=',2)
                        ->where('company_id','=', Auth::user()->company_id)
                        ->first();
                        $financial = LatestFinancialHighlight::find($one->id);
                        LatestFinancialItems::where('latest_financial_highlights_id', '=', $one->id)->delete();
                    }
                    else
                    {
                        $financial = new LatestFinancialHighlight;
                    }
                    
                    // CHECKING
                    if(isset($getLastActiveFinancial->year) && ($getLastActiveFinancial->year > Input::get('select_year'))) {
                        $financial->is_archive = 1;
                    }  
                    $financial->company_id = Auth::user()->company_id;
                   //dec4 $financial->quarter = Input::get('select_quarter');
                    $financial->year = Input::get('select_year');                        
                    $financial->created_at = Carbon::now();
                    $financial->created_by = Auth::user()->id;
                    $financial->title = Input::get('financial_highlight_title');
                   //dec4 $financial->publish_date = date('y-m-d', strtotime(Input::get('publish_date')));
                   
                   $financial->publish_date = date('y-m-d');
                   
                    $financial->status = 0;
                    
                }
                
                $financial->save();
                
                $this->saveLastestFinancialHighlightItems(Input::get('titles'), Input::get('values'), $financial->id,$String);
                
                if(isset($data['myfile'])){
                    $data['value'] = $this->doUploadPdf($data, $financial->id);
                    
                }
                if(isset($data['myfile1'])){
                    $data['value1'] = $this->doUploadPdf1($data, $financial->id);
                    
                }
                
                if(isset($data['myfile2'])){
                    $data['value2'] = $this->doUploadPdf2($data, $financial->id);
                    
                }
                /*dec4 if(isset($data['myfile3'])){
                    $data['value3'] = $this->doUploadPdf3($data, $financial->id);
                    
                }
                 dec4*/
                 
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
                    		    $route_financials = route('package.financial-result');
                    		    $cmpname = Session::get('company_name');
                    		    $send    = $send;
                    		    $reply   = $reply;
                    		    $rpy     = $rpy;
                    		    
                    			$html = "<h2>New Financials</h2>
                                            <p>To view New Financials Detail, <a href='{$route_financials}'>click here.</a></p>
                                            Best Regards,<br />
                                            {$cmpname} Team";
                    			$message->subject('<PREVIEW> New Financials');
                    			$message->from($send, $cmpname);
                    			$message->to($email_data['email_sl']);
                    			$message->bcc($rpy);
                    			$message->setBody($html, 'text/html');
                    			
                    		});
                    		
                    	
                    	}
                    }     
                 
                return redirect()->back()->with('success', trans('crud.success.financials.created'));    
              }
        }
        	}else{
        	    
        	    return redirect()->back()->with('error', 'Please upload financial result file');
        	}
        	}
        	else
        	{
        	   return redirect()->back()->with('error', 'Please enter correct year format');
        	}
        	
        }
        
       }else{
           
        return redirect()->back()->with('error', 'Please enter the year');
           
       } 
        
        }
   
    public function saveLastestFinancialHighlightItems($titles, $values, $financialId,$webca) {
        $index= 0;
        foreach ($titles as $title) { 
            if (!empty($title)) {
                $financial_term = new LatestFinancialItems;
                $financial_term->latest_financial_highlights_id = $financialId;                        
                if($title == 'Financial Results *'){
                    
                    $title =  substr($title, 0, -2);
                }else{
                    
                    $title = $title;
                }
                
                $financial_term->title = $title;
                $financial_term->created_at = Carbon::now();
                $financial_term->created_by = Auth::user()->id;
                if (empty($values[$index])) {
                    $financial_term->value = 0;   
                } else{
                    
                    //$data = $this->doUploadPdf($values[$index]);
                    //$financial_term->value = $values[$index];
                }
                
                if($title == 'Webcast'){
                    $financial_term->value = $webca;                }
                $financial_term->save();
            }
            $index++;
        }
    }
    
    
    
    
    public function updateLastestFinancialHighlightItems($titles, $values, $financialId,$webca) {
        $index= 0;
       
        foreach ($titles as $title) { 
            if (!empty($title)) {
                
                $financial_term = new LatestFinancialItems;
                $financial_term->latest_financial_highlights_id = $financialId;                        
                $financial_term->title = $title;
                $financial_term->created_at = Carbon::now();
                $financial_term->created_by = Auth::user()->id;
          
                if (empty($values)) {
                    $financial_term->value = 0;   
                } else{
                    
                    //$data = $this->doUploadPdf($values[$index]);
                     $financial_term->value = $values;
                }
                
                if($title == 'Webcast'){
                    $financial_term->value = $webca;                }
                $financial_term->save();
            }
            $index++;
        }
    }    
    
    
    /* NEW UPDATE */
    
     public function doUploadPdf($data,$financialId)
    {
       
       
        $data = Input::all();
        $title = $data['titles']['0'];
        $titles =  substr($title, 0, -2);
        
        //$titles = $data['titles']['0'];
        
        $destinationPath = "files/latest-financial-highlights/";
        $file       = $data['myfile'];
        $filename   = str_random(8).$_FILES['myfile']['name'];
        $full_path  = $destinationPath.$filename;
  
        $file->move($destinationPath, $filename);
 
        $financial = LatestFinancialItems::where('latest_financial_highlights_id', $financialId)->where('title', $titles)->first();
      
        
        $financial->value = $full_path;
        $financial->update();
 
 
 
        // return $full_path;
    }
    
    
    
    public function doUploadPdf1($data,$financialId)
    {
       
        $data = Input::all();
        
        $titles = $data['titles']['1'];
        
        $destinationPath = "files/latest-financial-highlights/";
        $file       = $data['myfile1'];
        $filename   = str_random(8).$_FILES['myfile1']['name'];
        $full_path  = $destinationPath.$filename;
  
        $file->move($destinationPath, $filename);
        
        
        $financial = LatestFinancialItems::where('latest_financial_highlights_id', $financialId)->where('title', $titles)->first();
        
        $financial->value = $full_path;
        $financial->update();
 
       //  return $full_path;
    }
    
    public function doUploadPdf2($data,$financialId)
    {
       
        $data = Input::all();
        
        $titles = $data['titles']['2'];
        
        $destinationPath = "files/latest-financial-highlights/";
        $file       = $data['myfile2'];
        $filename   = str_random(8).$_FILES['myfile2']['name'];
        $full_path  = $destinationPath.$filename;
  
        $file->move($destinationPath, $filename);
        
        
        $financial = LatestFinancialItems::where('latest_financial_highlights_id', $financialId)->where('title', $titles)->first();
        
        $financial->value = $full_path;
        $financial->update();
 
       //  return $full_path;
    }
    public function doUploadPdf3($data,$financialId)
    {
       
        $data = Input::all();
        
        $titles = $data['titles']['3'];
        
        $destinationPath = "files/latest-financial-highlights/";
        $file       = $data['myfile3'];
        $filename   = str_random(8).$_FILES['myfile3']['name'];
        $full_path  = $destinationPath.$filename;
  
        $file->move($destinationPath, $filename);
        
        
        $financial = LatestFinancialItems::where('latest_financial_highlights_id', $financialId)->where('title', $titles)->first();
        
        $financial->value = $full_path;
        $financial->update();
 
       //  return $full_path;
    }
    /* NEW UPDATE */
    
    
    
    
    
    public function postUpdate($itemsId) {
     
     
    $data = Input::all();
  
   if(!empty($data['webcas'])){
    if((preg_match("@^https://@i",$data['webcas'])) || (preg_match("@^http://@i",$data['webcas']))){
            $String = preg_replace("@(http://)+@i",'http://',$data['webcas']);
        }else{
            $String = 'https://'.$data['webcas'];
        }    
   }else{
         $String = '';
        }   
      
       $data['webcas'] = $String;
       
       
        if (!preg_match('/^[0-9]*$/', $data['select_year']))
        {
        
         return redirect()->back()->with('error', 'Please enter correct year');
        
        }
        else
        {
        	if (strlen($data['select_year']) == 4)
        	{
        	    

       $validate = Validator::make(Input::all(), [
            'titles'    => 'required',
            'values'    => 'required',
            'financial_highlight_title' => 'required'
        ]);
         
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        } else { 
             
           if(Input::get('preview')) 
            
           {
            
          
            
                    $financial = new LatestFinancialHighlight;
                    $financial->company_id = Auth::user()->company_id;
                   //dec4 $financial->quarter = Input::get('select_quarter');
                    $financial->quarter = "1";
                    $financial->year = Input::get('select_year');                        
                    $financial->created_at = Carbon::now();
                    $financial->created_by = Auth::user()->id;
                    $financial->title = Input::get('financial_highlight_title');
                    //dec4 $financial->publish_date = date('y-m-d', strtotime(Input::get('publish_date')));
                    
                    $financial->publish_date = date('y-m-d');
                    
                    $financial->status = 2;
                    
                    $financial->save();
                    $this->saveLastestFinancialHighlightItems(Input::get('titles'), Input::get('values'), $financial->id,Input::get('values3'));
                    
           
            
            } 
            
            else
            {
                
           
            $financial = LatestFinancialHighlight::findOrNew($itemsId);
             $financial->year = Input::get('select_year'); 
            $financial->updated_at = Carbon::now();
            $financial->updated_by = Auth::user()->id;
            $financial->title = Input::get('financial_highlight_title');
           //dec4 $financial->publish_date = date('y-m-d', strtotime(Input::get('publish_date')));
            $financial->publish_date = date('y-m-d');
            $financial->update();
            
            
            LatestFinancialItems::where('latest_financial_highlights_id', '=', $itemsId)->delete();
            
             $data = Input::all();
            
             $this->updateLastestFinancialHighlightItems(Input::get('titles'), Input::get('values'), $financial->id, $String);
             
        
              if(isset($data['myfile'])){
                    $data['value'] = $this->doUploadPdf($data, $itemsId);
                }
                
                if(isset($data['myfile1'])){
                    $data['value1'] = $this->doUploadPdf1($data, $itemsId);
                    
                }
                
                if(isset($data['myfile2'])){
                    $data['value2'] = $this->doUploadPdf2($data, $itemsId);
                    
                }
            $getDels = LatestFinancialHighlight::where('status','=',2)->get();
             foreach($getDels as $detdel)
             {
               
                  $checjuu= LatestFinancialHighlight::find($detdel->id);
                  $checjuu->delete();
             }
              
            }
 
            return redirect()->back()->with('success', trans('crud.success.financials.updated'));       
        }

  }
  else
    {
    	   return redirect()->back()->with('error', 'Please enter correct year format');
    }
  
 }
}
    
    public function getEdit($id)
    {
        
        $getData = LatestFinancialHighlight::where('is_deleted','!=', 1)
                ->select('year')
                ->where('status','!=',2)
                ->where('company_id','=', Auth::user()->company_id)
                ->groupBy('year')
                ->get();
        
        
        $year = array();
                foreach ($getData as $key => $value) {
                $year[]=$value['year'];
                }
                $year_list = "";
                foreach ($year as $key => $value) {
                $year_list .="'".$value."',";
                }

        
        $financialHighlight = LatestFinancialItems::join('latest_financial_highlights as lfh', 'lfh.id', '=', 'latest_financial_highlights_items.latest_financial_highlights_id')
                    ->select('lfh.quarter', 'lfh.year', 'lfh.title as f_title', 'lfh.is_archive', 'lfh.id', 'lfh.publish_date', 'latest_financial_highlights_items.*')
                    ->where('lfh.is_deleted', '=', 0)
                    ->where('company_id', '=', Auth::user()->company_id)
                    ->where('latest_financial_highlights_items.latest_financial_highlights_id', '=', $id)
                    ->get();
                    
                    
        $financialResult = LatestFinancialItems::join('latest_financial_highlights as lfh', 'lfh.id', '=', 'latest_financial_highlights_items.latest_financial_highlights_id')
                    ->select('lfh.quarter', 'lfh.year', 'lfh.title as f_title', 'lfh.is_archive', 'lfh.id', 'lfh.publish_date', 'latest_financial_highlights_items.*')
                    ->where('lfh.is_deleted', '=', 0)
                    ->where('company_id', '=', Auth::user()->company_id)
                    ->where('latest_financial_highlights_items.latest_financial_highlights_id', '=', $id)
                    ->where('latest_financial_highlights_items.title', '=', 'Financial Results')
                    ->get();
        
        $financialPresentation = LatestFinancialItems::join('latest_financial_highlights as lfh', 'lfh.id', '=', 'latest_financial_highlights_items.latest_financial_highlights_id')
                    ->select('lfh.quarter', 'lfh.year', 'lfh.title as f_title', 'lfh.is_archive', 'lfh.id', 'lfh.publish_date', 'latest_financial_highlights_items.*')
                    ->where('lfh.is_deleted', '=', 0)
                    ->where('company_id', '=', Auth::user()->company_id)
                    ->where('latest_financial_highlights_items.latest_financial_highlights_id', '=', $id)
                    ->where('latest_financial_highlights_items.title', '=', 'Presentation')
                    ->get(); 
                    
        $financialPress = LatestFinancialItems::join('latest_financial_highlights as lfh', 'lfh.id', '=', 'latest_financial_highlights_items.latest_financial_highlights_id')
                    ->select('lfh.quarter', 'lfh.year', 'lfh.title as f_title', 'lfh.is_archive', 'lfh.id', 'lfh.publish_date', 'latest_financial_highlights_items.*')
                    ->where('lfh.is_deleted', '=', 0)
                    ->where('company_id', '=', Auth::user()->company_id)
                    ->where('latest_financial_highlights_items.latest_financial_highlights_id', '=', $id)
                    ->where('latest_financial_highlights_items.title', '=', 'Press Release')
                    ->get(); 
        
        $financialWebcast = LatestFinancialItems::join('latest_financial_highlights as lfh', 'lfh.id', '=', 'latest_financial_highlights_items.latest_financial_highlights_id')
                    ->select('lfh.quarter', 'lfh.year', 'lfh.title as f_title', 'lfh.is_archive', 'lfh.id', 'lfh.publish_date', 'latest_financial_highlights_items.*')
                    ->where('lfh.is_deleted', '=', 0)
                    ->where('company_id', '=', Auth::user()->company_id)
                    ->where('latest_financial_highlights_items.latest_financial_highlights_id', '=', $id)
                    ->where('latest_financial_highlights_items.title', '=', 'Webcast')
                    ->get();             
                    
                     
    	return $this->view('backend.edit')->with('loadyear', $getData)->with('year_list', $year_list)->with('data', $financialHighlight)->with('result', $financialResult)->with('presentation',$financialPresentation)->with('press', $financialPress)->with('webcast',$financialWebcast)->with('year', $this->getYear())->with('quarter',  $this->getQuarter());
    }
    public function deleted($latest_financial_highlights_id) {
        $latestFinancialHighlight = LatestFinancialHighlight::findOrNew($latest_financial_highlights_id);
        $latestFinancialHighlight->is_deleted = 1;
        $latestFinancialHighlight->quarter = $latestFinancialHighlight->quarter . "_" . Carbon::now();
        $latestFinancialHighlight->deleted_at = Carbon::now();
        $latestFinancialHighlight->deleted_by = Auth::user()->id;
        $latestFinancialHighlight->update();
        return redirect()->back()->with('deleted', trans('crud.success.financials.deleted'));   
    }
    public function softDeleteMulti(Request $request ) {
        if(is_array($request->input('check'))) {
            $latestFinancialHighlight = LatestFinancialHighlight::whereIn('id', $request->input('check'))->get();
            if(count($latestFinancialHighlight)){
                foreach($latestFinancialHighlight as $item){
                    $data = LatestFinancialHighlight::find($item['id']);
                    $data->quarter = $data->quarter . "_" . Carbon::now();
                    $data->is_deleted = 1;
                    $data->deleted_by = \Auth::user()->id;
                    $data->deleted_at =  Carbon::now();
                    $data->update();
                }
            }
            return redirect()->back()->with('deleted', trans('crud.success.lastestfinancialhighlight.multi_deleted'));
        }
    }
    public function publishMulti(Request $request) {
        if(is_array($request->input('check'))) {
            $latestFinancialHighlight = LatestFinancialHighlight::whereIn('id', $request->input('check'))->get();
            if(count($latestFinancialHighlight)){
                foreach($latestFinancialHighlight as $item){
                    $data = LatestFinancialHighlight::find($item['id']);
                    $data->status = 0;
                    $data->updated_by = \Auth::user()->id;
                    $data->updated_at =  Carbon::now();
                    $data->update();
                }
            }
            return redirect()->back()->with('global', trans('crud.success.lastestfinancialhighlight.multi_publish'));
        }
    }
    public function unpublishMulti(Request $request) {
        if(is_array($request->input('check'))) {
            $latestFinancialHighlight = LatestFinancialHighlight::whereIn('id', $request->input('check'))->get();
            if(count($latestFinancialHighlight)){
                foreach($latestFinancialHighlight as $item){
                    $data = LatestFinancialHighlight::find($item['id']);
                    $data->status = 1;
                    $data->updated_by = \Auth::user()->id;
                    $data->updated_at =  Carbon::now();
                    $data->update();
                }
            }
            return redirect()->back()->with('global', trans('crud.success.lastestfinancialhighlight.multi_unpublish'));
        }
    }
}
