<?php

namespace App\Components\Package\Modules\NewsletterAndBroadcast;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use Auth;
use File;
use Session;
use DB;
use Image;
use App\Components\Client\Modules\Home\HomeController;

class NewsletterAndBroadcastBackendController extends Controller
{
	//this function used to view page webcast list
	public function getlist()
	{
     $news = Newsletter::where('company_id', Auth::user()->company_id)->get();
        
        
     $brodcast = Newsletter::join('broadcast_setting as bs','newsletter.id','=','bs.newsletter_id')->where('company_id', Auth::user()->company_id)->get(); 
     
		return $this->view('backend.list')->with('data', $news)->with('brodcast', $brodcast)->with('controller', $this);
	}

	//this function used to view page add new webcast
	public function getForm($id = '')
	{
        $num_broadcast = Newsletter::join('broadcast_setting as bs','newsletter.id','=','bs.newsletter_id')
                                        ->where('newsletter.company_id', Auth::user()->company_id)->where('bs.is_delete', '<>', 1)->count();
        $limit_bc = $this->getSettingJsonFile();
      
        if($id == ''){
            $reach_limit = $this->checkBroadcast();
            $template = $this->getTemplate();
            return $this->view('backend.form')->with('num_broadcast', $num_broadcast)->with('limit_bc', $limit_bc)->with('reach_limit', $reach_limit)->with('template', $template);
        }else{
            $news = Newsletter::findOrFail($id);
            $broadcast = Broadcast::where('newsletter_id', $id)->first();
            $template_content = TemplateContent::where('newsletter_id', $id)->first();
            $news->email_group_list = explode(",", $news->email_group_list);
            $reach_limit = $this->checkBroadcast($id);

            return $this->view('backend.edit')->with('news', $news)->with('broadcast', $broadcast)->with('tp_content', $template_content)
                                            ->with('num_broadcast', $num_broadcast)->with('limit_bc', $limit_bc)->with('reach_limit', $reach_limit);
        }
		
	}



   
   public function postconfig()
	{
	  $company_id = Auth::user()->company_id;

		
	   $users = DB::table('email_config')
	           ->where('company_id', $company_id)->get();

       
        if(empty($users)){

		$insert = array
			(
			   	'company_id' => $company_id,
			    'sender_email' => $_POST["sender_email"],
				'reply_name' => $_POST["reply_to_name"],
				'reply_email' => $_POST["reply_to_email"],
				'status' => '1',
				'created_at' => date("Y-m-d H:i:s"),
				
			); 
			
	    	$result = 	DB::table('email_config')->insert($insert);
        }else{
            
            $update = array
			(
			   	'company_id' => $company_id,
			    'sender_email' => $_POST["sender_email"],
				'reply_name' => $_POST["reply_to_name"],
				'reply_email' => $_POST["reply_to_email"],
				'status' => '1',
				'created_at' => date("Y-m-d H:i:s"),
				
			); 
          	$result = DB::table('email_config')->where('company_id', $company_id)->update($update);
            
        }
        $users = DB::table('email_config')
	           ->where('company_id', $company_id)->get();
	           
	           
	     return Redirect::route('package.admin.newsletter-broadcast.email-seed-list.form');      
         //return $this->view('backend.email_seed_form')->with('users', $users);
      
	}












	public function postSave($id = '')
	{
		$validate = Validator::make(Input::all(), [
            'newsletter_type'	=> 'required|min:1|max:1',
            'subject'     	=> 'required'
           /* 'sender_email' 	=> 'required|min:5|max:50',
            'reply_to_name' 	=> 'required|min:2',
            'reply_to_email' 	=> 'required|min:5|max:50'*/
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }

        $data = Input::all();
       
       if(isset($data['broadcast_date'])){
        $schedule_date = date_create($data['broadcast_date']);
        $schedule_date = date_format($schedule_date, 'Y-m-d');
        $schedule_time = date("H:i", strtotime($data['broadcast_time']));
       }else{
        $schedule_date = '';
        $schedule_time = '';   
       }
   
        if($data['submit'] == 'Save Draft'){
            $data['status'] = 0;
        }else{
            $data['status'] = 1;
        }
        
        
        if($data['submit'] == 'Send Test'){
            $email_group = 1;
        }elseif($data['submit'] == 'Send Subscribers'){
             $email_group = 2;
         }else{
             $email_group = 1;
        }    
        
        
        if(isset($data['myfile'])){
            $data['upload'] = $this->doUploadPdf($data);
            if($id != ''){
                $pre = Newsletter::findOrFail($id);
                File::delete($pre->upload);
            }
        }

        if(isset($data['schedule_it'])){
            $schedule = 1;
        }else{
            $schedule = 0;
        }
         $company_id = Auth::user()->company_id;
        $users = DB::table('email_config')
	           ->where('company_id', $company_id)->get();
        if(!empty($users)){
        foreach($users as $users)
        {
            $send = $users->sender_email;
            $reply= $users->reply_name;
            $rpy = $users->reply_email;
        }
        }else{
            
            $send = '';
            $reply= '';
            $rpy = '';
            
        }
        
      if(!empty($send)){
        if($id == ''){
            $reach_limit = $this->checkBroadcast();
            if($reach_limit){
                $schedule = 0;
            }
            $news = new Newsletter();
            $news->newsletter_type  = $data['newsletter_type'];
            //$news->predefined_templated  = $data[''];
            $news->company_id       = Auth::user()->company_id;
            $news->upload           = $data['upload'];
            $news->subject          = $data['subject'];
            $news->sender_email     = $send;
            $news->reply_to_name    = $reply;
            $news->reply_to_email   = $rpy;
            $news->email_group_list = $email_group;
            $news->schedule_it      = $schedule;
            $news->schedule_date    = $schedule_date;
            $news->schedule_time    = $schedule_time;
            $news->is_active        = 1;
            $news->created_by       = Auth::user()->id;
            $news->save();

            if($data['newsletter_type'] == 2){
                $this->storeTemplateContent($data, $news->id);
            }
            
           
            if(isset($data['schedule_it']) && $reach_limit == false){
                $this->storeBroadcast($data, $news->id);
            }
            
        if(!empty($users)){
        if(($data['submit'] == 'Send Test') && ($schedule == 0)){    
            
        $id = $news->id;
        $news = Newsletter::findOrFail($id);   
         
        $cid = Auth::user()->company_id;
		$cmpname = Session::get('company_name');
            
        $email = DB::table('email_seed_list')->where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_delete', '<>', 1 )->lists('email');
        $email_data = [
                        'news' => $news,
                        'email_sl' => $email,
                        'cmpname' => $cmpname,
                        'id' => $cid
                        ];
        $route_unsubscribe = route('package.email-alerts.unsubscribe');
       
        
		
        if($news){
            if($news->newsletter_type == 1){
                
            $email = $email_data['email_sl'];
            
            foreach($email as $email1)
            	{
            	Mail::send([], [],
            	function ($message) use($email_data, $email1)
            		{
            		  
            		  if(!empty($email_data['news']['reply_to_email']))
            		  {
            		      $replay = $email_data['news']['reply_to_email'];
            		  }else{
            		      $replay = $email_data['news']['sender_email'];
            		  }
            		    
            		$route_unsubscribe = route('package.email-alerts.unsubscribe');
            		$html = "<h2>New newsletter</h2>
                                    <p>To unsubscribe newsletter, <a href='{$route_unsubscribe}/{$email1}/{$email_data['id']}'>click here.</a></p>
                                    Best Regards,<br/>
                                    {$email_data['cmpname']} Team";
            		$message->subject('<PREVIEW> ' . $email_data['news']['subject']);
            		$message->from($email_data['news']['sender_email'], Session::get('company_name'));
            		$message->to($email1);
            		$message->bcc($replay);
            		$message->attach($email_data['news']['upload']);
            		$message->setBody($html, 'text/html');
            		});
            	}
                
            }else{

            $email = $email_data['email_sl'];
            foreach($email as $email1)
            	{
            	Mail::send([], [],
            	function ($message) use($email_data, $email1)
            		{
            		 
            		  if(!empty($email_data['news']['reply_to_email']))
            		  {
            		      $replay = $email_data['news']['reply_to_email'];
            		  }else{
            		      $replay = $email_data['news']['sender_email'];
            		  }
            		    
            		$template_content = TemplateContent::where('newsletter_id', $email_data['news']->id)->first();
            		
            		$route_unsubscribe = route('package.email-alerts.unsubscribe');
            		$html="{$template_content['content']}<br>
            			   <span style='font-size: 10px; color: #575757; line-height: 120%;font-family: arial; text-decoration: none;'>If you no longer want to receive our emails, please <a target='_brank' href='{$route_unsubscribe}/{$email1}/{$email_data['id']}'>un-subscribe here</a>.</span>";     
            						
            		$message->subject('<PREVIEW> ' . $email_data['news']['subject']);
            		$message->from($email_data['news']['sender_email'], Session::get('company_name'));
            		$message->to($email1);
            		$message->bcc($replay);
            		$message->setBody($html, 'text/html');
            		});
            	}
            }
            
           }
        }elseif(($data['submit'] == 'Send Subscribers') && ($schedule == 0)) {
           
        $id = $news->id;
        $news = Newsletter::findOrFail($id);   
        $cid = Auth::user()->company_id;
		$cmpname = Session::get('company_name');   
        $email = DB::table('email_alert')->where('company_id', Auth::user()->company_id)->where('is_active', 1)->lists('email_address');
        $email_data = [
                        'news' => $news,
                        'email_sl' => $email,
                         'cmpname' => $cmpname,
                        'id' => $cid
                        ];
        $route_unsubscribe = route('package.email-alerts.unsubscribe');

        if($news){
            if($news->newsletter_type == 1){
                

            $email = $email_data['email_sl'];
            
            foreach($email as $email1)
            	{
            	Mail::send([], [],
            	function ($message) use($email_data, $email1)
            		{
            		    
            		 if(!empty($email_data['news']['reply_to_email']))
            		  {
            		      $replay = $email_data['news']['reply_to_email'];
            		  }else{
            		      $replay = $email_data['news']['sender_email'];
            		  }   
            		    
            		$route_unsubscribe = route('package.email-alerts.unsubscribe');
            		$html = "<h2>New newsletter</h2>
                                    <p>To unsubscribe newsletter, <a href='{$route_unsubscribe}/{$email1}/{$email_data['id']}'>click here.</a></p>
                                    Best Regards,<br />
                                     Team";
            		$message->subject('<PREVIEW> ' . $email_data['news']['subject']);
            		$message->from($email_data['news']['sender_email'], Session::get('company_name'));
            		$message->to($email1);
            		$message->bcc($replay);
            		$message->attach($email_data['news']['upload']);
            		$message->setBody($html, 'text/html');
            		});
            	}
                
                
            }else{

            $email = $email_data['email_sl'];
            foreach($email as $email1)
            	{
            	Mail::send([], [],
            	function ($message) use($email_data, $email1)
            		{
            		    
            		  if(!empty($email_data['news']['reply_to_email']))
            		  {
            		      $replay = $email_data['news']['reply_to_email'];
            		  }else{
            		      $replay = $email_data['news']['sender_email'];
            		  } 
            		  
            		$template_content = TemplateContent::where('newsletter_id', $email_data['news']->id)->first();    
            		$route_unsubscribe = route('package.email-alerts.unsubscribe');
            		$html="{$template_content['content']}<br>
            			   <span style='font-size: 10px; color: #575757; line-height: 120%;font-family: arial; text-decoration: none;'>If you no longer want to receive our emails, please <a target='_brank' href='{$route_unsubscribe}/{$email1}/{$email_data['id']}'>un-subscribe here</a>.</span>";     
            						
            		$message->subject('<PREVIEW> ' . $email_data['news']['subject']);
            		$message->from($email_data['news']['sender_email'], Session::get('company_name'));
            		$message->to($email1);
            		$message->bcc($replay);
            		$message->setBody($html, 'text/html');
            		});
            	}
            }
            
           } 
            
        }
         
        }    
            return Redirect::route('package.admin.newsletter-broadcast')->with('global', 'Newsletter created.');
        }else{
            $reach_limit = $this->checkBroadcast($id);
            if($reach_limit){
                $schedule = 0;
            }

            $news = Newsletter::findOrFail($id);
            $news->newsletter_type  = $data['newsletter_type'];
            //$news->predefined_templated  = $data[''];
            $news->upload           = $data['upload'];
            $news->subject          = $data['subject'];
            $news->sender_email     = $send;
            $news->reply_to_name    = $reply;
            $news->reply_to_email   = $rpy;
            $news->email_group_list = $email_group;
            $news->schedule_it      = $schedule;
            $news->schedule_date    = $schedule_date;
            $news->schedule_time    = $schedule_time;
            $news->updated_by       = Auth::user()->id;
            $news->update();

            if($data['newsletter_type'] == 2){
                $this->storeTemplateContent($data, $news->id);
            }else{
                $template_content = TemplateContent::where('newsletter_id', $id)->first();
                if(count($template_content) >= 1){
                    $template_content->is_delete = 1;
                    $template_content->deleted_by = Auth::user()->id;
                    $template_content->update();
                    $template_content->delete();
                }
            }

            if(isset($data['schedule_it']) && $reach_limit == false){
                $this->storeBroadcast($data, $news->id);
                
            }else{
                $broadcast = Broadcast::where('newsletter_id', $id)->first();
                if(count($broadcast) >= 1){
                    $broadcast->is_delete = 1;
                    $broadcast->deleted_by = Auth::user()->id;
                    $broadcast->update();
                    $broadcast->delete();
                }
            }

            return Redirect::route('package.admin.newsletter-broadcast')->with('global', 'Newsletter updated.');
        }
      }else{
          return Redirect::route('package.admin.newsletter-broadcast.form')->with('global', 'Mailer not configured, <a href="https://www.w3schools.com"> please configure here.</a>');
      }   
	}

	/*this function used for upload pdf into temp folder
        - return path of pdf file  
    */
    public function doUploadPdf($data)
    {
        //$data = Input::all();
        $destinationPath = "files/newsletter/";
        $file       = $data['myfile'];
        $filename   = str_random(8).$_FILES['myfile']['name'];
        $full_path  = $destinationPath.$filename;
        $file->move($destinationPath, $filename);

        return $full_path;
    }

    public function getNewletterStatus($value, $news_id)
    {
        if($value == 0){
            return 'No schedule';
        }elseif($value == 1){
            $broadcast = Broadcast::where('newsletter_id', $news_id)->first();
            if($broadcast){
                if($broadcast->status == 1){
                    return 'Live';
                }else{
                    return 'Draft';
                }
            }
        }
    }

    public function getNewsType($value)
    {
        if($value == 1){
            return 'PDF';
        }else{
            return 'Template';
        }
    }

    public function getViewTemplate($news_id)
    {

        $tp_content = TemplateContent::where('newsletter_id', $news_id)->first();
        $route_unsubscribe = route('package.email-alerts.unsubscribe');
        if(count($tp_content) <= 0){
            return Redirect::route('package.admin.newsletter-broadcast');
        }

        return $this->view('template.email.template1')->with('data', $tp_content)->with('route_unsubscribe', $route_unsubscribe)->with('view_only', 1);
    }

    public function storeTemplateContent($data, $news_id)
    {
        $template_content = TemplateContent::where('newsletter_id', $news_id)->first();
        if(count($template_content) <= 0){
            $template_content = new TemplateContent();
            $template_content->newsletter_id = $news_id;
            //$template_content->title    = $data['title'];
            $template_content->content  = $data['content'];
            $template_content->save();
        }else{
            //$template_content->title    = $data['title'];
            $template_content->content  = $data['content'];
            $template_content->update();
        }
        
    }

    public function storeBroadcast($data , $news_id)
    {
        
       if(isset($data['broadcast_date'])){
        $schedule_date = date_create($data['broadcast_date']);
        $schedule_date = date_format($schedule_date, 'Y-m-d');
        $schedule_time = date("H:i", strtotime($data['broadcast_time']));
       }else{
        $schedule_date = '';
        $schedule_time = '';  
       }
       
        $broadcast = Broadcast::where('newsletter_id', $news_id)->first();
        if(count($broadcast)<= 0){
            $broadcast = new Broadcast();
            $broadcast->newsletter_id   = $news_id;
            $broadcast->status          = $data['status'];
            $broadcast->broadcast_date  = $schedule_date;
            $broadcast->broadcast_time  = $schedule_time;
            $broadcast->created_by      = Auth::user()->id;
            $broadcast->save();
        }else{

            $broadcast->status          = $data['status'];
            $broadcast->broadcast_date  = $schedule_date;
            $broadcast->broadcast_time  = $schedule_time;
            $broadcast->updated_by      = Auth::user()->id;
            $broadcast->update();
        }
    }

    public function getSoftDelete($id = '')
    {
        $news = Newsletter::findOrFail($id);
        $upload = $news->upload;
        $news->is_delete    = 1;
        $news->deleted_by   = Auth::user()->id;
        $news->update();
        $news->delete();

        File::delete($upload);

        $tp_contnet = TemplateContent::where('newsletter_id', $id)->first();
        if(count($tp_contnet) >= 1){
            $tp_contnet->is_delete  = 1;
            $tp_contnet->deleted_by = Auth::user()->id;
            $tp_contnet->update();
            $tp_contnet->delete();
        }

        $broadcast = Broadcast::where('newsletter_id', $id)->first();
        if(count($broadcast) >= 1){
            $broadcast->is_delete  = 1;
            $broadcast->deleted_by = Auth::user()->id;
            $broadcast->update();
            $broadcast->delete();
        }

        return Redirect::route('package.admin.newsletter-broadcast')->with('global-danger', 'Newsletter has been deleted.');
    }

    public function softDeleteMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {

            $news = Newsletter::whereIn('id',$input['check'])->get();

            if(count($news)){
                foreach($news as $item){
                    $new = Newsletter::find($item['id']);
                    $upload = $new->upload;
                    $new->is_delete = 1;
                    $new->deleted_by = \Auth::user()->id;
                    $new->save();

                    File::delete($upload);

                    $tp_contnet = TemplateContent::where('newsletter_id', $item['id'])->first();
                    if(count($tp_contnet) >= 1){
                        $tp_contnet->is_delete  = 1;
                        $tp_contnet->deleted_by = Auth::user()->id;
                        $tp_contnet->update();
                        $tp_contnet->delete();
                    }

                    $broadcast = Broadcast::where('newsletter_id', $item['id'])->first();
                    if(count($broadcast) >= 1){
                        $broadcast->is_delete  = 1;
                        $broadcast->deleted_by = Auth::user()->id;
                        $broadcast->update();
                        $broadcast->delete();
                    }
                }
            }

            $delete = Newsletter::whereIn('id',$input['check']);
            if($delete->delete())
            {
                return Redirect::route('package.admin.newsletter-broadcast')->with('global-danger', 'Newsletter(s) deleted.');
            }
        }
    }

    public function getnewsDetail($id)
    {
        $news = Newsletter::findOrFail($id);
        $broadcast = Broadcast::where('newsletter_id', $id)->first();
        $template_content = TemplateContent::where('newsletter_id', $id)->first();

        return $this->view('backend.detail')->with('news', $news)->with('broadcast', $broadcast)->with('content', $template_content)->with('controller', $this);
    }

    public function getSendMail($id)
    {
        $news = Newsletter::findOrFail($id);
        $email = DB::table('email_seed_list')->where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_delete', '<>', 1 )->lists('email');
        $email_data = [
                        'news' => $news,
                        'email_sl' => $email
                        ];
        $route_unsubscribe = route('package.email-alerts.unsubscribe');

        if($news){
            if($news->newsletter_type == 1){
                Mail::queue('app.Components.Package.Modules.NewsletterAndBroadcast.views.template.email.mail_pdf', ['data' => $news, 'route_unsubscribe' => $route_unsubscribe], function($message) use($email_data)
                {
                    $message->subject('<PREVIEW> '.$email_data['news']['subject']);
                    $message->from($email_data['news']['sender_email'], Session::get('company_name'));
                    $message->to($email_data['news']['reply_to_email']);
                    $message->bcc($email_data['email_sl']);
                    $message->attach($email_data['news']['upload']);
                });
            }else{

                $template_content = TemplateContent::where('newsletter_id', $id)->first();
                $route_preview = route('package.admin.newsletter-broadcast.view.template', $id);

                Mail::queue('app.Components.Package.Modules.NewsletterAndBroadcast.views.template.email.template1', ['data' => $template_content, 'route_unsubscribe' => $route_unsubscribe, 'route_preview' => $route_preview], function($message) use($email_data)
                {
                    $message->subject('<PREVIEW> '.$email_data['news']['subject']);
                    $message->from($email_data['news']['sender_email'], Session::get('company_name'));
                    $message->bcc($email_data['email_sl']);
                    $message->to($email_data['news']['reply_to_email']);
                });
            }
            
            return Redirect::route('package.admin.newsletter-broadcast.detail', $id)->with('global', 'Email sent.');
        }
        
    }

    //Start Email Seed List

     public function getsubscribes()
    {
        
      $email_sl = DB::table('subscribes_mail')->where('company_id', Auth::user()->company_id)->get();
       
        return $this->view('backend.subscribes')->with('email_sl', $email_sl)->with('controller', $this);
    }



    public function getEmailSeedList()
    {
        
        
        
        $email_sl = EmailSeedList::where('company_id', Auth::user()->company_id)->get();
        return $this->view('backend.email_seed_list')->with('email_sl', $email_sl)->with('controller', $this);
    }

    public function getEmailSeedListForm($id = '')
    {
        
       $company_id = Auth::user()->company_id;
		
	   $users = DB::table('email_config')
	           ->where('company_id', $company_id)->get();
	   
        
        if($id == ''){
            return $this->view('backend.email_seed_form')->with('users', $users);
        }else{
            $email_sl = EmailSeedList::findOrFail($id);
            return $this->view('backend.email_seed_edit')->with('email_sl', $email_sl)->with('users', $users);
        }
        
    }

    public function postEmailSeedList($id = '')
    {
        $validate = Validator::make(Input::all(), [
            'name'   => 'required',
            'email'       => 'required|email'
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }

        $data = Input::all();

        if($id == ''){
            $email_sl = new EmailSeedList();
            $email_sl->company_id   = Auth::user()->company_id;
            $email_sl->name         = $data['name'];
            $email_sl->email        = $data['email'];
            $email_sl->is_active    = 1;
            $email_sl->created_by   = Auth::user()->id;
            $email_sl->save();

            return Redirect::route('package.admin.newsletter-broadcast.email-seed-list')->with('global', 'Email created.');
        }else{
            $email_sl = EmailSeedList::findOrFail($id);
            $email_sl->name = $data['name'];
            $email_sl->email = $data['email'];
            $email_sl->updated_by = Auth::user()->id;
            $email_sl->save();

            return Redirect::route('package.admin.newsletter-broadcast.email-seed-list')->with('global', 'Email updated.');
        }
    }


    public function getEmailSeedSoftDelete($id)
    {
        $email_sl = EmailSeedList::findOrFail($id);
        $email_sl->is_delete     = 1;
        $email_sl->deleted_by    = Auth::user()->id;
        $email_sl->update();
        $email_sl->delete();
        return Redirect::route('package.admin.newsletter-broadcast.email-seed-list')->with('global-danger', 'Email deleted.');
    }

    public function EmailSeedsoftDeleteMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {

            $delete = EmailSeedList::whereIn('id',$input['check'])->get();

            if(count($delete)){
                foreach($delete as $item){
                    $delete = EmailSeedList::find($item['id']);
                    $delete->is_delete   = 1;
                    $delete->deleted_by  = Auth::user()->id;
                    $delete->update();
                }
            }

            if($delete->delete())
            {
                return redirect()->route('package.admin.newsletter-broadcast.email-seed-list')->with('global-danger', 'Email(s) deleted.');
            }
        }
    }

    public function EmailSeedpublishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $webcast = EmailSeedList::whereIn('id',$input['check'])->update(['is_active' => '1']);

            return redirect()->route('package.admin.newsletter-broadcast.email-seed-list')->with('global', 'Email(s) published.');

        }
    }

    public function EmailSeedunPublishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $webcast = EmailSeedList::whereIn('id',$input['check'])->update(['is_active' => '0']);

            return redirect()->route('package.admin.newsletter-broadcast.email-seed-list')->with('global', 'Email(s) unpublished.');

        }
    }

    public function checkBroadcast($id = '')
    {
        if($id == '')
        {
            $num_broadcast = Newsletter::join('broadcast_setting as bs','newsletter.id','=','bs.newsletter_id')
                                        ->where('newsletter.company_id', Auth::user()->company_id)->where('bs.is_delete', '<>', 1)->count();
        }else{
            $num_broadcast = Newsletter::join('broadcast_setting as bs','newsletter.id','=','bs.newsletter_id')
                                        ->where('newsletter.company_id', Auth::user()->company_id)->where('bs.is_delete', '<>', 1)->where('newsletter.id', '<>', $id)->count();
        }
        

        $limit_bc = $this->getSettingJsonFile();

        $limit = $limit_bc['broadcasts_per_year'];

        if($num_broadcast >= $limit){
            return true;
        }else{
            return false;
        }
    }

    public function uploadImage()
    {
        $data = Input::all();

        // $destinationPath    = "files/";
        // $image       = $data['image-file'];

        $old_image   = $data['old-img'];
        // $obj_image  = Image::make($image);
        // $mime       = $obj_image->mime();

        // if ($mime == 'image/png')
        //     $extension = 'png';
        // else
        //     $extension = 'jpg';

        // $filename              = str_random(8).'.'.$extension;
        // $new_img  = $destinationPath.$filename;
        // rename($image, $new_img);

        $data = Input::all();
        $destinationPath = "files/newsletter/";
        $file       = $data['image-file'];
        $filename   = $_FILES['image-file']['name'];
        $full_path  = $destinationPath.$filename;
        $file->move($destinationPath, $filename);
        //return $full_path;
 
        if (strpos($old_image,'img') !== false) {
            return $full_path;
        }else{
            File::delete($old_image);
            return $full_path;
        }
    }
    
    
    
    
    
    public function Multipleemails(){
        
       
        $company_id   = Session::get('company_id');
        
        if(isset($_POST['mail_id'])){
            
            
        	$already_exit = DB::table('email_seed_list')->where('company_id',$company_id)->where('email',$_POST["mail_id"])->first();
        	
        	 if(count($already_exit) == 1){
	            
	            echo "0";
	            
	        }else{
            
       
        $insert = array
			(
			   	'company_id' => $company_id,
			    'email' => $_POST["mail_id"],
				'is_active' => '1',
				'created_at' => date("Y-m-d H:i:s"),
				
			); 
			
	    	$result = 	DB::table('email_seed_list')->insert($insert);
	    	
	    	$get_all_email = DB::table('email_seed_list')->where('company_id',$company_id)->orderBy('id','desc')->get();
	    	
	    	 $output = '';
	    	
	    	if(isset($get_all_email)){
	    	    
	    	    
	    	   
	    	    
	    	    foreach($get_all_email as $allmails){
	    	        
	    	          $output .='
                                 
                                 <div class="row">
                                 <div class="col-md-6">

                                <div class="form-group">
                                <input type="text" value="'.$allmails->email.'"
                                        id="mail_ids" readonly class="form-control" />  
                                           <input type="hidden" id="mail-id" value="'.$allmails->id.'" />
                                </div>
                            </div>
            
                             <div class="col-md-1">
                
                                 <button type="button" class="btn btn-danger" id="rm_mail" onClick="remove_mail()"><i class="fa fa-minus"></i></button>
                             </div>
                        </div>	
                    ';
	    	        
	    	        
	    	        
	    	    }
	    	    
	    	    
	    	    
	    	}
        
        echo $output;
            
        }
    }
    
    
        
    }
    
    
    public function listingmails(){
        
             $company_id   = Session::get('company_id');
        
           	$get_all_email = DB::table('email_seed_list')->where('company_id',$company_id)->orderBy('id', 'desc')->get();
           	
           	 $output = '';
	    	
	    	if(isset($get_all_email)){
	    	    
	    	    
	    	   
	    	    
	    	    foreach($get_all_email as $allmails){
	    	        
	    	          $output .='
                                 
                                 <div class="row">
                                 <div class="col-md-6">

                                <div class="form-group">
                                <input type="text" value="'.$allmails->email.'"
                                        id="mail_ids" readonly class="form-control" />  
                                        <input type="hidden" id="mail-id" value="'.$allmails->id.'" />
                                </div>
                            </div>
            
                             <div class="col-md-1">
                
                                 <button type="button" class="btn btn-danger" id="rem_mail" onClick="remove_mail()"><i class="fa fa-minus"></i></button>
                             </div>
                        </div>	
                    ';
	    	        
	    	        
	    	        
	    	    }
	    	    
	    	    
	    	    
	    	}
        
        echo $output;
        
        
        
        
    }
    
    
    
    public function Deletemail(){
        
        
          $company_id   = Session::get('company_id');
        
        if(isset($_POST['id'])){
        
            $result = 	DB::table('email_seed_list')->where('company_id',$company_id)->where('id',$_POST['id'])->delete();
            
            if($result){
                  
            $output = '';
                
            $get_all_email = DB::table('email_seed_list')->where('company_id',$company_id)->orderBy('id','desc')->get();
	    	
	    	if(isset($get_all_email)){
	    	    
	    	    
	    	    foreach($get_all_email as $allmails){
	    	        
	    	          $output .='
                                
                                 <div class="row">
                                 <div class="col-md-6">

                                <div class="form-group">
                                <input type="text" value="'.$allmails->email.'"
                                        id="mail_ids" readonly class="form-control" />  
                                        <input type="hidden" id="mail-id" value="'.$allmails->id.'" />
                                </div>
                            </div>
            
                             <div class="col-md-1">
                
                                 <button type="button" class="btn btn-danger" id="rm_mail" onClick="remove_mail()"><i class="fa fa-minus"></i></button>
                             </div>
                        </div>	
                    ';
	    	        
	    	        
	    	        
	    	    }
	    	    
	    	    
	    	    
	    	}
        
        echo $output;
        
                
                
                
            }
        
        }
        
    }
    
  /* Schedule */  
  

  public function schedulel()
	{
	$currentdate = date("Y-m-d");
	$currenttime = date("H:i:s");
	$selectedTime = $currenttime;
	$endTime = strtotime("-15 minutes", strtotime($selectedTime));
	$intervel = date('H:i:s', $endTime);
	$company = DB::table('company')->select('*')->where('is_active', '=', 1)->get();
	foreach($company as $company)
		{
		$id = $company->id;
		$cmpname = $company->company_name;
		$news = DB::table('newsletter')->join('broadcast_setting', 'newsletter.id', '=', 'broadcast_setting.newsletter_id')->where('company_id', $id)->where('schedule_it', '=', 1)->where('email_status', '!=', 1)->where('broadcast_date', $currentdate)->whereBetween('broadcast_time', array(
			$intervel,
			$currenttime
		))->get();
		
		
		
	     $email = DB::table('email_seed_list')->where('company_id', $id)->where('is_active', 1)->where('is_delete', '<>', 1)->lists('email');
	    
		foreach($news as $newstest)
		{
		    if($newstest->email_group_list == 1){
    		$email = DB::table('email_seed_list')->where('company_id', $id)->where('is_active', 1)->where('is_delete', '<>', 1)->lists('email');	
    		}elseif($newstest->email_group_list == 2){
    	    $email = DB::table('email_alert')->where('company_id', $id)->where('is_active', 1)->lists('email_address');
    	    
    	    
    		}
		}

		$email_data = ['news' => $news, 'email_sl' => $email,'cmpname' => $cmpname,'id' => $id];
		$route_unsubscribe = route('package.email-alerts.unsubscribe');
		foreach($news as $news)
			{
			if ($news)
				{
				
			/*	echo'<pre>';
				print_r($email_data);
				die;
			*/	
			
       	       if ($news->newsletter_type == 1)
					{
					    
			  /*Mail::queue('app.Components.Package.Modules.NewsletterAndBroadcast.views.template.email.mail_pdf', ['data' => $news, 'route_unsubscribe' => $route_unsubscribe], */
			  
			    $email = $email_data['email_sl'];
			    foreach($email as $email1){
		
				  Mail::send([], [],
					function ($message) use($email_data, $email1)
						{
						   
						   
					 if(!empty($email_data['news']['0']->reply_to_email))
            		  {
            		      $replay = $email_data['news']['0']->reply_to_email;
            		  }else{
            		      $replay = $email_data['news']['0']->sender_email;
            		  }   
						    
						$route_unsubscribe = route('package.email-alerts.unsubscribe');
						$html="<h2>New newsletter</h2>
                        <p>To unsubscribe newsletter, <a href='{$route_unsubscribe}/{$email1}/{$email_data['id']}'>click here.</a></p>
                        Best Regards,<br>
                        {$email_data['cmpname']} Team";    
						    
						$message->subject('<PREVIEW> ' . $email_data['news']['0']->subject);
						$message->from($email_data['news']['0']->sender_email,$email_data['cmpname']);
						$message->to($email1);
						$message->bcc($replay);
						$message->setBody($html, 'text/html');
						$message->attach($email_data['news']['0']->upload);
						});
						
			    }
						
					}
				  else
					{
					    
		/*	Mail::queue('app.Components.Package.Modules.NewsletterAndBroadcast.views.template.email.template1', ['data' => $template_content, 'route_unsubscribe' => $route_unsubscribe, 'route_preview' => $route_preview],
		
		*/
		        $email = $email_data['email_sl'];
                foreach($email as $email1)
                	{
    
    	         Mail::send([], [],
					function ($message) use($email_data, $email1)
						{
						
					 if(!empty($email_data['news']['0']->reply_to_email))
            		  {
            		      $replay = $email_data['news']['0']->reply_to_email;
            		  }else{
            		      $replay = $email_data['news']['0']->sender_email;
            		  }
						
						$template_content = TemplateContent::where('newsletter_id', $email_data['news']['0']->newsletter_id)->first();    
						$route_unsubscribe = route('package.email-alerts.unsubscribe');    
						$html="{$template_content['content']}<br>
						<span style='font-size: 10px; color: #575757; line-height: 120%;font-family: arial; text-decoration: none;'>If you no longer want to receive our emails, please <a target='_brank' href='{$route_unsubscribe}/{$email1}/{$email_data['id']}'>un-subscribe here</a>.</span>";     
						    
						$message->subject('<PREVIEW> ' . $email_data['news']['0']->subject);
						$message->from($email_data['news']['0']->sender_email,$email_data['cmpname']);
						$message->bcc($replay);
						$message->to($email1);
						$message->setBody($html, 'text/html');
						});
                	}	
		
					}

				}
			  $update = DB::table('broadcast_setting')->where('newsletter_id', $news->newsletter_id)->update(['email_status' => 1]);    
				}
			} 
		}

	
  /* Schedule */ 	

   
}