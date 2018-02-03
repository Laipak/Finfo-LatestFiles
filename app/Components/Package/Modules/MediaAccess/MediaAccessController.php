<?php

namespace App\Components\Package\Modules\MediaAccess;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use Session;
use App\Components\Client\Modules\Company\Company;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Components\Client\Modules\Home\HomeController;

class MediaAccessController extends Controller
{
    protected $companyExpire = false;
    private $secretKey = "6Lf82hITAAAAAC_igYld6oIm8guG0FkTgRhm6a-n";
    
    public function __construct() { 
        $this->setConfigAuth();
        $homeController = new HomeController();
        if ($homeController->checkCompanyIsExpired() == true) {
            $this->companyExpire = true;
        }
    } 
    public function mediaLogout() {
       \Auth::logout();
       Session::forget('mediaAccessAuthKey');
       return redirect()->route('package.media-access'); 
    }
    public function getFrontend() {
        if ($this->companyExpire == true) {
            return redirect::to('/');
        }
        if (Auth::check() && Session::has('mediaAccessAuthKey')) {
            return redirect()->route('package.media-access.lists'); 
        }
        return $this->view('frontend.media-access');
    }
    public function doForgotPassword(Request $request){
        $validate = Validator::make($request->all(), ['email' => 'required|email']);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }else {
            $mediaAccessUserInfo = MediaAccess::where('email', $request->input('email'))->where('company_id', Session::get('company_id'))->where('status', 1)->get()->first();
            if (!empty($mediaAccessUserInfo)) {                
                $this->sendMediaAccessResetPassword($request->input('email'), $mediaAccessUserInfo);
                return redirect()->back()->with('mediaAccessSuccessSendForgotpassword', 'Reset password link was sent to your email.');
            }else{
                $validate->errors()->add('email', trans('crud.errors.mediaaccess.not_existing_email'));
                return redirect()->back()->withErrors($validate->errors())->withInput();
            }
        }
    }
    
    public function doResetPasswordForm(Request $request) {
        $validate = Validator::make($request->all(),[
                        'password'	=> 'required|min:6',
                        'confirm_password' => 'required|min:6|same:password']);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }else{
            $auth_token = \Crypt::decrypt($request->input('key'));
            $mediaAccessUser = $this->getCurrentMediaAccessUserInfor($auth_token);
            if (!empty($mediaAccessUser)) {
                $getUsersData = MediaAccess::findOrNew($mediaAccessUser->id);
                $getUsersData->password = Hash::make($request->input('password'));
                $getUsersData->auth_token =  md5($mediaAccessUser->email.rand());
                $getUsersData->update();
                return redirect()->route('package.media-access')->with('resetPasswordSuucess', 'Password have been reset, please login.');
            }else{
                $validate->errors()->add('confirm_password', trans('crud.errors.mediaaccess.access_deny'));
                return redirect()->back()->withErrors($validate->errors())->withInput();
            }  
        }
    }
    public function getResetPasswordForm() {
        if (Input::get('auth_key')) {
            return $this->view('frontend.reset-password')->with('auth_key', Input::get('auth_key') );
        }
        return redirect()->route('package.media-access');
    }
    private function getClientCompanyEmailAndName() {
        $getCompanyData = Company::findOrFail(Session::get('company_id'));
        return $getCompanyData;
    }
    private function sendMediaAccessResetPassword($email, $usersData) {
        $companyData = $this->getClientCompanyEmailAndName();
        $value = array(
                'user_email' => $email,
                'company_name' => $companyData->company_name,
                'company_email' => $companyData->email_address,
        );
        $resetpassword_link = route('package.media-access.reset-password', array('auth_key='. \Crypt::encrypt($usersData->auth_token)));
        Mail::queue('app.Components.Package.Modules.MediaAccess.views.email.resetpassword', array('resetpassword_link' => $resetpassword_link, 'userData' => $usersData, 'companyName' => $companyData->company_name), 
                    function ($message) use($value) {
                        $message->subject("Media Access Client Resetpassword");
                        $message->from($value['company_email'], ucfirst($value['company_name']));
                        $message->to($value['user_email']);
                    }
                );
        return true;
    }
    private function getMediaAccessFilesBelongToUsers($getCurrentUserInfo) {
        $mediaAccessUserInfo = MediaAccessFiles::join("media_access_permission", "media_access_files.id", "=", "media_access_permission.media_access_file_ids")
                            ->select('media_access_permission.user_id', 'media_access_files.*')
                            ->where('media_access_permission.user_id', $getCurrentUserInfo->id)
                            ->where('media_access_files.is_deleted', 0)
                            ->where('media_access_files.status', 0)
                            ->where('media_access_files.expire_date', '>', date('Y-m-d'))
                            ->get();
        return $mediaAccessUserInfo;
    }
    public function listsMedia() {
        if ($this->companyExpire == true) {
            return redirect::to('/');
        }
        if (Auth::check() && Session::has('mediaAccessAuthKey')) {
            $getCurrentUser = $this->getCurrentMediaAccessUserInfor(Session::get('mediaAccessAuthKey'));
            $getMediaFiles = $this->getMediaAccessFilesBelongToUsers($getCurrentUser);
            return $this->view('frontend.lists')->with(compact('getMediaFiles'));
        }
        return redirect()->route('package.media-acces'); 
    }
    public function getForgotPassword() {
        if (Auth::check() && Session::has('mediaAccessAuthKey')) {
           return $this->view('frontend.lists');
        }
        return $this->view('frontend.forgot-password')->with('auth_key', Input::get('auth_key'));
    }
    private function getCurrentMediaAccessUserInfor($mediaAccessAuthKey) {
        $mediaAccessUserInfo = MediaAccess::where('auth_token', $mediaAccessAuthKey)->where('company_id', Session::get('company_id'))->where('status', 1)->get()->first();
        return $mediaAccessUserInfo;
    }
    private function setConfigAuth() {
        \Config::set( 'auth.model' , 'App\Components\Package\Modules\MediaAccess\MediaAccess' );
        \Config::set( 'auth.table' , 'media_access_users' );
    }
    public function getLogin()
    {
        return $this->view('frontend.login');
    }
    public function doLogin(Request $request){
        if (Auth::check() && Session::has('mediaAccessAuthKey')) {
           return $this->view('frontend.lists');
        }
        $this->setConfigAuth();
        $validate = Validator::make($request->all(),[
                        'login_email'	=> 'required|email',
                        'login_password' => 'required|min:6']);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        } else {
            if (Auth::attempt(array('email' => $request->input('login_email'), 'password'=> $request->input('login_password'), 'status' => 1 ))){
                $mediaAccessUserInfo = MediaAccess::where('email', $request->input('login_email'))->where('company_id', Session::get('company_id'))->get()->first();
                Session::put('mediaAccessAuthKey', $mediaAccessUserInfo->auth_token);
                $mediaAccessUserInfo->last_login = Carbon::now()->format('Y-m-d h:m:s');
                $mediaAccessUserInfo->update();
                return redirect()->route('package.media-access.lists'); 
            }else{
                return redirect()->back()->withInput()->withErrors(['login_password' => 'Invalid Password']);
            }
        }
    }
    private function checkCaptCharCurlJson($url){
       $getResult = json_decode(file_get_contents($url), true);
       return $getResult['success'];
    }
    public function getThanksYou() {
        if (Auth::check() && Session::has('mediaAccessAuthKey')) {
           return $this->view('frontend.lists');
        }
        if (Session::get('data')) {
            return $this->view('frontend.thank-you');
        }else{
            return Redirect::route('package.media-access');
        }
    }
    private function checkExistEmailWithCompany($email, $companyId) {
        $getData = MediaAccess::where('email', $email )
                    ->where('company_id', $companyId )
                    ->get()
                    ->first();
        if (!empty($getData)) {
           return true;
        }else{
            return false;
        }
    }
    public function doRegister(Request $request){
        if (Auth::check() && Session::has('mediaAccessAuthKey')) {
           return $this->view('frontend.lists');
        }
        $validate = Validator::make($request->all(),[
                        'name'	=> 'required',
                        'phone_number' => 'numeric|min:6',
                        'user_email' => 'required|email|min:5',
                        'organization' => 'required|min:2',
                        'organization_phone' => 'numeric|min:6',
                        'organization_mobile' => 'numeric|min:6',
                        'g-recaptcha-response' => 'required',
                        'password' => 'required|min:8',
                        'confirm_password' => 'required|min:8|same:password'
                    ]);
        
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }else{
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$this->secretKey."&response=".$request->input('g-recaptcha-response')."&remoteip=".$_SERVER['REMOTE_ADDR'];
            if ($this->checkCaptCharCurlJson($url) == false) {
                return redirect()->back()->withErrors('g-recaptcha-response', 'something wrong' )->withInput();
            } else{
                $checkExistingEmail = $this->checkExistEmailWithCompany($request->input('user_email'), Session::get('company_id'));
                if ( $checkExistingEmail == false) {
                    $this->storeUserInformation($request->all());
                    return Redirect::route('package.media-access.thanks-you')->with('data', $request->input('name'));
                } else {
                    $validate->errors()->add('user_email', trans('crud.errors.mediaaccess.existing_email'));
                    return redirect()->back()->withErrors($validate->errors())->withInput();
                }
            }
        }
    }
    private function storeUserInformation($data){
        $mediaAccessUsers = new MediaAccess();
        $mediaAccessUsers->name = $data['name'];
        $mediaAccessUsers->designation = $data['designation'];
        $mediaAccessUsers->phone = $data['phone_number'];
        $mediaAccessUsers->email = $data['user_email'];
        $mediaAccessUsers->fax = $data['fax'];
        $mediaAccessUsers->password = Hash::make($data['password']);
        $mediaAccessUsers->auth_token = md5($data['user_email'].rand());
        $mediaAccessUsers->company_id = Session::get('company_id');
        $mediaAccessUsers->status = 0;
        $mediaAccessUsers->save();
        $mediaAccessOrganization = new MediaAccessOrganization();
        $mediaAccessOrganization->media_access_user_ids = $mediaAccessUsers->id;
        $mediaAccessOrganization->organization = $data['organization'];
        $mediaAccessOrganization->org_phone = $data['organization_phone'];
        $mediaAccessOrganization->org_mobile =$data['organization_mobile'];
        $mediaAccessOrganization->address =$data['address'];
        $mediaAccessOrganization->save();
        $getSetting = MediaAccessSettings::where('company_id', Session::get('company_id') )->get()->first();
        if ($getSetting->auto_approved == 1) {
            $updateUserStatus = MediaAccess::findOrNew($mediaAccessUsers->id);
            $updateUserStatus->status = 1;
            $updateUserStatus->update();
            $this->sendMediaAccessUserApprovedFrontend($mediaAccessUsers, $mediaAccessUsers->id );
        }
        if (!empty($getSetting->recipe_notify_email)) {
            $this->sendMediaAccessEmailNotifyToCompanyAdmin($mediaAccessUsers, $getSetting->recipe_notify_email );
        }
    }
    private function getBackendClientCompanyEmailAndName() {
        $getCompanyData = Company::findOrFail(Session::get('company_id'));
        return $getCompanyData;
    }
    
    private function sendMediaAccessEmailNotifyToCompanyAdmin($userData, $emailNotify) {
        $companyData = $this->getBackendClientCompanyEmailAndName();
        $value = array(
                    'user_email' => $userData->email ,
                    'user_full_name' =>$userData->name,
                    'admin_email' => $emailNotify,
                    'admin_name' => $companyData->company_name
                );
        Mail::queue('app.Components.Package.Modules.MediaAccess.views.email.admin-notify', array('userData'=> $userData, 'companyName' => $companyData->company_name), 
                    function ($message) use($value) {
                        $message->subject("Media access new user has register");
                        $message->from($value['user_email'], ucfirst($value['user_full_name']));
                        $message->to($value['admin_email']);
                    }
                );
    }
    private function sendMediaAccessUserApprovedFrontend($userData, $userId) {
        $companyData = $this->getBackendClientCompanyEmailAndName();
        $value = array(
                'user_email' => $userData->email,
                'company_name' => $companyData->company_name,
                'company_email' => $companyData->email_address,
        );
        $loginLink = route('package.media-access');
        Mail::queue('app.Components.Package.Modules.MediaAccess.views.email.user-approved', array('loginLink' => $loginLink, 'userData'=> $userData, 'companyName' => $companyData->company_name), 
                    function ($message) use($value) {
                        $message->subject("Media access approved client");
                        $message->from($value['company_email'], ucfirst($value['company_name']));
                        $message->to($value['user_email']);
                    }
                );
    }
    private function getDownloadLinkMediaAccessFile($id) {
        $getMediaFile = MediaAccessFiles::findOrNew($id);
        $pdfPath = '/files/media-access/'.Session::get('company_name').'/'.$getMediaFile->file_name;
        return $pdfPath;
    }
    public function downloadMediaAccessFiles(Request $request) {
        if (Auth::check() && Session::has('mediaAccessAuthKey')) {
            $getCurrentUser = $this->getCurrentMediaAccessUserInfor(Session::get('mediaAccessAuthKey'));
            $checkFilesDownloaded = MediaAccessDownload::where('media_access_file_ids', \Crypt::decrypt($request->input('file_id')))
                    ->where('user_id', $getCurrentUser->id)->get()->first();
            if (empty($checkFilesDownloaded)) {
                $mediaAccessDownload  = new MediaAccessDownload();
                $mediaAccessDownload->user_id = $getCurrentUser->id; 
                $mediaAccessDownload->media_access_file_ids = \Crypt::decrypt($request->input('file_id'));
                $mediaAccessDownload->download_at = Carbon::now();
                $mediaAccessDownload->save();
            }
           echo $this->getDownloadLinkMediaAccessFile(\Crypt::decrypt($request->input('file_id')));
        }
    }
    private function addMediaAccessSettingForCompany(){
        $getCompanyAndUserData = Company::join("user", "company.id", "=", "user.company_id")
                ->select('company.email_address','company.id as companyId', 'user.id') 
                ->groupby('companyId')
                ->get();
        foreach($getCompanyAndUserData as $data) {
            $mediaAccessSettings = new MediaAccessSettings(); 
            $mediaAccessSettings->company_id =  $data->companyId;
            $mediaAccessSettings->auto_approved =  0;
            $mediaAccessSettings->created_by =  $data->id;
            $mediaAccessSettings->recipe_notify_email =  $data->email_address;
            $mediaAccessSettings->default_expiry_date =  Carbon::now();
            $mediaAccessSettings->save();
        }
    }
}
