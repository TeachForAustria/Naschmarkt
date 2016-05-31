@extends('layouts.app')
@push('stylesheets')
<link href="{{ URL::asset('css/pages/post.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="col-sm-10 col-sm-offset-1">
            <form class="form-inline" action=" {{ url('/project/' . $project->id) }}" method="POST" id="form">
                {!! csrf_field() !!}
                <div class="row">
                    <ul class="pager">
                        <li class="previous"><a href="{{ url('/project/' . $project->id) }}"><i class="fa fa-angle-left" aria-hidden="true"></i>Project</a></li>
                    </ul>

                    <div class="page-header">
                        <input type="text" value="{{ $project->name }}" name="title" class="form-control input-lg edit-title" />
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save" aria-hidden="true"></i>Save</button>
                    </div>
                </div>
                <div class="row">
                    <div class="panel panel-default stats">
                        <div class="panel-body">
                            <div class="col-sm-3">
                                <strong><i class="fa fa-user" aria-hidden="true"></i></strong>
                                <span> {{ $project->owner->name }}</span>
                            </div>
                            <div class="col-sm-3">
                                <strong><i class="fa fa-clock-o" aria-hidden="true"></i></strong>
                                <span> {{ $project->created_at->format('d. m. Y') }}</span>
                            </div>
                            <div class="col-sm-3">
                                <strong><i class="fa fa-repeat" aria-hidden="true"></i></strong>
                                <span> {{ $project->updated_at->format('d. m. Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading"> Folder </div>
                        <div class="panel-body">
                            @foreach($project->folders->all() as $folder)
                                <div class="center-block">
                                    <a class="btn btn-success" role="button" href="#collapse{{ $folder->id }}"
                                       aria-controls="collapse{{ $folder->id }}" data-toggle="collapse" aria-expanded="false">
                                        <i class="fa fa-folder-open" aria-hidden="true"></i> {{ $folder->name }}
                                    </a>
                                    <div class="collapse" id="collapse{{ $folder->id }}">
                                        <div class="well pro-edit">
                                            <div class="list-group">
                                                @foreach($folder->posts->all() as $post)
                                                    <a href="{{ url('/posts?q=' . $post->name) }}" class="list-group-item"> {{ $post->name }}</a>
                                                @endforeach
                                                <a class="list-group-item">
                                                    <i class="fa fa-plus-circle"></i>
                                                    <input type="text" name="newpost{{ $folder->id}}" placeholder="Add new Posts">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="well folder-input">
                                <input class="input-md form-control" type="text" name="newfolder" placeholder="Add new Folders">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection