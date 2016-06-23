@extends('layouts.app')
@push('stylesheets')
<link href="{{ URL::asset('css/pages/post.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput.css') }}">
<link rel="stylesheet" href="{{ URL::asset('lib/tagsInput/assets/bsTagsInput.css') }}">
@endpush

@push('scripts')
<script src="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ URL::asset('lib/tagsInput/assets/bsTagsInput.js') }}"></script>
<script src="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput-typeahead.js') }}"></script>
<script>
    $.get( "/postnames" )
            .done(function( data ) {
                var postNames = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace,
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    // data is an array with all the post names
                    local: data
                });

                $('.posts .twitter-typeahead').typeahead({
                            hint: true,
                            highlight: true,
                            minLength: 1
                        },
                        {
                            name: 'namesHound',
                            source: postNames
                        });
            });


</script>
@endpush

@section('content')
    <div class="container">
        <div class="col-sm-10 col-sm-offset-1">
            <form class="form-inline" action=" {{ url('/project/' . $project->id) }}" method="POST" id="form">
                {!! csrf_field() !!}
                <div class="row">
                    <ul class="pager">
                        <li class="previous"><a href="{{ url('/project/' . $project->id) }}"><i class="fa fa-angle-left" aria-hidden="true"></i>Projekte</a></li>
                    </ul>

                    <div class="page-header">
                        <input type="text" value="{{ $project->name }}" name="title" class="form-control input-lg edit-title" />
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save" aria-hidden="true"></i>Speichern</button>
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
                        <div class="panel-heading"> Ordner </div>
                        <div class="panel-body">
                            @foreach($project->folders->all() as $folder)
                                <div class="center-block">
                                    <a class="btn btn-success" role="button" href="#collapse{{ $folder->id }}"
                                       aria-controls="collapse{{ $folder->id }}" data-toggle="collapse" aria-expanded="false">
                                        <i class="fa fa-folder-open" aria-hidden="true"></i> {{ $folder->name }}
                                    </a>

                                    <a class="remove_fields" href="/projects/{{ $folder->id }}">
                                        <i class="fa fa-remove icon-trash icon-white"></i>
                                    </a>

                                    <div class="collapse" id="collapse{{ $folder->id }}">
                                        <div class="well pro-edit">
                                            <div class="list-group">
                                                @foreach($folder->posts->all() as $post)

                                                    <div class="list-group-item">
                                                        <a href="{{ url('/posts/' . $post->name) }}"> {{ $post->name }} </a>

                                                        <a class="remove_fields pull-right" href="/projects/{{ $folder->id }}/remove/{{ $post->id }}">
                                                            <i class="fa fa-remove icon-trash icon-white"></i>
                                                        </a>
                                                    </div>


                                                @endforeach
                                                <a class="list-group-item">
                                                    <i class="fa fa-plus-circle"></i>
                                                    <div class="posts">
                                                        <input type="text" name="newpost{{ $folder->id}}" placeholder="Add new Posts" class="twitter-typeahead">
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="well folder-input">
                                <input class="input-md form-control" type="text" name="newfolder" placeholder="Neuen Ordner hinzuf&uuml;gen">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection