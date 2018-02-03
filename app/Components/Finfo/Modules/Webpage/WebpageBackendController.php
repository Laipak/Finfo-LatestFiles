<?php

namespace App\Components\Finfo\Modules\Webpage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Components\Finfo\Modules\Clients\Company;
use Carbon\Carbon;
use App\Components\Finfo\Modules\Users\User;

class WebpageBackendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(\Auth::check() && \Auth::user()->user_type_id != 3)
        {
            App::abort(403);
        }
    }
    public function listAllPages(Request $request)
    {
        
        
     
        $getPageDatas = Webpage::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.*', 'company.company_name') 
                        ->where('content.is_delete', '=', 0)
                        ->where('company.id','=', \Auth::user()->company_id)
                        ->get();
        $index = 0;
        foreach($getPageDatas as $page) {
            if (!empty($page->updated_by)) {
                $modifyUsersInfo = User::find($page->updated_by);
                if (!empty($modifyUsersInfo)) {
                    $getPageDatas[$index]['updated_by_user'] = $modifyUsersInfo->first_name." ". $modifyUsersInfo->last_name;
                }
            }
            if (!empty($page->created_by)) {
                $createdUsersInfo = User::find($page->created_by);
                if (!empty($createdUsersInfo)) {
                    $getPageDatas[$index]['create_by_user'] = $createdUsersInfo->first_name." ". $createdUsersInfo->last_name;
                }
            }
            $index++;
        }
        return $this->view('list')->with('pageData', $getPageDatas);
    }
    public function createPage(Request $request) {
        return $this->view('create');
    }
    public function savePage(Request $request) {
        $validate = Validator::make($request->all(), [
            'title'     => 'required|min:2|max:50',
            'body'    => 'required',
            'order'    => 'numeric',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors( $validate->errors())->withInput();
        }else{
            $getPageData  = Webpage::where('title', $request->input('title'))
                            ->orwhere('content.name','=', strtolower($request->input('title')))
                            ->where('content.company_id','=', \Auth::user()->company_id)
                            ->where('content.is_delete', '=', 0)->get()->first();
            $id = $this->storePageData($request->all(), $getPageData );
            return redirect()->route('finfo.webpage.backend.list')->with('page_success_added', trans("crud.success.page.created"));
        }
    }
    private function storePageData($data, $getPage = null){
        $webpage = new Webpage();
         if (!empty($getPage)) { 
            $webpage->name = strtolower(str_replace(' ', "-", $data['title']))."-".  rand(1, 9);
        }else{
            $webpage->name = strtolower(str_replace(' ', "-", $data['title']));
        }
        $webpage->title = $data['title'];
        $webpage->ordering = $data['order'];
        $webpage->meta_keyword = $data['meta'];
        $webpage->meta_description =  $data['description'];
        $webpage->content_description = $data['body'];
        $webpage->is_active = $data['status'];
        $webpage->company_id = \Auth::user()->company_id;
        $webpage->created_by = \Auth::user()->id;
        $webpage->save();
        return $webpage->id;
    }
    public function editPage($id) {
        $getPageData = Webpage::find($id);
        return $this->view('edit')->with('pageData', $getPageData);
    }
    
    private function checkNameAlreadyExist($id, $name) {
       $getResultData = Webpage::where('id', '!=' , $id)
                        ->where('company.id','=', \Auth::user()->company_id)
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
            'order'    => 'numeric',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        } else {
            $getStatus = $this->checkNameAlreadyExist($id, $request->input('name'));    
            if ($getStatus == true) {
                $validate->errors()->add('name', 'The Page Url has already been taken..');
                return redirect()->back()->withErrors($validate->errors())->withInput();
            }
            $webpage = Webpage::findOrFail($id); 
            $webpage->title = $request->input('title');
            $webpage->name = str_replace(' ', "-", $request->input('name'));
            $webpage->ordering = ($request->input('order') == 0) ? 1 : $request->input('order') ;
            $webpage->meta_keyword =  $request->input('meta');
            $webpage->meta_description =  $request->input('description');
            $webpage->content_description = $request->input('body');
            $webpage->is_active = $request->input('status');
            $webpage->company_id = \Auth::user()->company_id;
            $webpage->updated_by = \Auth::user()->id;
            $webpage->update();
            return redirect()->back()->with('page_success_updated', trans("crud.success.page.updated"));
        }
    }
    public function getSoftDelete($id = '')
    {
        $webpage = Webpage::findOrFail($id);
        $webpage->update(['is_delete' => 1]);
        $webpage->name = $webpage->name . "_" . Carbon::now();
        $webpage->save();
        $webpage->delete();
        return Redirect::route('finfo.webpage.backend.list')->with('page_success_deleted', trans("crud.success.page.deleted"));
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
                return redirect()->route('finfo.webpage.backend.list')->with('page_success_deleted', trans("crud.success.page.multi_deleted"));
            }
        }
    }
    public function publishMulti(Request $request)
    {
        if(is_array($request->input('check'))) {
            Webpage::whereIn('id', $request->input('check'))->update(['is_active' => '1']);
            return redirect()->route('finfo.webpage.backend.list')->with('page_success_added', trans("crud.success.page.multi_publish"));
        }
    }
    public function unPublishMulti(Request $request)
    {
        if(is_array($request->input('check'))) {
            Webpage::whereIn('id', $request->input('check'))->update(['is_active' => '0']);
            return redirect()->route('finfo.webpage.backend.list')->with('page_success_deleted', trans("crud.success.page.multi_unpublish"));
        }
    }
    public function saveUploadImages() {
        $data = Input::all();
        $destinationPath = "content/images/";
        $file       = $data['file'];
        $filename   = $_FILES['file']['name'];
        $full_path  = $destinationPath.$filename;
        $file->move($destinationPath, $filename);
        return $full_path;
    }
    public function checkCompanyWebpageIsExist(){
        $getWebpage = Webpage::where('company_id', '=', 1)->where('is_delete', '=', 0)->get();
        foreach($getWebpage as $page) {
            if (strtolower($page->name) == strtolower(str_replace("/", "",$_SERVER['REQUEST_URI'])) ) {
               return true;
            }
        }
        return false;
    }
}
