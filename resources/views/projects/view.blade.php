@extends('layouts.app')
@push('stylesheets')
<link href="{{ URL::asset('css/pages/post.css') }}" rel="stylesheet">
@endpush

@section('content')

    <div class="container">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="row">
                <ul class="pager">
                    <li class="previous"><a href="{{ url('/projects') }}"><i class="fa fa-angle-left" aria-hidden="true"></i>Projekte</a></li>
                </ul>

                <div class="page-header">
                    @if(Auth::user()->name == $project->owner->name or Auth::user()->is_staff)
                        <a href="{{'/project/deleteProject/' . $project->id}}" class="btn btn-danger pull-right delete-button"><i class="fa fa-trash-o" aria-hidden="true"></i>L&ouml;schen </a>
                        <a href="{{ '/project/' . $project->id . '/edit' }}" class="btn btn-warning pull-right"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Bearbeiten</a>
                    @endif
                    <h2>{{ $project->name }}</h2>
                </div>
            </div>

            <div class="row">
                <div class="panel panel-default stats">
                    <div class="panel-body">
                        <div class="col-sm-3">
                            <strong><i class="fa fa-user" aria-hidden="true"></i></strong>
                            <span>{{ $project->owner->name }}</span>
                        </div>
                        <div class="col-sm-3">
                            <strong><i class="fa fa-clock-o" aria-hidden="true"></i></strong>
                            <span>{{ $project->created_at->format('d. m. Y') }}</span>
                        </div>
                        <div class="col-sm-3">
                            <strong><i class="fa fa-repeat" aria-hidden="true"></i></strong>
                            <span>{{ $project->updated_at->format('d. m. Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <h3> Ordner <span class="badge"> {{ $project->folders->count() }}</span></h3>
                <div class="files panel panel-default">
                    <div class="panel-body">
                        @foreach($project->folders->all() as $folder)
                            <div class="col-sm-3 folder">
                                <div class="center-block">
                                    <div class="dropdown">
                                        <button class="btn btn-success dropdown-toggle" type="button"
                                                id="dropButton{{ $folder->id }}" data-toggle="dropdown" aria-haspopup="true">
                                            <i class="fa fa-folder-open" aria-hidden="true"></i> {{ $folder->name }}
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropButton{{ $folder->id }}">
                                            @foreach($folder->posts->all() as $post)
                                                <li><a href="{{url('/posts/'.$post->id)}}">{{ $post->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection