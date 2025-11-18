const readURL = input => {

    if (input.files && input.files[0]) {

        var reader = new FileReader();



        reader.onload = function(e) {

            document.querySelector(".profile-pic").src = e.target.result;

        }



        reader.readAsDataURL(input.files[0]);

    }

}



const handleEditClick = tg => {

    const input = tg.parentNode.querySelector("input");

    input.disabled = false;

    tg.style.display = "none";

    input.focus()

}



document.querySelector("#personal-profile-tab-links").addEventListener("click", e => {

    const li = e.target.closest("li");

    if (li) {

        e.target.closest("ul").querySelectorAll("li").forEach(node => node.classList.toggle("active", node == li));

        document.querySelectorAll(".tab-content").forEach(tab => {

            tab.classList.toggle("active", tab.id == e.target.href.split("#")[1])

        })

    }

})



document.querySelector("#personal-profile-tab-links li:first-child a").click();

// Ahmed El-Atab Code Start.


jQuery(function($) {

    var language_code = $("html:first").attr("lang");

    $("#btnCancel").click(function() {

        console.log("Cancel clicked!");
        // "THEMEREX_site_url" is an echoed variable by the theme holding the site URL .

        if (language_code == "ar") {
            location.href = THEMEREX_site_url;
        } else {
            location.href = THEMEREX_site_url + "/en";
        }
    });

    // Validating DOB Start.

    $("#btnSave").click(function(event) {
        /*let dob = $("#dtDateOfBirth").val();

        if (!dob) {
            return false;
        }

        dob = new Date(dob);
        let dateNow = new Date();

        let dateDifferences = dateNow.getTime() - dob.getTime();

        let age = Math.floor(dateDifferences / (1000 * 60 * 60 * 24 * 365.25));

        if (age < 25) {
            alert($("html:first").attr("lang") == "ar" ? "ينبغي أن يكون عمرك 25 سنة على الأقل! خانة تاريخ الولادة غير صحيحة!" : "You must be at leat 25 years old! Date of birth field is incorrect!");
            return false;
        }

        let phone = $("#txtPhone").val();

        if (phone.match(/\d/g).length != 8) {
            alert($("html:first").attr("lang") == "ar" ? "رقم الهاتف غير صحيح!" : "Incorrect phone number!");

            return false;
        }*/

        let password = $("#password").val();
        let confirmPassword = $("#confirm-password").val();

        // Prevent form submission if password fileds aren't set. 
        if (!password && !confirmPassword) {
            return false;
        }

        if (!password) {
            alert(language_code == "ar" ? "الرجاء إدخال كلمة السر" : "Please enter a password");
            return false;
        }

        if (!confirmPassword) {
            alert(language_code == "ar" ? "الرجاء تأكيد كلمة السر" : "Passwords confirm the password");
            return false;
        }

        if (password != confirmPassword) {
            alert(language_code == "ar" ? "!كلمة السر غير متطابقة" : "Passwords don't match!");
            return false;
        }

        return true;
    });


    // Validating DOB End.
});


// Ahmed El-Atab Code Start.