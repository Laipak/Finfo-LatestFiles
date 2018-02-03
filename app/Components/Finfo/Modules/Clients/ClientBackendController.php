<?php

namespace App\Components\Finfo\Modules\Clients;


use App\Components\Finfo\Modules\Clients\Company;
use App\Components\Package\Modules\MediaAccess\MediaAccessSettings;
use App\Components\Client\Modules\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Validator;
use DateTime;
use DB;
use Session;

use App\Components\Finfo\Modules\Clients\CompanySubscription;
use App\Components\Finfo\Modules\Clients\Billing;
use App\Components\Finfo\Modules\Clients\Invoice;
use App\Components\Finfo\Modules\Registers\Package;
use Illuminate\Support\Facades\Hash;
use App\Components\Client\Modules\Invoice\InvoiceController;

use App\Components\Package\Modules\Announcements\Announcement;
use App\Components\Package\Modules\FinancialAnnualReports\AnnualReports;
use App\Components\Package\Modules\InvestorRelationsCalendar\InvestorRelationsCalendar;
use App\Components\Client\Modules\Webpage\Webpage;
use App\Components\Package\Modules\PressReleases\PressReleases;
use App\Components\Package\Modules\LatestFinancialHighlight\LatestFinancialHighlight;
use App\Components\Package\Modules\LatestFinancialHighlight\LatestFinancialItems;
use App\Components\Package\Modules\Presentation\Presentation;


class ClientBackendController extends Controller
{ 
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data['client'] = Company::join('user','user.id','=','company.admin_id')
                                    ->join('company_subscription_package as csp', 'csp.company_id','=','company.id')
                                    ->join('package','package.id','=','csp.package_id')
                                    ->select('company.*', 'user.first_name', 'user.last_name', 'user.email_address as uemail','user.phone as uphone', 'csp.package_id', 'package.title', 'csp.expire_date')
                                    ->where('company.id','!=',1)
                                    ->where('csp.is_current', 1)
                                    ->where('csp.is_cancel','=', 0)
                                    ->where('csp.is_trail', '=', 0)
                                    ->orderBy('company.id','DESC')
                                    ->get();

        return $this->view('list')->with('data', $data)->with('controller',$this);
    }

    public function approve()
    {
        $input = Input::all();
        $id = $input['h_id'];

        $validator = Validator::make($input, array(
                                'option'=> 'required',
        ));        
        if ( $validator->fails() ) {
            return redirect()->back();
        }
        $company = Company::find($id);

        if($company){
            $subDoman = strtolower($input['option']);
            if (!empty($input['otherText'])) {
                $accountNameSurrfix = explode('-', $company->finfo_account_name1);
                $prefixDomain = preg_replace('/[^A-Za-z0-9\_\-]/', '', $input['otherText']);
                $subDoman = strtolower($prefixDomain."-".end($accountNameSurrfix));
            }
            $finfo_account_name = $company->finfo_account_name =  $subDoman;
            $company->approved_msg = $input['description'];
            $company->approved_by = \Auth::user()->id;
            $company->approved_at = Carbon::now();
            $company->is_active = 1;
            $company->save();
            $value = User::find($company->admin_id)->toArray();
            $subscribed_package = CompanySubscription::companySubcribed($company->id);
            // SAVE DEFAULT MEDIA ACCESS
            $this->storeDefaultMediaAccessSetting($company->id, $value['email_address']);
            // SAVE THEME SETTING
            $this->storeDefaultThemeSetting($company->id);
            $package = $subscribed_package[0]->title;

            if(isset($input['start_trail'])){
                $company_id = $company->id;
                $currency_id = $subscribed_package[0]->currency_id;
                $trail_start = date( 'Y-m-d', strtotime($input['start_trail']));
                $trail_end = date( 'Y-m-d', strtotime($input['end_trail']));
                $expire_date  = explode('-', $trail_end);
                $new_month = $expire_date[0] + 1;
                $new_expire_date = $expire_date[0].'-'.$new_month.'-'.$expire_date[2];

               
                $com_sub = new CompanySubscription;
                $com_sub->package_id = $subscribed_package[0]->package_id;
                $com_sub->company_id = $company_id;
                $com_sub->start_date = $trail_start;
                $com_sub->expire_date = $trail_end;
                $com_sub->is_current = 0;
                $com_sub->is_active = 1;
                $com_sub->currency_id = $currency_id;
                $com_sub->is_trail = 1;
                $com_sub->save();

                $old_com = Company::join('company_subscription_package as csp','csp.company_id','=','company.id')
                                    ->join('invoice', 'invoice.company_subscription_package_id', '=', 'csp.id')
                                    ->where('company.id', '=', $company_id)
                                    ->where('csp.is_current', '=', 1)
                                    ->where('csp.is_active', '=', 1)
                                    ->where('invoice.status', '=', 0)
                                    ->select('csp.id as id')
                                    ->first();
                if(count($old_com) >= 1){
                    $com_sub_id = $old_com->id;
                    $sub_old = CompanySubscription::find($com_sub_id);
                    $sub_old->start_date = $trail_end;
                    $sub_old->expire_date = date("Y-m-d", strtotime($input['end_trail'] ."+1 month"));
                    $sub_old->save();
                }

            }
            

            $value['from_email'] = $this->getFromFinfoEmail();

            $link = config('app.protocol') . "://" . $finfo_account_name . "." . config('app.base_domain') . "/admin/login";
            Mail::queue('resources.views.emails.approved_client', array('value' => $value, 'package' => $package, 'account' => $finfo_account_name, 'link' => $link), function ($message) use($value) {
                $message->subject("Welcome to FINFO");
                $message->from($value['from_email'], 'FINFO Solutions');
                $message->to($value['email_address']);
            });

            return redirect()->back()->with('global', 'Company has been approved.');
        }
    }
    
    public function getClientStatus($id, $companyExpiredDate)
    {
        if($id){
            $company = Company::find($id);
            if($company){
                if($company->approved_by == 0 && $company->rejected_by == 0){
                    return '<span class="label label-warning">Pending</span>';
                }elseif($company->approved_by != 0){
                    $user = User::find($company->approved_by);
                    if (!empty($user)) {
                        if (strtotime($companyExpiredDate) < strtotime(date('Y-m-d h:i:s')) ) {
                            return "<a href='#' title='Expired'><span class='label label-danger'>Expired</span></a>";
                        }
                        return "<a href='#' title='Approved By: ".$user->first_name . " " . $user->last_name ."'><span class='label label-success'>Approved</span></a>";
                    }
                }elseif($company->rejected_by != 0){
                    $user = User::find($company->rejected_by);
                    if (!empty($user)) {
                        return "<a href='#' title='Rejected By: ".$user->first_name . " " . $user->last_name ."'><span class='label label-danger'>Rejected</span></a>";    
                    }
                }
            }
        }
    }

    /* Display the specified resource.
        - param ($id)
        - return specific result
     */
    public function getDetail($id)
    {
        $client = DB::table('company_subscription_package as csp')
                         ->join('company', 'csp.company_id', '=', 'company.id')
                         ->join('user','user.id','=','company.admin_id')                         
                         ->join('package', 'csp.package_id', '=', 'package.id')
                         ->select('csp.*', 'company.*', 'package.title', 'user.first_name','user.last_name','user.email_address as user_email', 'user.active_date')
                         ->where('company.id','=',$id)
                         ->first();
        
        return $this->view('detail')->with('data', $client);
    }

    /* Display all invoices.
        - return result
    */
    public function getInvoice()
    {
        $invoices = Invoice::getInvoice();
        return $this->view('invoice')->with(compact('invoices'));
    }

    /* Display specific invoices.
        - parm ($id)
        - return result
    */
    public function getInvoiceDetail($id)
    {
        $invoice = Invoice::find($id);
        return $this->view('invoice_detail')->with(compact('invoice'));
    }

    /* Display specific client.
        - parm ($id)
        - return result
    */
    public function edit($id)
    {

        $client = Company::join('user','user.id','=','company.admin_id')                         
                         ->select('company.*', 'user.first_name','user.last_name','user.email_address as user_email')
                         ->where('company.id','=',$id)->first();
        
        return $this->view('edit')->with('data', $client);
    }

    public function postEdit()
    {
        $input = Input::all();
        $rules = array(
            'company_name' => 'min:2max:100',
            'finfo_account_name' => 'min:6max:20',
            'finfo_account_name1' => 'min:6max:20',
            'finfo_account_name2' => 'min:6max:20',
            'phone' => 'min:6max:20',
            'address' => 'max:50',
            'email_address' => 'min:5max:50email',
            'website' => 'min:5max:50',
            'established' => '',
            'number_of_employee' => 'numeric',
            'common_stock' => '',
            'main_business_activities' => '',

        );

        $validator = Validator::make($input, $rules);
        if ( $validator->fails() ) {
           return redirect()->back()->withErrors($validator->errors());
        }
        $id = Input::get('id');
        $company = Company::find($id);
        $company->company_name = Input::get('company_name');
        $company->finfo_account_name = Input::get('finfo_account_name');
        $company->finfo_account_name1 = Input::get('finfo_account_name1');
        $company->finfo_account_name2 = Input::get('finfo_account_name2');
        $company->phone = Input::get('phone');
        $company->address = Input::get('address');
        $company->email_address = Input::get('email_address');
        $company->website = Input::get('website');
        $company->established_at = date('y-m-d', strtotime(Input::get('established')));
        $company->number_of_employee = Input::get('number_of_employee');
        $company->common_stock = Input::get('common_stock');
        $company->main_business_activities = Input::get('main_business_activities');
        $company->update();

        return redirect()->back()->with('success', 'Update Successfully!'); 


    }

    public function reject()
    {
        $input = Input::all();
        $id = $input['r_id'];

        $validator = Validator::make($input, array(
                                'message'=> 'required',
        ));        
        if ( $validator->fails() ) {
            return redirect()->back();
        }

        $company = Company::find($id);

        if($company) {

            $company->approved_by = 0;
            $company->rejected_by = \Auth::user()->id;
            $company->rejected_at = Carbon::now();
            $company->rejected_msg = $input['message'] ;
            $company->is_active = 0;
            $company->save();

            $value = User::find($company->admin_id)->toArray();

            Mail::queue('resources.views.emails.rejected_client', array('value' => $value, 'reason' => $input['message']), function ($message) use($value) {
                    $message->subject("FINFO: Account Rejected");
                    $message->from('no-reply@finfo.com', 'FINFO');

                    $message->to($value['email_address']);
            });

            return redirect()->back()->with('global', 'Company has been rejected.');    
        }
        
    }
    private function storeDefaultMediaAccessSetting($companyId, $recipeEmail) {
        $mediaAccessSetting = new MediaAccessSettings();
        $mediaAccessSetting->company_id = $companyId;
        $mediaAccessSetting->auto_approved = 0;
        $mediaAccessSetting->default_expiry_date = Carbon::now();
        $mediaAccessSetting->recipe_notify_email = $recipeEmail;
        $mediaAccessSetting->created_by = \Auth::user()->id;
        $mediaAccessSetting->save();
    }
    private function storeDefaultThemeSetting($companyId) {
		
		$check_clone = DB::table('company')->where('company.id','=',$companyId)->where('is_clone',1)->first();
		
        if(count($check_clone) != 1 ){
			$settings = new Settings\Setting();
			$settings->company_id = $companyId;
			$settings->font_family = 'Open Sans, sans-serif';
			$settings->save();
		}
		
    }
    public function validateDomainName(Request $request)
    {
        $checkAccountName = Company::where('finfo_account_name', '=', $request->input('account'))                                                        
                                    ->count();
        if ($checkAccountName) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    public function checkDomainWithId(Request $request)
    {
        $id = $request->input('id');

        $checkAccountName = Company::where('finfo_account_name', '=', $request->input('finfo_account_name'))
                                    ->where('id', '!=', $id)
                                    ->count();
        if ($checkAccountName) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function checkClientPayment(Request $request)
    {
        $client_id = $request->input('client_id');
        $company = Company::join('company_subscription_package as csp', 'csp.company_id','=','company.id')
                            ->join('invoice', 'invoice.company_subscription_package_id', '=', 'csp.id')
                            ->where('company.id', '=', $client_id)
                            ->where('csp.is_current', '=', 1)
                            ->where('csp.is_active', '=', 1)
                            ->where('invoice.status', '=', 0)
                            ->first();
        if(count($company) >= 1 ){
            return 0; // not yet payment
        }else{
            return 1;
        }
        
    }
	
	
	
	 public function checkClonedetails(Request $request)
    {
        $client_id = $request->input('client_id');
		
        $client = Company::join('user','user.id','=','company.admin_id')
                                    ->join('company_subscription_package as csp', 'csp.company_id','=','company.id')
                                    ->join('package','package.id','=','csp.package_id')
                                    ->select('company.*', 'user.first_name', 'user.last_name', 'user.email_address as uemail','user.phone as uphone', 'csp.package_id', 'csp.currency_id')
									->where('company.id','=',$client_id)->first();
		echo json_encode($client);
        
    }
	
	
	public function cloning(){
		
		
		$input = Input::all();
		
		$old_Cmpy_id = $input['oldcid'];
		 
		$activation_code = md5(time().rand());
		
				
		// SAVE COMPANAY INFORMATION
            $company = $this->storeCompanyInformation($input);
			
		// SAVE USER INFORMATION
            $userId = $this->storeUserInformation($company['company_id'], $activation_code, $input);

        // SAVE COMPANY SUBSCRIBER
            $this->updateAdminIdOfCompanyInformatonOn($company['company_id'], $userId); 
	
					
				for($i=1;$i<=4;$i++)
                {
					DB::table('menu_permissions')->insert(
							['menus_id' => $i, 'company_id' => $company['company_id']]
                                                        );
                }
				
				for($i=6;$i<=12;$i++)
                {
					DB::table('menu_permissions')->insert(
							['menus_id' => $i, 'company_id' => $company['company_id']]
                                                        );
                }
				
				
		    $company_img =  DB::table('company')->where('id',$old_Cmpy_id)->first();
		    
		    $new_cmpy_imag = array('company_logo' => $company_img->company_logo,'favicon' => $company_img->favicon);
		    
		    $update_new_cmpy = DB::table('company')->where('id',$company['company_id'])->update($new_cmpy_imag);
				
			
			$financial = new LatestFinancialHighlight;
		
		$old_financial_highlight = $financial->where('company_id','=',$old_Cmpy_id)->get()->toArray();
		
		if(!empty($old_financial_highlight)){
			
			foreach ($old_financial_highlight as $finan) 
			{
				$finan_id = $finan['id'];
				
				$finan['id'] = NULL;
				
				$finan['company_id'] = $company['company_id'];
				$finan['created_by'] = $userId;
				
				$finan['updated_by'] = 0;
				$finan['deleted_by'] = 0;
			
				$new_item_id = $financial->insertGetId($finan);
				
				$financial_items = new LatestFinancialItems;
				
				$old_financial_highlight_items = $financial_items->where('latest_financial_highlights_id','=',$finan_id)->get()->toArray();
				
				if(!empty($old_financial_highlight_items)){
					foreach ($old_financial_highlight_items as $fitem) 
					{
						$fitem['id'] = NULL;
						$fitem['latest_financial_highlights_id'] = $new_item_id;
						$fitem['created_by'] = $userId;
					
						
						$financial_items->insert($fitem);
					}
				}
				
				
			}
			
		}
	
		$announcements = new Announcement();
		 
		$old_announcement = $announcements->where('company_id', '=', $old_Cmpy_id)->get()->toArray();
		
	if(!empty($old_announcement)){
        foreach ($old_announcement as $announce) 
        {
            $announce['id'] = NULL;
			$announce['company_id'] = $company['company_id'];
			$announce['created_by'] = $userId;
			//$announce['created_at'] = Carbon::now();
			//$announce['announce_at'] = date('Y-m-d');
			$announce['updated_at'] = NULL;
			$announce['updated_by'] = NULL;
			$announce['deleted_by'] = NULL;
			//$announce['deleted_at'] = NULL;
			
            $announcements->insert($announce);
        }
	}
		
		
		$financial_annual_report = new AnnualReports();
		
		$old_financial = $financial_annual_report->where('company_id','=',$old_Cmpy_id)->get()->toArray();
		
	if(!empty($old_financial)){
		foreach($old_financial as $annual){
			
			$annual['id'] = NULL;
			$annual['company_id'] = $company['company_id'];
			$annual['created_by'] = $userId;
						
			$annual['updated_by'] = 0;
			$annual['deleted_by'] = NULL;
			
            $financial_annual_report->insert($annual);
			
		}
	}
	
	
		$events = new InvestorRelationsCalendar();
		
		$old_events = $events->where('company_id','=',$old_Cmpy_id)->get()->toArray();
		
	if(!empty($old_events)){
		foreach($old_events as $event){
			
			$event['id'] = NULL;
			$event['company_id'] = $company['company_id'];
			$event['created_by'] = $userId;
						
			$event['updated_at'] = NULL;
			$event['updated_by'] = NULL;
			$event['deleted_by'] = NULL;
			
            $events->insert($event);
			
		}
	}
	
	
	
		$company_info = new Webpage();
		
		$old_company_info = $company_info->where('company_id','=',$old_Cmpy_id)->get()->toArray();
		
	if(!empty($old_company_info)){
		foreach($old_company_info as $companies){
			
			$companies['id'] = NULL;
			$companies['company_id'] = $company['company_id'];
			$companies['created_by'] = $userId;
						
			$companies['updated_by'] = 0;
			$companies['deleted_by'] = 0;
			
            $company_info->insert($companies);
			
		}
	}
	
	
	  $press_release = new PressReleases();
	  
	  $old_press_release = $press_release->where('company_id','=',$old_Cmpy_id)->get()->toArray();
		
	if(!empty($old_press_release)){
		foreach($old_press_release as $press){
			
			$press['id'] = NULL;
			$press['company_id'] = $company['company_id'];
			$press['created_by'] = $userId;
						
			$press['updated_by'] = NULL;
			$press['deleted_by'] = NULL;
			
            $press_release->insert($press);
			
		}
	}
	
	
	  $presentation = new Presentation();
	  
	  $old_presentation = $presentation->where('company_id','=',$old_Cmpy_id)->get()->toArray();
		
	if(!empty($old_presentation)){
		foreach($old_presentation as $presen){
			
			$presen['id'] = NULL;
			$presen['company_id'] = $company['company_id'];
			$presen['created_by'] = $userId;
						
			$presen['updated_by'] = NULL;
			$presen['deleted_by'] = NULL;
			
            $presentation->insert($presen);
			
		}
	}
	
		
	$settings = new Settings\Setting();
	 
	$old_settings = $settings->where('company_id','=',$old_Cmpy_id)->first();

	$newSettings = $old_settings->replicate();
	$newSettings->company_id = $company['company_id'];
	$newSettings->save();
	
	
	$sliders = new Settings\Slider();
		
	$old_sliders = $sliders->where('company_id','=',$old_Cmpy_id)->get()->toArray();
		
	if(!empty($old_sliders)){
		foreach($old_sliders as $slid){
			
			$slid['id'] = NULL;
			$slid['company_id'] = $company['company_id'];
		
		    $sliders->insert($slid);
			
		}
	}
	
		 	
		return redirect()->back()->with('global', 'Company details has been Cloned.');
		
	}
	
	
	 private function storeCompanyInformation($input)
    {
        //$payment = new Payment();
        //$market = strtolower($payment->getContryCodeByContryName($market));
        $company = new Company;
        $company->finfo_account_name1 = strtolower($input['prefered_url']);
       // $company->finfo_account_name2 = strtolower($request->input('account_name2')."-".$request->input('market'));
        $company->company_name = $input['cname'];
        $company->phone = $input['phone'];
        $company->email_address = $input['email'];
        $company->website = $input['website'];
        $company->address = $input['address'];
        $company->country = $input['country'];
        $company->market = $input['cmarket'];
		$company->is_clone = 1;
        $company->save();

        //$package_id = \Crypt::decrypt($request->input('h_package'));

        $package = Package::find($input['package']);

        $com_sub = new CompanySubscription;
        $com_sub->package_id = $input['package'];
        $com_sub->company_id = $company->id;
        $com_sub->start_date = Carbon::now();
        $com_sub->expire_date = Carbon::now()->addMonths(1);
        $com_sub->is_current = 1;
        $com_sub->is_active = 1;
		$com_sub->currency_id = $input['currency'];
        $com_sub->save();
       
        $invoice = new Invoice;
        $invoice->payment_method_id = 1;
        $invoice->company_subscription_package_id = $com_sub->id;
        $invoice->invoice_date = Carbon::now();
        $invoice->due_date = Carbon::now()->addWeeks(1);
        $invoice->amount =   $package->price;
        $invoice->invoice_number = Carbon::now()->format('Y-m');
        $invoice->transaction_id = 00;        
        $invoice->status = 0;
        $invoice->save();
        $invoice->invoice_number = Carbon::now()->format('Y-m').'-'.$invoice->id;
        $invoice->save();
        return array('company_id' => $company->id, 'csp_id' => $com_sub->id, 'invoice_id' => $invoice->id );
    }
	
	private function storeUserInformation($companyId, $activation_code, $input )
    {
        $user = new User;
        $user->user_type_id = 5;
        $user->company_id = $companyId;
        $user->first_name = $input['fname'];
        $user->last_name =$input['lname'];
        $user->phone =$input['pnumber'];
        $user->email_address = $input['uemail'];
        $user->password =  Hash::make($input['upword']);
        $user->lunch_country =  $input['country'];
        $user->lunch_market =  $input['cmarket'];
        $user->verify_token = $activation_code;
        $user->save();
        return $user->id;
    }

    private function updateAdminIdOfCompanyInformatonOn($companyId, $userId)
    {
        $com = Company::find($companyId);
        $com->admin_id = $userId;
        $com->save();
    }
	
	
}
