<?php

namespace App\Components\Package\Modules\FinancialAnnualReports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnnualReports extends Model 
{
	use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */


    protected $table = 'financial_annual_reports';

    protected $dates = ['deleted_at'];


}
