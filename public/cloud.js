
/*!
 * Create an array of word objects, each representing a word in the cloud
 */
var word_array = [
    {text: "Deutsch", weight: 15},
    {text: "Englisch", weight: 6},
    {text: "Sport", weight: 7},
    {text: "Geographie", weight: 10},
    {text: "Geschichte", weight: 20},
    {text: "Matura", weight: 14},
    {text: "Physik", weight: 2},
    {text: "Schularbeiten", weight: 4},
    {text: "AHS", weight: 4},
    {text: "Volksschule", weight: 4},
    {text: "Neue Mittelschule", weight: 7},
    {text: "Hauptschule", weight: 7},
    {text: "Informatik", weight: 7},
    {text: "Naschmarkt", weight: 30, link: '/posts?searchQuery=test'},
    {text: "Lernen", weight: 10},
    {text: "Verwaltung", weight: 6},
    {text: "Ausflüge", weight: 6},
    {text: "Hausübung", weight: 2},
    {text: "Präsentationen", weight: 4},
    {text: "Word", weight: 4},
    {text: "Excel", weight: 4},
    {text: "Access", weight: 7},
    {text: "Nachhilfe", weight: 7},
    {text: "Latein", weight: 7},
    {text: "Russisch", weight: 3},
    {text: "Spanisch", weight: 12},
    {text: "Biologie", weight: 12},
    {text: "Erste Klasse", weight: 14},
    {text: "Zweite Klasse", weight: 15},
    {text: "Dritte Klasse", weight: 13},
    {text: "Vierte Klasse", weight: 15}
    // ...as many words as you want
];

$(function() {
    // When DOM is ready, select the container element and call the jQCloud method, passing the array of words as the first argument.
    $("#example").jQCloud(word_array);
});
