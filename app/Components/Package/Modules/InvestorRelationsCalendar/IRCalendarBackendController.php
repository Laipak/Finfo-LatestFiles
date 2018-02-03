<?php

namespace App\Components\Package\Modules\InvestorRelationsCalendar;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use DB;
use Mail;
use Auth;
use Carbon\Carbon;

class IRCalendarBackendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
	//
    public function getList()
    {
        $event = InvestorRelationsCalendar::where('company_id', Auth::user()->company_id)->get();
        return $this->view('backend.list')->with('data', $event)->with('controller', $this);
    }

    // 
    public function createIrCalendar($id = '')
    {
        if($id != ''){
            $event = InvestorRelationsCalendar::findOrFail($id);
            return $this->view('backend.edit')->with('data', $event);
        }
        return $this->view('backend.create');
    }

    // 
    public function postIrCalendar($id = '')
	{
		$validate = Validator::make(Input::all(), [
            'event_title'	        => 'required|min:5|max:50',
            'event_datetime' => 'required|min:1',
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
        $data = Input::all();
        if($id == ''){                                
            
                                                if(Input::get('preview')) 
                                
                                                      {
                                                        $event = new InvestorRelationsCalendar();
                                                        $event->company_id      = Auth::user()->company_id;
                                                        $event->event_title     = $data['event_title'];
                                                        $event->event_datetime  = date("Y-m-d H:i:s", strtotime($data['event_datetime']));
                                                        $event->created_by      = Auth::user()->id;
                                                        $event->is_active       = 2;
                                                      }
                                                      
                                                       else
                                                        {
                                                            
                                                            
                                                            $getData = InvestorRelationsCalendar::where('event_title', '=', Input::get('event_title'))
                                                                ->where('is_active','=',2)
                                                                ->where('company_id','=', Auth::user()->company_id)
                                                                ->count();
                                                                if($getData > 0)
                                                                {
                                                                     $press = InvestorRelationsCalendar::where('event_title', '=', Input::get('event_title'))
                                                                     ->where('is_active','=',2)
                                                                    ->where('company_id','=', Auth::user()->company_id)
                                                                    ->first();
                                                                     $event = InvestorRelationsCalendar::find($press->id);
                                                                 
                                                                }
                                                    
                                                        
                                                          else
                                                            {
                                                                  $event = new InvestorRelationsCalendar;
                                                            }
                                                    
                                    
                                    
                                    
                                                             {
                                                                 
                                                                 
                                                                            $event->company_id      = Auth::user()->company_id;
                                                                            $event->event_title     = $data['event_title'];
                                                                            $event->event_datetime  = date("Y-m-d H:i:s", strtotime($data['event_datetime']));
                                                                            $event->created_by      = Auth::user()->id;
                                                                            $event->is_active       = 0;
                                                                            
                                                             }
                                                }
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
            $event->save();

            return Redirect::route('package.admin.investor-relations-calendar')->with('global', 'Event created.');
        }else{
                                           
                                           
             
                                           
                                           
                                           
                                           
                                             if(Input::get('preview')) 
                                
                                                      {
                                                        $event = new InvestorRelationsCalendar();
                                                        $event->company_id      = Auth::user()->company_id;
                                                        $event->event_title     = $data['event_title'];
                                                        $event->event_datetime  = date("Y-m-d H:i:s", strtotime($data['event_datetime']));
                                                        $event->created_by      = Auth::user()->id;
                                                        $event->is_active       = 2;
                                                        $event->save();
                                                      }
                                           else
                                           {
                                               
                                               
                                                $event = InvestorRelationsCalendar::findOrFail($id);
                                                    
                                                 
                                                   
                                               
                                                   
                                                  
                                                    $event->event_title     = $data['event_title'];
                                                    $event->event_datetime  = date("Y-m-d H:i:s", strtotime($data['event_datetime']));
                                                    $event->updated_by      = Auth::user()->id;
                                                    $event->update();
                                                    
                                                                
                                                  $getDels = DB::table('investor_relations_calendar')->where('is_active','=',2)->get();
                                                  
                                                
                                     
                                     
                                                    foreach($getDels as $detdel)
                                                        {
                                   
                                                              $pressdelet= DB::table('investor_relations_calendar')->where('id','=',$detdel->id)->delete();
                                                        }
                                               
                                           }
                                           
                                           
                                           
                                           
                                           

            return Redirect::route('package.admin.investor-relations-calendar')->with('global', 'Event updated.');
        }
        
	}

    public function getSoftDelete($id = '')
    {
        $event = InvestorRelationsCalendar::findOrFail($id);
        $event->is_delete = 1;
        $event->deleted_by  = Auth::user()->company_id;
        $event->update();
        $event->delete();

        return Redirect::route('package.admin.investor-relations-calendar')->with('global-danger', 'Event has been deleted.');
    }

    public function softDeleteMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
                
                            $events = InvestorRelationsCalendar::whereIn('id',$input['check'])->get();
                
                            if(count($events)){
                                foreach($events as $item){
                                    $event = InvestorRelationsCalendar::find($item['id']);
                                    $event->is_delete = 1;
                                    $event->deleted_by = \Auth::user()->id;
                                    $event->update();
                                }
                            }

            $delete = InvestorRelationsCalendar::whereIn('id',$input['check']);
            if($delete->delete())
            {
                return redirect()->route('package.admin.investor-relations-calendar')->with('global-danger', 'Event(s) deleted.');
            }
        }
    }

    public function publishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $event = InvestorRelationsCalendar::whereIn('id',$input['check'])->update(['is_active' => '1']);

            return redirect()->route('package.admin.investor-relations-calendar')->with('global', 'Event(s) published.');

        }
    }

    public function unPublishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $event = InvestorRelationsCalendar::whereIn('id',$input['check'])->update(['is_active' => '0']);

            return redirect()->route('package.admin.investor-relations-calendar')->with('global', 'Event(s) unpublished.');

        }
    }
}