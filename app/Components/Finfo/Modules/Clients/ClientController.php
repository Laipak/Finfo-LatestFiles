<?php

namespace App\Components\Finfo\Modules\Clients;

use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function index()
    {
        return $this->view('list');
    }
}
