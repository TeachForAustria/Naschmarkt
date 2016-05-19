@extends('layouts.app')
@push('stylesheets')
    <link rel="stylesheet" href="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('lib/tagsInput/assets/bsTagsInput.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('lib/dropzone/dropzone.css') }}">
@endpush

@push('scripts')
    <script src="{{ URL::asset('uploadFunctions.js') }}"></script>
    <script src="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ URL::asset('lib/tagsInput/assets/bsTagsInput.js') }}"></script>
    <script src="{{ URL::asset('lib/dropzone/dropzone.js') }}"></script>
    <script src="{{ URL::asset('js/fileinput.js') }}"></script>
@endpush

@section('content')
    <div class="modal fade" id="file-exists-modal" tabindex="-1" role="dialog" aria-labelledby="fileExistsModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="fileExistsModal">Datei existiert bereits.</h4>
                </div>
                <div class="modal-body">
                    Diese Datei existiert bereits. M&ouml;chtest du sie &uuml;berschreiben?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn btn-primary" id="overwrite-file-modal">&Uuml;berschreiben</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container"><br/>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <!-- Header -->
                    <div class="panel-heading">Upload</div>
                    <!-- Body -->
                    <div class="panel-body col-sm-offset-1">
                        <form action="/upload" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form">
                        {!! csrf_field() !!}
                        <!-- name -->
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label class="col-md-2 control-label">Titel*</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="title" value="{{ old('title') }}"/>

                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
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
                            <div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
                                <label class="col-md-2 control-label">Tags*</label>
                                <div class="col-md-8">
                                    @include('partials.tagsinput', array($errors))

                                    @if ($errors->has('tags'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('tags') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- files -->
                            <input type="hidden" id="files" name="files" value="[]" />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Dateien</label>
                                <div class="col-md-8">
                                    <div class="dropzone" id="dropzone"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label"> </label>
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-default">Hochladen</button>
                                </div>
                            </div>

                            <hr>

                            <!-- help-block -->
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