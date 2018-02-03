<?php

namespace App\Components\Finfo\Modules\Revenue;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Invoice extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoice';

    public function getReport($date, $view)
    {

        $current_month = date("m", strtotime($date));
        $current_year = date("Y", strtotime($date));

    	$invoice = new Invoice();
        if($view == 'month'){
            $data =     $invoice->join('company_subscription_package as csp','invoice.company_subscription_package_id','=','csp.id')
                            ->join('company','csp.company_id','=','company.id')
                            ->distinct('invoice_date')
                            ->select(DB::raw('DATE(invoice_date) as date'), 
                                    DB::raw('sum(status=0)AS unpaid,
                                                sum(status=1)AS paid,
                                                sum(status=2)AS overdue,
                                                sum(status=3)AS cancel'
                                            )
                                    )
                            ->where(DB::raw('MONTH(invoice_date)'), $current_month)
                            ->where(DB::raw('YEAR(invoice_date)'), $current_year)
                            ->groupBy('date')
                            ->get();
        }else{
            $data =     $invoice->join('company_subscription_package as csp','invoice.company_subscription_package_id','=','csp.id')
                            ->join('company','csp.company_id','=','company.id')
                            ->distinct('invoice_date')
                            ->select(DB::raw('MONTH(invoice_date) as date'), 
                                    DB::raw('sum(status=0)AS unpaid,
                                                sum(status=1)AS paid,
                                                sum(status=2)AS overdue,
                                                sum(status=3)AS cancel'
                                            )
                                    )
                            ->where(DB::raw('YEAR(invoice_date)'), $current_year)
                            ->groupBy('date')
                            ->get();
        }

        return $data;
    }
}