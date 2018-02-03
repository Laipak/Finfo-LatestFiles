<?php

namespace App\Components\Finfo\Modules\Clients;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  	protected $table = 'company';

  	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_name', 'finfo_account_name', 'phone', 'address', 'email_address', 'website', 'established_at', 'number_of_employee', 'common_stock', 'main_business_activities'];

}
