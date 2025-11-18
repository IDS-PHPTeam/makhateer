jQuery(function($) {
    $("#send_reset_password_link").click(function(event) {
        // Prevent form submission.
        event.preventDefault();


        let body = {
            "user_login": $("#user_login").val().trim(),
            "lang": $("html:first").attr("lang")
        }

        console.log(body);

        $("#ids-loader").css({ "display": "inline-block" });

        $.post(ids_apiURL + "/lost-password", body, function(response) {
            console.log(response);
            $("#ids-loader").css({ "display": "none" });

            alert(response["message"]);
        }).fail(function(error) {
            console.log(error);
            alert(response["message"]);
        });

    });
});