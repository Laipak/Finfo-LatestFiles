<?php

namespace App\Components\Finfo\Modules\Pricing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Components\Finfo\Modules\Currency\Currency as Currency;

class PricingController extends Controller 
{
	public function index()
	{
		
		
		if(Input::get('currency') == '') {
            $currency_id = 2;
        }else{
            $currency_id = Input::get('currency');
        }


        $package_module = app('App\Components\Finfo\Modules\Registers\Package_module')->getPackage();
        $currency = DB::table('currency')->whereIn('id', ['2', '11','14'])->lists('name','id');
        //$currency = DB::table('currency')->whereIn('id', ['2'])->lists('name', 'id');
        
        
        $exchange = DB::table('currency')->where('id', $currency_id)->select('exchange_rate','symbol', 'id')->first();

		return $this->view('pricing')
					->with(compact('package_module'))
					->with('currencyData', $currency)
	                ->with('exchange', $exchange)
	                //->with('menus', $this->getMenus())
	                ->with('country', $this->getCountry())
	                ->with('currentCountry', $this->getCurrentContryAccess())
	                ->with('market', $this->getMarket());
	}

	public function getCountry()
	{
		return app('App\Components\Finfo\Modules\Registers\RegisterController')->getCountry();
	}

	public function getCurrentContryAccess()
	{
		return app('App\Components\Finfo\Modules\Registers\RegisterController')->getCurrentContryAccess();
	}

	public function getMarket()
	{
		return app('App\Components\Finfo\Modules\Registers\RegisterController')->getMarket();
	}
}