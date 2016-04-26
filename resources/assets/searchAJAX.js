function uploadQuery(){
    alert("in function");
        var request = new XMLHttpRequest();
        request.open('post', '/search', true);
        request.send(null);
    alert("post request");
}
