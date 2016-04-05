<!-- About Page contains information about Naschmarkt, such as
telephone numbers, email, address, etc.-->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">About Naschmarkt</div>

                    <div class="panel-body">

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-3"> <img src="{{ URL::asset('Vogel.png') }}"></div>
                                <div class="col-md-9">
                                    <div class="aboutText">
                                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod sea takimata sanctus est Lorem .
                                    </div>
                                    <ul>
                                        <li>  Kontakt: Abigal</li>
                                        <li>  Email: abi@gail.com </li>
                                        <li>  TelNr.: 0699 184 456 87</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
