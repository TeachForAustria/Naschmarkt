@extends('layouts.app')

@push('scripts')
<script type="text/javascript" src="{{ URL::asset('jqcloud-1.0.4.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('cloud.js') }}"></script>
@endpush

@section('content')
    <div class="container">

        <form action="" method="POST">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">Sortieren</div>
                            <div class="panel-body">
                                <form action="/" method="POST" class="form-horizontal" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <select class="form-control" id="select">
                                            <option value="2">Meist verwendet</option>
                                            <option value="1">Auf Gut Gl&uuml;ck</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div id="example" class="centered" style="height: 350px ;width: 60%;"></div>

    </div>
@endsection