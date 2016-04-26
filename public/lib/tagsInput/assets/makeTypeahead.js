
var usableTags = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: {
        url: "test.json",
    filter: function(list) {
        return $.map(list, function(usableTag) {
            return { name: usableTag }; });
    }
}
});
usableTags.initialize();

$('#searchQuery').tagsinput({
    typeaheadjs: {
        name: 'usableTags',
        displayKey: 'name',
        valueKey: 'name',
        source: usableTags.ttAdapter()
    }
});

