<?php

namespace App\Components\Package\Modules\EmailAlerts;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Components\Package\Modules\Announcements\Announcement;
use App\Components\Package\Modules\LatestFinancialHighlight\LatestFinancialItems;
use App\Components\Package\Modules\PressReleases\PressReleases;
use App\Components\Package\Modules\FinancialAnnualReports\AnnualReports;
use App\Components\Package\Modules\LatestFinancialHighlight\LatestFinancialHighlight;
use App\Components\Package\Modules\Presentation\Presentation;
use App\Components\Package\Modules\Webcast\Webcast;
use App\Components\Package\Modules\InvestorRelationsCalendar\InvestorRelationsCalendar;
use App\Components\Client\Modules\Company\Company;
use DateTime;
use Hash;
use Mail;
use Session;
use Auth;
use Excel;
use Carbon\Carbon;

class EmailAlertBackendController extends Controller
{
	// EamilAlert list page
    public function getList()
    {
        $emails = EmailAlerts::where('company_id', Auth::user()->company_id)->get();
        return $this->view('backend.list')->with('emails', $emails)->with('controller', $this);
    }

    // EamilAlert create page
    public function createEamilAlert($id = '')
    {
        $category = EmailAlertsCategory::get();
        if($id == ''){
            return $this->view('backend.create')->with('category', $category);
        }else{
            $email = EmailAlerts::findOrFail($id);
            $arr_category_id = EmailAlertsItem::where('email_alert_id', $id)->lists('email_alert_category_id')->all();

            return $this->view('backend.edit')->with('category', $category)->with('email', $email)->with('arr_category_id', $arr_category_id);
        }
        
    }

    //
    public function postEamilAlert($id = '')
	{
		$validate = Validator::make(Input::all(), [
            'name'	        => 'required|min:5|max:50',
            'email_address' => 'required|email',
            'category'	    => 'required|min:1',
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
        $data = Input::all();
        if($id == ''){
            $check_email = EmailAlerts::where('company_id', Session::get('company_id'))->where('email_address', $data['email_address'])->first();
            if(count($check_email) >= 1){
                $check_email->name = $data['name'];
                $check_email->update();

                $email_al_id = $check_email->id;
                $this->deleteEmailAlertItem($email_al_id);
                $this->storeEmailAlertItem($email_al_id, $data['category']);


            }else{
                $email_alert = new EmailAlerts();
                $email_alert->company_id    = Session::get('company_id');
                $email_alert->name          = $data['name'];
                $email_alert->email_address = $data['email_address'];
                $email_alert->is_active     = 1;
                $email_alert->save();

                $email_al_id = $email_alert->id;
                $this->storeEmailAlertItem($email_al_id, $data['category']);
            }
            return Redirect::route('package.admin.email-alerts')->with('global', 'Email alerts created');
        }else{
            $email_alert = EmailAlerts::where('id', $id)->where('company_id', Auth::user()->company_id)->first();
            if($email_alert){
                $email_alert->name          = $data['name'];
                $email_alert->email_address = $data['email_address'];
                $email_alert->updated_by    = Auth::user()->id;
                $email_alert->save();

                $email_al_id = $email_alert->id;
                $this->deleteEmailAlertItem($email_al_id);
                $this->storeEmailAlertItem($email_al_id, $data['category']);

            }
            return Redirect::route('package.admin.email-alerts')->with('global', 'Email alerts updated');
        }
	}

    public function getListCategory($email_alert_id)
    {
        $pieces = EmailAlertsItem::join('module as ec','email_alert_items.email_alert_category_id','=','ec.id')->where('email_alert_id', $email_alert_id)->get();
        foreach($pieces as $item)
        {
            echo "<li>$item->name</li>";
        }
    }

    public function getExListCategory($email_alert_id)
    {
        $pieces = EmailAlertsItem::join('module as ec','email_alert_items.email_alert_category_id','=','ec.id')->where('email_alert_id', $email_alert_id)->get();
        
        $category = '';
        foreach($pieces as $item)
        {
            $category .="$item->name, ";
        }
        return $category;
    }

    public function getSoftDelete($id)
    {
        $email = EmailAlerts::findOrFail($id);
        $email->is_delete     = 1;
        $email->deleted_by    = Auth::user()->id;
        $email->update();
        $email->delete();

        $email_alert_item = EmailAlertsItem::where('email_alert_id', $id)->delete();

        return Redirect::route('package.admin.email-alerts')->with('global-danger', 'Email alerts has been deleted.');
    }

    public function softDeleteMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {

            $emails = EmailAlerts::whereIn('id',$input['check'])->get();

            if(count($emails)){
                foreach($emails as $item){
                    $email = EmailAlerts::find($item['id']);
                    $email->is_delete = 1;
                    $email->deleted_by = \Auth::user()->id;
                    $email->update();

                    $email_alert_item = EmailAlertsItem::where('email_alert_id', $item['id'])->delete();
                }
            }

            $delete = EmailAlerts::whereIn('id',$input['check']);
            if($delete->delete())
            {
                return redirect()->route('package.admin.email-alerts')->with('global-danger', 'Email alert(s) deleted.');
            }
        }
    }

    public function deleteEmailAlertItem($email_alert_id)
    {

        $delete = EmailAlertsItem::where('email_alert_id',$email_alert_id)->delete();
            
    }

    public function storeEmailAlertItem($email_alert_id, $arr_category_id)
    {
        foreach($arr_category_id as $item){
            $email_alert_item = new EmailAlertsItem();
            $email_alert_item->email_alert_id = $email_alert_id;
            $email_alert_item->email_alert_category_id = $item;
            $email_alert_item->created_by = Auth::user()->id;
            $email_alert_item->save();
        }
    }

    public function exportExcel()
    {
        $date = Carbon::now();
        $current_date   = date("Y-m-d", strtotime($date));
        $file_name      = 'Email-alerts-'.$date;

        $data['email'] = EmailAlerts::where('company_id', Auth::user()->company_id)->get();
        $data['title'] = $file_name;

        Excel::create($file_name, function($excel) use($data) {

            $excel->sheet('Sheetname', function($sheet) use($data) {
                $sheet->row(2, function($row) {
                    $row->setBackground('#CBF2CB');
                });

                $sheet->mergeCells('A2:E2');


                $sheet->row(2,[$data['title']]);
                $sheet->appendRow(['Name', 'Email', 'Category', 'subscribed date']);

                foreach ($data['email'] as $email) {
                    $date = date("Y-m-d", strtotime($email->created_at));
                    $category = $this->getExListCategory($email->id);
                    //$category = '';
                    $sheet->appendRow([$email->name, $email->email_address, $category, $date]);
                }

            });

        })->export('xls');
    }

    public function sendMail($company_id)
    {
        $route = $this->CheckContent($company_id);

        $emails = EmailAlerts::where('company_id', $company_id)->where('is_active', 1)->get();
        $company = Company::findOrFail($company_id);

        if(count($emails) >= 1){
            foreach ($emails as $items) {
                $content = '';
                $email = EmailAlertsItem::where('email_alert_id', $items->id)->get();
                    
                if(count($email) >= 1){
                    foreach ($email as $value) {
                        switch ($value->email_alert_category_id) {
                            case 1 : 
                                    if(isset($route['financialHighlights'])){
                                        $content['financialHighlights'] = $route['financialHighlights'];
                                    }
                                    break;
                            case 3 : 
                                    if(isset($route['annual_report'])){
                                        $content['annual_report'] = $route['annual_report'];
                                    }
                                    break;
                            case 4 : 
                                    if(isset($route['press_release'])){
                                        $content['press_release'] = $route['press_release'];
                                    }
                                    break;
                            case 5 : 
                                    if(isset($route['finan_result'])){
                                        $content['finan_result'] = $route['finan_result'];
                                    }
                                    break;
                            case 6 : 
                                    if(isset($route['webcast'])){
                                        $content['webcast'] = $route['webcast'];
                                    }
                                    break;
                            case 7 : 
                                    if(isset($route['presentation'])){
                                        $content['presentation'] = $route['presentation'];
                                    }
                                    break;
                            case 8 : 
                                    if(isset($route['announment'])){
                                        $content['announment'] = $route['announment'];
                                    }
                                    break;
                            case 9 : 
                                    if(isset($route['calendar'])){
                                        $content['calendar'] = $route['calendar'];
                                    }
                                    break;

                            default: 
                                    break;
                        }
                    }

                    if($content != ''){

                        Mail::queue('app.Components.Package.Modules.EmailAlerts.views.email.email_alert_notification', ['data' => $content, 'name' => $items->name, 'company' => $company], function($message) use($items)
                        {
                            $message->subject('New Data');
                            //$message->from($email_data['news']['sender_email'], Session::get('company_name'));
                            $message->to($items['email_address'], $items['name']);
                        });
                    }
                }

            }
        }
        return Redirect::route('package.admin.email-alerts')->with('global', 'Email has been sent.');
    }


    public function publishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $pre = EmailAlerts::whereIn('id',$input['check'])->update(['is_active' => '1']);

            return redirect()->route('package.admin.email-alerts')->with('global', 'Email Alert(s) published.');

        }
    }

    public function unPublishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $pre = EmailAlerts::whereIn('id',$input['check'])->update(['is_active' => '0']);

            return redirect()->route('package.admin.email-alerts')->with('global', 'Email Alert(s) unpublished.');

        }
    }
    private function getFinancialHighlightsSubscribe($company_id)
    {
        $getFinancialHighlights = LatestFinancialHighlight::where('is_deleted', '=', 0)
                            ->where('is_archive', '=', 0)
                            ->where('status', 0)
                            ->where('subscribe', 0)
                            ->where('company_id', '=', $company_id)
                            ->get();
        if(count($getFinancialHighlights) >= 1){
            $update = LatestFinancialHighlight::where('is_deleted', '=', 0)
                            ->where('is_archive', '=', 0)
                            ->where('status', 0)
                            ->where('subscribe', 0)
                            ->where('company_id', '=', $company_id)
                            ->update(['subscribe' => 1]);
        }
        return count($getFinancialHighlights);
    }

    private function getAnnualReportSubscribe($company_id)
    {
        $annual_report = AnnualReports::where('company_id', $company_id)
                            ->where('is_active', 1)
                            ->where('subscribe', 0)
                            ->get();
        if(count($annual_report) >= 1){
            $update = AnnualReports::where('company_id', $company_id)
                            ->where('is_active', 1)
                            ->where('subscribe', 0)
                            ->update(['subscribe' => 1]);
        }
        return count($annual_report);
    }

    private function getPressReleaseSubscribe($company_id)
    {
        $press_release = PressReleases::where('company_id', $company_id)
                                        ->where('subscribe', 0)
                                        ->where('is_active', 1)
                                        ->get();
        if(count($press_release) >= 1){
            $update = PressReleases::where('company_id', $company_id)
                                        ->where('subscribe', 0)
                                        ->where('is_active', 1)
                                        ->update(['subscribe' => 1]);
        }
        return count($press_release);
    }

    private function getFinancialResultSubscribe($company_id)
    {
        $finan_result = LatestFinancialHighlight::where('is_deleted', '=', 0)
                            ->where('is_archive', '=', 0)
                            ->where('status', 0)
                            ->where('subscribe', 0)
                            ->where('company_id', '=', $company_id)
                            ->get();

        if(count($finan_result) >= 1){
            $update = LatestFinancialHighlight::where('is_deleted', '=', 0)
                            ->where('is_archive', '=', 0)
                            ->where('status', 0)
                            ->where('subscribe', 0)
                            ->where('company_id', '=', $company_id)
                            ->update(['subscribe' => 1]);
        }
        return count($finan_result);
    }

    private function getWebcastSubscribe($company_id)
    {
        $webcast = Webcast::where('company_id', $company_id)
                            ->where('subscribe', 0)
                            ->where('is_active', 1)
                            ->get();
        if(count($webcast) >= 1){
            $update = Webcast::where('company_id', $company_id)
                            ->where('subscribe', 0)
                            ->where('is_active', 1)
                            ->update(['subscribe' => 1]);
        }
        return count($webcast);
    }

    private function getPresentationSubscribe($company_id)
    {

        $presentation = Presentation::where('company_id', $company_id)
                                    ->where('subscribe', 0)
                                    ->where('is_active', 1)
                                    ->get();
        if(count($presentation) >= 1){
            $update = Presentation::where('company_id', $company_id)
                                    ->where('subscribe', 0)
                                    ->where('is_active', 1)
                                    ->update(['subscribe' => 1]);
        }
        return count($presentation);
    }

    private function getAnnouncementSubscribe($company_id)
    {

        $announment = Announcement::where('company_id', $company_id)
                                    ->where('subscribe', 0)
                                    ->where('status', 0)
                                    ->get();
        if(count($announment) >= 1){
            $update = Announcement::where('company_id', $company_id)
                                    ->where('subscribe', 0)
                                    ->where('status', 0)
                                    ->update(['subscribe' => 1]);
        }
        return count($announment);
    }

    private function getInvestorRelationsCalendarSubscribe($company_id)
    {

        $calendar = InvestorRelationsCalendar::where('company_id', $company_id)
                                    ->where('is_active', 1)
                                    ->where('subscribe', 0)
                                    ->get();
        if(count($calendar) >= 1){
            $update = InvestorRelationsCalendar::where('company_id', $company_id)
                                    ->where('is_active', 1)
                                    ->where('subscribe', 0)
                                    ->update(['subscribe' => 1]);
        }
        return count($calendar);
    }

    private function CheckContent($company_id)
    {
        $route = '';
        $company = Company::findOrFail($company_id);
        if($company){
            $finfo_account_name = $company->finfo_account_name;
            $financialHighlights = $this->getFinancialHighlightsSubscribe($company_id);
            if($financialHighlights >= 1){
                $route['financialHighlights'] = "https://".$finfo_account_name.".wizwerx.info/financial-result";
            }
            $annual_report = $this->getAnnualReportSubscribe($company_id);
            if($annual_report >= 1){
                $route['annual_report'] = "https://".$finfo_account_name.".wizwerx.info/annual-report";
            }
            $press_release = $this->getPressReleaseSubscribe($company_id);
            if($press_release >= 1){
                $route['press_release'] = "https://".$finfo_account_name.".wizwerx.info/press-releases";
            }
            $finan_result = $this->getFinancialResultSubscribe($company_id);
            if($finan_result >= 1){
                $route['finan_result'] = "https://".$finfo_account_name.".wizwerx.info/financial-result";
            }
            $webcast =  $this->getWebcastSubscribe($company_id);
            if($webcast >= 1){
                $route['webcast'] = "https://".$finfo_account_name.".wizwerx.info/financial-result";
            }
            $presentation = $this->getPresentationSubscribe($company_id);
            if($presentation >= 1){
                $route['presentation'] = "https://".$finfo_account_name.".wizwerx.info/financial-result";
            }
            $announment = $this->getAnnouncementSubscribe($company_id);
            if($announment >= 1){
                $route['announment'] = "https://".$finfo_account_name.".wizwerx.info/announcements";
            }
            $calendar = $this->getInvestorRelationsCalendarSubscribe($company_id);
            if($calendar >= 1){
                $route['calendar'] = "https://".$finfo_account_name.".wizwerx.info/investor-relations-calendar ";
            }
        }
        
        return $route;
    }


    public function sendMailToAllClient()
    {
        $company = new Company();
        $client = $company->join('company_subscription_package as csp','csp.company_id','=','company.id')
                                        ->select('company_id')
                                        ->where('csp.is_current', '=', 1)
                                        ->where('csp.is_active', '=', 1)
                                        ->get();
        if(count($client) >= 1){
            foreach ($client as $company) {
                $this->sendMail($company->company_id);
            }
            return 'mail has been sent.';
        }
        return 'success';
    }
    

}