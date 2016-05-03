/**
 * Created by Matthias on 20.03.2016.
 */


//JSON.parse('/resources/assets/tags.json');

var fachlich = ["Andere Fremdsprachen", "Berufsorientierung", "Bewegung und Sport", "Bildnerische Erziehung",
    "Biologie und Umweltkunde", "Chemie", "Deutsch", "Englisch", "Ern&auml;hrung",
    "Geographie und Wirtschaftskunde", "Geometrisches Zeichen", "Geschichte und Sozialkunde",
    "Informatik", "Lerncoaching", "Mathematik", "Musikerziehung", "Phyisk", "Religion",
    "Technisches Werken", "Textiles Werken"];

var inhaltliches = ["CRM", "Didaktik", "Jahresplan", "Lesson plan", "Soziale Kompetenz", "Themenplan",
    "Unterichtsmaterial", "Wochenplan"];

var programmbezogenes = ["Sommerakademie", "Workshop", "Seminar", "Leadership", "TfA-Vorlagen", "TfA-Hinweisen"];

var bildung = ["Arbeitsrecht", "Blog-Eintrag", "Fördermöglichkeiten", "Schulrecht", "Studie", "Zeitungsartikel"];


$(document).ready(function () {

    //Show/hide presetTags
    $("#toggleTags").click(function () {
        $("#presetTags").slideToggle();
        $("i",this).toggleClass("fa-plus-square-o fa-minus-square-o")
    });

    //Print Checkboxes for tags
    var printF = '<table style="width: 100%;" border="0px">';
    var next = '';
    for (var i = 0; i < fachlich.length / 2; i++) {
        next = fachlich[Math.round(fachlich.length / 2) + i];
        printF += '<tr><td style="width: 50%"><div style="height: 5px" class="checkbox"><label><input type="checkbox" name="givenTags[]" class="myCheckBox" value="' + fachlich[i] + '">' + fachlich[i] + '</label></div></td>';
        if (next != undefined)
            printF += '<td style="width: 50%"><div style="height: 5px" class="checkbox"><label><input type="checkbox" name="givenTags[]" class="myCheckBox" value="' + next + '">' + next + '</label></div></td></tr>';
    }

    $("#printFachliches").html(printF + '</table>');

    var printI = '';
    for (var i = 0; i < inhaltliches.length; i++) {
        printI = printI + ('<div class="checkbox"><label><input type="checkbox" name="givenTags[]" class="myCheckBox" value="'+inhaltliches[i]+'">' + inhaltliches[i] + '</label></div>');
    }
    $("#printInhaltliches").html(printI);

    var printP = '';
    for (var i = 0; i < programmbezogenes.length; i++) {
        printP = printP + ('<div class="checkbox"><label><input type="checkbox" name="givenTags[]" class="myCheckBox" value="'+programmbezogenes[i]+'">' + programmbezogenes[i] + '</label></div>');
    }
    $("#printProgrammbezogenes").html(printP);

    var printB = '';
    for (var i = 0; i < bildung.length; i++) {
        printB = printB + ('<div class="checkbox"><label><input type="checkbox" name="givenTags[]" class="myCheckBox" value="'+bildung[i]+'">' + bildung[i] + '</label></div>');
    }
    $("#printBildung").html(printB);

    $("#addFiles").click(function(){

        var parentDiv = $("#invisibleButton").parent();

        $(parentDiv).append('<input name="files[]" multiple="multiple" type="file" />')

        console.log(myFile.name);

    });
});

/**
 * Reurns Checkbox labels separated with commas
 */
function getSelectedTags() {
    //init all checkboxes
    var boxes = document.querySelectorAll(".myCheckBox");
    var checked = '';

    //iterate boxes
    for (var i = 0; i < boxes.length; i++) {
        if (boxes[i].checked) {
            checked += boxes[i].name + ",";
        }
    }

    return checked;
}