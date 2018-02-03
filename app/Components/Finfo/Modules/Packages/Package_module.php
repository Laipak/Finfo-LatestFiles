<?php

namespace App\Components\Finfo\Modules\Packages;

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
						->select('name')
						->where('package_module.package_id', $row->id)
						->get();

			$package = array('id'=>$row->id,'name'=>$row->name, 'price'=>$row->price);

			$arr = array('package' => $package, 'module' => $module);
			$data[] = $arr;

		}
		return $data;
	}


	public static function addModule($name)
	{
		$module = DB::table('module')->insert(['name'=>$name]);
	}
}