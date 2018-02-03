<?php

namespace App\Components\Client\Modules\Menus;

use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index()
    {
        return $this->view('list')->with('data','Yooo');
    }
}
