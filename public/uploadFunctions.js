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