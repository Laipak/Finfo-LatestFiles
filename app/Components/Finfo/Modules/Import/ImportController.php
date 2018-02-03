<?php

namespace App\Components\Finfo\Modules\Import;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use App\Components\Finfo\Modules\Menus\Menu;
use App\Components\Finfo\Modules\Webpage\Webpage as Webpage;
use Input;
use DB;
use Excel;
use Illuminate\Support\Facades\Hash;


class ImportController extends Controller 
{
 	
  public function getImport()
    {
        
       return $this->view('home');
    
        
    }
    
  public function importExcel()
	{
		if(Input::hasFile('import_file')){
			$path = Input::file('import_file')->getRealPath();
			$data = Excel::load($path, function($reader) {
			})->get();
			
			
			if(!empty($data) && $data->count()){
				foreach ($data as $key => $value) {
				
			//	echo'<pre>';
			//    print_r($value);die;
				
					$id = DB::table('company')->insertGetId(
                         [
    					'company_name' => $value->company_name,
    					'finfo_account_name' => $value->domain_name,
    					'finfo_account_name1' => $value->domain_name,
    					'finfo_account_name2' => $value->domain_name,
    					'email_address' => $value->email,
    					'company_logo' =>'img/logo/'. $value->company_logo_name,
    					'is_active' => '1',
    					'approved_by' => '48',
    					'approved_at' =>  date('Y-m-d H:i:s'),
    					'market' => $value->lunch_market
    					]
                    );
                    
                    
                     $user = DB::table('user')->insertGetId(
                         [
    					'user_type_id' => '5',
    					'company_id' => $id,
    					'first_name' => $value->first_name,
    					'last_name' => $value->last_name,
    					'email_address' => $value->email,
    					'password' => Hash::make($value->password),
    					'verify_token' => md5(time().rand()),
    					'created_at' => date('Y-m-d H:i:s'),
    					'is_active' => '1',
    					'lunch_market' => $value->lunch_market
    					]
                    );
                    
                    
                    $update =   DB::table('company')
                                ->where('id', $id)
                                ->update(['admin_id' => $user]);
                                
                    
                    $package = DB::table('company_subscription_package')->insertGetId(
                         [
    					'package_id' => '2',
    					'company_id' => $id,
    					'is_current' => '1',
    					'is_active' => '1',
    					'start_date' => date('Y-m-d H:i:s'),
    					'expire_date' => date('2018-m-d H:i:s'),
    					'currency_id' => '2',
    					'is_trail' => '1',
    					'created_at' => date('Y-m-d H:i:s'),
    					'updated_at' => date('Y-m-d H:i:s')
    					]
                    );
                    
                    
                    
                    $data = array(
                        array('menus_id'=>'1', 'company_id'=> $id),
                        array('menus_id'=>'2', 'company_id'=> $id),
                        array('menus_id'=>'3', 'company_id'=> $id),
                        array('menus_id'=>'4', 'company_id'=> $id),
                        array('menus_id'=>'6', 'company_id'=> $id),
                        array('menus_id'=>'7', 'company_id'=> $id),
                        array('menus_id'=>'8', 'company_id'=> $id),
                        array('menus_id'=>'9', 'company_id'=> $id),
                        array('menus_id'=>'10', 'company_id'=> $id),
                        array('menus_id'=>'11', 'company_id'=> $id),
                        array('menus_id'=>'12', 'company_id'=> $id),
                       );
                     DB::table('menu_permissions')->insert($data); 
                     
                    
                    $setting = DB::table('setting')->insertGetId(
                         [
    					'company_id' => $id,
    					'theme_color' => $value->primary_color,
    					'background_color' => $value->secondary_color,
    					'slider_description' =>  $value->masthead_title,
    					'company_info_img' => 'img/companyinfo/'. $value->company_info_image,
    					'created_at' => date('Y-m-d H:i:s'),
    					'updated_at' => date('Y-m-d H:i:s')
    					]
                    ); 
                     
                     
                     
                    $dataSlider = array(
                        array('company_id'=> $id,'s_id'=> '1','banner_img'=>'img/banner/'. $id.'/'.$value->masthead_image_1,'slide_order'=> '1'),
                        array('company_id'=> $id,'s_id'=> '2','banner_img'=>'img/banner/'. $id.'/'.$value->masthead_image_2,'slide_order'=> '2'),
                        );
                     DB::table('slider_images')->insert($dataSlider);  
                     
                     
                     
                     $aboutus = DB::table('content')->insertGetId(
                         [
    					'company_id' => $id,
    					'name' => 'about-us',
    					'title' => 'About Us',
    					'content_description' =>  $value->about_us,
    					'is_active' => '1',
    					'ordering' => '1',
    					'created_by' => $user,
    					'created_at' => date('Y-m-d H:i:s'),
    					'updated_at' => date('Y-m-d H:i:s')
    					]
                    );
                    
                     $announcements = array(
                        array('title'=>$value->announcement_title_1, 'quarter'=>'1', 'year'=>date('Y'),'announce_at'=>date('Y-m-d H:i:s'),'created_at'=>date('Y-m-d H:i:s'),'created_by'=>$user,'file_upload'=>'samplePDF.pdf','company_id'=>$id,'financial_apply'=>'1','description'=> $value->announcement_1,'option_selected'=>'pdf'),
                        
                        array('title'=>$value->announcement_title_2, 'quarter'=>'1', 'year'=>date('Y'),'announce_at'=>date('Y-m-d H:i:s'),'created_at'=>date('Y-m-d H:i:s'),'created_by'=>$user,'file_upload'=>'samplePDF.pdf','company_id'=>$id,'financial_apply'=>'1','description'=> $value->announcement_2,'option_selected'=>'pdf'),
                        
                        array('title'=>$value->announcement_title_3, 'quarter'=>'1', 'year'=>date('Y'),'announce_at'=>date('Y-m-d H:i:s'),'created_at'=>date('Y-m-d H:i:s'),'created_by'=>$user,'file_upload'=>'samplePDF.pdf','company_id'=>$id,'financial_apply'=>'1','description'=> $value->announcement_3,'option_selected'=>'pdf'),
                        );
                     DB::table('announcements')->insert($announcements); 
    			}	
			}
			

		}
		return back();
	}    
 	
 	
}
