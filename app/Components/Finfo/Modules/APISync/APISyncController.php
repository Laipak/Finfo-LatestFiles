<?php

namespace App\Components\Finfo\Modules\APISync;

use App\Http\Controllers\Controller;

class APISyncController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        if(\Auth::check() && \Auth::user()->user_type_id != 3)
        {
            App::abort(403);
        }
    }
    public function index() {
       die("call me");
    }
}
