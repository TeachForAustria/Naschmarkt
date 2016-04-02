<?php

namespace App\Http\Controllers;

use Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Http\Request;

use App\Http\Requests;

class UploadController extends Controller
{
    public function upload() {
        //Get data of POST-request
        $file = array('file' => Input::file('file'));
        //which formats are allowed
        $allowedExtensions = "pdf,txt,html,docx,zip,jpg,jpeg,png,gif";
        $rules = array('file' => 'required|mimes:'.$allowedExtensions);
        $validator = Validator::make($file,$rules);
        //When file does not match allowedExtensions
        if($validator->fails()) {
            //Redirect::to('upload');
            echo "<script type='text/javascript'> alert('Allowed Extensions: $allowedExtensions'); window.location.href='/upload';</script>";
        } else {
            //When file is valid:
            if(Input::file('file')->isValid()) {
                $destinationPath = 'uploads';
                $extension = Input::file('file')->getClientOriginalExtension();
                $fileName = rand(11111,99999).'.'.$extension;
                //upload file to given path
                Input::file('file')->move($destinationPath, $fileName);
                Session::flash('success', 'Upload successful');
                return Redirect::to('upload');
            } else {
                // sending back with error message.
                Session::flash('error', 'uploaded file is not valid');
                return Redirect::to('upload');
            }
        }
    }
}
