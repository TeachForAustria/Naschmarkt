@extends('layouts.app')
@push('stylesheets')
    <link href="{{ URL::asset('css/pages/activate.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default activate-panel">
                    <div class="panel-heading">Account aktivieren</div>
                    <div class="panel-body">
                        <button class="btn btn-primary btn-lg btn-block" type="button" data-toggle="collapse" data-target="#password-form" aria-expanded="false" aria-controls="password-form"><i class="fa fa-lock"></i>Passwort</button>

                        <div class="collapse btn-block @if($errors->count()) in @endif" id="password-form">
                            <div class="well">
                                <form method="POST">
                                    {!! csrf_field() !!}
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password">Passwort</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="donaudampfsschiffs...">
                                        <span id="helpBlock" class="help-block">Dein Passwort muss mindestens aus einem Buchstaben, einer Zahl und einem Sonderzeichen bestehen und mindestens 8 Zeichen lang sein.</span>
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="password-repeat">Passwort wiederholen</label>
                                        <input type="password" class="form-control" id="password-confirmation" name="password_confirmation" placeholder="donaudampfsschiffs...">
                                    </div>
                                    <button type="submit" class="btn btn-default">Speichern</button>
                                </form>
                            </div>
                        </div>
                        <a class="btn btn-primary btn-lg btn-block facebook-btn" role="button" href="{{ url('/auth/socialLogin?provider=facebook&type=connect') }}"><i class="fa fa-facebook"></i> Facebook</a>
                        <a class="btn btn-primary btn-lg btn-block google-btn" role="button" href="{{ url('/auth/socialLogin?provider=google&type=connect') }}"><i class="fa fa-google"></i>Google</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection