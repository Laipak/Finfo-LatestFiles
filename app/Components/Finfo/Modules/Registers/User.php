<?php

namespace App\Components\Finfo\Modules\Registers;

use Illuminate\Database\Eloquent\Model;
use DB;


class User extends Model
{
	protected $table = 'user';

	protected $fillable = ['user_type_id', 'company_id', 'first_name', 'last_name', 'email_address', 'password', 'verify_token'];

	public static function getAdmin($admin_email)
	{
		$admin = DB::table('company as c')
					->join('user', 'c.admin_id', '=', 'user.id')
					->select('user.first_name')
					->where('user.email_address', $admin_email)
					->get();
		return $admin;
	}
}