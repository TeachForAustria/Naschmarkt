
var tagsFromJson = null;

// Get all the tags from the test.json and return it in a callback
// Returns an array of javascript objects
function getWordArray(callback){
    $.ajax({
        url: 'test.json',
        type: 'GET',
        dataType: 'json',
    }).done(function(data) {

        var tagArray = new Array();

        for (var i = 0; i < data.length; i++) {
            var currentItem = {
                    text: data[0],
                    weight: 10,
                    link: '/cloudController?query='+data[0]
                };
            tagArray.push(currentItem);
        }

       callback(tagArray);
    });
}

getWordArray(function(response){
    // should be the same as the word_array, doesnt work though
    // alert(response);
    $(function()
    {
        // When DOM is ready, select the container element and call the jQCloud method, passing the array of words as the first argument.
        $("#example").jQCloud(word_array);
    });
});

/*
 * Create an array of word objects, each representing a word in the cloud
 */
var word_array = [
    {text: "Andere Fremdsprachen", weight: Math.floor((Math.random()*30)+1), link: '/cloudController?query=Andere Fremdsprachen'},
    {text: "Berufsorientierung", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Berufsorientierung'},
    {text: "Bewegung und Sport", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Bewegung und Sport'},
    {text: "Bildnerische Erziehung", weight:  Math.floor((Math.random()*30)+1), link: 'cloudController?query=Bildnerische Erziehung'},
    {text: "Biologie und Umweltkunde", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Biologie und Umweltkunde'},
    {text: "Chemie", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Chemie'},
    {text: "Deutsch", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Deutsch'},
    {text: "Ern&auml;hrung", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Ern&auml;hrung'},
    {text: "Geographie und Wirtschaftskunde", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Geographie und Wirtschaftskunde'},
    {text: "Geometrisches Zeichnen", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Geometrisches Zeichnen'},
    {text: "Geschichte und Sozialkunde", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Geschichte und Sozialkunde'},
    {text: "Informatik", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Informatik'},
    {text: "Lerncoaching", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Lerncoaching'},
    {text: "Mathematik", weight: Math.floor((Math.random()*30)+1), link: '/cloudController?query=Mathematik'},
    {text: "Musikerziehung", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Musikerziehung'},
    {text: "Phsysik", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Phsysik'},
    {text: "Religion", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Religion'},
    {text: "Technisches Werken", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Technisches'},
    {text: "Textiles Werken", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Textiles Werken'},
    {text: "Didaktik", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Didaktik'},
    {text: "CRM", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=CRM'},
    {text: "Jahresplan", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Jahresplan'},
    {text: "Lesson plan", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Lesson plan'},
    {text: "Soziale Kompetenz", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Soziale Kompetenz'},
    {text: "Themenplan", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Themenplan'},
    {text: "Unterichtsmaterial", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Unterichtsmaterial'},
    {text: "Wochenplan", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Wochenplan'},
    {text: "Sommerakademie", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Sommerakademie'},
    {text: "Workshop", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Workshop'},
    {text: "Seminar", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Seminar'},
    {text: "Leadership", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Leadership'},
    {text: "TfA-Vorlagen", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=TfA-Vorlagen'},
    {text: "TfA-Hinweisen", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=TfA-Hinweisen'},
    {text: "Arbeitsrecht", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Arbeitsrecht'},
    {text: "Blog-Eintrag", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Blog-Eintrag'},
    {text: "Fördermöglichkeiten", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Fördermöglichkeiten'},
    {text: "Schulrecht", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Schulrecht'},
    {text: "Studie", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Studie'},
    {text: "Zeitungsartikel", weight: Math.floor((Math.random()*30)+1), link: 'cloudController?query=Zeitungsartikel'}
    // ...as many words as you want
];
/*
$(function() {
    // When DOM is ready, select the container element and call the jQCloud method, passing the array of words as the first argument.
    $("#example").jQCloud(word_array);
});
*/