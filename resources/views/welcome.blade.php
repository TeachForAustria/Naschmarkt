@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="main">
            <div class="row">
                <div class="centered">
                    <div class="col-xs-12 col-sm-4"></div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="bannerText">Wilkommen bei Naschmarkt</div>
                    </div>
                    <div class="col-xs-12 col-sm-4"></div>
                </div>
            </div>
            <div class="row">
                <div class="centered">
                    <div class="col-xs-12 col-sm-4"></div>
                    <div class="col-xs-12 col-sm-4">
                        <img class="center-block" src={{ URL::asset("Vogel.png") }}> </img>
                    </div>
                    <div class="col-xs-12 col-sm-4"></div>
                </div>
            </div>
    </div>
</div>
@endsection
