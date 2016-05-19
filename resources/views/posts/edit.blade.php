@extends('layouts.app')
@push('stylesheets')
    <link href="{{ URL::asset('css/pages/post.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('lib/dropzone/dropzone.css') }}">
@endpush

@push('scripts')
    <script src="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ URL::asset('lib/tagsInput/assets/bsTagsInput.js') }}"></script>
    <script src="{{ URL::asset('lib/dropzone/dropzone.js') }}"></script>
    <script src="{{ URL::asset('js/fileinput.js') }}"></script>
@endpush

@section('content')
    <div class="container">
        <div class="col-sm-10 col-sm-offset-1">
            <form class="form-inline" action="{{ URL::to('/posts/' . $post->id) }}" method="POST">
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
                            <div class="col-sm-3">]
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
                        <textarea class="form-control col-sm-12 edit-description" name="description">{{ $post->description }}</textarea>
                </div>
                <div class="row">
                        <h3>Dateien <span class="badge">{{ $post->documents->count() }}</span></h3>
                        <div class="files panel panel-default">
                            @foreach($post->documents->all() as $document)
                                <div class="panel-body post">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-sm-12 file-name"><strong><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>{{ $document->name }}</a></strong></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 file-info">
                                            <i class="fa fa-folder-open" aria-hidden="true"></i> Unknown
                                        </div>
                                        <div class="col-sm-2 file-info">
                                            <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $document->created_at->format('d. m. Y') }}
                                        </div>
                                        <div class="col-sm-1 file-info">
                                            <i class="fa fa-repeat" aria-hidden="true"></i> {{ $document->documentVersions->count() }}
                                        </div>
                                        <div class="col-sm-3 file-info">
                                            <a href="#"><i class="fa fa-download" aria-hidden="true"></i> Herunterladen</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
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