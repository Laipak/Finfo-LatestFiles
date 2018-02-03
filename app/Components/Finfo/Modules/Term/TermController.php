<?php

namespace App\Components\Finfo\Modules\Term;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Input;


class TermController extends Controller 
{
	public function index()
	{
		return $this->view('term');
	}

	public function privacy()
	{
		return $this->view('privacy');
	}

	public function agreement() {
		return $this->view('agreement');	
	}

}