//var $APIUrl="http://localhost:8080/GithubProject/Makhateer/api/";

var $APIUrl = "http://makhateer.ids-demo.me/integration/api/";

var $IntegrationAPIUrl = ids_apiURL;
var $AJAXIntegrationAPIUrl = ids_ajaxurl;

var $Password = "IDSpassword";

var $Username = "idsadmin";



var $LoggedInUserName = (ReadCookie("Makhateer_UserId") == null || ReadCookie("Makhateer_UserId") == "") ? "-" : ReadCookie("Makhateer_UserId");

function ReadCookie(cname) {

    var nameEQ = cname + "=";

    var ca = document.cookie.split(';');

    for (var i = 0; i < ca.length; i++) {

        var c = ca[i];

        while (c.charAt(0) == ' ') c = c.substring(1, c.length);

        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);

    }

    return null;

}