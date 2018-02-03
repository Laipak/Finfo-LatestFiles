<?php

namespace App\Components\Client\Modules\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Hash;
use Session;
use Carbon\Carbon;
use Mail;

class UserBackendController extends Controller
{
     public function __construct()
     {
        $this->middleware('auth');

        if(\Auth::check() && \Auth::user()->user_type_id != 5)
        {
            App::abort(403);
        }
     }

    // Get user list
    public function index(Request $request)
    {
        $limit = 5;
        $user = new User();
        $companyId  = $user->getCompanyID(Session::get('account'));

        $data['user'] = $user
                        ->where('company_id', $companyId)
                        ->get();

        return $this->view('list')->with('data', $data)->with('controller',$this);
    }
    
    // Get create user
    public function getCreate()
    {
        $data = [];
        $data['user_type'] = UserType::whereIn('id', [5,6])->lists('name', 'id');

        return $this->view('create-user')->with('data', $data);
    }

    // Get edit user
    public function getEdit($id = '')
    {
        $data = [];
        $data['user_type'] = UserType::whereIn('id', [5,6])->lists('name', 'id');
        
        $data['user'] = User::find($id);
        return $this->view('edit-user')->with('data', $data);
    }

    /* This function will be used with save user 
        - params (first_name, last_name, email_address, user_type, password, password_confirmation)
        - check validation
        - insert user info
        - return result
    */
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
        $data = new User;
        $data->first_name = $value['first_name'];
        $data->last_name = $value['last_name'];
        $data->email_address = $value['email_address'];
        $data->user_type_id = $value['user_type'];
        $data->password = Hash::make($value['password']);
        $data->company_id = Session::get('company_id');
        $data->verify_token = $activation_code;
        $data->created_by = \Auth::user()->id;
        $data->updated_by = \Auth::user()->id;
        $data->save();
        
        $link = route('client.user.frontend.verify',$activation_code);
        $login = route('client.login');
        $datas = array(
                'data' => $data,
                'company_name' => strtoupper(Session::get('company_name'))
            );
        $datas['from_email'] = $this->getFromFinfoEmail();

        Mail::queue('resources.views.emails.user_client_verify', array('data' => $data, 'value' => $value, 'link' => $link, 'login' => $login, 'company' => strtoupper(Session::get('company_name'))), function ($message) use($datas) {
            $message->subject($datas['company_name'].": Account Verification");
            $message->from($datas['from_email'], $datas['company_name']);
            $message->to($datas['data']->email_address);
        });

        return Redirect::route('client.user.backend.list')->with('global', 'User has been created.');
    }

    /* This function will be used with update user 
        - params (first_name, last_name, email_address, user_type)
        - check validation
        - insert user info
        - return result
    */
    public function postUpdate($id = '')
    {
        $validate = Validator::make(Input::all(), [
            'first_name' => 'required|min:2|max:50',
            'last_name' => 'required|min:2|max:50',
            'user_type_id' => 'required',
        ]);

        if ($validate->fails()){
            return redirect()->back()->withErrors($validate->errors())->withInput();
        } else {
            $data = Input::all();
            $dataUpdate['is_active'] = 1;
            if (isset($data['password']) && !empty($data['password'])) {
                $dataUpdate['password'] = Hash::make($data['password']);
            }
            $user = User::findOrFail($id);
            if (isset($data['email_address']) && !empty($data['email_address'])) {
                $dataUpdate['email_address'] = $data['email_address'];
                if(!isset($data['is_active'] )){
                    $dataUpdate['is_active'] = 0;
                }
            }
            
            $dataUpdate['first_name'] = $data['first_name'];
            $dataUpdate['last_name'] =  $data['last_name'];
            $dataUpdate['user_type_id'] = $data['user_type_id'];
            $user->update($dataUpdate);
            return Redirect::route('client.user.backend.list')->with('global', 'User has been updated.');
        }
    }

    public function getSoftDelete($id = '')
    {
        if ($id == \Auth::user()->id ) {
            return Redirect::route('client.user.backend.list')->with('global-deleted', 'You can not delete this user.');
        }
        $user = User::findOrFail($id);
        $user->update(['is_delete' => 1]);
        $user->email_address = $user->email_address . "_" . Carbon::now();
        $user->save();
        $user->delete();

        return Redirect::route('client.user.backend.list')->with('global-deleted', 'User has been deleted.');
    }

    public function postCheckExitEmail()
    {
        $id = Input::get('user_id');
        if($id == ""){
            $email = User::Where('email_address', Input::get('email_address'))->where('company_id', Session::get('company_id'))->first();
        }else{
            $email = User::Where('email_address', Input::get('email_address'))->where('company_id', Session::get('company_id'))->whereNotIn('id', [$id])->first();
        }
        
        if( count($email) >= 1 ){
            echo 'false';
        }
        else{
            echo 'true';
        }
    }


    public function getStatus($id)
    {
        if($id){
            $user = User::select('verify_token','is_active')->where('id','=',$id)->first();

            if($user){
                if(!empty($user->verify_token)){
                    $extraAction = "Inactive";
                    if ($user->is_active == 1) {
                        $extraAction = "Active";
                    }
                    return "Not verify yet (".$extraAction.")";
                } elseif($user->is_active == 1){
                    return "Active";
                }else {
                    return "Inactive";
                }
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
                    if ($item['id'] != \Auth::user()->id) {
                        $user = User::find($item['id']);
                        $user->email_address = $user->email_address . "_" . Carbon::now();
                        $user->is_delete = 1;
                        $user->deleted_by = \Auth::user()->id;
                        $user->save();
                    }
                }
            }
            $deletedItems = array_diff($input['check'], array(\Auth::user()->id));
            $delete = User::whereIn('id', $deletedItems);
            if($delete->delete()){
                return redirect()->route('client.user.backend.list')->with('global-deleted', 'User(s) has been deleted.');
            }else{
                return redirect()->route('client.user.backend.list')->with('global-deleted', 'You can not delete this user.');
            }
        }elseif($input['check'] == \Auth::user()->id ){
            die("call me");
            return redirect()->route('client.user.backend.list')->with('global-deleted', 'You can not delete this user.');
        }
    }

    public function publishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $users = User::whereIn('id',$input['check'])->update(['is_active' => '1']);

            return redirect()->route('client.user.backend.list')->with('global', 'User(s) has been published.');

        }
    }

    public function unPublishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $users = User::whereIn('id',$input['check'])->update(['is_active' => '0']);

            return redirect()->route('client.user.backend.list')->with('global', 'User(s) has been unpublished.');
        }
    }
}
