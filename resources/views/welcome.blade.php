@extends('layouts.app')

@section('content')
    <style>
        .bannerText {
            font-size: 20px;
        }
        .centered{
            margin: 0 auto;
            width: 80%;
        }
        .bannerText {
            text-align: center;

            font:normal 40pt Lato;
            color:#FFFFFF;
            text-shadow: 0 1px 0 #ccc,
            0 2px 0 #c9c9c9,
            0 3px 0 #bbb,
            0 4px 0 #b9b9b9,
            0 5px 0 #aaa,
            0 6px 1px rgba(0,0,0,.1),
            0 0 5px rgba(0,0,0,.1),
            0 1px 3px rgba(0,0,0,.3),
            0 3px 5px rgba(0,0,0,.2),
            0 5px 10px rgba(0,0,0,.25),
            0 10px 10px rgba(0,0,0,.2),
            0 20px 20px rgba(0,0,0,.15);
        }
        .main {
            margin-top: 10%;
        }
        .center-block {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: 15px;
        }
    </style>
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
