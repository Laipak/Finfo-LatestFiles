<?php

namespace App\Components\Finfo\Modules\Registers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Package_module extends Model
{
	/* This function will be used with register
        - select package and module
        - return result in array
  */
	public static function getPackage()
	{
		$packages = DB::table('package')->get();

		$data = array();
		$arr = array();

		foreach($packages as $row) {

			$module = DB::table('package_module')
						->join('module', 'package_module.module_id', '=', 'module.id')
						->select('name', 'package_module.ordering as ordering')
						->where('package_module.package_id', $row->id)
						->orderBy('ordering')
						->get();

			$package = array('id'=>$row->id,'name'=>$row->name, 'price'=>$row->price, 'price_aud' => $row->price_aud);

			$arr = array('package' => $package, 'module' => $module);
			$data[] = $arr;

		}
		return $data;
	}
}
