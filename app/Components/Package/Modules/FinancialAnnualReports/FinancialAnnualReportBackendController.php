<?php

namespace App\Components\Package\Modules\FinancialAnnualReports;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use Auth;
use Image;
use File;
use Carbon\Carbon;

class FinancialAnnualReportBackendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getList()
    {
        $report = AnnualReports::where('company_id', Auth::user()->company_id)->where('is_active', '!=', 2)->get();
        
      

        return $this->view('backend.list')->with('data', $report)->with('controller', $this);
    }

    public function getform($id = '')
    {
        $year = ['' => 'Select financial year'];
        $now = date('Y', strtotime(Carbon::now())) + 1;
        for($i = 0 ; $i <= 10; $i++ ){
            $now = $now - 1;
            $year[$now] = $now;
        }

        if($id != ''){
            $report = AnnualReports::findOrFail($id);

            return $this->view('backend.edit')->with('data', $report)->with('year', $year);
        }
    	return $this->view('backend.create-financial-annual-report')->with('year', $year);
    }

    public function postSave($id = '')
    {
    	$data = Input::all();
    	$rules = array(
            'title' => 'required|min:5|max:50',
            'file_upload'       => 'required',
            'financial_year'    => 'required',
            'file' => 'max:500000'
        );
    
        $validator = Validator::make($data, $rules);

        if ( $validator->fails() ) {
        	return redirect()->back()->withInput()->withErrors($validator);
        }

        if(isset($data['myfile'])){
            $data['file_upload'] = $this->doUploadPdf($data);
            if($id != ''){
                $ann = AnnualReports::findOrFail($id);
                File::delete($ann->file_upload);
            }
        }

        if($id == ''){
            $destinationPath    = "files/reports/";

            if($data['cover_image'] != ''){
                $logo = $data['cover_image'];
                $obj_image  = Image::make($logo);
                $mime       = $obj_image->mime();
                
                if ($mime == 'image/png')
                    $extension = 'png';
                else
                    $extension = 'jpg';

                $filename             = str_random(8).'.'.$extension;
                $data['cover_image']  = $destinationPath.$filename;
                rename($logo, $data['cover_image']);
            }
       
       
       
       
       
       
       
                
       
         
                                if(Input::get('preview')) 
                                
                                {
                           
                           
                           
                                        $report = new AnnualReports();
                                        $report->company_id     = Auth::user()->company_id;
                                        $report->title          = $data['title'];
                                        $report->description    = $data['description'];
                                        $report->financial_year = $data['financial_year'];
                                        $report->file_pdf       = $data['file_upload'];
                                        $report->cover_image    = $data['cover_image'];
                                        $report->publish_date    = date('y-m-d', strtotime($data['publish_date'])) ;
                                         /*test*/
                                        $report->is_active      = 2;
                                        $report->created_by     = Auth::user()->id;
                                        
                                }       
                    
                    
                                     else
                                    {
                                    
                                            $getData = AnnualReports::where('financial_year', '=', Input::get('financial_year'))
                                            ->where('is_active','=',2)
                                            ->where('company_id','=', Auth::user()->company_id)
                                            ->count();
                                            if($getData > 0)
                                            {
                                                 $press = AnnualReports::where('financial_year', '=', Input::get('financial_year'))
                                                 ->where('is_active','=',2)
                                                ->where('company_id','=', Auth::user()->company_id)
                                                ->first();
                                                 $report = AnnualReports::find($press->id);
                                             
                                            }
                                            
                                         else
                                            {
                                                  $report = new AnnualReports;
                                            }
                    
                    
                                        $report->company_id     = Auth::user()->company_id;
                                        $report->title          = $data['title'];
                                        $report->description    = $data['description'];
                                        $report->financial_year = $data['financial_year'];
                                        $report->file_pdf       = $data['file_upload'];
                                        $report->cover_image    = $data['cover_image'];
                                        $report->publish_date    = date('y-m-d', strtotime($data['publish_date'])) ;
                                         /*test*/
                                        $report->is_active      = 0;
                                        $report->created_by     = Auth::user()->id;
                    
                                    }
                    
                    
                    
                    
                    
                    
                            
                    
                    
                    
                    $report->save();
        
                    return Redirect::route('package.admin.financial-annual-reports')->with('global', 'Report created.');
                }else{
                    $this->updateReport($data);
        
                    return Redirect::route('package.admin.financial-annual-reports')->with('global', 'Report updated.');
                }

    }

    public function doUploadPdf($data)
    {
        $data = Input::all();
        $destinationPath = "files/reports/";
        $file       = $data['myfile'];
        $filename   = str_random(8).$_FILES['myfile']['name'];
        $full_path  = $destinationPath.$filename;
        $file->move($destinationPath, $filename);

        return $full_path;
    }

    public function doTempUploadCover()
    {
        $data = Input::all();
        
        $destinationPath = "files/temp/";
        $file       = $data['file_image'];
        $filename   = $_FILES['file_image']['name'];
        $full_path  = $destinationPath.$filename;
        $file->move($destinationPath, $filename);
        return $full_path;
    }

    public function updateReport($data)
    {
        
    
    
    
                                if(Input::get('preview'))
                     
                                                                          {         
                                                                            $id = $data['id'];
                                            $destinationPath    = "files/reports/";
                                            $report     = AnnualReports::findOrFail($id);
                                    
                                            if($data['cover_image'] != ''){
                                                $logo = $data['cover_image'];
                                                $obj_image  = Image::make($logo);
                                                $mime       = $obj_image->mime();
                                                
                                                if ($mime == 'image/png')
                                                    $extension = 'png';
                                                else
                                                    $extension = 'jpg';
                                    
                                                $filename             = str_random(8).'.'.$extension;
                                                $data['cover_image']  = $destinationPath.$filename;
                                                rename($logo, $data['cover_image']);
                                                $old_cover = $report->cover_image;
                                                File::delete($old_cover);
                                            }
                                        
                                        
                                         $report = new AnnualReports();
                                        $report->company_id     = Auth::user()->company_id;
                                        $report->title          = $data['title'];
                                        $report->description    = $data['description'];
                                        $report->financial_year = $data['financial_year'];
                                        $report->file_pdf       = $data['file_upload'];
                                        $report->cover_image    = $data['cover_image'];
                                        $report->publish_date    = date('y-m-d', strtotime($data['publish_date'])) ;
                                         /*test*/
                                        $report->is_active      = 2;
                                        $report->created_by     = Auth::user()->id;
                                        $report->save();
                                        
                                        
                                      }
                                      
                                      else
                                      
                                      
                                      {
                                                                          $id = $data['id'];
                                        $destinationPath    = "files/reports/";
                                        $report     = AnnualReports::findOrFail($id);
                                
                                        if($data['cover_image'] != ''){
                                            $logo = $data['cover_image'];
                                            $obj_image  = Image::make($logo);
                                            $mime       = $obj_image->mime();
                                            
                                            if ($mime == 'image/png')
                                                $extension = 'png';
                                            else
                                                $extension = 'jpg';
                                
                                            $filename             = str_random(8).'.'.$extension;
                                            $data['cover_image']  = $destinationPath.$filename;
                                            rename($logo, $data['cover_image']);
                                            $old_cover = $report->cover_image;
                                            File::delete($old_cover);
                                     }
                                            $report->title          = $data['title'];
                                            $report->description    = $data['description'];
                                            $report->financial_year = $data['financial_year'];
                                            $report->file_pdf       = $data['file_upload'];
                                            $report->cover_image    = $data['cover_image'];
                                            $report->publish_date    = date('y-m-d', strtotime($data['publish_date'])) ;
                                            $report->updated_by     = Auth::user()->id;
                                            $report->update();  
                                            
                                            $getDels = AnnualReports::where('is_active','=',2)->get();
                                                    foreach($getDels as $detdel)
                                                        {
                                   
                                                              $pressdelet= AnnualReports::find($detdel->id);
                                                              $pressdelet->delete();
                                                        }
                                          
                                          
                                      }
                                        
                                        
                                        
                                        
                                        
                                        
    
                                
    }


    public function getDelete($id)
    {
        $report = AnnualReports::findOrFail($id);
        $report->is_delete      = 1;
        $report->deleted_by     = Auth::user()->id;
        $report->update();

        $img    = $report->cover_image;
        $pdf    = $report->file_pdf;
        if($report->delete()){
            File::delete($img);
            File::delete($pdf);
            return Redirect::route('package.admin.financial-annual-reports')->with('global-danger', 'Report deleted.');
        }
    }

    public function publishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $report = AnnualReports::whereIn('id',$input['check'])->update(['is_active' => '1']);

            return redirect()->route('package.admin.financial-annual-reports')->with('global', 'Report(s) published.');

        }
    }

    public function unPublishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $report = AnnualReports::whereIn('id',$input['check'])->update(['is_active' => '0']);

            return redirect()->route('package.admin.financial-annual-reports')->with('global', 'Report(s) unpublished.');

        }
    }

    
    public function deleteMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $report = AnnualReports::whereIn('id', $input['check'])->get();

            if(count($report) >= 1){
                foreach($report as $item){
                    $report = AnnualReports::find($item['id']);
                    $report->is_delete      = 1;
                    $report->deleted_by     = Auth::user()->id;
                    $report->update();
                    File::delete($report->file_pdf);
                    File::delete($report->cover_image);
                }
            }


            $delete = AnnualReports::whereIn('id',$input['check']);
            if($delete->delete())
            {
                return redirect()->route('package.admin.financial-annual-reports')->with('global-danger', 'Report(s) deleted.');
            }
        }
    }

}
