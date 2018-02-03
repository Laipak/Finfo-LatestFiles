<?php

namespace App\Components\Finfo\Modules\Clients;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Billing extends Model
{
  	protected $table = 'billing_info';
        protected $fillable = ['company_id', 'address1', 'address2', 'city', 'zip_code', 'country', 'state', 'phone'];
}
