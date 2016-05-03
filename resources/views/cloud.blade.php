@extends('layouts.app')

@push('scripts')
<script type="text/javascript" src="{{ URL::asset('jqcloud-1.0.4.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('cloud.js') }}"></script>
@endpush

@section('content')
    <div class="main">

        <div id="example" class="centered" style="height: 350px ;width: 50%;"></div>

    </div>
@endsection