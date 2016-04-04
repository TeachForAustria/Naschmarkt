
/*!
 * Create an array of word objects, each representing a word in the cloud
 */
var word_array = [
    {text: "Deutsch", weight: 15},
    {text: "Mathe", weight: 9, link: "http://jquery.com/"},
    {text: "Englisch", weight: 6},
    {text: "Sport", weight: 7},
    {text: "Baumschule 3 Ast", weight: 10},
    {text: "Medt suckt", weight: 20},
    {text: "Deutsch", weight: 14},
    {text: "Cloud", weight: 2},
    {text: "Cloud", weight: 4},
    {text: "Cloud", weight: 4},
    {text: "Cloud", weight: 4},
    {text: "Cloud", weight: 7},
    {text: "Cloud", weight: 7},
    {text: "Cloud", weight: 7},
    {text: "Cloud", weight: 30},
    {text: "Cloud", weight: 10},
    {text: "Cloud", weight: 6},
    {text: "Cloud", weight: 6},
    {text: "Cloud", weight: 2},
    {text: "Cloud", weight: 4},
    {text: "Cloud", weight: 4},
    {text: "Cloud", weight: 4},
    {text: "Cloud", weight: 7},
    {text: "Cloud", weight: 7},
    {text: "Cloud", weight: 7},
    {text: "Cloud", weight: 3},
    {text: "Cloud", weight: 12},
    {text: "Cloud", weight: 12},
    {text: "Cloud", weight: 14},
    {text: "Cloud", weight: 15},
    {text: "Cloud", weight: 13},
    {text: "Cloud", weight: 15}
    // ...as many words as you want
];

$(function() {
    // When DOM is ready, select the container element and call the jQCloud method, passing the array of words as the first argument.
    $("#example").jQCloud(word_array);
});
