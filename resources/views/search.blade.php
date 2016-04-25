@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <!-- Header -->
                    <div class="panel-heading">Search</div>

                    <!-- Body -->
                    <div class="panel-body">
                        <form action="/search" method="POST" class="form-horizontal" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="searchQuery">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-default" name="search">Search</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection