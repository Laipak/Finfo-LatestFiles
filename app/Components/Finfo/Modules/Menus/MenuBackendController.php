<?php

namespace App\Components\Finfo\Modules\Menus;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use Carbon\Carbon;
use App\Components\Finfo\Modules\Webpage\Webpage;

class MenuBackendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(\Auth::check() && \Auth::user()->user_type_id != 3)
        {
            App::abort(403);
        }
    }

    public function index()
    {
        $data = Menu::get();

        return $this->view('list')->with('data', $data)->with('controller', $this);
    }
    private function getAllPageOfCompany(){
        $getPageDatas = Webpage::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.*', 'company.company_name') 
                        ->where('content.is_delete', '=', 0)
                        ->where('company.id','=', \Auth::user()->company_id)
                        ->get();
        return $getPageDatas;
    }
    public function create($id = '') 
    {
        if($id != ""){
            $data = Menu::findOrFail($id);
            return $this->view('edit')->with('data', $data)->with('pageData', $this->getAllPageOfCompany());
        }
        return $this->view('create')->with('pageData', $this->getAllPageOfCompany());
    }
    public function store($id = '')
    {
        if($id == ''){
            $data = Input::all();
            $menu = new Menu();
            $menu->title    = $data['title'];
            $menu->link     = $data['link'];
            $menu->ordering = $data['ordering'];
            $menu->company_id   = \Auth::user()->company_id;
            $menu->status       = 1;
            $menu->created_at   = Carbon::now();
            $menu->created_by   = \Auth::user()->id;
            $menu->save();
            return Redirect::route('finfo.menus.backend.list')->with('global', 'Menu has been created.');
        }else{
            $data = Input::all();
            $menu = Menu::findOrFail($id);
            $menu->title    = $data['title'];
            $menu->link     = $data['link'];
            $menu->ordering = $data['ordering'];
            $menu->updated_by   = \Auth::user()->id;
            $menu->update();
            return Redirect::route('finfo.menus.backend.list')->with('global', 'Menu has been updated.');
        }
        
    }

    public function deleteMenu($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return Redirect::route('finfo.menus.backend.list')->with('global-deleted', 'Menu has been deleted.');
    }

    public function deleteMenuMulti(){
        $input = Input::all();
        if(is_array($input['check']))
        {
            $delete = Menu::whereIn('id',$input['check']);
            if($delete->delete())
            {
                return redirect()->route('finfo.menus.backend.list')->with('global-deleted', 'Menu(s) has been deleted.');
            }
        }
    }

    public function unpublicMenuMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $users = Menu::whereIn('id',$input['check'])->update(['status' => '0']);

            return redirect()->route('finfo.menus.backend.list')->with('global', 'Menu(s) has been unpublished.');

        }
    }

    public function publicMenuMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $users = Menu::whereIn('id',$input['check'])->update(['status' => '1']);

            return redirect()->route('finfo.menus.backend.list')->with('global', 'Menu(s) has been published.');

        }
    }

    public function getStatus($id)
    {
        if($id == 1){
            return "Active";
        }else{
            return "Inactive";
        }
    }
}
