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
                        <div class="form-horizontal">
                            <div class="form-group">
                                <div class="col-md-10">
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-default">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
@endsection