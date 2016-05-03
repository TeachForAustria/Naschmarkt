
var tagsFromJson = new Array();

// Get all the tags from the test.json and return it in a callback
// Returns an array of javascript objects
function getWordArray(callback){

    // Make new ajax get request for the json file containing the
    // default tags
    $.ajax({
        url: 'test.json',
        type: 'GET',
        dataType: 'json',
    }).done(function(data) {

        // Create a new array
        var tagArray = new Array();

        // Loop through all items from the json file
        for (var i = 0; i < data.length; i++) {

            var fontWeight;


            // Randomise the fontweight of the tags in the cloud
            var random = Math.floor((Math.random()*100)+1);
            var countBigWords = 0;

            if(random <= 60){
                fontWeight = Math.floor((Math.random()*20)+1);
            }else if(random <= 90 || random > 60){
                fontWeight = Math.floor((Math.random()*25)+20);
            }else if(random <=100 || random > 90 || countBigWords < 3){
                fontWeight = Math.floor((Math.random()*30)+28);
                countBigWords++;
            }else{
                fontWeight = 10;
            }

            // Save the current json item into an object
            var currentItem = {
                    text: data[i],
                    weight: fontWeight,
                    link: '/cloudController?query='+data[i]
                };

            // Push into the existing array
            tagArray.push(currentItem);
        }

        // Return the generated array as callback of the method
       callback(tagArray);
    });
}

//Print the wordcloud from json data
getWordArray(function(response){
    // alert(response);

    // Save the method response into an array
    tagsFromJson = response;

    // Wait for the DOM
    $(function()
    {
        // When DOM is ready, select the container element and call the jQCloud method, passing the array of words as the first argument.
        $("#example").jQCloud(tagsFromJson);
    });
});