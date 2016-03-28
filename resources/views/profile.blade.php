@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Profile</div>

                    <div class="panel-body">


                        <table style="width: 30%">
                            <tr>
                                <td class="userThLeft">Username</td>
                                <td class="userThRight">{{ $user->name }} </td>
                            </tr>
                            <tr>
                                <td class="userThLeft">Email</td>
                                <td class="userThRight">{{ $user->email }} </td>
                            </tr>


                            @if(Auth::user()==$user)
                                <tr>
                                    <td class="userThLeft">Facebook</td>

                                    <td class="userThRight">
                                        @if($user->sociallogins()->where('provider', 'facebook')->first() === null)
                                            <a href="{{ url('/auth/socialLogin?provider=facebook&type=connect') }}">
                                                <input class="btn" type="button" value="Connect" />
                                            </a>
                                        @else
                                            <a href="{{ url('/auth/socialLogin/disconnect?type=facebook') }}">
                                                <input class="btn" type="button" value="Disconnect" />
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>

                                    <td class="userThLeft">Google+</td>

                                    <td class="userThRight">
                                        @if($user->sociallogins()->where('provider', 'google')->first() === null)
                                            <a href="{{ url('/auth/socialLogin?provider=google&type=connect') }}">
                                                <input class="btn" type="button" value="Connect" />
                                            </a>
                                        @else
                                            <a href="{{ url('/auth/socialLogin/disconnect?type=google') }}">
                                                <input class="btn" type="button" value="Disconnect" />
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endif

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
