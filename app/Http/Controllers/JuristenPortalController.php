<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\File;


use Carbon\Carbon;
use App\Http\Requests;
use App\Helpers\ViewHelper;
use App\Helpers\OcrHelper;
use App\Document;
use App\EditorVariant;
use App\DocumentUpload;



class JuristenPortalController extends Controller
{
    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        $this->portalOcrUploads = public_path().'/files/juristenportal/ocr-uploads/';
       
     }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( ViewHelper::universalHasPermission( array(28,29) ) == false ){
             return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        return view('juristenportal.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( ViewHelper::universalHasPermission( array(28,29) ) == false ){
             return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( ViewHelper::universalHasPermission( array(28,29) ) == false ){
             return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if( ViewHelper::universalHasPermission( array(28,29) ) == false ){
             return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( ViewHelper::universalHasPermission( array(28,29) ) == false ){
             return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if( ViewHelper::universalHasPermission( array(28,29) ) == false ){
             return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadView()
    {
        if( ViewHelper::universalHasPermission( array(28,29) ) == false ){
             return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        return view('juristenportal.upload');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        if( ViewHelper::universalHasPermission( array(28,29) ) == false ){
             return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $uploaded = $this->fileUpload($request->file);
        
        if($uploaded == false){
            return redirect()->back()->with('messageSecondary','Upload fehlgeschlagen'); 
        }else{
            return redirect()->back()->with('messageSecondary','Dateien hochgeladen'); 

        }
    }
    
    /**
     * Process files for upload
     * @param array $files
     * @return \Illuminate\Http\Response
     */
    private function fileUpload($files)
    {
        $folder = $this->portalOcrUploads;
        $uploadedNames = array();
        if ( ! \File::exists( $folder ) ) {
			\File::makeDirectory( $folder, $mod=0777,true,true); 
		}
		if( is_array($files)){
		    $uploadedNames = array();
	        $counter=0;
	       //  dd($files);
		    foreach($files as $k=>$file){
		 
		         $counter++;
		        if( is_array($file)){
		            foreach($file as $f){
		                if($f !== NULL){
                            if( ViewHelper::fileTypeAllowed($file) ){
                                $uploadedNames[] =  $this->moveUploaded($f,$folder,$k);
                            }
		                }
		                
		            }
		        }
		        else{
		            if( ViewHelper::fileTypeAllowed($file) ){
		                $uploadedNames[] =  $this->moveUploaded($file,$folder,$k);
		             }
		        }
		        
		            
		    }
		}
		else{
		     if( ViewHelper::fileTypeAllowed($files) ){
		         $uploadedNames[] = $this->moveUploaded($files,$folder);
		     }
		}
            
        if( count($uploadedNames) )
            return $uploadedNames;
        else{
            return false;
        }
	
    }
    
    /**
     * Move files from temp folder and rename them
     *
     * @param file object $file
     * @param string $folder
     * @return string $newName
     */
    private function moveUploaded($file,$folder,$counter=0)
    {
        //$filename = $image->getClientOriginalName();
        $diffMarker = time()+$counter;
		$newName = date("d-m-Y-H:i:s").'-'.$diffMarker.'.'.$file->getClientOriginalExtension();
		$path = "$folder/$newName";+
        $filename = $file->getClientOriginalName();
        $uploadSuccess = $file->move($folder, $newName );
      	\File::delete($folder .'/'. $filename);
      	return $newName;
    }

   
}
