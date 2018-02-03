<?php

namespace App\Components\Package\Modules\Presentation;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DateTime;
use Hash;
use Mail;
use Auth;
use Carbon\Carbon;
use File;

class PresentationBackendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // this function used for view page list presentation
    public function getList()
    {
        $pre = Presentation::where('company_id', Auth::user()->company_id)->get();
        return $this->view('backend.list')->with('data', $pre)->with('controller', $this);
    }

    //this function used for view form of presentation
    public function getForm($id = '')
    {
        if($id != ''){
            $presentation = Presentation::findOrFail($id);
            return $this->view('backend.edit')->with('year', $this->getYear())->with('quarter', $this->getQuarter())->with('data', $presentation);
        }
        return $this->view('backend.form')->with('quarter', $this->getQuarter())->with('year', $this->getYear());
    }

    /*this function used for upload pdf into temp folder
        - return path of pdf file  
    */
    private function checkExistingPresentation($quarter,  $year) {
        $getPresentationData = Presentation::where('company_id', Auth::user()->company_id )
                            ->where('quarter', $quarter)
                            ->where('year', $year)
                            ->first();
        if (count($getPresentationData) > 0) {
            return true;
        }
        return false;
    }
    public function aJaxCheckExistingPresentation(Request $request) {
        $getPresentationData = Presentation::where('company_id', Auth::user()->company_id )
                            ->where('quarter', $request->input('quarter'))
                            ->where('year', $request->input('year'))
                            ->count();
        if ($getPresentationData) {
            echo "false";
        }else{
            echo "true";
        }
    }
    public function doUploadPdf($data)
    {
        $data = Input::all();
        $destinationPath = "files/presentation/";
        $file       = $data['myfile'];
        $filename   = str_random(8).$_FILES['myfile']['name'];
        $full_path  = $destinationPath.$filename;
        $file->move($destinationPath, $filename);

        return $full_path;
    }

    public function postSave($id = '')
    {
        if($id == ''){
            $validate = Validator::make(Input::all(), [
                'quarter'   => 'required|min:1',
                'year'      => 'required|min:1',
                'upload'    => 'required',
            ]);

            if ($validate->fails())
            {
                return redirect()->back()->withErrors($validate->errors())->withInput();
            }
        }
        $data = Input::all();
        if (empty($id)) {
            if ($this->checkExistingPresentation($data['quarter'], $data['year']) == true ) {
                $validate->errors()->add('quarter', 'Quarter already existing with year.');
                return redirect()->back()->withInput()->withErrors($validate);
            }
        }
        
        if(isset($data['myfile'])){
            $data['upload'] = $this->doUploadPdf($data);
            if($id != ''){
                $pre = Presentation::findOrFail($id);
                File::delete($pre->upload);
            }
        }

        if($id == ''){
            $presentation = new Presentation();
            $presentation->quarter  = $data['quarter'];
            $presentation->year     = $data['year'];
            $presentation->upload   = $data['upload'];
            $presentation->company_id = Auth::user()->company_id;
            $presentation->created_by = Auth::user()->id;
            $presentation->publish_date = date('Y-m-d', strtotime($data['publish_date']));
            $presentation->is_active  = 1;
            $presentation->save();
            return Redirect::route('package.admin.presentation')->with('global', 'Presentation created.');
        }else{
            $presentation = Presentation::findOrFail($id);
            $presentation->upload   = $data['upload'];
            $presentation->updated_by = Auth::user()->id;
            $presentation->publish_date = date('Y-m-d', strtotime($data['publish_date']));
            $presentation->update();

            return Redirect::route('package.admin.presentation')->with('global', 'Presentation updated.');
        }
    }

    public function getSoftDelete($id)
    {
        $pre = Presentation::findOrFail($id);
        $pre->is_delete     = 1;
        $pre->deleted_by    = Auth::user()->id;
        $old_file = $pre->upload;
        $pre->update();
        $pre->delete();
        File::delete($old_file);

        return Redirect::route('package.admin.presentation')->with('global-danger', 'Presentation has been deleted.');
    }

    public function softDeleteMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {

            $pres = Presentation::whereIn('id',$input['check'])->get();

            if(count($pres)){
                foreach($pres as $item){
                    $pre = Presentation::find($item['id']);
                    $pre->is_delete = 1;
                    $pre->deleted_by = \Auth::user()->id;
                    $pre->update();
                    $old_file = $pre->upload;
                    File::delete($old_file);
                }
            }

            $delete = Presentation::whereIn('id',$input['check']);
            if($delete->delete())
            {
                return redirect()->route('package.admin.presentation')->with('global-danger', 'Presentation(s) deleted.');
            }
        }
    }

    public function publishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $pre = Presentation::whereIn('id',$input['check'])->update(['is_active' => '1']);

            return redirect()->route('package.admin.presentation')->with('global', 'Presentation(s) published.');

        }
    }

    public function unPublishMulti()
    {
        $input = Input::all();
        if(is_array($input['check']))
        {
            $pre = Presentation::whereIn('id',$input['check'])->update(['is_active' => '0']);

            return redirect()->route('package.admin.presentation')->with('global', 'Presentation(s) unpublished.');

        }
    }

}
