@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Mein Account</h3></div>
                    <div class="panel-body">
                        <h4>Allgemeine Einstellungen</h4>
                        <form method="POST">
                            {!! csrf_field() !!}
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="control-label">Username</label>
                                <input class="form-control" value="{{ $user->name }}" name="name"/>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="control-label">Email</label>
                                <input class="form-control" value="{{ $user->email }}" type="email" name="email"/>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="control-label">Passwort</label>
                                <input class="form-control" type="password" name="password" placeholder="donaudampfsschiffs..."/>
                                <span id="helpBlock" class="help-block">Dein Passwort muss mindestens aus einem Buchstaben, einer Zahl und einem Sonderzeichen bestehen und mindestens 8 Zeichen lang sein.</span>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label class="control-label">Passwort wiederholen</label>
                                <input class="form-control" type="password" name="password_confirmation" placeholder="donaudampfsschiffs..."/>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Speichern</button>
                        </form>

                        <hr/>
                        <h4>Social Login</h4>
                        <div class="form-group">
                            <label class="control-label">
                                Google+
                                <span class="label label-{{ $user->sociallogins()->where('provider', 'google')->first() === null ? 'success' : 'danger' }}">
                                    {{ $user->sociallogins()->where('provider', 'google')->first() === null ? 'Verbunden' : 'Nicht verbunden' }}
                                </span>
                            </label>
                            <div class="input-group-btn">
                                @if($user->sociallogins()->where('provider', 'google')->first() === null)
                                    <a href="{{ url('/auth/socialLogin?provider=google&type=connect') }}">
                                        <input class="btn" type="button" value="Verbinden" />
                                    </a>
                                @else
                                    <a href="{{ url('/auth/socialLogin/disconnect?provider=google') }}">
                                        <input class="btn" type="button" value="Verbindung entfernen" />
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">
                                Facebook
                                <span class="label label-{{ $user->sociallogins()->where('provider', 'facebook')->first() === null ? 'success' : 'danger' }}">
                                    {{ $user->sociallogins()->where('provider', 'facebook')->first() === null ? 'Verbunden' : 'Nicht verbunden' }}
                                </span>
                            </label>
                            <div class="input-group-btn">
                                @if($user->sociallogins()->where('provider', 'facebook')->first() === null)
                                    <a href="{{ url('/auth/socialLogin?provider=facebook&type=connect') }}">
                                        <input class="btn" type="button" value="Verbinden" />
                                    </a>
                                @else
                                    <a href="{{ url('/auth/socialLogin/disconnect?provider=facebook') }}">
                                        <input class="btn" type="button" value="Verbindung entfernen" />
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
