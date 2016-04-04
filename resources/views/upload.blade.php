@extends('layouts.app')
@section('stylesheets')
    <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
@endsection
@section('content')

    <div class="container"> <br />
        <div class="row">


            <div class="col-md-7" id="uploadContainer">
                <div class="panel panel-default" style="background-color: transparent; border: 0px;">
                    <div class="panel-heading bannerText" style="background-color: transparent; border: 0px; color: #FFFFFF; font-size: 400%">Upload files<small> </small></div>
                    <div class="panel-body">
                        <!--div class="input-group image-preview">
                            <input placeholder="" type="text" class="form-control image-preview-filename" disabled="disabled" id="uploadFilePath">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="glyphicon glyphicon-remove"></span> Clear
                                </button>

                                <button class="btn btn-default image-preview-input">
                                    <span class="glyphicon glyphicon-folder-open"></span>
                                    <span class="image-preview-input-title">Browse</span>
                                </button>
                                <button type="button" class="btn btn-labeled btn-default"> <span class="btn-label"><i class="glyphicon glyphicon-upload"></i> </span>Upload</button>
                            </span>
                        </div-->
                        <form action="/upload" method="POST" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <input name="file" type="file" id="invisibleButton"/>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /container -->
@endsection