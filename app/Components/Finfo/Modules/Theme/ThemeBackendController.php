<?php

namespace App\Components\Finfo\Modules\Theme;

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

class ThemeBackendController extends Controller
{
    private $filesUploadPath = 'themes/temp/';
    
    private $pathZipFile;
    
    public function __construct() {
        $this->middleware('auth');
        if(\Auth::check() && \Auth::user()->user_type_id != 3) {
            App::abort(403);
        }
    }
    public function index() {
        $getThemeData = Theme::get();
        $index = 0;
        
        foreach($getThemeData as $theme) {
            if (file_exists(public_path()."/".trim($theme->theme_path)."/thumnail.jpg")) {
                $getThemeData[$index]->thumnail_img = "/".trim($theme->theme_path)."/thumnail.jpg";    
            }
            $index++;
        }
        return $this->view('list')->with(compact('getThemeData'));
    }
    public function installTheme(Request $request) {
        $validate = Validator::make($request->all(), [
            'themes_file'    => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
        $file_name = $_FILES['themes_file']['name'];
        $file = Input::file('themes_file');
        $file->move($this->filesUploadPath, $file_name);
        $zipFilePath = public_path().'/'.$this->filesUploadPath.$file_name;
        // EXTRACT TO ROOT PATH
        $zip = new \ZipArchive();
        $check = false;
        if ($zip->open($zipFilePath) == true) {
            $getDefaultTemplateContent = $zip->getFromName('default_template.txt');
            if ($getDefaultTemplateContent) {
                if ($this->storeThemeInformation($getDefaultTemplateContent)) {
                    $zip->extractTo(base_path('/'));
                }else{
                    $check = true;
                }
            }else{
                $check = true;
            }
        }
        $zip->close();
        // REMOVE DEFAULT TEMPLATE FILES
        if (file_exists(base_path()."/default_template.txt")) {
            unlink(base_path()."/default_template.txt");    
        }
        // REMOVE ZIP FILE
        unlink( $zipFilePath);
        if ($check == false) {
            return redirect()->back()->with('successExtract', 'Theme installed.');
        }else{
            return redirect()->back()->with('invalidInstallTheme', 'Theme not installed. Note: theme may incorrect structure or already existing.');
        }
    }
    private function checkExistingTheme($theme_name) {
        $getThemeData = Theme::where('theme_path', '=', $theme_name)->first();
        if (!empty($getThemeData)){
            return true;
        }
        return false;
    }
    private function storeThemeInformation($templateData) {
        $get_default_template_text = explode("\n", $templateData);
        $tableTheme = new Theme();
        foreach($get_default_template_text as $theme) {
            if (!empty($theme)) {
                $templateName = explode(":", $theme);
                if ($templateName[0] == "TEMPLATE_NAME") {
                    $setThemeName = trim(preg_replace('/[^A-Za-z0-9\_ ]/', '', $templateName[1]));
                    $tableTheme->theme_name = $setThemeName;
                }elseif($templateName[0] == "TEMPLATE_CSS_PATH"){
                    $tableTheme->css_path = $templateName[1]; 
                }elseif($templateName[0] == "TEMPLATE_THEME_PATH"){
                    $tableTheme->theme_path = $templateName[1];  
                }
            }
        }
        if ($this->checkExistingTheme($tableTheme->theme_path ) == true) {
            return false;
        }
        if (!empty($tableTheme->theme_name) && !empty($tableTheme->css_path) && !empty($tableTheme->theme_path) ) {
            $tableTheme->save();
            return true;
        }
        return false;
    }
    public function activateTheme($id) {
        // UPDATE PREVIOUS THEME TO INACTIVE
        $themeStatus = Theme::where('id', '<>', $id);
        $themeStatus->status = 0;
        $themeStatus->update(array('status' => 0));
        // UPDATE THEME TO BE ACTIVE
        $theme = Theme::findOrNew($id);
        $theme->status = 1;
        $theme->update();
        return redirect()->back()->with('successExtract', 'Theme have been activated.');
    }
    public function deactivateTheme($id) {
        // UPDATE THEME TO BE INACTIVE
        $theme = Theme::findOrNew($id);
        $theme->status = 0;
        $theme->update();
        return redirect()->back()->with('successExtract', 'Theme have been inactivated.');
    }
    
    function removeDirectory($path) {
 	$files = glob($path . '/*');
	foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
	}
	rmdir($path);
 	return true;
    }
    public function uninstallTheme($id) {
        $theme = Theme::findOrNew($id);
        if (isset( $theme->theme_path) && !empty($theme->theme_path)) {
            $getThemeName = $theme->theme_path;
            $css_path = public_path()."/".trim($getThemeName);
            $theme_path = base_path('resources/views/templates/client/'.trim($theme->theme_path));
            // REMOVE CSS FROM PUBLIC FOLDER
            if (is_dir($css_path)){
                $this->removeDirectory($css_path);
            } else {
                $error_remove['css_path'] = $css_path;
            }
            if (is_dir($theme_path)) {
                $this->removeDirectory($theme_path);
            } else {
                $error_remove['theme_path'] = $theme_path;
            }
            $theme->delete();
            if (!empty($error_remove)) {
                return redirect()->back()->with('error_remove', $error_remove  );
            }
            return redirect()->back()->with('successUninstall', 'Theme have been uninstall.');
        }
        return redirect()->back();
    }
}
