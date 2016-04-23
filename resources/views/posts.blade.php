@extends('layouts.app')
@push('stylesheets')
    <link href="{{ URL::asset('css/pages/posts.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        @foreach($posts as $post)
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
        @endforeach
    </div>
@endsection