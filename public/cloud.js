
/*!
 * Create an array of word objects, each representing a word in the cloud
 */
var word_array = [
    {text: "Andere Fremdsprachen", weight: 15, link: '/cloudController?query=Andere Fremdsprachen'},
    {text: "Berufsorientierung", weight: 6, link: 'cloudController?query=Berufsorientierung'},
    {text: "Bewegung und Sport", weight: 7, link: 'cloudController?query=Bewegung und Sport'},
    {text: "Bildnerische Erziehung", weight: 10, link: 'cloudController?query=Bildnerische Erziehung'},
    {text: "Biologie und Umweltkunde", weight: 20, link: 'cloudController?query=Biologie und Umweltkunde'},
    {text: "Chemie", weight: 14, link: 'cloudController?query=Chemie'},
    {text: "Deutsch", weight: 2, link: 'cloudController?query=Deutsch'},
    {text: "Ern&auml;hrung", weight: 4, link: 'cloudController?query=Ern&auml;hrung'},
    {text: "Geographie und Wirtschaftskunde", weight: 4, link: 'cloudController?query=Geographie und Wirtschaftskunde'},
    {text: "Geometrisches Zeichnen", weight: 4, link: 'cloudController?query=Geometrisches Zeichnen'},
    {text: "Geschichte und Sozialkunde", weight: 7, link: 'cloudController?query=Geschichte und Sozialkunde'},
    {text: "Informatik", weight: 7, link: 'cloudController?query=Informatik'},
    {text: "Lerncoaching", weight: 7, link: 'cloudController?query=Lerncoaching'},
    {text: "Mathematik", weight: 30, link: '/cloudController?query=Mathematik'},
    {text: "Musikerziehung", weight: 10, link: 'cloudController?query=Musikerziehung'},
    {text: "Phsysik", weight: 6, link: 'cloudController?query=Phsysik'},
    {text: "Religion", weight: 6, link: 'cloudController?query=Religion'},
    {text: "Technisches Werken", weight: 2, link: 'cloudController?query=Technisches'},
    {text: "Textiles Werken", weight: 4, link: 'cloudController?query=Textiles Werken'},
    {text: "Didaktik", weight: 4, link: 'cloudController?query=Didaktik'},
    {text: "CRM", weight: 4, link: 'cloudController?query=CRM'},
    {text: "Jahresplan", weight: 7, link: 'cloudController?query=Jahresplan'},
    {text: "Lesson plan", weight: 7, link: 'cloudController?query=Lesson plan'},
    {text: "Soziale Kompetenz", weight: 7, link: 'cloudController?query=Soziale Kompetenz'},
    {text: "Themenplan", weight: 3, link: 'cloudController?query=Themenplan'},
    {text: "Unterichtsmaterial", weight: 12, link: 'cloudController?query=Unterichtsmaterial'},
    {text: "Wochenplan", weight: 12, link: 'cloudController?query=Wochenplan'},
    {text: "Sommerakademie", weight: 14, link: 'cloudController?query=Sommerakademie'},
    {text: "Workshop", weight: 15, link: 'cloudController?query=Workshop'},
    {text: "Seminar", weight: 13, link: 'cloudController?query=Seminar'},
    {text: "Leadership", weight: 15, link: 'cloudController?query=Leadership'},
    {text: "TfA-Vorlagen", weight: 15, link: 'cloudController?query=TfA-Vorlagen'},
    {text: "TfA-Hinweisen", weight: 15, link: 'cloudController?query=TfA-Hinweisen'},
    {text: "Arbeitsrecht", weight: 15, link: 'cloudController?query=Arbeitsrecht'},
    {text: "Blog-Eintrag", weight: 15, link: 'cloudController?query=Blog-Eintrag'},
    {text: "Fördermöglichkeiten", weight: 15, link: 'cloudController?query=Fördermöglichkeiten'},
    {text: "Schulrecht", weight: 15, link: 'cloudController?query=Schulrecht'},
    {text: "Studie", weight: 15, link: 'cloudController?query=Studie'},
    {text: "Zeitungsartikel", weight: 15, link: 'cloudController?query=Zeitungsartikel'}
    // ...as many words as you want
];

$(function() {
    // When DOM is ready, select the container element and call the jQCloud method, passing the array of words as the first argument.
    $("#example").jQCloud(word_array);
});
