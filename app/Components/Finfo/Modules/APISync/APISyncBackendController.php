<?php

namespace App\Components\Finfo\Modules\APISync;

use App\Http\Controllers\Controller;

class APISyncBackendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    private function getStockPriceAPIRequest() {
        return 'http://request.weblink.com.au/api.asmx/StockPrice?symbol=bhp';
    }
    private function getStockQuoteAPIRequest() {
        return 'http://request.weblink.com.au/api.asmx/StockQuote?symbol=BHP';
    }
    private function getStockistAPIRequest() {
        return 'http://request.weblink.com.au/api.asmx/StockHist?symbol=bhp&type=1min';
    }
    private function getOptionSummaryAPIRequest() {
        return 'http://request.weblink.com.au/api.asmx/OptionSummary?symbol=bhp';
    }
    private function getWarrantSummaryAPIRequest() {
        return 'http://request.weblink.com.au/api.asmx/WarrantSummary?symbol=bhp';
    }
    private function getHeadlineAPIRequest() {
        return 'http://request.weblink.com.au/api.asmx/Headline';
    }
    private function getSecDetailsAPIRequest($option) {
        switch ($option) { 
            case "details" : 
                $restAPIUrl = "http://request.weblink.com.au/api.asmx/SecDetails?symbol=BHP&type=details";
                break;
            case "ratios" :  
                $restAPIUrl = "http://request.weblink.com.au/api.asmx/SecDetails?symbol=BHP&type=ratios";
                break;
            case "dividends" :  
                $restAPIUrl = "http://request.weblink.com.au/api.asmx/SecDetails?symbol=BHP&type=dividends";
                break;
            case "asx index constituents" :  
                $restAPIUrl = "http://request.weblink.com.au/api.asmx/IndexList?index=XAO";
                break;
        }
        return $restAPIUrl;
    }
    
    public function APIConnect()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->getStockQuoteAPIRequest());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $data = curl_exec($curl);
        curl_close($curl);
        $getData = json_decode($data, true);
        echo "<pre>";
        print_r($getData);
        echo "</pre>";
    }
    public function APIConnectDataStockPriceAPIRequest()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->getStockQuoteAPIRequest());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $data = curl_exec($curl);
        curl_close($curl);
        $getData = json_decode($data, true);
        return $getData;
    }

    public function APIConnectDataStockistAPIRequest()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->getStockistAPIRequest());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $data = curl_exec($curl);
        curl_close($curl);
        $getData = json_decode($data, true);
        return $getData;
    }
}
