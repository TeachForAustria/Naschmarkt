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
                                    <input type="text" value="2016" data-role="tagsinput" name="tags" />
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

                            <br/>

                            <hr>

                            <!-- presetTags -->
                            <button type="button" class="btn btn-default" id="toggleTags"><i class="fa fa-plus-square-o fa-lg" style="padding-right: 5px" aria-hidden="true"></i> Vorhandene Tags</button>
                            <br><br>
                            <div id="presetTags" style="display: none">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#fachliches">Fachliches</a></li>
                                    <li><a data-toggle="tab" href="#menu1">Inhaltliches</a></li>
                                    <li><a data-toggle="tab" href="#menu2">Programmbezogenes</a></li>
                                    <li><a data-toggle="tab" href="#menu3">Bildung-Schulsysteme-LehrerInnenberuf</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div id="fachliches" class="tab-pane fade in active">
                                        <!--<h4>Fachliches</h4>-->
                                        <div id="printFachliches"></div>
                                    </div>

                                    <div id="menu1" class="tab-pane fade">
                                        <!--<h4>Inhaltliches</h4>-->
                                        <div id="printInhaltliches"></div>
                                    </div>

                                    <div id="menu2" class="tab-pane fade">
                                        <!--<h4>Programmbezogenes</h4>-->
                                        <div id="printProgrammbezogenes"></div>
                                    </div>

                                    <div id="menu3" class="tab-pane fade">
                                        <!--<h4>Bildung-Schulsysteme-LehrerInnenberuf</h4>-->
                                        <div id="printBildung"></div>
                                    </div>
                                </div>
                            </div> <!-- End presetTags -->

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
    </div> <!-- /container -->
@endsection