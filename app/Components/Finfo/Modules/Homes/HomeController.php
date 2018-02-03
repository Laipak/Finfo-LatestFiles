<?php

namespace App\Components\Finfo\Modules\Homes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use App\Components\Finfo\Modules\Menus\Menu;
use App\Components\Finfo\Modules\Webpage\Webpage as Webpage;

class HomeController extends Controller 
{
 	// Get home page
    public function index()
    {
        $package_module = app('App\Components\Finfo\Modules\Registers\Package_module')->getPackage();
        return $this->view('home')->with('menus', $this->getMenus())->with(compact('package_module'));
    }

    // Get about-us page
    public function about()
    {
        $package_module = app('App\Components\Finfo\Modules\Registers\Package_module')->getPackage();
        return $this->view('about-us')->with('menus', $this->getMenus())->with(compact('package_module'));
    }

    private function settingJsonPath() {
        $saveJsonFilesPath = public_path().'/setting.json';
        return $saveJsonFilesPath;
    }

    public function subDomainNameValidation($getSubDomainName) {
        $getSettings = $this->getSettingJsonFile();
        if (isset($getSettings['sub_domain_exclusive']) && !empty($getSettings['sub_domain_exclusive'])){
            $exclusiveSubDomain = explode(",", $getSettings['sub_domain_exclusive']);
            $checked = false;
            foreach($exclusiveSubDomain as $subDomain) {
                if ($getSubDomainName == $subDomain ) {
                    return true;
                }
            }
            return $checked;
        }
    }

    public function checkExclusiveDomainSetting($subdomain) {
        $getstatus = $this->subDomainNameValidation($subdomain);
        return json_encode(array("is_exclusive_domain" => $getstatus));
    }

    public function getSettingEmailSendTo() {
        $getSettings = $this->getSettingJsonFile();
        $emailSendTo = null;
        if (isset($getSettings['admin_email_receive_noti']) && !empty($getSettings['admin_email_receive_noti'])) {
            $emailSendTo = $getSettings['admin_email_receive_noti'];
        }
        return json_encode(array("admin_email_receive_noti"=> $emailSendTo));
    }

    public function displayPage(Request $request) {
        //$request->path() GET CURRENT PAGE NAME
        $pageData = Webpage::where('is_delete', '=', 0)
                            ->where('company_id', '=', 1)
                            ->where('name', '=', $request->path())
                            ->get()->first();
                                 
        return $this->view('default')->with(compact('pageData'))->with('menus', $this->getMenus());
    }
}
