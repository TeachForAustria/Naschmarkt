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
                            <strong><i class="fa fa-file" aria-hidden="true"></i>Dateien</strong>
                            <span>{{ $post->documents->count() }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h3>Dateien</h3>
                    <div class="files panel panel-default">
                        @foreach($post->documents->all() as $document)
                            <div class="panel-body post">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="row">
                                            <div class="col-sm-12"><strong><a href="#">{{ $document->name }}</a></strong></div>
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
                                    <div class="col-sm-2 file-info">
                                        <a href="#"><i class="fa fa-download" aria-hidden="true"></i> Herunterladen</a>
                                    </div>
                                    <div class="col-sm-2 file-info">
                                        <a href="#"><i class="fa fa-caret-left" aria-hidden="true"></i> Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h3>Tags</h3>
                    <div class="tags">
                        <span class="label label-info">Info</span>
                        <span class="label label-info">Info</span>
                        <span class="label label-info">Info</span>
                        <span class="label label-info">Info</span>
                        <span class="label label-info">Info</span>
                        <span class="label label-info">Info</span>
                        <span class="label label-info">Info</span>
                        <span class="label label-info">Info</span>
                        <span class="label label-info">Info</span>
                    </div>
                </div>
            </div>
        </div>
        {{-- @foreach($posts as $post)
            <div class="panel panel-default col-sm-10 col-sm-offset-1">
                <div class="panel-body post">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="row">
                                <div class="col-sm-12"><h4 class="post-title"><a href="#">{{ $post->name }}</a></h4></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    @foreach($post->tags->take(4) as $tag)
                                        <span class="label label-info">{{ $tag->value }}</span>
                                    @endforeach

                                    @if($post->tags->count() > 4)
                                        <span class="badge">+{{ $post->tags->count() - 4 }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 tiles">
                            <div class="row">
                                <div class="col-xs-7"><i class="fa fa-user" aria-hidden="true"></i>{{ $post->owner->name }}</div>
                                <div class="col-xs-5"><i class="fa fa-line-chart" aria-hidden="true"></i> {{ $post->access_count }}</div>
                            </div>
                            <div class="row">
                                <div class="col-xs-7"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $post->created_at->format('d. m. Y') }}</div>
                                <div class="col-xs-5"><i class="fa fa-file" aria-hidden="true"></i> 1</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach --}}
    </div>
@endsection