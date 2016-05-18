(function(){
    var overwriteFile = null;
    var oldOverwriteFile = null;
    Dropzone.autoDiscover = false;
    var dropzone = new Dropzone("div#dropzone", {
        addRemoveLinks: false,
        url: "/documents",
        dictDefaultMessage: 'Hier klicken oder Dateien hineinziehen um sie hochzuladen.',
        init: function() {
            this.on("sending", function(file, xhr, formData) {
                formData.append("_token", $('meta[name=csrf-token]').attr("content"));
            });

            this.on("success", function(file, response) {
                var filesElement = $('#files');
                var files = JSON.parse(filesElement.val());
                files.push(response);
                filesElement.val(JSON.stringify(files));
            });

            var self = this;
            this.on("addedfile", function(file) {
                var i;
                var files = self.getAcceptedFiles();
                for(i = 0; i < files.length; i++) {
                    if(files[i].name === file.name && overwriteFile !== file) {
                        overwriteFile = file;
                        oldOverwriteFile = files[i];
                        $('#file-exists-modal').modal();
                        self.removeFile(file);
                        break;
                    }
                }
            });
        }
    });

    $('#overwrite-file-modal').click(function(e) {
        dropzone.removeFile(oldOverwriteFile);
        dropzone.addFile(overwriteFile);
        $('#file-exists-modal').modal('hide');
    });
})();