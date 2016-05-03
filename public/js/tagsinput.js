var tags = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: {
        url: 'test.json',
        filter: function(list) {
            return $.map(list, function(tagname) {
                return { name: tagname }; });
        }
    }
});
tags.initialize();

$('.tagsinput').tagsinput({
    typeaheadjs: {
        name: 'tags',
        displayKey: 'name',
        valueKey: 'name',
        source: tags.ttAdapter()
    }
});