@extends('layouts.app')
@push('stylesheets')
    <link href="{{ URL::asset('css/pages/post.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('lib/dropzone/dropzone.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('lib/quill/quill.base.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('lib/quill/quill.snow.css') }}">
@endpush

@push('scripts')
    <script src="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ URL::asset('lib/tagsInput/assets/bsTagsInput.js') }}"></script>
    <script src="{{ URL::asset('lib/dropzone/dropzone.js') }}"></script>
    <script src="{{ URL::asset('js/fileinput.js') }}"></script>
    <script>
        (function() {
            var existingFiles = [];
            @foreach($post->documents->all() as $document)
                @if($document->documentVersions->count() > 0)
                    existingFiles.push({
                        name: '{{ $document->name }}',
                        size: '{{ $document->documentversions->last()->filesizeBytes() }}',
                        accepted: true
                    });

                    var i;
                    for(i = 0; i < existingFiles.length; i++) {
                        // from: http://stackoverflow.com/questions/24009298/dropzone-js-display-existing-files-on-server
                        dropzone.options.addedfile.call(dropzone, existingFiles[i]);
                        dropzone.emit("added_existing", {
                            name: '{{ $document->name }}',
                            uuid: '{{ $document->documentversions->last()->uuid }}'
                        });
                        dropzone.emit("complete", existingFiles[i]);
                        dropzone.files.push(existingFiles[i]);
                    }
                @endif
            @endforeach
        })();
    </script>
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
    <div class="container">
        <div class="col-sm-10 col-sm-offset-1">
            <form class="form-inline" action="{{ URL::to('/posts/' . $post->id) }}" method="POST" id="form">
                {!! csrf_field() !!}
                <div class="row">
                    <ul class="pager">
                        <li class="previous"><a href="{{ URL::to('/posts/' . $post->id) }}"><i class="fa fa-angle-left" aria-hidden="true"></i>Post</a></li>
                    </ul>

                    <div class="page-header">
                        <input type="text" value="{{ $post->name }}" name="title" class="form-control input-lg edit-title" />
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save" aria-hidden="true"></i>Save</button>
                    </div>
                </div>
                <div class="row">
                    <div class="panel panel-default stats">
                        <div class="panel-body">
                            <div class="col-sm-3">
                                <strong><i class="fa fa-user" aria-hidden="true"></i></strong>
                                <span>{{ $post->owner->name }}</span>
                            </div>
                            <div class="col-sm-3">
                                <strong><i class="fa fa-line-chart" aria-hidden="true"></i></strong>
                                <span>{{ $post->access_count }}</span>
                            </div>
                            <div class="col-sm-3">
                                <strong><i class="fa fa-clock-o" aria-hidden="true"></i></strong>
                                <span>{{ $post->created_at->format('d. m. Y') }}</span>
                            </div>
                            <div class="col-sm-3">
                                <strong><i class="fa fa-repeat" aria-hidden="true"></i></strong>
                                <span>{{ $post->updated_at->format('d. m. Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h3>Beschreibung</h3>
                    @include('partials.richTextEditor', ['content' => $post->description, 'inputName' => 'description'])
                </div>
                <div class="row">
                    <h3>Dateien <span class="badge">{{ $post->documents->count() }}</span></h3>
                    <input type="hidden" id="files" name="files" value="[]" />
                    <div class="dropzone" id="dropzone"></div>
                </div>
                <div class="row">
                    <h3>Tags <span class="badge">{{ $post->tags->count() }}</span></h3>
                    @include(
                        'partials.tagsinput',
                        [
                            'tags' => array_map(function($tag) {
                                return $tag->value;
                            }, $post->tags->all())
                        ]
                    )
                </div>
            </form>
        </div>
    </div>
@endsection