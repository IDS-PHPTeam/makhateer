jQuery(function($) {

    function getQueryString(key) {
        let url = window.location.href;
        key = key.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + key + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }


    $("#reset_password_link").click(function(event) {
        // Prevent form submission.
        event.preventDefault();

        let languageCode = $("html:first").attr("lang");

        let password = $("#password").val();
        let confirmPassword = $("#confirm_password").val();

        if (password != confirmPassword) {
            alert(languageCode == "ar" ? "كلمتي السر غير متطابقتين!" : "Passwords don't match!");
            return false;
        }

        let body = {
            "key": getQueryString("key"),
            "login": getQueryString("login"),
            "password": password,
            "confirm_password": confirmPassword,
        }

        console.log(body);

        $("#ids-loader").css({ "display": "inline-block" });

        $.post(ids_apiURL + "/reset-password", body, function(response) {
            console.log(response);
            $("#ids-loader").css({ "display": "none" });

            alert(response["message"]);
        }).fail(function(error) {
            console.log(error);
            alert(response["message"]);
        });

    });
});