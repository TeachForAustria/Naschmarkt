@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Hinzuf&uuml;gen
                    </div>
                    <div class="panel-body">
                        <form action="/project/new" method="POST" class="form-horizontal" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <div class="col-md-10 pull-left new">
                                    <input type="text" name="newPro" placeholder="Projekt hinzuf&uuml;gen ..." class="form-control input-md">
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-default" name="submit">Hinzuf&uuml;gen</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        @foreach($projects as $project)
            <div class="panel panel-default col-sm-10 col-sm-offset-1">
                <div class="panel-body post">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="row">
                                <div class="col-sm-12"><h4 class="post-title"><a href="{{ url('/project/' . $project->id) }}">{{ $project->name }}</a></h4></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    @foreach($project->folders->take(4) as $folder)
                                        <span class="label label-success">{{ $folder->name }}</span>
                                    @endforeach

                                    @if($project->folders->count() > 4)
                                        <span class="badge">+{{ $project->folders->count() - 4 }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

@endsection