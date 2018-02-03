<?php

namespace App\Components\Finfo\Modules\Clients;

use Illuminate\Database\Eloquent\Model;
use DB;

class CompanySubscription extends Model
{
  	protected $table = 'company_subscription_package';

  	public static function companySubcribed($company_id)
  	{
      $pakcage = DB::table('company_subscription_package as csp')
                    ->join('company', 'csp.company_id', '=', 'company.id')
                    ->join('package', 'csp.package_id', '=', 'package.id')
                    ->select('package.title', 'company.company_name', 'csp.currency_id', 'package.id as package_id')
                    ->where('company.id', $company_id)
                    ->get();

  		return $pakcage;
  	}
}
