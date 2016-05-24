@extends('layouts.app')
@push('stylesheets')
<link href="{{ URL::asset('css/pages/posts.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput.css') }}">
<link rel="stylesheet" href="{{ URL::asset('lib/tagsInput/assets/bsTagsInput.css') }}">
<link rel="stylesheet" href="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput-typeahead.css') }}">
@endpush

@push('scripts')
<script src="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ URL::asset('lib/tagsInput/assets/bsTagsInput.js') }}"></script>
<script src="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput-typeahead.js') }}"></script>
<script src="{{ URL::asset('lib/tagsInput/assets/makeTypeahead.js') }}"></script>
@endpush

@section('content')


    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Search</div>
                    <div class="panel-body">
                        <form action="/posts" method="GET" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-md-10">
                                    <input id="searchQuery" data-role="tagsinput" type="text"  name="q" class="form-control" value = "@if(isset($search_query)){{ $search_query }}@endif" >
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-default">Search</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>
                                    <input name="fullTextSearch" type="checkbox" value="yes"> Volltextsuche
                                </label>
                            </div>

                            <div class="col-sm-2 col-sm-offset-7">
                                <label>Sortieren nach: </label>
                                <select class="form-control" name="s">
                                    <option value="created_at">Datum</option>
                                    <option value="name">Titel</option>
                                    <option value="owner_id">Author</option>
                                    <option value="access_count,desc">Zugriffs Zahl</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="container">

        @foreach($posts as $post)
            <div class="panel panel-default col-sm-10 col-sm-offset-1">
                <div class="panel-body post">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="row">
                                <div class="col-sm-12"><h4 class="post-title"><a href="{{ Url::to('/posts/' . $post->id) }}">{{ $post->name }}</a></h4></div>
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
                                <div class="col-xs-5"><i class="fa fa-file" aria-hidden="true"></i> {{ count($post->documents) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="text-center">
            {!! $posts->links() !!}
        </div>
    </div>
@endsection