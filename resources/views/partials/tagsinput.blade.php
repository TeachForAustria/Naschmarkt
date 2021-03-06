@push('stylesheets')
    <link rel="stylesheet" href="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput-typeahead.css') }}">
@endpush

@push('scripts')
    <script src="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ URL::asset('lib/tagsInput/dist/bootstrap-tagsinput-typeahead.js') }}"></script>
    <script src="{{ URL::asset('js/tagsinput.js') }}"></script>
@endpush

<input type="text" value="{{ old('tags') }} @if(isset($tags)) {{ implode(',', $tags) }} @endif" data-role="tagsinput" name="tags" class="tagsinput has-error"/>

<div class="help-block">
    <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        <i class="fa fa-plus fa-lg" aria-hidden="true"></i> Vorgeschlagene Tags
    </button>
    <div class="collapse" id="collapseExample">
        <div class="well">
            @foreach(array_keys(\App\Tag::$predefinedTags) as $category)
                <div>
                    {{ $category }}
                    <div class="tags">
                        @foreach(\App\Tag::$predefinedTags[$category] as $tag)
                            <span class="label label-info" onclick="$('.tagsinput').tagsinput('add', '{{ $tag }}')">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>