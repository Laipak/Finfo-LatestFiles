<?php

namespace App\Components\Package\Modules\FinancialResults;

use App\Http\Controllers\Controller;
use App\Components\Package\Modules\Announcements\AnnouncementController;
use App\Components\Package\Modules\Presentation\PresentationController;
use App\Components\Package\Modules\PressReleases\PressReleaseController;
use App\Components\Package\Modules\Webcast\WebcastController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Components\Package\Modules\LatestFinancialHighlight\LatestFinancialHighlight;
use App\Components\Package\Modules\LatestFinancialHighlight\LatestFinancialItems;
use DateTime;
use Hash;
use Auth;
use Mail;
use Session;
use App\Components\Client\Modules\Home\HomeController;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Components\Client\Modules\Webpage\Webpage as Contents;


class FinancialResultController extends Controller
{
    protected $companyExpire = false;
    protected $countYear = null;
    public function __construct(){
        $homeController = new HomeController();
        if ($homeController->checkCompanyIsExpired() == true) {
            $this->companyExpire = true;
        }
    }
    
    //DISPLAY DATA OF LATEST FINANCIAL
    public function getFinancialResult()
    {
        if($this->companyExpire == true) {
            return redirect::to('/');
        }
        
        $getArchiveFinancialData = null;
        $getArchiveFinancialsItems   = null;
        $getLastestQuarterData = $this->getLastestFinancialInfoIsActive();
        $getFinancialsItems  = $this->getLatestFinancialHighlightsItems($getLastestQuarterData);
        
        
        /* New Financial Info*/
        
        $getLastestInfoData = $this->getNewFinancialInfoIsActive();
        $getDataLastestInfoData = $this->getDataFinancialInfoIsActive();
        $getNewDetailFinancialInfoIsActive = $this->getNewDetailFinancialInfoIsActive();
       
          
        /* New Financial Info*/
        
        
        //echo'<pre>';
        //print_r($getLastestQuarterData);die;
        
        
         $menuPermissions = DB::table('menu_permissions')
                        ->join('module', 'module.id', '=', 'menu_permissions.menus_id')
                        ->select('module.*')
                        ->where('menu_permissions.company_id',Session::get('company_id'))
                         ->orderBy('ordering', 'asc')
                        ->get();
						
        
          $title = Contents::join('company', 'content.company_id','=', 'company.id')
                        ->select('content.name','content.title', 'content.content_description', 'content.created_at', 'company.company_name', 'content.meta_keyword', 'content.meta_description')
                        ->where('company.id','=', Session::get('company_id'))
                        ->where('content.is_delete', '=', 0 )
                        ->where('content.is_active', '=', 1)
                        ->where('content.ordering', '!=', 0)
                        ->orderBy('content.ordering', 'asc')
                        ->get();  
         /* Stock API */
         $stock = DB::table('stock_pricing_and_chart')->where('company_id',Session::get('company_id'))->get();
         foreach($stock as $stock){
             
             $stk = $stock->api_url;
         }
         
    if(!empty($stk)){     
       $curl = curl_init();

         curl_setopt_array($curl, array(
          CURLOPT_URL => $stk,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: 4e36495b-68d5-7322-d88e-a79514dc5628"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        
        $Filedata = $response;
        
        $xml = simplexml_load_string($Filedata);
        
        $result = (string) $xml->snap->equityDomainGroup->equityDomain->tradePrice->last;
        $resultid = (string) $xml->snap->equityDomainGroup->equityDomain->instrumentIdentifier->code;
      }else{
        $result ='';
        $resultid ='';  
      }  
          /* Stock API */  
        
        if (isset($getLastestQuarterData->year)) {
        $menu = DB::table('menu_permissions')->where('company_id',Session::get('company_id'))->get();
            //var_dump($menu); 
       $menu_pers = DB::table('menu_permissions')->where('company_id',Session::get('company_id'))->lists('menus_id');

            // TAB DATA
            $getAnnouncementData = $this->getAnnouncement(null, $getLastestQuarterData->year, $getLastestQuarterData->quarter);
            $getPresentationData = $this->getPresentation($getLastestQuarterData->year, $getLastestQuarterData->quarter);
            $getPressReleaseData = $this->getPressRelease($getLastestQuarterData->year, $getLastestQuarterData->quarter);
            $getWebcastData = $this->getWebcast($getLastestQuarterData->year, $getLastestQuarterData->quarter);
            $tabDev = $this->getSeperateDevTab($getAnnouncementData, $getPresentationData, $getPressReleaseData, $getWebcastData);
            $getArchiveFinancialData = $this->getLastestFinancialInfoIsArchive($getLastestQuarterData->year, $getLastestQuarterData->quarter);
            $getArchiveFinancialsItems = $this->getLatestFinancialHighlightsItems($getArchiveFinancialData);
         
         
       
         
         
            
              return $this->view('frontend.financial-result')
                ->with('title',$title)
                ->with('menuPermissions',$menuPermissions)
                ->with('getFinancialItemsData', $getFinancialsItems)
                ->with('getArchiveFinancialItemsData', $getArchiveFinancialsItems)
                ->with('getCurrentFinancialData', $getLastestQuarterData)
                ->with('result',$result)->with('resultid',$resultid)
                
                ->with('getLastestInfoData', $getLastestInfoData)
                ->with('getDataLastestInfoData', $getDataLastestInfoData)
                ->with('getNewDetailFinancialInfoIsActive', $getNewDetailFinancialInfoIsActive)
                
                
                ->with('getArchiveFinancialData', $getArchiveFinancialData)
                ->with('getAnnouncementData', $getAnnouncementData)
                ->with('getPresentationData', $getPresentationData)
                ->with('getPressReleaseData', $getPressReleaseData)
                ->with('getWebcastData', $getWebcastData)
                ->with('getArchiveResultsList', $this->getArchiveResultsList())
                ->with('getPagination', $this->getFinancialYear())
                ->with('controller', $this)
                ->with('tabDev', $tabDev )
                ->with('menu',$menu)
                ->with('menu_pers',$menu_pers);

        }
        return $this->view('frontend.financial-result')->with('no_data', true)->with('title',$title);
    }
    private function getIdByYearAndQuarter($financial_archive_name) {
        $getArchiveName = explode('-', $financial_archive_name );
        if (isset($getArchiveName[1]) && isset($getArchiveName[2])) {
            $getQuarterId = $this->getQuarterNameByName($getArchiveName[1].'-'.$getArchiveName[2]);
            if (!empty($getQuarterId)) {
                return array('year' => $getArchiveName[0], 'quarter' => $getQuarterId);
            }
        }
        return null;
    }
    private function getFinancialIdByYearAndQuarter($year, $quarter){
        $getData = LatestFinancialHighlight::where('company_id', Session::get('company_id'))
                            ->where('year', $year )
                            ->where('quarter', $quarter)
                            ->where('is_deleted', 0)
                            ->first();
        if (!empty($getData)) {
            return $getData;
        }
        return false;
    }
    // GET ARCHIVE DATA AFTER CLICK FINANCIAL RESULT LIST
    public function getArchivedFinancailResult($financial_archive_name){    
        $getYearAndQuarter = $this->getIdByYearAndQuarter($financial_archive_name);
        $getAnnouncementData = $this->getAnnouncement(null, $getYearAndQuarter['year'], $getYearAndQuarter['quarter']);
        $getPresentationData = $this->getPresentation($getYearAndQuarter['year'], $getYearAndQuarter['quarter']);
        $getPressReleaseData = $this->getPressRelease($getYearAndQuarter['year'], $getYearAndQuarter['quarter']);
        $getWebcastData = $this->getWebcast($getYearAndQuarter['year'], $getYearAndQuarter['quarter']);
        $tabDev = $this->getSeperateDevTab($getAnnouncementData, $getPresentationData, $getPressReleaseData, $getWebcastData);
        
        $getFinancialObject = $this->getFinancialIdByYearAndQuarter($getYearAndQuarter['year'], $getYearAndQuarter['quarter']);
        $getItems = $this->getLatestFinancialHighlightsItems($getFinancialObject);
        $getFinancialObject->quarterName = $this->getQuarterLetter($getYearAndQuarter['quarter']);
        $getFinancialObject->year = $getYearAndQuarter['year'];
        $getFinancialObject->dateQuarterEnd = $this->getQuarterDateEnd($getYearAndQuarter['quarter'], $getYearAndQuarter['year']);
        
        if (isset($getYearAndQuarter['year'])) {
            $getArchiveFinancialData = $this->getLastestFinancialInfoIsArchive($getYearAndQuarter['year'], $getYearAndQuarter['quarter']);
            $getArchiveFinancialsItems = $this->getLatestFinancialHighlightsItems($getArchiveFinancialData);
            return $this->view('frontend.financial-result')
                ->with('getFinancialItemsData', $getItems)
                ->with('getArchiveFinancialItemsData', $getArchiveFinancialsItems)
                ->with('getCurrentFinancialData', $getFinancialObject)
                ->with('getArchiveFinancialData', $getArchiveFinancialData)
                ->with('getAnnouncementData', $getAnnouncementData)
                ->with('getPresentationData', $getPresentationData)
                ->with('getPressReleaseData', $getPressReleaseData)
                ->with('getWebcastData', $getWebcastData)
                ->with('getArchiveResultsList', $this->getArchiveResultsList())
                ->with('getPagination', $this->getFinancialYear())
                ->with('controller', $this)
                ->with('tabDev', $tabDev );
        }
        return $this->view('frontend.financial-result')->with('no_data', true);
    }
    private function getSeperateDevTab($getAnnouncementData, $getPresentationData, $getPressReleaseData, $getWebcastData){
        $countDev = 0;
        if (isset($getAnnouncementData) && !empty($getAnnouncementData)) {
            $countDev += 1;
        }
        if (isset($getPresentationData) && !empty($getPresentationData)) {
            $countDev += 1;
        }
        if (isset($getPressReleaseData) && !empty($getPressReleaseData)) {
            $countDev += 1;
        }
        if (isset($getWebcastData) && !empty($getWebcastData)) {
            $countDev += 1;
        }
        if ($countDev == 0 ) {
            return 12;
        }
        $dev = 12 / $countDev;
        return $dev;
    }
     private function getLastestFinancialInfoIsArchive($year, $quarter) {
         
         
        
        $getFinancials = LatestFinancialHighlight::where('company_id', Session::get('company_id'))
                ->where('is_archive', 1)
                ->where('status', 0)
                ->where('year', '<>', $year)
                ->where('year',  $year - 1)
                ->where('quarter',  $quarter)
                ->where('is_deleted', 0)
                ->orderBy('year', 'desc')
                ->orderBy('quarter', 'desc')
                ->get()->first();
        if (!empty($getFinancials)) {
            $getFinancials->quarterName = $this->getQuarterLetter($getFinancials->quarter);
            $getFinancials->dateQuarterEnd = $this->getQuarterDateEnd($getFinancials->quarter, $getFinancials->year );
            return $getFinancials;
        }
        return false;
    }
    private function getLastestFinancialInfoIsActive() {
      $query = LatestFinancialHighlight::where('company_id', Session::get('company_id'))
                ->where('is_archive', 0);
                //->where('status', 0)
                
                if(Auth::user())
                 {
                     
                     $query->where('status', '!=',1);
                     
                 }
                 
                 
                 else
                 {
                     
                     $query->where('status', 0);
                 }
                
                
                $query->where('is_deleted', 0);
                $query->orderBy('year', 'desc');
                $query->orderBy('quarter', 'desc');
                $getFinancials = $query->get()->first();
                
                
                
        if (!empty($getFinancials)) {
            $getFinancials->quarterName = $this->getQuarterLetter($getFinancials->quarter);
            $getFinancials->dateQuarterEnd = $this->getQuarterDateEnd($getFinancials->quarter, $getFinancials->year );
            return $getFinancials;
        }
        return false;
    }
    
    
    /* New Financial Code */
    
    private function getNewFinancialInfoIsActive() {
      $query = LatestFinancialHighlight::where('company_id', Session::get('company_id'))
                ->where('is_archive', 0);
                if(Auth::user())
                 {
                     $query->where('status', '!=',1);
                 }
                 else
                 {
                      $query->where('status', 0);
                 }
                $query->where('is_deleted', 0);
                $query->orderBy('year', 'desc');
                $query->orderBy('quarter', 'desc');
                $query->groupBy('year');
                $getFinancials = $query->get();
                return $getFinancials;
      
    }
    
    private function getNewDetailFinancialInfoIsActive() {
      $query = LatestFinancialItems::where('value','!=', '');
                $getFinancials = $query->get();
                return $getFinancials;
      
    }
    
    
    private function getDataFinancialInfoIsActive() {
      $query = LatestFinancialHighlight::where('company_id', Session::get('company_id'))
                ->where('is_archive', 0);
                if(Auth::user())
                 {
                     $query->where('status', '!=',1);
                 }
                 else
                 {
                      $query->where('status', 0);
                 }
                $query->where('is_deleted', 0);
                $query->orderBy('year', 'desc');
                $query->orderBy('quarter', 'desc');
                $getFinancials = $query->get();
                return $getFinancials;
      
    }
    

    
  /* New Financial Code */ 
    
    private function getLatestFinancialHighlightsItems($financialData){
        if (!empty($financialData)) {
            $getFinancialItems = LatestFinancialItems::where('latest_financial_highlights_id', $financialData->id)->get();
            return $getFinancialItems;
        }
        return null;
    }
            
    public function getQuarterLetter($value)
    {
    	if($value == 1){
    		return 'First Quarter';
    	}elseif($value ==2){
    		return 'Second Quarter';
    	}elseif($value == 3){
    		return 'Third Quarter';
    	}elseif($value == 4){
    		return 'Fourth Quarter';
    	}
    }
    private function getQuarterDateEnd($quater, $year) {
        $endMonth = null;
        $quater = 1;
        switch($quater) {
            case 1: $endMonth = 3; break;
            case 2: $endMonth = 6; break;
            case 3: $endMonth = 9; break;
            case 4: $endMonth = 12; break;
        }
       // $getDay = cal_days_in_month(CAL_GREGORIAN, $endMonth, $year);
        
         $getDay = '';
        $date = $getDay." ".date('F',  mktime(0, 0, 0, $endMonth))." ".$year;
        return $date;
    }
    public function getAnnouncement($sort = null, $year = null, $quarter = null){
        $AnnouncementController = new AnnouncementController();
        if($sort) {
            return $AnnouncementController->getSortByYear($sort);
        } else {
            $getAnnouncementController  = $AnnouncementController->getLatestQuarterFrontend($year, $quarter);
            if (!empty($getAnnouncementController)) {
                $data = array(
                    'option_selected' => $getAnnouncementController->option_selected,
                    'file_upload' => $getAnnouncementController->file_upload,
                    'wysiwyg' => $getAnnouncementController->description,
                    'link' => $getAnnouncementController->link,
                );
                return $data;
            }
            return null;
        }
    }
    public function getPresentation($year = null, $quarter = null)
    {
        $PresentationController = new PresentationController();
        $getPresentationController =  $PresentationController->getLatestQuarterFrontend($year, $quarter);
         if (!empty($getPresentationController)) {
            return $getPresentationController->upload;
        }
        return null;
    }
    public function getPressRelease($year = null, $quarter = null)
    {
        $PressReleaseController = new PressReleaseController();
        $getPressReleaseController = $PressReleaseController->getLatestQuarterFrontend($year, $quarter);
         if (!empty($getPressReleaseController)) {
             $data = array(
                    'option_selected' => $getPressReleaseController->option_selected,
                    'file_upload' => $getPressReleaseController->upload,
                    'wysiwyg' => $getPressReleaseController->description,
                    'link' => $getPressReleaseController->link
                );
            return $data;
        }
        return null;
    }
    public function getWebcast($year = null, $quarter = null)
    {
        $WebcastController = new WebcastController();
        $getWebcast = $WebcastController->getLastQuarterFrontend($year, $quarter);
        if (!empty($getWebcast)) {
            return $getWebcast->url;
        }
        return null;
    }
    public function getFinancialYear() {
        $getFinancials = LatestFinancialHighlight::where('company_id', Session::get('company_id'))->where('is_deleted', 0)
                ->where('status', 0)
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->get();
        return $getFinancials;
    }

    private function getFinancialQuarter($year){
        $getFinancialsQuarter = LatestFinancialHighlight::where('company_id', Session::get('company_id'))->where('is_deleted', 0)
                    ->where('year',  $year)
                    ->where('status', 0)
                    ->orderBy('quarter', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->limit(4)
                    ->get();
        $quaters = array();
        for($index = 1 ; $index < 5; ) {
            $quaters[$index] = null;
            $index++;
        }
        if (count($getFinancialsQuarter) > 1) {
            foreach($getFinancialsQuarter as $quarter) {
                $quaters[$quarter->quarter] = $quarter->id;
            }
            krsort($quaters);
            return $quaters;
        }
        return null;
    }
    public function getCountYearOfFinanancial() {
        
        
       
        $getFinancials = LatestFinancialHighlight::where('company_id', Session::get('company_id'))->where('is_archive', 1)->where('is_deleted', 0)
                ->where('status', 0)
                ->groupBy('year')
                ->get();
        return count($getFinancials);
    }
    public function getCountYear(){
        return $this->countYear;
    }
    private function getArchiveResultsList( $div = null)
    {
        if (!empty($this->getFinancialYear()) && count($this->getFinancialYear()) > 0) {
            foreach( $this->getFinancialYear() as $valueYear) {
                $quarter = $this->getFinancialQuarter($valueYear->year);
                if (!empty($quarter)) {
                    $div .= '<div class="col-md-3">';
                    $div .= "<h4>".$valueYear->year."</h4>";   
                    $div .= '<div class="list-item">';
                        foreach($quarter as $quaterKey => $quaterValue) {
                            if (!empty($quaterValue)) {
                                $quarterName = $this->getQuarterLetter($quaterKey);
                                $financailString = strtolower(str_replace(" ", '-', $quarterName));
                                $linkURL = route('package.financial-result.financial_archive_name', $valueYear->year.'-'.$financailString);
                                $div .= "<a href='$linkURL'><p>".$quarterName."</p></a>";
                            }
                        }
                    $div .= '</div>';
                    $div .= '</div>';
                    $this->countYear += 1;
                }
            }
        }
        return $div;
    }
    public function generateArchiveResultDownloadPDF($financialId) {
        $getArchiveData = LatestFinancialItems::where('company_id', Session::get('company_id'))
                        ->join('latest_financial_highlights as lfh', 'lfh.id', '=', 'latest_financial_highlights_items.latest_financial_highlights_id')
                        ->where('lfh.id', $financialId)
                        ->where('lfh.is_archive', 1)
                        ->get();
        $quarterTitle = $this->getQuarterMonthById($getArchiveData[0]->quarter);
        $data['getArchiveData'] = $getArchiveData;
        $data['titleQuarter'] = $quarterTitle." Quarter, ". $getArchiveData[0]->year ;
        $pdfData = PDF::loadView('app.Components.Package.Modules.FinancialResults.views.download.pdf', $data );
        return $pdfData->download('Financial Results for '.$quarterTitle.' Quarter.pdf');
    }
}


