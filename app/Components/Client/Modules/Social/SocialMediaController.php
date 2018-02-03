<?php

namespace App\Components\Client\Modules\Social;


use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
//use App\Components\Client\Modules\Settings\Setting;
//use App\Components\Client\Modules\Settings\Slider;
use App\Components\Client\Modules\Company\Company as Company;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Image;
use File;

class SocialMediaController extends Controller
{
    // Get the setting
    public function index()
    {
    
        
        return $this->view('social');
    }

  
}
