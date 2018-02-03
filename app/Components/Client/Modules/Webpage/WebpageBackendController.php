<?php

namespace App\Components\Client\Modules\Webpage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Components\Finfo\Modules\Clients\Company;
use Carbon\Carbon;
use DB;
use Auth;
use Session;
use App\Components\Client\Modules\Webpage\Pages;
use App\Components\Client\Modules\Users\User;

class WebpageBackendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(\Auth::check() && \Auth::user()->user_type_id != 5 && \Auth::user()->user_type_id != 6)
        {
            App::abort(403);
        }
    }
    public function listAllPages(Request $request)
    {
        $getPageDatas = Webpage::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.*', 'company.company_name') 
                        ->where('content.is_delete', '=', 0)
                        ->where('content.ordering', '!=', 0)
                      
                        ->where('company.id','=', \Auth::user()->company_id)
                        
                        ->orderby('content.ordering', 'asc')
                        ->get();
                        
                        
        $index = 0;
        foreach($getPageDatas as $page) {
            $getUpdateUserData = User::findorNew($page->updated_by);
            $getCreateUserData = User::findorNew($page->created_by);
            $getPageDatas[$index]['created_by_user'] = $getCreateUserData->first_name." ".$getCreateUserData->last_name ;
            $getPageDatas[$index]['updated_by_user'] = $getUpdateUserData->first_name." ".$getUpdateUserData->last_name ;
            $index++;
        }
        return $this->view('list')->with('pageData', $getPageDatas);
    }
    public function createPage(Request $request) {
        return $this->view('create');
    }
    private function checkLimitPage() {
        $getPageData  = Webpage::where('content.company_id', \Auth::user()->company_id )
                        ->where('content.is_delete', '=', 0)
                        ->count();
                        
       $package = DB::table('company_subscription_package')->where('company_id',Session::get('company_id'))->orderBy('id', 'desc')->limit(1)->get();
       
       foreach($package as $package) {
           
          $packageid = $package->package_id;
      
       }
    if($packageid == 1){
        if ($getPageData == 3) {
            return true;  
        }      
       }
        
        
        return false;
    }
    public function savePage(Request $request) {
        $validate = Validator::make($request->all(), [
            'title'     => 'required|min:2|max:50',
            'body'    => 'required',
            'order' => 'numeric'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors( $validate->errors())->withInput();
        } else {
            if ($this->checkLimitPage() == true){
                return redirect()->route('client.webpage.backend.list')->with('page_maximum', trans("crud.errors.page.maximum"));            
            }
            $getPageData  = Webpage::where('title', $request->input('title'))
                    ->orWhere('content.name', strtolower($request->input('title')))
                    ->where('content.company_id', \Auth::user()->company_id )
                    ->where('content.is_delete', '=', 0)
                    ->get()
                    ->first();
            $id = $this->storePageData($request->all(), $getPageData);
            return redirect()->route('client.webpage.backend.list')->with('page_success_added', trans("crud.success.page.created"));            
        }
    }
    private function getLastOrderOfPage() {
        $pageData = Webpage::where('company_id', \Auth::user()->company_id )->orderby('content.ordering', 'desc')->where('content.is_delete', '=', 0)->limit(1)->get();
        
        
        
        
        $ordering = 0;
        foreach($pageData as $page) {
            $ordering = $page->ordering;
        }
        return $ordering;
    }
    private function storePageData($data, $getPage = null){
        
        
                            if(Input::get('preview')) 
                                
                                {
                                    $webpage = new Webpage();
                                    if (!empty($getPage)) {   
                                        $webpage->name = strtolower(str_replace(' ', "-", $data['title']))."-".  rand(1, 9);
                                    }else{
                                        $webpage->name = strtolower(str_replace(' ', "-", $data['title']));
                                    }
                                    $ordering = $data['order'];
                                    if ($data['order'] == 0) {
                                        $ordering = $this->getLastOrderOfPage() + 1;
                                    }
                                    $webpage->title = $data['title'];
                                    $webpage->ordering = $ordering;
                                    $webpage->meta_keyword = $data['meta'];
                                    $webpage->meta_description =  $data['description'];
                                    $webpage->content_description = $data['body'];
                                    /*$webpage->is_active = $data['status'];*/
                                    
                                     $webpage->is_active = 2;
                                    $webpage->company_id = \Auth::user()->company_id;
                                    $webpage->created_by = \Auth::user()->id;
                                    
                                    
                                }
                                
                                
                                 else
                                    {
                                        
                                        
                                        $getData = Webpage::where('meta_keyword', '=', Input::get('meta'))
                                            ->where('is_active','=',2)
                                            ->where('company_id','=', Auth::user()->company_id)
                                            ->count();
                                            if($getData > 0)
                                            {
                                                 $press = Webpage::where('meta_keyword', '=', Input::get('meta'))
                                                 ->where('is_active','=',2)
                                                ->where('company_id','=', Auth::user()->company_id)
                                                ->first();
                                                 $webpage = Webpage::find($press->id);
                                             
                                            }
                                
                                
                                
                                else
                                            {
                                                  $webpage = new Webpage;
                                            }
                                    
                                    
                                    
                                    
                                    {
                                        
                                        
                                        
                                      
                                    if (!empty($getPage)) {   
                                        $webpage->name = strtolower(str_replace(' ', "-", $data['title']))."-".  rand(1, 9);
                                    }else{
                                        $webpage->name = strtolower(str_replace(' ', "-", $data['title']));
                                    }
                                    $ordering = $data['order'];
                                    if ($data['order'] == 0) {
                                        $ordering = $this->getLastOrderOfPage() + 1;
                                    }
                                    $webpage->title = $data['title'];
                                    $webpage->ordering = $ordering;
                                    $webpage->meta_keyword = $data['meta'];
                                    $webpage->meta_description =  $data['description'];
                                    $webpage->content_description = $data['body'];
                                   $webpage->is_active = $data['status'];
                                    
                                    $webpage->company_id = \Auth::user()->company_id;
                                    $webpage->created_by = \Auth::user()->id;
                                        
                                        
                                        
                                    }
                                
                             }  
                                
                                    
                                    
                                    
        $webpage->save();
        return $webpage->id;
    }
    public function editPage($id) {
        $getPageData = Webpage::find($id);
        return $this->view('edit')->with('pageData', $getPageData);
    }
    private function checkNameAlreadyExist($id, $name) {
        $getResultData = Webpage::where('id', '!=' , $id)
                        ->where('company_id', '=',\Auth::user()->company_id )
                        ->where('name', '=', $name)
                        ->where('content.is_delete', '=', 0)
                        ->get()->first();
        if (!empty($getResultData)) {
           return true;
        }
        return false;
    }
    public function updatePage($id, Request $request) {
        
    
        
        
        $validate = Validator::make($request->all(), [
            'title'     => 'required|min:2|max:50',
            'body'    => 'required',
            'order' => 'numeric'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        } else {
            $getStatus = $this->checkNameAlreadyExist($id, $request->input('name'));    
            if ($getStatus == true) {
                $validate->errors()->add('name', 'The Page Url has already been taken.');
                return redirect()->back()->withErrors($validate->errors())->withInput();
            }
                                       
                                       
                                if(Input::get('preview')) 
                                {
                                    $webpage = new Webpage();
                                    if (!empty($getPage)) {   
                                        $webpage->name = strtolower(str_replace(' ', "-", $request->input('title')))."-".  rand(1, 9);
                                    }else{
                                        $webpage->name = strtolower(str_replace(' ', "-", $request->input('title')));
                                    }
                                    $ordering = $request->input('order');
                                    if ($request->input('order') == 0) {
                                        $ordering = $this->getLastOrderOfPage() + 1;
                                    }
                                    $webpage->title = $request->input('title');
                                    $webpage->ordering = $ordering;
                                    $webpage->meta_keyword = $request->input('meta');
                                    $webpage->meta_description =  $request->input('description');
                                    $webpage->content_description = $request->input('body');
                                    /*$webpage->is_active = $data['status'];*/
                                    
                                     $webpage->is_active = 2;
                                    $webpage->company_id = \Auth::user()->company_id;
                                    $webpage->created_by = \Auth::user()->id;
                                    $webpage->save();
                                       
                                       
                                       
                                }
                                
                                
                                else
                                
                                
                                {
                                    
                                    
                                        $webpage = Webpage::findOrFail($id); 
                                        
                                        
                                        
                                        $webpage->title = $request->input('title');
                                        $webpage->name = str_replace(' ', "-", $request->input('name'));
                                        $webpage->ordering = ($request->input('order') == 0) ? 1 : $request->input('order');
                                        $webpage->meta_keyword =  $request->input('meta');
                                        $webpage->meta_description =  $request->input('description');
                                        $webpage->content_description = $request->input('body');
                                        $webpage->is_active = $request->input('status');
                                        $webpage->company_id = \Auth::user()->company_id;
                                        $webpage->updated_by = \Auth::user()->id;
                                        $webpage->update();
                                        
                                         $getDels = DB::table('content')->where('is_active','=',2)->get();
                                     
                                     
                                                    foreach($getDels as $detdel)
                                                        {
                                   
                                                              $pressdelet= DB::table('content')->where('id','=',$detdel->id)->delete();
                                                        }
                                    
                                }
            return redirect()->back()->with('page_success_updated', trans("crud.success.page.updated"));
        }
    }
    public function getSoftDelete($id = '')
    {
        $webpage = Webpage::findOrFail($id);
        $webpage->is_delete = 1;
        $webpage->name = $webpage->name . "_" . Carbon::now();
        $webpage->deleted_by = \Auth::user()->id;
        $webpage->save();
        $webpage->delete();
        return Redirect::route('client.webpage.backend.list')->with('page_success_deleted', trans("crud.success.page.deleted"));
    }
    public function softDeleteMulti(Request $request)
    {
        if(is_array($request->input('check'))) {
            $webpages = Webpage::whereIn('id', $request->input('check'))->get();
            if(count($webpages)){
                foreach($webpages as $item){
                    $page = Webpage::find($item['id']);
                    $page->name = $page->name . "_" . Carbon::now();
                    $page->is_delete = 1;
                    $page->deleted_by = \Auth::user()->id;
                    $page->update();
                }
            }
            $delete = Webpage::whereIn('id', $request->input('check'));
            if($delete->delete()) {
                return redirect()->route('client.webpage.backend.list')->with('page_success_deleted', trans("crud.success.page.multi_deleted"));
            }
        }
    }
    public function publishMulti(Request $request)
    {
        if(is_array($request->input('check'))) {
            Webpage::whereIn('id', $request->input('check'))->update(['is_active' => '1']);
            return redirect()->route('client.webpage.backend.list')->with('page_success_added', trans("crud.success.page.multi_publish"));
        }
    }
    public function unPublishMulti(Request $request)
    {
        if(is_array($request->input('check'))) {
            Webpage::whereIn('id', $request->input('check'))->update(['is_active' => '0']);
            return redirect()->route('client.webpage.backend.list')->with('page_success_deleted', trans("crud.success.page.multi_unpublish"));
        }
    }
    public function saveUploadImages() 
    {
        $data = Input::all();
        $destinationPath = "content/images/";
        $file       = $data['file'];
        $filename   = $_FILES['file']['name'];
        $full_path  = $destinationPath.$filename;
        $file->move($destinationPath, $filename);
        return $full_path;
    }

    public function UploadFeatureImages() 
    {
        $data = Input::all();
        $destinationPath = "img/client/webpage/feature-images/";
        $file       = $data['feature_image'];
        $filename   = $_FILES['feature_image']['name'];
        $file->move($destinationPath, $filename);
        return $filename;
    }







    
   /* session data start*/
    
        public function sessiondtata(Request $request) 
        {
      
        $request->session()->put('formdata', $request->all());
        

        $request->session()->save(); //manually save session
        return response()->json(['message' => $request->session()->get('formdata')], 200);
        
        }
    
      /* session data end*/
    
   public function pages()
   {
       
    $company = DB::table('company_subscription_package')
              ->where('company_id',\Auth::user()->company_id)
              ->first();
       
     $menu_per = DB::table('module')
                ->join('package_module', 'module.id', '=', 'package_module.module_id')
                ->where('package_module.package_id','=', $company->package_id)
                ->where('module.id','!=', '12')->where('module.id','!=', '11')->where('module.id','!=', '7')->get();
       
        
                
     $menu_pers = DB::table('menu_permissions')->where('company_id',\Auth::user()->company_id)->where('menus_id','!=', '12')->where('menus_id','!=', '7')->where('menus_id','!=', '11')->lists('menus_id');
     
    //return print_r(in_array('menus_id',$menu_pers));
    return $this->view('pages')->with('menu_per',$menu_per)->with('menu_pers',$menu_pers);
   }
   public function page_index(Request $request)
   {
     //return $request->all();
    //$page = DB::table('menu_permissions')->where('',$request->input('checkbox'))
  //  $menu_per = DB::table('module')->get();
     
     $datas = DB::table('menu_permissions')->where('company_id',$request->input('company_id'))->get();
      

    foreach ($datas  as $data) 
      {
        //print_r ($value1);
        $del = DB::table('menu_permissions')->where('id',$data->id)->delete();
      }  

      if($request->input('checkbox'))
        {
            foreach ($request->input('checkbox') as $key => $value)
                {
                  
            DB::table('menu_permissions')
                    ->insert([
                        'menus_id' => $value,
                        'company_id' => $request->input('company_id')
                        ]);
                
       
                }
              
        }
      
        return redirect()->route('client.webpage.backend.pages'); 
   }

}
