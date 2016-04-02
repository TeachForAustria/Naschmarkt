@extends('layouts.app')
@section('stylesheets')
    <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
@endsection
@section('content')

    <div class="container"> <br />
        <div class="row">


            <div class="col-md-7" id="uploadContainer">
                &nbsp;
                <div class="panel panel-default" style="background-color: transparent; border: 0px;">
                    <div class="panel-heading bannerText" style="background-color: transparent; border: 0px; color: #FFFFFF; font-size: 400%">Upload files<small> </small></div>
                    <div class="panel-body">
                        <div class="input-group image-preview">
                            <input placeholder="" type="text" class="form-control image-preview-filename" disabled="disabled" id="uploadFilePath">
                            <!-- don't give a name === doesn't send on POST/GET -->
						<span class="input-group-btn">
						<!-- image-preview-clear button -->
						<button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                            <span class="glyphicon glyphicon-remove"></span> Clear </button>
                            <!-- image-preview-input -->
						<div class="btn btn-default image-preview-input">
                            <!-- picture of open filder -->
                            <span class="glyphicon glyphicon-folder-open"></span>
                            <span class="image-preview-input-title">Browse</span>

                            <!-- rename it -->
                        </div>
						<button type="button" class="btn btn-labeled btn-default"> <span class="btn-label"><i class="glyphicon glyphicon-upload"></i> </span>Upload</button>
						</span> </div>
                        <!-- /input-group image-preview [TO HERE]
                        <img src="http://www.w3schools.com/images/w3schools_green.jpg" alt="W3Schools.com">
                        -->
                        <br />
                        <!-- Drop Zone
                        <div class="upload-drop-zone"> Or drag and drop files here </div>
                        <br /> -->

                        <!-- Progress Bar
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"> <span class="sr-only">0% Complete</span> </div>
                        </div> -->
                        <br />


                        {!! Form::open(array('url'=>'apply/upload','method'=>'POST', 'files'=>true)) !!}
                        <!-- the invisible button o.O -->
                        <input name="file" type="file" style="visibility: hidden" accept="image/png, image/jpeg, image/gif" name="input-file-preview" id="invisibleButton"/>
                        {!! Form::submit('Submit', array('class'=>'send-btn')) !!}

                        {!! Form::close() !!}



                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /container -->
@endsection