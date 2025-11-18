jQuery(function($) {


});




$('#btnSync').click(function() {

    var Url = $IntegrationAPIUrl + "Sync?password=" + $Password + "&username=" + $Username;

    $.ajax({
        url: Url,
        type: "GET",
        contentType: 'application/json',
        success: function(result) {
            console.log('done')
        }
    });
})