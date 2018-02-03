<?php

namespace App\Components\Package\Modules\RealTimeStockPricingAndCharts;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use Auth;
use Session;
use DB;
use App\Stockid;
use Carbon\Carbon;
use App\Components\Client\Modules\Webpage\Webpage as Contents;

class StockBackendController extends Controller
{

	public function getStockAndPrice()
	{
		$setting = $this->checkStockRecord();
		$stock_values = $this->APIConnectDataStockPriceAPIRequest();
		
	$title = Contents::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.name','content.title', 'content.content_description', 'content.created_at', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                        ->where('company.id','=', Session::get('company_id'))
                        ->where('content.is_delete', '=', 0 )
                        ->where('content.is_active', '=', 1)
                        ->orderBy('content.ordering', 'asc')
                        ->get();
		
		return $this->view('backend.pricing-and-chart')->with(compact('title'))->with('stock_value', $stock_values)->with('setting', $setting);
	}

	public function getEdit()
	{
		$setting = $this->checkStockRecord();
		return $this->view('backend.edit')->with('setting', $setting);
	}

	public function getSave()
	{
		$validate = Validator::make(Input::all(), [
            'button_color'   	=> 'required',
            'text_color'      	=> 'required',
            'highlight_color'  => 'required',
            'background_color'  => 'required',
            'border_thickness'  => 'required',
            'view_option'    	=> 'required',
            'refresh_frequency' => 'required',
            'chart_template'    => 'required',            
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
        $data = Input::all();

        $stock_price = StockPricing::where('company_id', Auth::user()->company_id)->first();
        $stock_price->button_color 	= $data['button_color'];
        $stock_price->text_color 	= $data['text_color'];
        $stock_price->highlight_color 	= $data['highlight_color'];
        $stock_price->background_color 	= $data['background_color'];
        $stock_price->border_thickness 	= $data['border_thickness'];
        $stock_price->view_option 		= $data['view_option'];
        $stock_price->refresh_frequency = $data['refresh_frequency'];
        $stock_price->chart_template 	= $data['chart_template'];
        $stock_price->updated_by 		= Auth::user()->id;
        $stock_price->update();

        return Redirect::route('package.admin.stock.form')->with('global', 'Pricing and chart updated.');

	}

	public function checkStockRecord()
	{
		$setting = StockPricing::where('company_id', Session::get('company_id'))->first();
		if(count($setting) <= 0){
			$setting = new StockPricing();
			$setting->company_id 		= Session::get('company_id');
			$setting->refresh_frequency = 30000;
			$setting->view_option 		= 1;
			$setting->chart_template 	= 'spline';
			$setting->text_color 		= '#959090';
			$setting->button_color		= '#e63e3e';
			$setting->highlight_color	= '#bd7676';
			$setting->background_color	= '#ffffff';
			$setting->save();
		}

		return $setting;
	}

	public function saveNewAPI()
	{
		$validate = Validator::make(Input::all(), [
            //'api_url'   	=> 'required|url',       
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
		$api = StockPricing::where('company_id', Auth::user()->company_id)->first();
        $api->api_url 	= Input::get('api_url');
        $api->stock_url 	= Input::get('api_charturl');
        $api->update();

        return Redirect::route('package.admin.stock')->with('global', 'Data API & Chart API Updated.');
	}




   /* NEW  */
   
   
   	public function saveNewChartAPI()
	{
		$validate = Validator::make(Input::all(), [
           // 'api_charturl'   	=> 'required|url',       
        ]);

        if ($validate->fails())
        {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
		$api = StockPricing::where('company_id', Auth::user()->company_id)->first();
        $api->stock_url 	= Input::get('api_charturl');
        $api->update();

        return Redirect::route('package.admin.stock')->with('global', 'Data API & Chart API Updated.');
	}
   
   
   public function removechartAPI()
   {
        $api = StockPricing::where('company_id', Auth::user()->company_id)->first();
        $api->stock_url 	= "";
        $api->update();
        return Redirect::route('package.admin.stock')->with('global', 'API was removed.');
    }
   
   
   /* NEW */







    public function stockid(Request $request)
	{

         $api = StockPricing::where('company_id', Auth::user()->company_id)->first();
         
        $api->stockid 	= Input::get('stockid');
        
        $api->save();
        return Redirect::route('package.admin.stock')->with('global', 'Pricing and chart updated.');
	}








	public function getData()
	{
		$filter = false;
		//$test = app('App\Components\Finfo\Modules\APISync\APISyncBackendController')->APIConnectDataStockistAPIRequest();
		$stock_chart = $this->APIConnectDataStockListAPIRequest();
		if(Input::has('filter_value') && Input::get('filter_value') != ''){
			$current = Carbon::now();
			$value = Input::get('filter_value');
			if($value == 1 || $value == 2){
				$date = $current->subMonths(1); 
			}else{
				$date = $current->subMonths(Input::get('filter_value')); 
			}
			$date = date("Ym", strtotime($date));
			$filter = true;
		}

		$data = [];

      
        $stock_chart = json_decode($stock_chart, true);


		if(isset($stock_chart['header']['dataDateTime'])){

			foreach($stock_chart as $t){
			  
				if($filter){
				//	$arr_date = date("Ym", strtotime($t['dataDateTime']));
						
				//	if($arr_date <= $date){
				//		break;
				//	}
				}
			
			
			$date = $stock_chart['header']['dataDateTime'];
			
			
			$date1=  substr($date,0,10);
		
		
			$price = $stock_chart['snap']['equityDomainGroup']['equityDomain']['tradePrice']['last'];
			
		   $volume = $stock_chart['snap']['equityDomainGroup']['equityDomain']['tradeVolume']['totalVolume'];		
					
				//$t_date = new Carbon($t['Date']);
			$arr = [
							//'x' => date("Y/m/d", strtotime($date1)),
							'x' => date("Y/m/d", strtotime($date1)),
							'price' =>$price,
							'volume' => $volume / 1000000,
						];
				$data[] = $arr;
			}
		}
		

		return $data;
	}

	public function getPrice()
	{
		$stock = $this->APIConnectDataStockPriceAPIRequest();

		if($stock){
			if($stock[0]['Movement'] < 0){
				$move_icon = 'down';
			}else{
				$move_icon = 'up';
			}
			$data = [
						'AsAt' => date("d M Y h:i a", strtotime($stock[0]['AsAt'])),
						'Close'		=> $stock[0]['Close'],
						'High'		=> $stock[0]['High'],
						'Low'		=> $stock[0]['Low'],
						'Movement'	=> str_replace('-', '',$stock[0]['Movement']),
						'move_icon'	=> $move_icon,
						'Volume'	=> number_format($stock[0]['Volume'] / 1000000, 2),
					];
			return $data;
		}
	}

	public function APIConnectDataStockListAPIRequest()
    {
    	
    	$setting = $this->checkStockRecord();
    	$api_url = $setting->api_url;
    	if(!empty($api_url)){
    		//$api_url = "http://request.weblink.com.au/api.asmx/StockHist?symbol=bhp&type=1min";
    		$api_url = str_replace('StockQuote', 'StockHist', $api_url)."&type=1min";
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $api_url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);
                $data = curl_exec($curl);
                curl_close($curl);
               // $getData = json_decode($data, true);
                $getData = json_encode(simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA));
                return $getData;
    	}
        return null;
    }

    public function APIConnectDataStockPriceAPIRequest()
    {
    	$setting = $this->checkStockRecord();
    	$api_url = $setting->api_url;
    	if(!empty($api_url)){
    		//$api_url = "http://request.weblink.com.au/api.asmx/StockQuote?symbol=BHP";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            $data = curl_exec($curl);
            curl_close($curl);
            $getData = json_decode($data, true);
            return $getData;
    	}
        return null;
    }
    public function removeAPI() {
        $api = StockPricing::where('company_id', Auth::user()->company_id)->first();
        $api->api_url 	= "";
        $api->update();
        return Redirect::route('package.admin.stock')->with('global', 'API was removed.');
    }
}