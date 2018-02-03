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
use Auth;
use Carbon\Carbon;
use App\Components\Client\Modules\Company\Company;

class MediaAccessBackendController extends Controller
{
    public function __construct() {
        if (\Auth::check() && \Auth::user()->user_type_id != 5 && session::has('mediaAccessAuthKey')) {
           App::abort(403);
        } 
    }
    public function getCountMediaDownloaded($mediaFileId) {
        $getCount = MediaAccessDownload::where('media_access_file_ids', $mediaFileId )->count();
        return $getCount;
    }
    public function getMediaAccessList()
    {
        $mediaAccessFiles = MediaAccessFiles::join('media_access_category as category', 'category.id', '=' , 'media_access_files.category_id')
                ->select('category.category_name', 'media_access_files.*' )
                ->where('category.company_id', \Auth::user()->company_id)
                ->where('category.is_deleted', 0)
                ->where('media_access_files.is_deleted', 0)
                ->get();
        return $this->view('backend.files.list')->with(compact('mediaAccessFiles'))->with('controller', $this);
    }
    private function checkMediaAccessForCompany($userId) {
        $getMediaAccessUser = MediaAccess::join('media_access_organization as media_organization', 'media_organization.media_access_user_ids', '=', 'media_access_users.id')
                            ->where('media_access_users.id', $userId)
                            ->where('media_access_users.company_id', \Auth::user()->company_id)
                            ->where('is_deleted', 0)
                            ->get()
                            ->first();
        if (isset($getMediaAccessUser) && !empty($getMediaAccessUser)) {
            return true;
        }
        return false;
    }
    
    private function updateUserMediaAccessStatus($action, $userId) {
        if ($this->checkMediaAccessForCompany($userId) == true) {
            $mediaUser = MediaAccess::findOrNew($userId);
            if ($action == 'approved') {
                $mediaUser->status = 1;
                 $this->sendMediaAccessUserApproved($mediaUser, $userId);
            } else {
                $mediaUser->status = 0;
            }
            $mediaUser->update();
            $mediaAccessOrganization = MediaAccessOrganization::where('media_access_user_ids', $userId)->get()->first();
            $mediaAccessOrganizationUpdate = MediaAccessOrganization::findOrNew($mediaAccessOrganization->id);
            $mediaAccessOrganizationUpdate->updated_by = \Auth::user()->id;
            $mediaAccessOrganizationUpdate->updated_at = date('Y-m-d h:m:s');
            $mediaAccessOrganizationUpdate->update();
        }
        return true;
    }
    public function getMediaUserApproval($id) {
        $this->updateUserMediaAccessStatus('approved', $id);
        return redirect()->route('package.admin.media-access.list-user')->with('mediaAccessUserApproved', trans('crud.success.mediaaccess.user_multi_approved'));
    }
    public function getMediaUserReject($id) {
        $this->updateUserMediaAccessStatus('reject', $id);
        return redirect()->route('package.admin.media-access.list-user')->with('mediaAccessUserRejected', trans('crud.success.mediaaccess.user_reject'));
    }
    public function createMediaAccess()
    {
        return $this->view('backend.create');
    }
    public function postMediaAccess() {
        $validate = Validator::make(Input::all(), [
            'name'	        => 'required|min:5|max:50',
            'email_address' => 'required|email',
            'categories'	=> 'required|min:1',
        ]);
        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
    }
    public function editMediaAccess($id) {
        $getMediaAccessCategory = MediaAccessCategory::where('company_id', \Auth::user()->company_id)->orderBy('category_name')->get();
        $getMediaAccessFileData = MediaAccessFiles::findOrNew($id);
        $getMediaAccessFilePermissionData = MediaAccessUserPermission::where('media_access_file_ids', $id)->get();
        $getMediaAccessUsers = $this->getMediaAccessUsers();
        $index = 0;
        foreach($getMediaAccessUsers as $users) {
            foreach($getMediaAccessFilePermissionData as $mediaAccessUsersFiles) {
                if ($mediaAccessUsersFiles->user_id == $users->id) {
                    $getMediaAccessUsers[$index]->is_access = true;
                    break;
                }
            }
            $index++;
        }
        return $this->view('backend.files.edit-file')
                ->with(compact('getMediaAccessFileData'))
                ->with(compact('getMediaAccessUsers'))
                ->with(compact('getMediaAccessCategory'));
    }
    private function deletedMediaAccessUsersWithFiles($fileId) {
        $deletedMediaAccessUserPermission =  MediaAccessUserPermission::where('media_access_file_ids', $fileId);
        $deletedMediaAccessUserPermission->delete();
    }
    
    public function getMediaAccessCountFilesByCategory($categoryId) {
        $mediaAccessCountFiles = MediaAccessFiles::where('category_id', $categoryId)->count();
        return $mediaAccessCountFiles;
    }
    public function listMediaAccessCategory() {
        $mediaAccessCategoryInfo = MediaAccessCategory::where('company_id', \Auth::user()->company_id)->get();
        return $this->view('backend.category.lists')->with(compact('mediaAccessCategoryInfo'))->with('controller', $this);
    }
    public function addMediaAccess() {
        $getMediaAccessCategory = MediaAccessCategory::where('company_id', \Auth::user()->company_id)->orderBy('category_name')->get();
        $getMediaAccessUsers = $this->getMediaAccessUsers();
        return $this->view('backend.files.create-file')->with(compact('getMediaAccessCategory'))->with(compact('getMediaAccessUsers'));
    }
    private function getMediaAccessUsers() {
        $mediaAccessUserInfo = MediaAccess::join('media_access_organization as media_organization', 'media_organization.media_access_user_ids', '=', 'media_access_users.id')      
                ->select('media_organization.*', 'media_access_users.*')
                ->where('media_access_users.company_id', Session::get('company_id'))
                ->where('is_deleted', 0)
                ->get();
        return $mediaAccessUserInfo;
    }
    public function getListMediaAccessUsers() {
        $mediaAccessUserInfo = $this->getMediaAccessUsers();
        return $this->view('backend.user-list')->with(compact('mediaAccessUserInfo'))->with('controller', $this);
    }
    public function getMediaUserMultiApproval(Request $request) {
        if (is_array($request->input('check'))) {
            foreach($request->input('check') as $check) {
                $this->updateUserMediaAccessStatus('approved', $check);
            }
        }
        return redirect()->route('package.admin.media-access.list-user')->with('mediaAccessUserApproved', trans('crud.success.mediaaccess.user_multi_approved'));
    }
    public function deleteMediaUser($userId) {
        if ($this->checkMediaAccessForCompany($userId) == true) {
            $mediaUsers = MediaAccess::findOrNew($userId);
            $mediaUsers->email = $mediaUsers->email."_".date('Y-m-d');
            $mediaUsers->update();
            $delete = MediaAccessOrganization::where('media_access_user_ids', $userId)->first();
            $mediaUsersDelete = MediaAccessOrganization::findOrNew($delete->id);
            $mediaUsersDelete->is_deleted = 1;
            $mediaUsersDelete->deleted_by = \Auth::user()->id;
            $mediaUsersDelete->update();
            $delete->delete();
        }
    }
    public function getMulitiMediaUserdelete(Request $request){
         if (is_array($request->input('check'))) {
            foreach($request->input('check') as $check) {
                $this->deleteMediaUser($check);
            }
        }
        return redirect()->route('package.admin.media-access.list-user')->with('mediaAccessUserDeleted', trans('crud.success.mediaaccess.user_multi_deleted'));
    }
    public function getMediaUserdelete($id){
        $this->deleteMediaUser($id);
        return redirect()->route('package.admin.media-access.list-user')->with('mediaAccessUserDeleted', trans('crud.success.mediaaccess.user_multi_deleted'));
    }
    private function getBackendClientCompanyEmailAndName() {
        $getCompanyData = Company::findOrFail(Session::get('company_id'));
        return $getCompanyData;
    }
    
    private function sendMediaAccessUserApproved($userData, $userId) {
        if ($this->checkMediaAccessForCompany($userId) == true) {
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
            return true;
        }
    }
    public function getGenerateReportCSV() {
        $mediaAccessUserInfo = MediaAccess::join('media_access_organization as media_organization', 'media_organization.media_access_user_ids', '=', 'media_access_users.id')      
                ->select('media_organization.*', 'media_access_users.*')
                ->where('media_access_users.company_id', Session::get('company_id'))
                ->where('is_deleted', 0)
                ->orderBy('media_access_users.id', 'desc')
                ->get();
        $this->formatCSV($mediaAccessUserInfo);
        return redirect()->back();
    }
    private function formatCSV($mediaAccessUsers) {
        $output = fopen('php://output', 'w');
        fputcsv($output, $this->exportHeaderCsv());
        foreach($mediaAccessUsers as $mediaUser) {
            $name = isset($mediaUser->name) ? $mediaUser->name : "";
            $email = isset($mediaUser->email) ? $mediaUser->email : "";
            $userPhone = isset($mediaUser->phone) ? $mediaUser->phone : "";
            $userFax = isset($mediaUser->fax) ? $mediaUser->fax : "";
            $designation = isset($mediaUser->designation) ? $mediaUser->designation : "";
            $userStatus = (isset($mediaUser->status) && $mediaUser->status == 1) ? 'Approved' : "Pending";
            $lastLogin = isset($mediaUser->last_login) ?  date("d/M/Y", strtotime($mediaUser->last_login))  : "";
            $organization = isset($mediaUser->organization) ? $mediaUser->organization : "";
            $org_mobile = isset($mediaUser->org_mobile) ? $mediaUser->org_mobile : "";
            $org_phone = isset($mediaUser->org_phone) ? $mediaUser->org_phone : "";
            $address = isset($mediaUser->address) ? $mediaUser->address : "";
            fputcsv($output, array($name, $email, $userPhone, $userFax, $designation,  $organization, $org_mobile, $org_phone, $address, $userStatus, $lastLogin));
        }
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-type: text/x-csv");
        $filename = "Media Access Users Export - ".date("d/M/Y").".csv";
        header("Content-Disposition: attachment; filename=$filename");
        fclose($output);
        exit;
    }
    private function exportHeaderCsv() {
        $header = array('Name', 'Email Adress', 'Phone', 'Fax', 'Designation', 'Organization', 'Org\'s Mobile', 'Org\'s Phone', 'Org\'s Address', 'Status', 'Last Login');
        return $header;
    }
    public function createCategory() {
        return $this->view('backend.category.create');
    }   
    public function storeCategory(Request $request) {
        $validate = Validator::make(Input::all(), [
            'category_name' => 'required|min:5|max:50',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        } else {
            $mediaAccessCategory = new MediaAccessCategory();
            $mediaAccessCategory->category_name = $request->input('category_name');
            $mediaAccessCategory->status = 1;
            $mediaAccessCategory->created_by = \Auth::user()->id;
            $mediaAccessCategory->company_id = \Auth::user()->company_id;
            $mediaAccessCategory->save();
            return redirect()->route('package.admin.media-access.list-category')->with('category_created', trans('crud.success.mediaaccess.category_created'));
        }
    }
    public function editCategory($id) {
        $mediaAccessCategoryInfo = MediaAccessCategory::where('id',$id)->where('company_id', \Auth::user()->company_id)->get();
        return $this->view('backend.category.edit')->with(compact('mediaAccessCategoryInfo'));
    }
    public function updateCategory(Request $request) {
        $validate = Validator::make($request->all(), [
            'category_name' => 'required|min:5|max:50',
            'categoryInfo' => 'required'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        } else {
            $getMediaAccessCategory = MediaAccessCategory::where('id', \Crypt::decrypt($request->input('categoryInfo')))
                    ->where('company_id', Auth::user()->company_id)
                    ->get();
            if (!empty($getMediaAccessCategory)) {
                $mediaAccessCategory = MediaAccessCategory::findOrNew(\Crypt::decrypt($request->input('categoryInfo')));
                $mediaAccessCategory->category_name = $request->input('category_name');
                $mediaAccessCategory->status = 1;
                $mediaAccessCategory->updated_by = \Auth::user()->id;
                $mediaAccessCategory->update();
                return redirect()->back()->with('categoryUpdated', trans('crud.success.mediaaccess.category_updated'));
            }else{
                $validate->addError('category_name', trans('crud.errors.mediaaccess.files_notexist'));
                return redirect()->back()->withErrors($validate->errors())->withInput();
            }
        }
    }
    private function checkMediaCategoryIsBelongToCompany($categoryId) {
        $mediaAccessCategoryInfo = MediaAccessCategory::where('id', $categoryId)->where('company_id', \Auth::user()->company_id)->get();
        if (empty($mediaAccessCategoryInfo)) {
            return true;
        }else{
            return false;
        }
    }
    private function doDeleteCategory($id) {
        $deltedMediaCategory = MediaAccessCategory::findOrNew($id);
        $deltedMediaCategory->category_name = $deltedMediaCategory->category_name."_".date('d/m/y');
        $deltedMediaCategory->is_deleted = 1;
        $deltedMediaCategory->deleted_by = \Auth::user()->id;
        $deltedMediaCategory->update();
        $deltedMediaCategory->delete();
    }
    public function deleteCategory($id) {
        if ($this->checkMediaCategoryIsBelongToCompany($id) == true) {
            return redirect()->route('package.admin.media-access.list-category')->with('categoryDeleted', trans('crud.errors.mediaaccess.files_notexist'));
        }else{
            $this->doDeleteCategory($id);
            return redirect()->route('package.admin.media-access.list-category')->with('categoryDeleted', trans('crud.success.mediaaccess.category_deleted'));
        }
    }
    public function mulitiDeleteCategory(Request $request) {
        if (is_array($request->input('check'))) {
            foreach($request->input('check') as $checkValue) {
                if ($this->checkMediaCategoryIsBelongToCompany($checkValue) == false) {
                    $this->doDeleteCategory($checkValue);
                }
            }
            return redirect()->route('package.admin.media-access.list-category')->with('categoryDeleted', trans('crud.success.mediaaccess.category_deleted'));
        }
    }
    public function getSettingsMediaAccess() {
        $mediaAccessSettings = MediaAccessSettings::where('company_id', \Auth::user()->company_id)->get()->first();
        return $this->view('backend.settings')->with(compact('mediaAccessSettings'));
    }
    public function doSettingsMediaAccess(Request $request) {
        $validate = Validator::make($request->all(), [
            'auto_approved' => 'required',
            'recipe_email' => 'required|email',
            'defaul_expiry_date' => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->route('package.admin.media-access.settings')->withErrors($validate->errors())->withInput();
        }else {
            $getMediaAccessSettings = MediaAccessSettings::where('company_id', \Auth::user()->company_id)->get()->first();
            $mediaAccessSettings = MediaAccessSettings::findOrNew($getMediaAccessSettings->id);
            $mediaAccessSettings->auto_approved = $request->input('auto_approved');
            $mediaAccessSettings->company_id = \Auth::user()->company_id;
            $mediaAccessSettings->recipe_notify_email = $request->input('recipe_email');
            $mediaAccessSettings->default_expiry_date = date('Y-m-d', strtotime($request->input('defaul_expiry_date')));
            $mediaAccessSettings->created_by = \Auth::user()->id;
            $mediaAccessSettings->updated_by = \Auth::user()->id;
            $mediaAccessSettings->update();
            return redirect()->route('package.admin.media-access.settings')->with('successSettings', trans('crud.success.mediaaccess.settings'));
        }
    }
    public function updateMediaAccess($id, Request $request) {
        $validate = Validator::make(Input::all(), [
            'title' => 'required',
            'description' => 'required',
            'upload'=> 'required',
            'permission'=> 'required',
            'expire_date'=> 'required',
             'category'=> 'required'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }else {
            $mediaAccessFiles= MediaAccessFiles::findOrNew($id);
            if (!empty($request->input('upload_file'))){
                $getFilesName = $this->savePDFFilePath($request->all());
                $mediaAccessFiles->file_name = $getFilesName;
            }
            $mediaAccessFiles->title = $request->input('title');
            $mediaAccessFiles->category_id = $request->input('category');
            $mediaAccessFiles->description = $request->input('description');
            $mediaAccessFiles->expire_date = date('Y-m-d', strtotime($request->input('expire_date')));
            $mediaAccessFiles->status = $request->input('status');
            $mediaAccessFiles->updated_by = \Auth::user()->id;
            $mediaAccessFiles->update();
            $this->deletedMediaAccessUsersWithFiles($mediaAccessFiles->id);
            $this->saveMediaAccessUsersWithFiles($mediaAccessFiles->id, $request->input('permission'));
            return redirect()->route('package.admin.media-access')->with('successUpdated', trans('crud.success.mediaaccess.files_updated'));
        }
    }
    public function doAddMediaAccessForm(Request $request) {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'upload'=> 'required',
            'permission'=> 'required',
            'category'=> 'required'
        ]);
        if ($validate->fails()) {
            return redirect()->route('package.admin.media-access.form')->withErrors($validate->errors())->withInput();
        }else {
            $getSetting = MediaAccessSettings::where('company_id', \Auth::user()->company_id)->get()->first();
            $expire_date = $getSetting->default_expiry_date;
            if (!empty($request->input('expire_date'))) {
                $expire_date = $request->input('expire_date');
            }
            $getFileName = $this->savePDFFilePath($request->all());
            $mediaAccessFiles = new MediaAccessFiles();
            $mediaAccessFiles->title = $request->input('title');
            $mediaAccessFiles->file_name = $getFileName;
            $mediaAccessFiles->category_id = $request->input('category');
            $mediaAccessFiles->description = $request->input('description');
            $mediaAccessFiles->expire_date = date('Y-m-d', strtotime($expire_date));
            $mediaAccessFiles->status = $request->input('status');
            $mediaAccessFiles->created_by = \Auth::user()->id;
            $mediaAccessFiles->save();
            $this->saveMediaAccessUsersWithFiles($mediaAccessFiles->id, $request->input('permission'));
            return redirect()->route('package.admin.media-access')->with('successAdded', trans('crud.success.mediaaccess.files_created'));
        }
    }
    private function saveMediaAccessUsersWithFiles($fileId, $permissionUsers) {
        foreach($permissionUsers as $userAccess) {
            $mediaAccessPermissionFiles = new MediaAccessUserPermission();
            $mediaAccessPermissionFiles->user_id = $userAccess;
            $mediaAccessPermissionFiles->permission = 1;
            $mediaAccessPermissionFiles->media_access_file_ids = $fileId;
            $mediaAccessPermissionFiles->save();
        }
    }
    private function savePDFFilePath($data = null){
        $file = $data['upload_file'];
        $filename = str_random(8).$data['upload'];
        $filesUploadPath = 'files/media-access/'.Session::get('company_name');
        $file->move($filesUploadPath, $filename);
        return $filename;
    }
    public function deleteFile($id){
        $mediaAccessFiles = MediaAccessFiles::whereIn('id', array($id));
        $mediaAccessFiles->update( array('status'=> 1, 'deleted_by' => \Auth::user()->id, 'is_deleted' => 1 ));
        $mediaAccessFiles->delete();
        return redirect()->route('package.admin.media-access')->with('successDeleted', trans('crud.success.mediaaccess.files_multi_deleted'));
    }
    public function mulitiDeleteFiles(Request $request){
        $mediaAccessFiles = MediaAccessFiles::whereIn('id', $request->input('check'));
        $mediaAccessFiles->update( array('status'=> 1, 'deleted_by' => \Auth::user()->id, 'is_deleted' => 1 ));
        $mediaAccessFiles->delete();
        return redirect()->route('package.admin.media-access')->with('successDeleted', trans('crud.success.mediaaccess.files_multi_deleted'));
    }
   
    public function mulitiPublishFiles(Request $request) {
        $mediaAccessFiles = MediaAccessFiles::whereIn('id', $request->input('check'));
        $mediaAccessFiles->update( array('status'=> 0, 'updated_by' => \Auth::user()->id ));
        return redirect()->route('package.admin.media-access')->with('successStatus', trans('crud.success.mediaaccess.files_multi_publish'));
    }
    
    public function mulitiUnpublishFiles(Request $request) {
        $mediaAccessFiles = MediaAccessFiles::whereIn('id', $request->input('check'));
        $mediaAccessFiles->update( array('status'=> 1, 'updated_by' => \Auth::user()->id ));
        return redirect()->route('package.admin.media-access')->with('successStatus', trans('crud.success.mediaaccess.files_multi_unpublish'));
    }   
}
