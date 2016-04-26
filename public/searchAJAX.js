$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $("#searchBar").keyup(function(){

        var query = $(this).val();

        $.post("/posts", {"query":query});

    });

});