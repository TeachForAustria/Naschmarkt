var editor = new Quill('.editor-wrapper .editor-container', {
    modules: {
        'toolbar': {
            container: '.editor-wrapper .toolbar-container'
        },
        'link-tooltip': true,
        'image-tooltip': true
    },
    styles: false,
    theme: 'snow'
});

editor.on('text-change', function() {
    $('.editor-wrapper .editor-input').val(editor.getHTML());
});