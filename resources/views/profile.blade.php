@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Profile</div>

                    <div class="panel-body">

                        <form class="form-horizontal">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{ $user->name }}</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Facebook</label>
                                <div class="col-sm-10">

                                    @if($user->sociallogins()->where('provider', 'facebook')->first() === null)
                                        <a href="{{ url('/auth/socialLogin?provider=facebook&type=connect') }}">
                                            <input class="btn" type="button" value="Connect" />
                                        </a>
                                    @else
                                        <a href="{{ url('/auth/socialLogin/disconnect?provider=facebook') }}">
                                            <input class="btn" type="button" value="Disconnect" />
                                        </a>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Google+</label>
                                <div class="col-sm-10">

                                    @if($user->sociallogins()->where('provider', 'google')->first() === null)
                                        <a href="{{ url('/auth/socialLogin?provider=google&type=connect') }}">
                                            <input class="btn" type="button" value="Connect" />
                                        </a>
                                    @else
                                        <a href="{{ url('/auth/socialLogin/disconnect?provider=google') }}">
                                            <input class="btn" type="button" value="Disconnect" />
                                        </a>
                                    @endif

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
