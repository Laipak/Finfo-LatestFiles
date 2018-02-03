<?php 
namespace App\Components\Package\Modules\Announcements;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Session;
use DB;
use Mail;
use App\Components\Client\Modules\Webpage\Webpage as Contents;

class AnnouncementBackendController extends Controller
{
    protected $filesUploadPath = 'files/announcements/';
    
    public function __construct()
    {
        $this->middleware('auth');
        if(\Auth::check() && \Auth::user()->user_type_id != 5)
        {
            App::abort(403);
        }
    }
    
    public function getListAnnouncements()
    {
        $getAllAnnouncement = Announcement::where('is_deleted', '=', 0 )->where('company_id', Auth::user()->company_id)->where('status', '!=', 2)
                ->orderBy('id', 'desc')->get();
                
               
        $index = 0;
        foreach($getAllAnnouncement as $announcement) {
            $getAllAnnouncement[$index]->quarterName = $this->getQuarterMonthById( $announcement->quarter);
            $index++;
        }
        
        
        $title = Contents::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.name','content.title', 'content.content_description', 'content.created_at', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                        ->where('company.id','=', Session::get('company_id'))
                        ->where('content.is_delete', '=', 0 )
                        ->where('content.is_active', '=', 1)
                        ->orderBy('content.ordering', 'asc')
                        ->get();
        
        return $this->view('backend.list')->with(compact('title'))->with(compact('getAllAnnouncement'));
    }
    
    // check id has or not
    public function form($id = null)
    {
        return $id ? $this->deleteAnnouncement($id) : $this->createAnnouncement();
    }

    // Announcement create page
    public function createAnnouncement()
    {   
        return $this->view('backend.create')->with('year', $this->getYear())->with('quarter', $this->getQuarter());
    }

    // Announcement delete page
    public function deleteAnnouncement($id)
    {
        $announcement = Announcement::findOrNew($id);
        $announcement->is_deleted= 1;
        $announcement->deleted_by = \Auth::user()->id ;
        $announcement->title = $announcement->title.'_'.Carbon::now();
        $announcement->update();
        return redirect()->back()->with('deleted', trans('crud.success.anouncement.deleted'));
    }
    
    private function checkExistingQuarterAnnouncement($quarter, $year) {
        $getAnnouncementData = Announcement::where('company_id', Auth::user()->company_id )
                            ->where('quarter', $quarter)
                            ->where('year', $year)
                            ->where('is_deleted', 0 )
                            ->first();
        if (count($getAnnouncementData) > 0) {
            return true;
        }
        return false;
    }
    public function AjaxCheckExistingQuarterAnnouncement(Request $request) {
        $getAnnouncementData = Announcement::where('company_id', Auth::user()->company_id )
                            ->where('quarter',$request->input('quarter'))
                            ->where('year', $request->input('year'))
                            ->where('is_deleted', 0 )
                            ->count();
        if ($getAnnouncementData) {
            echo "false";
        }else{
            echo "true";  
        }
    }
     public function generateUrlLink($title, $id = null) {
        $title = strtolower(str_replace(' ', "-", trim($title)));
        $getTitle  = preg_replace('/[^A-Za-z0-9\-]/', '', $title);
        if (empty($id)) {
            $getPRData = Announcement::where('company_id', Auth::user()->company_id )
                        ->where('link', $getTitle )
                        ->count();
        } else{
            $getPRData = Announcement::where('company_id', Auth::user()->company_id )
                        ->where('link', $getTitle )
                        ->where('id', '<>', $id )
                        ->count();
        }
        
        if ($getPRData > 0) {
            return $getTitle."-".$getPRData;
        }
        return $getTitle;
    }
    
    public function postAnnouncement(Request $request)
    {
      /*  if ($request->input('editor-option') == 'wysiwyg') {
            $validate = Validator::make($request->all(), [
                'title'	=> 'required|min:5|max:100',
                'announce_at' 	=> 'required',
            ]);
        }else{
            $validate = Validator::make($request->all(), [
                'title'	=> 'required|min:5|max:100',
                'announce_at' 	=> 'required',
                'upload_file' => 'required',
            ]);
        } */
        
        
        
        $validate = Validator::make($request->all(), [
                'title'	=> 'required|min:5|max:100',
                'announce_at' 	=> 'required',
                'upload_file' => 'required',
            ]);
            
            
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }else{
                                 
                                 
                                 
                                  
                                   
                                 
                                  if(Input::get('preview')) 
                                
                                {
                                    
                                   
                                    $announcements = new Announcement();
                                    
                                    $announcementDate = str_replace(',',"", $request->input('announce_at'));
                                    
                                 /*   if ($request->input('editor-option') == 'wysiwyg') {
                                        $announcements->description = $request->input('body');    
                                    }else{
                                        $pdfFilesName = $this->savePDFFilePath($request->all());    
                                        $announcements->file_upload = $pdfFilesName;
                                    } */
                                    
                                    $announcements->description = $request->input('body'); 
                                    
                                    $pdfFilesName = $this->savePDFFilePath($request->all());    
                                        $announcements->file_upload = $pdfFilesName;
                                    
                                    $announcements->title = $request->input('title');
                                    $announcements->quarter = $request->input('quarter');
                                   // $announcements->year = $request->input('year');
                                   
                                       $announcements->year =  date('Y', $request->input('announce_at'));
                                       
                                    if ($request->input('checkbox')) {
                                        $announcements->financial_apply   = 1;
                                    }else{
                                        $announcements->financial_apply   = 1;
                                    }
                                    $announcements->link = $this->generateUrlLink($request->input('title'));
                                    /*$announcements->status = $request->input('status');*/
                                    
                                    $announcements->status      = 2;
                                    $announcements->created_by = \Auth::user()->id;
                                    $announcements->announce_at = date('Y-m-d', strtotime( $announcementDate));
                                    $announcements->company_id = \Auth::user()->company_id;
                                    $announcements->option_selected = 'pdf';
                                    
                                }
                                
                                
                                 else
                                    {
                                        
                                        
                                        $getData = Announcement::where('quarter', '=', Input::get('quarter'))
                                            ->where('status','=',2)
                                            ->where('company_id','=', Auth::user()->company_id)
                                            ->count();
                                            if($getData > 0)
                                            {
                                                 $press = Announcement::where('quarter', '=', Input::get('quarter'))
                                                 ->where('status','=',2)
                                                ->where('company_id','=', Auth::user()->company_id)
                                                ->first();
                                                 $announcements = Announcement::find($press->id);
                                             
                                            }
                                    
                                    else
                                            {
                                                  $announcements = new Announcement;
                                            }
                                    
                                    
                                        {
                                            
                                                        
                                                $announcementDate = str_replace(',',"", $request->input('announce_at'));
                                       /*         if ($request->input('editor-option') == 'wysiwyg') {
                                                    $announcements->description = $request->input('body');    
                                                }else{
                                                    $pdfFilesName = $this->savePDFFilePath($request->all());    
                                                    $announcements->file_upload = $pdfFilesName;
                                                }
                                                
                                         */
                                         
                                         $announcements->description = $request->input('body');
                                         
                                         $pdfFilesName = $this->savePDFFilePath($request->all());    
                                        
                                            $announcements->file_upload = $pdfFilesName;
                                         
                                                $announcements->title = $request->input('title');
                                                $announcements->quarter = $request->input('quarter');
                                                $announcements->year = $request->input('year');
                                                if ($request->input('checkbox')) {
                                                    $announcements->financial_apply   = 1;
                                                }else{
                                                    $announcements->financial_apply   = 1;
                                                }
                                                $announcements->link = $this->generateUrlLink($request->input('title'));
                                                /*$announcements->status = $request->input('status');*/
                                                
                                                $announcements->status = $request->input('status');
                                                $announcements->created_by = \Auth::user()->id;
                                                $announcements->announce_at = date('Y-m-d', strtotime( $announcementDate));
                                                $announcements->company_id = \Auth::user()->company_id;
                                                $announcements->option_selected = 'pdf';
                                            
                                        }
                                        
                                    }
                                    
                                    
            $announcements->save();
                   
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
                    		    $route_financials = route('package.announcements');
                    		    $cmpname = Session::get('company_name');
                    		    $send    = $send;
                    		    $reply   = $reply;
                    		    $rpy     = $rpy;
                    		    
                    			$html = "<h2>New Announcement</h2>
                                            <p>To view New Announcement Detail, <a href='{$route_financials}'>click here.</a></p>
                                            Best Regards,<br />
                                            {$cmpname} Team";
                    			$message->subject('<PREVIEW> New Announcement');
                    			$message->from($send, $cmpname);
                    			$message->to($email_data['email_sl']);
                    			$message->bcc($rpy);
                    			$message->setBody($html, 'text/html');
                    			
                    		});
                    		
                    	
                    	}
                    }
            
            
            return redirect()->back()->with('global', trans('crud.success.anouncement.created'));    
        }
    } 
    private function savePDFFilePath($data = null){
        $file = $data['upload_file'];
        $filename = str_random(8).$data['upload'];
        $file->move($this->filesUploadPath, $filename);
        return $filename;
    }
    public function editAnnouncement($id)
    {
        $getAnnouncementData = Announcement::findOrNew($id);
        return $this->view('backend.edit')
                ->with('year', $this->getYear())
                ->with('quarter', $this->getQuarter())
                ->with(compact('getAnnouncementData'));
    }
    public function updateAnnoucement($id, Request $request){
         $validate = Validator::make($request->all(), [
            'title'	=> 'required|min:5|max:100',
            'announce_at' => 'required'
        ]);
         
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        } else{
                                            
                                            
                                            
                                            
                                           if(Input::get('preview'))
                                           
                                           
                                           {
                                               
                                                $announcements = new Announcement();
                                                
                                                $announcementDate = str_replace(',',"", $request->input('announce_at'));
                                            
                                        /*   if ($request->input('editor-option') == 'wysiwyg') {
                                                    $announcements->description = $request->input('body');    
                                                }else{
                                                    $pdfFilesName = $this->savePDFFilePath($request->all());    
                                                    $announcements->file_upload = $pdfFilesName;
                                                }  */
                                                
                                                
                                           $announcements->description = $request->input('body'); 
                                           
                                            $pdfFilesName = $this->savePDFFilePath($request->all());    
                                                    $announcements->file_upload = $pdfFilesName;
                                                
                                                $announcements->title = $request->input('title');
                                                $announcements->quarter = $request->input('quarter');
                                               // $announcements->year = $request->input('year');
                                               
                                                     $announcements->year =  date('Y', $request->input('announce_at'));
                                       
                                       
                                                if ($request->input('checkbox')) {
                                                    $announcements->financial_apply   = 1;
                                                }else{
                                                    $announcements->financial_apply   = 1;
                                                }
                                                $announcements->link = $this->generateUrlLink($request->input('title'));
                                                /*$announcements->status = $request->input('status');*/
                                                
                                                $announcements->status      = 2;
                                                $announcements->created_by = \Auth::user()->id;
                                                $announcements->announce_at = date('Y-m-d', strtotime( $announcementDate));
                                                $announcements->company_id = \Auth::user()->company_id;
                                                $announcements->option_selected = $request->input('editor-option');
                                                
                                                $announcements->save();
                                                           
                                               
                                               
                                           }
                                            
                                            
                                            else
                                            
                                            {
                                            
                                            
                                                        
                                                        $announcementDate = str_replace(',',"", $request->input('announce_at'));
                                                        $getAnnouncementData = Announcement::findOrNew($id);
                                                        $getAnnouncementData->title = $request->input('title');
                                                        $getAnnouncementData->announce_at = date('Y-m-d', strtotime($announcementDate));
                                                        $getAnnouncementData->updated_by = \Auth::user()->id;
                                                        $getAnnouncementData->updated_at = Carbon::now();
                                                        $getAnnouncementData->status = $request->input('status');
                                                        $getAnnouncementData->quarter = $request->input('quarter');
                                                        $getAnnouncementData->year = $request->input('year');
                                                        if ($request->input('checkbox')) {
                                                            $getAnnouncementData->financial_apply   = 1;
                                                        }else{
                                                            $getAnnouncementData->financial_apply   = 1;
                                                        }
                                                        $getAnnouncementData->link = $this->generateUrlLink($request->input('title'));
                                            
                                            /*            if ($request->input('editor-option') == 'wysiwyg') {
                                                            $getAnnouncementData->description = $request->input('body');    
                                                        }else{
                                                            if ($request->file('upload_file')) {
                                                                $getAnnouncementData->file_upload = $this->savePDFFilePath($request->all());
                                                            }
                                                        } */
                                                         
                                        $getAnnouncementData->description = $request->input('body');
                                        
                                        if ($request->file('upload_file')) {
                                                                $getAnnouncementData->file_upload = $this->savePDFFilePath($request->all());
                                                            }
                                                        $getAnnouncementData->option_selected = $request->input('editor-option');
                                                        $getAnnouncementData->update();
                                                        
                                                        $getDels = Announcement::where('status','=',2)->get();
                                                    foreach($getDels as $detdel)
                                                        {
                                   
                                                              $pressdelet= Announcement::find($detdel->id);
                                                              $pressdelet->delete();
                                                        }
                                                        
                                                        
                                            }       
                                            
                                            
                                            
            return redirect()->back()->with('global', trans('crud.success.anouncement.updated'));
        }
    }
    public function softDeleteMulti(Request $request) {
        if(is_array($request->input('check'))) {
            $getAnnouncementData = Announcement::whereIn('id', $request->input('check'))->get();
            if(count($getAnnouncementData)){
                foreach($getAnnouncementData as $item){
                    $data = Announcement::find($item['id']);
                    $data->title = $data->title . "_" . Carbon::now();
                    $data->is_deleted = 1;
                    $data->deleted_by = \Auth::user()->id;
                    $data->deleted_at =  Carbon::now();
                    $data->update();
                }
            }
            return redirect()->back()->with('deleted', trans('crud.success.anouncement.multi_deleted'));
        }
    }
    public function publishMulti(Request $request) {
        if(is_array($request->input('check'))) {
            $latestFinancialHighlight = Announcement::whereIn('id', $request->input('check'))->get();
            if(count($latestFinancialHighlight)){
                foreach($latestFinancialHighlight as $item){
                    $data = Announcement::find($item['id']);
                    $data->status = 0;
                    $data->updated_by = \Auth::user()->id;
                    $data->updated_at =  Carbon::now();
                    $data->update();
                }
            }
            return redirect()->back()->with('global', trans('crud.success.anouncement.multi_publish'));    
        }
    }
    public function unpublishMulti(Request $request) {
        
        if(is_array($request->input('check'))) {
            $latestFinancialHighlight = Announcement::whereIn('id', $request->input('check'))->get();
            if(count($latestFinancialHighlight)){
                foreach($latestFinancialHighlight as $item){
                    $data = Announcement::find($item['id']);
                    $data->status = 1;
                    $data->updated_by = \Auth::user()->id;
                    $data->updated_at =  Carbon::now();
                    $data->update();
                }
            }
            return redirect()->back()->with('global', trans('crud.success.anouncement.multi_unpublish'));    
        }
    }
    
    
    
    
    
    
   /* session data start*/
    
        public function sessiondtata(Request $request) 
        {
      
             $request->session()->put('formdata', $request->all());
             $request->session()->save(); //manually save session
            return response()->json(['message' => $request->session()->get('formdata')], 200);
        
        }
    
      /* session data end*/
    
    
    
    
    
  
    
   
}