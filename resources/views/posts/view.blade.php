@extends('layouts.app')
@push('stylesheets')
<link href="{{ URL::asset('css/pages/post.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="row">
                <ul class="pager">
                    <li class="previous"><a href="{{ URL::to('/posts') }}"><i class="fa fa-angle-left" aria-hidden="true"></i>Posts</a></li>
                </ul>

                <div class="page-header">
                    <button class="btn btn-warning pull-right"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Edit</button>
                    <h2>{{ $post->name }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9">
                    <h3>Beschreibung</h3>
                    <p>{{ $post->description }}</p>
                </div>
                <div class="col-sm-3 stats">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong><i class="fa fa-user" aria-hidden="true"></i>Hochgeladen von</strong>
                            <span>{{ $post->owner->name }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong><i class="fa fa-line-chart" aria-hidden="true"></i>Zugriffe</strong>
                            <span>{{ $post->access_count }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong><i class="fa fa-clock-o" aria-hidden="true"></i>Hochgeladen am</strong>
                            <span>{{ $post->created_at->format('d. m. Y') }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong><i class="fa fa-clock-o" aria-hidden="true"></i>Zuletzt ge&auml;ndert am</strong>
                            <span>{{ $post->updated_at->format('d. m. Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h3>Dateien <span class="badge">{{ $post->documents->count() }}</span></h3>
                    <div class="files panel panel-default">
                        @foreach($post->documents->all() as $document)
                            <div class="panel-body post">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <div class="col-sm-12 file-name"><strong><a href="#">{{ $document->name }}</a></strong></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 file-info">
                                        <i class="fa fa-folder-open" aria-hidden="true"></i> Unknown
                                    </div>
                                    <div class="col-sm-2 file-info">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $document->created_at->format('d. m. Y') }}
                                    </div>
                                    <div class="col-sm-1 file-info">
                                        <i class="fa fa-refresh" aria-hidden="true"></i> {{ $document->documentVersions->count() }}
                                    </div>
                                    <div class="col-sm-3 file-info">
                                        <a href="#"><i class="fa fa-download" aria-hidden="true"></i> Herunterladen</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h3>Tags <span class="badge">{{ $post->tags->count() }}</span></h3>
                    <div class="tags">
                        @foreach($post->tags->all() as $tag)
                            <span class="label label-info">{{ $tag->value }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection