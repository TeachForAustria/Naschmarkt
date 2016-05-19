(function(self){
    var overwriteFile = null;
    var oldOverwriteFile = null;
    var files = [];
    Dropzone.autoDiscover = false;
    self.dropzone = new Dropzone("div#dropzone", {
        addRemoveLinks: false,
        url: "/documents",
        dictDefaultMessage: 'Hier klicken oder Dateien hineinziehen um sie hochzuladen.',
        init: function() {
            this.on("sending", function(file, xhr, formData) {
                formData.append("_token", $('meta[name=csrf-token]').attr("content"));
            });

            this.on("success", function(file, response) {
                files.push(response);
            });

            this.on('removedfile', function(file) {
                var i;
                for(i = 0; i < files.length; i++) {
                    if(files[i].name === file.name) {
                        files.splice(i, 1);
                        return;
                    }
                }
            });

            this.on('added_existing', function(file) {
                files.push(file);
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
        self.dropzone.removeFile(oldOverwriteFile);
        self.dropzone.addFile(overwriteFile);
        $('#file-exists-modal').modal('hide');
    });

    $("#form").submit( function() {
        $('#files').val(JSON.stringify(files));
        return true;
    });
})(this);