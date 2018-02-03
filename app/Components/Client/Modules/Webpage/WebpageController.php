<?php

namespace App\Components\Client\Modules\Webpage;

use App\Http\Controllers\Controller;

class WebpageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        if(\Auth::check() && \Auth::user()->user_type_id != 5)
        {
            App::abort(403);
        }
    }
    public function index() {
        return view('list');
    }
}
