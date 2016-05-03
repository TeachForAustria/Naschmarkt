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
                    <!-- edit/delete only if user is either the owner or a staff -->
                    @if(Auth::user()->name == $post->owner->name or Auth::user()->is_staff)
                        <a href="{{'/posts/deletePost/' . $post->id}}" class="btn btn-danger pull-right delete-button"><i class="fa fa-trash-o" aria-hidden="true"></i>L&ouml;schen</a>
                        <a href="{{ URL::to('/posts/' . $post->id . '/edit') }}" class="btn btn-warning pull-right"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Bearbeiten</a>
                    @endif
                    <h2>{{ $post->name }}</h2>
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
                    <div class="panel panel-default">
                        <div class="panel-body description">
                            <p>{{ $post->description }}</p>
                        </div>
                    </div>
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
                                        <i class="fa fa-folder-open" aria-hidden="true"></i> {{ $document->documentversions->last()->filesize() }}
                                    </div>
                                    <div class="col-sm-2 file-info">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $document->created_at->format('d. m. Y') }}
                                    </div>
                                    <div class="col-sm-1 file-info">
                                        <i class="fa fa-repeat" aria-hidden="true"></i> {{ $document->documentVersions->count() }}
                                    </div>
                                    <div class="col-sm-3 file-info">
                                        <a href="{{ URL::to('/documents/' . $document->id) }}"><i class="fa fa-download" aria-hidden="true"></i> Herunterladen</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
            </div>
            <div class="row">
                    <h3>Tags <span class="badge">{{ $post->tags->count() }}</span></h3>
                    <div class="tags-lg">
                        @foreach($post->tags->all() as $tag)
                            <span class="label label-info">{{ $tag->value }}</span>
                        @endforeach
                    </div>
                </div>
        </div>
    </div>
@endsection