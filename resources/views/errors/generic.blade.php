@extends('layouts.app')

@push('stylesheets')
    <link rel="stylesheet" href="{{ URL::asset('css/pages/error.css') }}">
@endpush

@section('content')
    <div class="container">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="jumbotron error-container">
                <h1><i class="fa fa-times" aria-hidden="true"></i><span>{{ $status }}</span></h1>
                <p>W&auml;hrend der Bearbeitung deiner Anfrage ist ein Fehler aufgetreten. Sollte dies wiederholt auftreten, wende dich bitte an einen Administator.</p>
            </div>
        </div>
    </div>
@endsection