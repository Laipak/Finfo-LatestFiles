<?php

namespace App\Components\Finfo\Modules\Clients;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Invoice extends Model
{
  	protected $table = 'invoice';

  	public static function getInvoice()
  	{
  		$invoices = DB::table('invoice')->get();
  		return $invoices;
  	}
}
