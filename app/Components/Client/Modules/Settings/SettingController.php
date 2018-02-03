<?php

namespace App\Components\Client\Modules\Settings;


use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Components\Client\Modules\Settings\Setting;
use App\Components\Client\Modules\Settings\Slider;
use App\Components\Client\Modules\Company\Company as Company;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Image;
use File;
use DateTime;
use Excel;

use App\Components\Client\Modules\Settings\TwitterAPIExchange;


class SettingController extends Controller
{
    // Get the setting
    public function index()
    {
        $settings = Setting::all();

        if(Session::has('company_id')){
            $company_id = Session::get('company_id');
            $settings = DB::table('setting')->where('company_id',$company_id)->first();
            $company = Company::findOrFail($company_id);  
            $cmp_info = DB::table('content')->where('company_id',$company_id)->where('name','company-info')->first();
            
            $social_data = DB::table('social_data')->where('company_id',$company_id)->first();
        }

        if(is_null($settings))
        {
            $this->createOneRow();
            return redirect('admin/setting')->with(compact('settings'));
        }
        
        return $this->view('setting')->with(compact('settings'))->with('data', $company)->with('cmp_info', $cmp_info)->with('social_data',$social_data);
    }

    // Create setting option for the company's homepage
    public function createOneRow()
    {
        if(Session::has('company_id')){
            $company_id = Session::get('company_id');
            DB::table('setting')->insert(array(
                'company_id' => $company_id,
            ));
        }
    }

    // update after changing
    public function change()
    {
        $data = Input::all();
        
  
        $company = Company::findOrFail(Session::get('company_id'));
        $setting_id = Input::get('setting_id');
    	$setting = Setting::find($setting_id);
        $setting->google_analytic = Input::get('google_analytic');    	
        $setting->font_color = Input::get('font_color');
    	$setting->font_family = Input::get('font_family');
        $setting->background_color = Input::get('bgcolor');
        $setting->container_color = Input::get('cnt_color');
        $setting->theme_color = Input::get('theme_color');
        $setting->slider_description = Input::get('slider_description');
        $setting->description_color = Input::get('description_color');
        $setting->footer_color = Input::get('footer_color');
        $setting->footer_text = Input::get('footer_text');
        
         
        $content = DB::table('content')->where('company_id',$company->id)->where('name','company-info')->count();
        
   
       if($content > '0' ){
        
         $update = array('company_id' => $company->id,
                        'name' => 'company-info',
                        'title' => $data['company_title'], 
                        'content_description' => $data['company_description'],
                        'is_active' => '1',
                        'ordering' => '0',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $company->id,
                        'updated_at' => date('Y-m-d H:i:s')
                        );
        
        $update_info = DB::table('content')->where('company_id',$company->id)->where('name','company-info')->update($update);

         }else{
            
             $insert = array('company_id' => $company->id,
                        'name' => 'company-info',
                        'title' => $data['company_title'], 
                        'content_description' => $data['company_description'],
                        'is_active' => '1',
                        'ordering' => '0',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $company->id,
                        'updated_at' => date('Y-m-d H:i:s')
                        );
                              
        $insert_info = DB::table('content')->insert($insert);
            
        }
        
        
         $social_data = DB::table('social_data')->where('company_id',$company->id)->count();
        
   
 
        
        // UPLOAD COMPANY INFO IMAGE
        if(!empty($data['data_company_info'])){
            if(isset($data['file_company_info']) && !empty($data['file_company_info'])) {
                if (!empty($setting->company_info_img)) {
                    // REMOVE OLD BANNER IMAGE
                    File::delete($setting->company_info_img);
                }
                $file = $data['file_company_info'];
                // MOVE FROM TEMP TO MAIN PATH
                $file->move('img/companyinfo/', $data['data_company_info'] );
                // REMOVE FROM TEMP
                File::delete($data['data_company_info']);
                $setting->company_info_img = 'img/companyinfo/'. $_FILES['file_company_info']['name'];
            }
        }else{
            $setting->company_info_img = "";
        }
        
        // UPLOAD FINNANCIALS IMAGE
        if(!empty($data['financials'])){
            if(isset($data['file_financials']) && !empty($data['file_financials'])) {
                if (!empty($setting->financial_img)) {
                    // REMOVE OLD BANNER IMAGE
                    File::delete($setting->financial_img);
                }
                $file = $data['file_financials'];
                // MOVE FROM TEMP TO MAIN PATH
                $file->move('img/financials/', $data['financials'] );
                // REMOVE FROM TEMP
                File::delete($data['financials']);
                $setting->financial_img = 'img/financials/'. $_FILES['file_financials']['name'];
            }
        } else {
            $setting->financial_img = "";
        }
        
        // UPLOAD BANNER IMAGE    
        if(!empty($data['img_banner'])){
            if(isset($data['logo']) && !empty($data['logo'])) {
                if (!empty($setting->banner_img)) {
                    // REMOVE OLD BANNER IMAGE
                    File::delete($setting->banner_img);
                }
                $file = $data['logo'];
                // MOVE FROM TEMP TO MAIN PATH
                $file->move('img/banner/', $data['img_banner'] );
                // REMOVE FROM TEMP
                File::delete($data['img_banner']);
                $setting->banner_img = 'img/banner/'. $_FILES['logo']['name'];
            }
        }else{
            $setting->banner_img = "";
        }
        
        // UPLOAD COMPANY LOGO
        if(!empty($data['data_company_logo'])){
            if(isset($data['company_logo']) && !empty($data['company_logo'])) {
                if (!empty($company->company_logo)) {
                    // REMOVE OLD LOGO IMAGE
                    File::delete($company->company_logo);
                }
                $file = $data['company_logo'];
                // MOVE FROM TEMP TO MAIN PATH
                $file->move('img/logo/', $data['data_company_logo'] );
                // REMOVE FROM TEMP
                File::delete($data['data_company_logo']);
                $data['company_logo'] = 'img/logo/'. $_FILES['company_logo']['name'];
            }
        }else{
              $data['company_logo'] = "";
        }
        
        // // UPLOAD FAVERITE ICON
        if(!empty($data['favicon'])) {                
            if (isset($data['file_favicon']) && !empty($data['file_favicon'])) {
                $destinationPathFavicons = "favicons/".Session::get('account').'/';
                $filename = str_random(8).'.ico';
                $file = $data['file_favicon'];
                $file->move($destinationPathFavicons, $filename );
                File::delete($data['favicon']);
                $data['favicon']  = $destinationPathFavicons.$filename;
                if (!empty($company->favicon)) {
                    File::delete($company->favicon);
                }
            }
        }else{
            $data['favicon'] = "";
        }
        $company->update($data);
    	$setting->update();
    	Session::flash('success', 'Update Successfully!');
    	return redirect('admin/setting');
    }

    public function storeImg($data)
    {
        $destinationPath    = "img/banner/";
        $img = $data['img_banner'];
        $setting = Setting::find($data['setting_id']);
        $old_banner   = $setting->banner_img;
        if($old_banner != $img){           
            $obj_image  = Image::make($img);
            $mime       = $obj_image->mime();
            if ($mime == 'image/png'){
                $extension = 'png';
            }else{
                $extension = 'jpg';
            }
            $filename  = str_random(8).'.'.$extension;
            $full_path  = $destinationPath.$filename;
            rename($img, $full_path);
            if (!empty($old_banner)) {
                File::delete($old_banner);
            }
            return $filename;
        }else{
            return $img;
        }
    }

    public function doCompanyUploadLogo() 
    {
        $data = Input::all();
        $destinationPath = "files/temp/";
        $file       = $data['company_logo'];
        $filename   = $_FILES['company_logo']['name'];
        $full_path  = $destinationPath.$filename;
        $file->move($destinationPath, $filename);
        return $full_path;
    }
    
    /* New Banners */
    
    public function dobannersUpload(){
        
        $data = Input::all();
        
       $company_id = Session::get('company_id');
       
       $sliders = DB::table('slider_images')->where('company_id',$company_id)->count();
       
     
       if($sliders < '5' ){
        
         $company_id = Session::get('company_id');
        
        $sliders = DB::table('slider_images')->where('company_id',$company_id)->count();
        
        $slider_order = $sliders + 1;
        
        $destinationPath = "img/banner/".$company_id;
        
       if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777,true);
        }
        
        $destinationPath = "img/banner/".$company_id."/";
        
       
        
        $file       = $data['banner_imag'];
        $filename   = $_FILES['banner_imag']['name'];
        $full_path  = $destinationPath.$filename;
        $file->move($destinationPath, $filename);
        
        
        $insert_array = array('company_id' => $company_id,
                              's_id' => $slider_order,
                              'banner_img' => $full_path, 
                              'slide_order' => $slider_order);
                              
                              
                              
        $insert_banner = DB::table('slider_images')->insert($insert_array);
        
        $get_sliders = DB::table('slider_images')->where('company_id',$company_id)->orderBy('slide_order', 'asc')->get();
        
        $output = '';
        
        $s_c = 1;
        if(isset($get_sliders)){
            
            foreach($get_sliders as $slide){
                
                
             $output .='
                <li id="'.$slide->s_id.'" class="all-scroll">
                <a class="text-right text-red remove-slider-logo remove-img" title="Remove" onClick="remove_slides('.$slide->id.')"><i class="fa fa-trash-o fa-lg" style="color:#fff"></i></a>
                <img src="/'.$slide->banner_img.'" height="100px">
                <span>#'.$s_c. ' Side</span>
                
                </li>';
                
            $s_c++;            
                
            }
            
            
        }
        
          $sliders = DB::table('slider_images')->where('company_id',$company_id)->count();
          
        echo $output . '^' . $sliders;
        
        
       }
       
    }   
       
    public function dobannersLoad(){
        
        
     
         $company_id = Session::get('company_id');
        
        $sliders = DB::table('slider_images')->where('company_id',$company_id)->count();
        
     
        
        $get_sliders = DB::table('slider_images')->where('company_id',$company_id)->orderBy('slide_order', 'asc')->get();
        
        $output = '';
        
        $s_c = 1;
        
        if(isset($get_sliders)){
            
            foreach($get_sliders as $slide){
                
                
               $output .='
                <li id="'.$slide->s_id.'" class="all-scroll">
                <a class="text-right text-red remove-slider-logo remove-img" title="Remove" onClick="remove_slides('.$slide->id.')"><i class="fa fa-trash-o fa-lg" style="color:#fff"></i></a>
                <img src="/'.$slide->banner_img.'" height="100px">
                <span>#'.$s_c. ' Side</span>
                
                </li>';
                
                $s_c++;
                
            }
            
            
        }
        
        
        echo $output . '^' . $sliders;;
        
        
    
        
    }
    
    
    public function dobannersOrder(){
        
        
        $sli_order = $_POST['list_order'];

        $company_id = Session::get('company_id');

        $sli = explode(',' , $sli_order);
        
        $i = 1 ;

        foreach($sli as $id) {
        
        
            $update_array = array('slide_order'=>$id);
            
            $slide_update = DB::table('slider_images')->where('company_id',$company_id)->where('s_id',$i)->update($update_array);
                
        
        $i++;
        
        }    
        
        
    }
    
    
     public function dobannerDelete(){
        
        
        $slide_id = $_POST['banner_id'];
        
        $company_id = Session::get('company_id');
        
        if(!empty($slide_id)){
            
              
        $get_sliders = DB::table('slider_images')->where('company_id',$company_id)->where('id',$slide_id)->delete();
            
            
        }

     
         
        if($get_sliders){ 
            
        $sliders = DB::table('slider_images')->where('company_id',$company_id)->count();
        
     
        
        $get_sliders = DB::table('slider_images')->where('company_id',$company_id)->orderBy('slide_order', 'asc')->get();
        
        $output = '';
        $s_c = 1;
        if(isset($get_sliders)){
            
            foreach($get_sliders as $slide){
                
                
                $output .='
                <li id="'.$slide->s_id.'" class="all-scroll">
                <a class="text-right text-red remove-slider-logo remove-img" title="Remove" onClick="remove_slides('.$slide->id.')"><i class="fa fa-trash-o fa-lg" style="color:red"></i></a>
                <img src="/'.$slide->banner_img.'" height="100px">
                <span>#'.$s_c. ' Side</span>
                
                </li>';
                
                $s_c++;
                
            }
            
            
        }
        
        
        echo $output . '^' . $sliders;;
        
        
    }
    
        
    }
    
    
    
    /* End of new Banners */

    public  function doCompanyUploadFavicon()
    {
        return app('App\Components\Client\Modules\Company\CompanyController')->doCompanyUploadFavicon();
    }

    public function storeLogo($data)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $old_logo = $company->company_logo;
        $destinationPath    = "img/logo/";
        $logo       = $data['company_logo'];
        if($old_logo != $logo){
            $obj_image  = Image::make($logo);
            $mime       = $obj_image->mime();

            if ($mime == 'image/png')
                $extension = 'png';
            else
                $extension = 'jpg';

            $filename              = str_random(8).'.'.$extension;
            $data['company_logo']  = $destinationPath.$filename;
            rename($logo, $data['company_logo']);
            if(!empty($old_logo)){
                File::delete($old_logo);
            }
            return $filename;
        }else {
            return $logo;
        }
    }
     public function doSettingUploadImage()
    {
        $data = Input::all();
        $destinationPath = "files/temp/";
        if (isset($data['file_financials'])) {
            $file = $data['file_financials'];
            $filename   = $_FILES['file_financials']['name'];
        }elseif(isset($data['file_company_info'])) {
            $file = $data['file_company_info'];
            $filename   = $_FILES['file_company_info']['name'];
        }
        $file->move($destinationPath, $filename);
        $full_path  = $destinationPath.$filename;
        return $full_path;
    }
    
  
  
}
