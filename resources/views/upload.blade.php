@extends('layouts.app')
@section('stylesheets')
    <link href="{{ URL::asset('css/upload.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('bootstrap-tagsinput-0.8.0/src/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('bootstrap-tagsinput-0.8.0/src/bootstrap-tagsinput-typeahead.css') }}" rel="stylesheet">
@endsection
@section('content')

    <script src="{{ URL::asset('uploadFunctions.js') }}"></script>
    <script src="{{ URL::asset('bootstrap-tagsinput-0.8.0/src/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ URL::asset('bootstrap-tagsinput-0.8.0/src/bootstrap-tagsinput-angular.js') }}"></script>


    <div class="container"><br/>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <!-- Header -->
                    <div class="panel-heading">Upload</div>
                    <!-- Body -->
                    <div class="panel-body">
                        <div class="form-horizontal">

                            <!-- title -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Titel*</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="title">
                                </div>
                            </div>

                            <!-- file
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Datei*</label>

                                    <div class="input-group">
                                        <form action="/upload" method="POST" enctype="multipart/form-data">
                                            {!! csrf_field() !!}
                                    <span class="input-group-btn">
                                        <span class="btn btn-default btn-file">
                                            Browse&hellip; <input name="file" type="file" class="form-control"/>
                                                </span>
                                            </span>
                                            <input type="text" class="form-control" disabled>

                                            <br/><br/>
                                            <button type="submit" class="btn btn-primary">Upload</button>
                                        </form>
                                    </div> -->


                            <!-- description -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Beschreibung </label>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="4" id="description"></textarea>
                                </div>
                            </div>

                            <!-- tags -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Tags*</label>
                                <div class="col-md-6">
                                    <input type="text" value="Wird, Noch ,Ge&auml;ndert" class="form-control" name="title">
                                    <span class="help-block">
                                        Mindestens 3 Tags, um die Suche zu erleichtern.
                                    </span>
                                </div>
                            </div>

                            <!-- <input type="text" value="Test,Hallo"  data-role="tagsinput" /> -->

                            <!-- file -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Datei*</label>
                                <div class="col-md-6">
                                    <form action="/upload" method="POST" enctype="multipart/form-data"
                                          style="float: left;">
                                        <div class="input-group">
                                            {!! csrf_field() !!}
                                            <span class="input-group-btn">
                                                    <span class="btn btn-primary btn-file">
                                                        Browse&hellip; <input name="file" type="file"
                                                                              class="form-control" id="browseButton"/>
                                                    </span>
                                                </span>
                                            <input type="text" class="form-control" id="fileName" disabled>
                                        </div>
                                        <br/>
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </form>
                                </div>
                            </div> <!-- /form-group -->



                            <!-- help-block -->
                            <br/><br/>
                            <span class="help-block">
                                *Felder m&uuml;ssen ausgef&uuml;llt werden.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div> <!-- /container -->
@endsection