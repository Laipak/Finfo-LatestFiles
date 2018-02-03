<?php

namespace App\Components\Finfo\Modules\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Hash;
use Mail;
use Carbon\Carbon;
use Session;
use Auth;

class UserBackendController extends Controller
{
     public function __construct()
     {
        $this->middleware('auth');

        if(\Auth::check() && \Auth::user()->user_type_id != 3)
        {
            App::abort(403);
        }
     }

    public function index(Request $request)
    {
        $limit = 5;
        $data['user'] = User::where('is_delete', '=','0')->where('company_id','=',1)->get();

        return $this->view('list')->with('data', $data)->with('controller',$this);
    }

    public function getUser($id)
    {
        if($id){
            $user = User::select('first_name','last_name')->where('id','=',$id)->first();
            if($user){
                return $user->first_name . " " . $user->last_name;
            }
        }
    }

    public function softDeleteMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {

            $users = User::whereIn('id',$input['check'])->get();

            if(count($users)){
                foreach($users as $item){
                    $user = User::find($item['id']);
                    $user->email_address = $user->email_address . "_" . Carbon::now();
                    $user->is_delete = 1;
                    $user->deleted_by = \Auth::user()->id;
                    $user->save();
                }
            }
            $deletedItems = array_diff($input['check'], array(\Auth::user()->id));
            $delete = User::whereIn('id', $deletedItems);
            if($delete->delete())
            {
                return redirect()->route('finfo.user.backend.list')->with('global-deleted', 'User(s) has been deleted.');
            }else{
                return redirect()->route('finfo.user.backend.list')->with('global-deleted', 'You can not delete this user.');
            }
        }elseif($input['check'] == \Auth::user()->id ){
            return redirect()->route('client.user.backend.list')->with('global-deleted', 'You can not delete this user.');
        }
    }

    public function publishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $users = User::whereIn('id',$input['check'])->update(['is_active' => '1']);

            return redirect()->route('finfo.user.backend.list')->with('global', 'User(s) has been published.');

        }
    }

    public function unPublishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $users = User::whereIn('id',$input['check'])->update(['is_active' => '0']);

            return redirect()->route('finfo.user.backend.list')->with('global', 'User(s) has been unpublished.');

        }
    }

    public function getStatus($id)
    {
        if($id){
            $user = User::select('verify_token','is_active')->where('id','=',$id)->first();

            if($user){
                if($user->verify_token != "")
                    return "Not verify yet";

                if($user->is_active == 1)
                    return "Active";
                else
                    return "Inactive";
            }
        }
    }
    
    public function getUserType($id)
    {
        if($id){
            $type = UserType::select('name')->where('id','=',$id)->first();
            if($type){
                return $type->name;
            }

            return null;
        }
    }

    public function getCreate()
    {
        $data = [];
        $data['user_type'] = UserType::whereIn('id', [3,4])->lists('name', 'id');

        return $this->view('create-user')->with('data', $data);
    }

    public function getEdit($id = '')
    {
        $data = [];
        $data['user_type'] = UserType::whereIn('id', [3,4])->lists('name', 'id');
        
        $data['user'] = User::find($id);
        return $this->view('edit-user')->with('data', $data);
    }

    public function postSave()
    {

        $validate = Validator::make(Input::all(), [
            'first_name'    => 'required|min:2|max:50',
            'last_name'     => 'required|min:2|max:50',
            'email_address' => 'required|min:5|max:50|email',
            'user_type'     => 'required',
            'password'      => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }

        $value = Input::all();
        $activation_code = md5(time().rand());

        $user = User::where('email_address','=',$value['email_address'])->where('company_id', 1)->first();
        if(!$user){
            $data = new User;
            $data->first_name = $value['first_name'];
            $data->last_name = $value['last_name'];
            $data->email_address = $value['email_address'];
            $data->user_type_id = $value['user_type'];
            $data->password = Hash::make($value['password']);
            $data->company_id = 1;
            $data->verify_token = $activation_code;
            $data->created_by = \Auth::user()->id;
            $data->updated_by = \Auth::user()->id;

            $data->save();

            $link = route('finfo.user.frontend.verify',$activation_code);
            $login = route('finfo.admin.login');

            $data['from_email'] = $this->getFromFinfoEmail();

            Mail::queue('resources.views.emails.verify', array('data' => $data, 'value' => $value, 'link' => $link, 'login' => $login), function ($message) use($data) {
                $message->subject("FINFO: Account Verification");
                $message->from($data['from_email'], 'FINFO');

                $message->to($data->email_address);
            });

        }else{
            return redirect()->back()->with('global', 'User Email is already existed.');
        }

        return redirect()->route('finfo.user.backend.list')->with('global', 'User has been created.');
    }

    public function postUpdate($id = '')
    {
        $validate = Validator::make(Input::all(), [
            'first_name' => 'required|min:2|max:50',
            'last_name' => 'required|min:2|max:50',
            'email_address' => 'required|min:5|max:50|email',
            'user_type_id' => 'required',
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }

        $data = Input::all();
        if(!isset($data['is_active'])){
            $data['is_active'] = 0;
        }
        $data['updated_by'] =  \Auth::user()->id;
        $user = User::findOrFail($id);
        $user->update($data);

        return Redirect::route('finfo.user.backend.list')->with('global', 'User has been updated.');
    }

    public function getSoftDelete($id = '')
    {
        if ($id == \Auth::user()->id ) {
            return Redirect::route('finfo.user.backend.list')->with('global-deleted', 'You can not delete this user.');
        }
        $user = User::findOrFail($id);
        $user->update(['is_delete' => 1]);
        $user->email_address = $user->email_address . "_" . Carbon::now();
        $user->save();
        $user->delete();

        return Redirect::route('finfo.user.backend.list')->with('global-deleted', 'User has been deleted.');
    }

    // Get user's information
    public function getProfile()
    {
        if(\Auth::id())
        {
            $user = User::findOrFail(\Auth::id());
            return $this->view('profile')->with(compact('user'));
        }
    }

    //
    public function postProfile()
    {
        $validate = Validator::make(Input::all(), [
            'first_name' => 'required|min:2|max:50',
            'last_name' => 'required|min:2|max:50',
            'email_address' => 'required|min:5|max:50|email',
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }

        $data = array(
            'first_name' => Input::get('first_name'),
            'last_name' => Input::get('last_name'),
            'email_address' => Input::get('email_address'),
        );

        $user = User::findOrFail(\Auth::id());
        $user->update($data);

        Session::flash('message', 'Profile has been updated successfully!');
        return redirect('admin/users/profile');

    }

    public function postCheckExitEmail()
    {
        $id = Input::get('user_id');

        if($id == ""){
            $email = User::Where('email_address', Input::get('email_address'))->where('company_id', 1)->first();
        }else{
            $email = User::Where('email_address', Input::get('email_address'))->where('company_id', 1)->whereNotIn('id', [$id])->first();
        }
        
        if( count($email) >= 1 ){
            echo 'false';
        }
        else{
            echo 'true';
        }
    }

    public function doProfileUpdatePassword()
    {
        $id = Auth::user()->id;
        $validate = Validator::make(Input::all(), [
            'current_password'          => 'required|min:6|max:15',
            'new_password'              => 'required|min:8|max:15|confirmed',
            'new_password_confirmation' => 'required|min:8|max:15',
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
        $user = User::FindOrFail($id);
        $password = $user->password;

        if (Hash::check(Input::get('current_password'), $password))
        { 
            $user->password = Hash::make(Input::get('new_password'));

            if($user->save())
                return redirect('admin/users/profile')->with('message', 'Password updated');
        }else{
            return redirect()->back()->with('message-error', 'Incorrect current password');
        }
    }

}
