@extends('layouts.app')
@push('stylesheets')
    <link href="{{ URL::asset('css/upload.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('lib/tagsinput/dist/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('lib/tagsinput/assets/bsTagsInput.css') }}">
@endpush

@push('scripts')
    <script src="{{ URL::asset('uploadFunctions.js') }}"></script>
    <script src="{{ URL::asset('lib/tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ URL::asset('lib/tagsinput/assets/bsTagsInput.js') }}"></script>
@endpush

@section('content')
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