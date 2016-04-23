@extends('layouts.app')
@section('stylesheets')
    <link href="{{ URL::asset('css/upload.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('bootstrap-tagsinput-0.8.0/src/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('bootstrap-tagsinput-0.8.0/src/bootstrap-tagsinput-typeahead.css') }}" rel="stylesheet">

    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">-->
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap-theme.min.css">-->
    <link rel="stylesheet" href="{{ URL::asset('tagsinput/dist/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rainbow/1.2.0/themes/github.css">
    <link rel="stylesheet" href="{{ URL::asset('tagsinput/assets/bsTagsInput.css') }}">
@endsection
@section('content')

    <script src="{{ URL::asset('uploadFunctions.js') }}"></script>
    <script src="{{ URL::asset('bootstrap-tagsinput-0.8.0/src/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ URL::asset('bootstrap-tagsinput-0.8.0/src/bootstrap-tagsinput-angular.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular.min.js"></script>
    <script src="{{ URL::asset('tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ URL::asset('tagsinput/dist/bootstrap-tagsinput-angular.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rainbow/1.2.0/js/rainbow.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rainbow/1.2.0/js/language/generic.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rainbow/1.2.0/js/language/html.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rainbow/1.2.0/js/language/javascript.js"></script>
    <script src="{{ URL::asset('tagsinput/assets/bsTagsInput.js') }}"></script>
    <script src="{{ URL::asset('tagsinput/assets/bsTagsInput_bs3.css') }}"></script>


    <div class="container"><br/>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <!-- Header -->
                    <div class="panel-heading">Upload</div>
                    <!-- Body -->
                    <div class="panel-body col-sm-offset-1">
                        <form action="/upload" method="POST" enctype="multipart/form-data" class="form-horizontal">
                            {!! csrf_field() !!}
                            <!-- name -->
                            <div class="form-group">
                                <label class="col-md-2 control-label">Titel*</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="title" />
                                </div>
                            </div>

                            <!-- description -->
                            <div class="form-group">
                                <label class="col-md-2 control-label">Beschreibung</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" rows="4" name="description"></textarea>
                                </div>
                            </div>

                            <!-- tags -->
                            <div class="form-group">
                                <label class="col-md-2 control-label">Tags*</label>
                                <div class="col-md-8">
                                    <input type="text" value="2016" data-role="tagsinput" />
                                    <!--<input type="text" value="Wird, Noch ,Ge&auml;ndert" class="form-control" name="title" />-->
                                    <span class="help-block">
                                        Mindestens 3 Tags, um die Suche zu erleichtern.
                                    </span>

                                </div>




                            </div>

                            <!-- file -->
                            <div class="form-group">
                                <label class="col-md-2 control-label">Datei*</label>
                                <div class="col-md-8">
                                    <input name="file" type="file" id="invisibleButton" />
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">Hochladen</button>
                                </div>
                            </div>

                            <!-- help-block -->
                            <br/><br/>
                            <span class="help-block">
                                *Felder m&uuml;ssen ausgef&uuml;llt werden.
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div> <!-- /container -->
@endsection