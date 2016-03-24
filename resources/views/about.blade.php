<!-- About Page contains information about Naschmarkt, such as
telephone numbers, email, address, etc.-->
@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="main">

            <!-- Headline -->
            <div class="row">
                <div class="col-xs-12 col-sm-4"></div>
                <div class="col-xs-12 col-sm-4">
                    <div class="bannerText">About Naschmarkt</div>
                </div>
                <div class="col-xs-12 col-sm-4"></div>
            </div>

            <!-- picture of company contact -->
            <div class="row">
                <div class="col-xs-12 col-sm-4"></div>
                <div class="col-xs-12 col-sm-4">
                    <!-- should be replaced with image of the company contact -->
                    <img class="center-block" src={{ URL::asset("Vogel.png") }}> </img>
                </div>
                <div class="col-xs-12 col-sm-4"></div>
            </div>

            <!-- contact information
            Should also be replaced with usefull values-->
            <div class="row content">
                <div class="col-xs-12 col-sm-4">
                    <div class="text">Tel.: 0699 123 467 84</div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="text"> Kontakt: Mr.Mockup </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="text"> E-Mail: foo@bar.com </div>
                </div>
            </div>

            <!-- maybe add a google maps field with the hq address here -->
@endsection