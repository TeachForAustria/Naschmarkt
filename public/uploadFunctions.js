/**
 * Created by Matthias on 20.03.2016.
 */

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'top'
    });
    /* end dot nav */

    /* get the path of the file from the invisible button */
    $("#browseButton").change(function () {
        var fullPath = document.getElementById('browseButton').value;
        if (fullPath) {
            var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
            var filename = fullPath.substring(startIndex);
            if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                filename = filename.substring(1);
            }
            //alert(filename);
            $('#fileName').attr("placeholder", filename);
            filename = "";
        }
    });
});