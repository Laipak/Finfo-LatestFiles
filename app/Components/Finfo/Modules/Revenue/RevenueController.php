<?php

namespace App\Components\Finfo\Modules\Revenue;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Components\Finfo\Modules\Clients\Company;
use App\Components\Finfo\Modules\Clients\CompanySubscription;
use App\Components\Finfo\Modules\Registers\Package;
//use App\Components\Finfo\Modules\Billing\Invoice;
use Illuminate\Support\Facades\DB;
use Hash;
use Mail;
use Carbon\Carbon;
use Session;
use Auth;
use Excel;

class RevenueController extends Controller
{
     public function __construct()
     {
        $this->middleware('auth');

        if(\Auth::check() && \Auth::user()->user_type_id != 3)
        {
            App::abort(403);
        }
     }

    public function index($view = 'month')
    {
        $date = Carbon::now();
        if(Input::get('date') != ''){
            $date = Input::get('date');
        }

        // if($view == '' || $view == 'month'){
        //     $view == 'month';
        // }else{
        //     $view == 'year';
        // }


        $current_month  = date("m", strtotime($date));
        $current_year   = date("Y", strtotime($date));
        $link_next     =  strtotime(date("Y-m-d", strtotime($date)) . " +1 ".$view);
        $link_next     =  date('Y-m-d', $link_next);
        $link_prevouis =  strtotime(date("Y-m-d", strtotime($date)) . " -1 ".$view);
        $link_prevouis =  date('Y-m-d', $link_prevouis);
        
        $invoice        = new Invoice();
        $data['value']  = $invoice->getReport($date, $view);
        $data['filter'] = [
                            'current_date'  => $date,
                            'next'     => $link_next,
                            'prevouis' => $link_prevouis,
                            ];

        return $this->view('list')->with('data', $data)->with('view', $view);
    }

    public function getData()
    {
        $date = Carbon::now();
        $view = Input::get('view');
        if(Input::get('date') != ''){
            $date = Input::get('date');
        }
        
        $current_month = date("m", strtotime($date));
        $current_year = date("Y", strtotime($date));

        $invoice = new Invoice();
        $data   = $invoice->getReport($date, $view);
        $chart  = array(['Date', 'New Inovices', 'Paid Invoices']);

        if(count($data) >= 1)
            foreach ($data as $value) {
                $date = $value->date;
                if($view == 'year')
                    $date = date('F', mktime(0, 0, 0, $value->date, 10));

                $arr = array($date, (int)$value->unpaid, (int)$value->paid);
                array_push($chart, $arr);
            }
        else{
            $arr = array(0, 0, 0);
            array_push($chart, $arr);
        }
        return $chart;
    }

    public function exportExcel($date, $view)
    {
        $invoice = new Invoice();
        $date_format = "F Y";
        if($view == 'year')
            $date_format = "Y";
        $file_name = 'report-'.date($date_format, strtotime($date));
        $data['value'] = $invoice->getReport($date, $view);
        $data['title'] = 'Report for '.date($date_format, strtotime($date));
        $data['view']  = $view;

        Excel::create($file_name, function($excel) use($data) {

            $excel->sheet('Sheetname', function($sheet) use($data) {
                $sheet->row(2, function($row) {
                    $row->setBackground('#CBF2CB');
                });

                $sheet->mergeCells('A2:E2');
                //$sheet->fromArray($data);

                $sheet->row(2,[$data['title']]);
                $sheet->appendRow(['Date', 'New Invoices', 'Paid Invoices']);

                foreach ($data['value'] as $value) {
                    $date = $value->date;
                    if($data['view'] == 'year')
                        $date = date('F', mktime(0, 0, 0, $value->date, 10));

                    $sheet->appendRow([$date, $value->unpaid, $value->paid]);
                }

            });

        })->export('xls');
    }
}
