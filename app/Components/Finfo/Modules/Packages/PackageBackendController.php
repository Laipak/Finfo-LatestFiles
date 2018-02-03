<?php

namespace App\Components\Finfo\Modules\Packages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\Finfo\Modules\Packages\Package;
use Illuminate\Support\Facades\Input;

class PackageBackendController extends Controller
{ 
    // public function __construct()
    // {
    //  $this->middleware('auth');
    // }

	public function index()
	{
		$packages = Package_module::getPackage();
		return $this->view('package')->with(compact('packages'));
	}

	public function addModule()
	{
		return $this->view('add_module');
	}
}