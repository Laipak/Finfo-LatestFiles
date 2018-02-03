<?php

namespace App\Components\Finfo\Modules\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Components\Finfo\Modules\Clients\Company;
use App\Components\Finfo\Modules\Clients\CompanySubscription;
use App\Components\Finfo\Modules\Clients\Billing;
use App\Components\Finfo\Modules\Registers\Package;
use App\Components\Finfo\Modules\Currency\Currency as Currency;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use File;
use Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class BillingController extends Controller
{
    private $setGenerateInvoiceKey = 'AAAAB3NzaC1yc2EAEapoRq7PwOJLmFzzdW3DvVtjPOSd15NWmxXmdB*8776s43355611-HTpXw==';

    // Get admin login page
    public function getInvoice($status)
    {   
        $invoice = new Invoice();

        $invoices = $invoice->join('company_subscription_package as csp','invoice.company_subscription_package_id','=','csp.id')
                            ->join('company','csp.company_id','=','company.id')
                            ->join('package', 'csp.package_id', '=', 'package.id')
                            ->where('invoice.status', $status)
                            ->select('invoice.*','company.admin_id as admin_id', 'csp.expire_date', 'csp.start_date', 'package.price as amount' )
                            ->orderby('invoice.id', 'desc')
                            ->get();
        return $this->view('list')->with('invoices', $invoices)->with('controller',$this)->with('status', $status);
    }

    public function getStatus($id)
    {
        if($id == 0){
            return '<span class="label label-info">Unpaid</span>';
        }elseif($id == 1){
            return '<span class="label label-success">Paid</span>';
        }elseif($id == 2){
            return '<span class="label label-warning">Overdue</span>';
        }else{
            return '<span class="label label-danger">Cancelled</span>';
        }
    }

    public function getStatusText($id)
    {
        if($id == 0){
            return 'Unpaid';
        }elseif($id == 1){
            return 'Invoice Paid';
        }elseif($id == 2){
            return 'Overdue';
        }else{
            return 'Cancelled';
        }
    }

    public function getPaymentMethod($id)
    {
        $payment = DB::table('payment_method')->where('id', $id)->get();
        if($payment){
            return $payment[0]->name;
        }
        return "None";
    }

    public function getFormInvoice($id = '')
    {
        if($id != ''){
            $invoices = Invoice::join('company_subscription_package as csp','invoice.company_subscription_package_id','=','csp.id')
                            ->join('company','csp.company_id','=','company.id')
                            ->where('invoice.id', $id)->select('invoice.*','company.admin_id as admin_id')->first();
            if($invoices)
            return $this->view('edit-invoice')->with('data', $invoices)->with('controller',$this);
        }
        //return $this->view('create-invoice');
    }

    public function getDeleteInvoice($id = '', $status = '')
    {
        $invoice = Invoice::find($id);
        $invoice->is_delete     = 1;
        $invoice->deleted_by    = Auth::user()->id;
        $invoice->update();
        $invoice->delete();

        return Redirect::route('finfo.admin.billing.invoice', $status)->with('global-danger', 'Invoice deleted.');
    }

    public function getInvoiceDetail($id = '')
    {
        $data['invoice']    = Invoice::findOrFail($id);
        $data['csp']        = CompanySubscription::findOrFail($data['invoice']->company_subscription_package_id);
        $data['company']    = Company::findOrFail($data['csp']->company_id);
        $data['billing']    = Billing::where('company_id', $data['csp']->company_id)->first();
        $data['user']       = User::findOrFail($data['company']->admin_id);
        $package            = Package::findOrFail($data['csp']->package_id);
        $currency_id        = $data['csp']['currency_id'];
        if($currency_id == 0){
            $currency_id = 1;
        }
        if($currency_id == 11){
            $price = $package->price_aud;
        }else{
            $price = $package->price;
        }
        $data['currency']       = Currency::findOrFail($currency_id);

        $data['package']    = array(
                                    'package_name' => $package->name,
                                    'package_price'=> $price,
                                    'package_period_title' => '1 Month'
                                );
        $data['url_back']   = route('finfo.admin.billing.invoice', $data['invoice']->status);
        $data['dl_route_name'] = 'finfo.admin.billing.invoice.download';

        return app('App\Components\Client\Modules\Invoice\InvoiceController')->showViewInvoice($data);
    }

    public function getInvoiceDownload($id = '')
    {
        $arr['invoice']    = Invoice::findOrFail($id);
        $arr['csp']        = CompanySubscription::findOrFail($arr['invoice']->company_subscription_package_id);
        $arr['company']    = Company::findOrFail($arr['csp']->company_id);
        $arr['billing']    = Billing::where('company_id', $arr['csp']->company_id)->first();
        $arr['user']       = User::findOrFail($arr['company']->admin_id);
        $package            = Package::findOrFail($arr['csp']->package_id);
        $currency_id        = $arr['csp']['currency_id'];
        if($currency_id == 0){
            $currency_id = 1;
        }
        $arr['currency']       = Currency::findOrFail($currency_id);
        $arr['package']    = array(
                                    'package_name' => $package->name,
                                    'package_price'=> $package->price,
                                    'package_period_title' => '1 Month'
                                );
        $data['data'] = $arr;

        return app('App\Components\Client\Modules\Invoice\InvoiceController')->getDLInvoice($data);
    }

    public function deleteMulti()
    {
        $input = Input::all();
        $status = $input['status'];
        if(is_array($input['check']))
        {
            $invoices = Invoice::whereIn('id',$input['check'])->get();

            if(count($invoices)){
                foreach($invoices as $item){
                    $inv = Invoice::find($item['id']);
                    $inv->is_delete = 1;
                    $inv->deleted_by = \Auth::user()->id;
                    $inv->update();
                }
            }

            $delete = Invoice::whereIn('id',$input['check']);
            if($delete->delete())
            {
                return redirect()->route('finfo.admin.billing.invoice', $status)->with('global-danger', 'Invoice(s) deleted.');
            }
        }
    }

    public function getInvoiceUpdate($id = '')
    {
        $data = Input::all();
        $invoice = Invoice::findOrFail($id);
        $invoice_status = $invoice->status;
        $invoice->due_date  =   date("Y-m-d", strtotime($data['due_date']));
        $invoice->amount    =   $data['amount'];
        $invoice->payment_method_id = $data['payment_method'];
        $invoice->save();
        return redirect::route('finfo.admin.billing.invoice', $invoice_status)->with('global', 'Invoice updated.');
    }

    public function getClientName($id)
    {
        $user = User::where('id', $id)->first();
        if($user){
            return $user->first_name.' '.$user->last_name;
        }
        
    }
    private function sendEmailVerifyToClient($email, $companyName)
    {
        $values = array(
            'company_name' => $companyName,
            'company_email' => $email,
        );
        $values['from_email'] = $this->getFromFinfoEmail();
        
        Mail::queue('app.Components.Finfo.Modules.Billing.views.email.confirm-expire-company',array('companyData'=> $values), function ($message) use($values){
            $message->subject("FINFO's account information");
            $message->from($values['from_email'], 'FINFO Solutions');
            $message->to($values['company_email']);
        });
    }
    public function generateClientInvoice($getGenerateInvoiceKey){  
        if ($this->setGenerateInvoiceKey == $getGenerateInvoiceKey) {
            $getCompanyData = Company::join('company_subscription_package as csp', 'company.id', '=','csp.company_id')
                    ->select('csp.expire_date', 'company.company_name', 'company.email_address', 'company.id')
                    ->where('csp.is_active', 1)
                    ->where('csp.is_current', 1)
                    ->where('company.is_delete', 0)
                    ->whereDate('csp.expire_date', '=', date('Y-m-d', strtotime(Carbon::now()->addWeek())))
                    ->get();
            foreach($getCompanyData as $company) {
                $this->sendEmailVerifyToClient($company->email_address, $company->company_name);
            }
        }
    }
}
