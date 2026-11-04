jQuery(function($) {

    BindElectoralStates();


    $language_code = $("html:first").attr("lang");


    function BindElectoralStates() {

        var html = '<option value="-1">' + $Select + '</option>';

        var d = new Date();

        var n = 3000;

        var count = 0;

        var EndYear = 0;

        if (n % 6 != 0) {
            n = n + (n % 6);
        }

        for (var i = 2010; i < n; i++) {

            if (count == 6) {

                EndYear = i;

                count = 0;

                html += '<option start="' + (EndYear - 6) + '" end="' + EndYear + '">' + (EndYear - 6) + '--' + EndYear + '</option>';

                EndYear = 0;

            }

            count++;
        }

        $('.periods:first').html(html);

    }





    $('#btnCalculate').click(function() {

        ids_calculate_eos();

    });


    function ids_calculate_eos() {
        let periods = [];

        let is_all_fields_filled = true;

        $(".term").each(function(index, value, array) {

            let period = new Object();

            period.is_complete = $(this).find("input[name^='is_complete']:checked").val() == "1";
            period.starting_year = $(this).find(".periods:first option:selected").attr("start");
            period.ending_year = $(this).find(".periods:first option:selected").attr("end");

            if (!period.is_complete) {
                period.starting_date = $(this).find("#starting_date").val();
                period.ending_date = $(this).find("#ending_date").val();
            }

            if ((period.starting_year && period.ending_year) && (period.is_complete || (!period.is_complete && period.starting_date && period.ending_date))) {
                periods.push(period);
            } else {
                if ($language_code != "ar") {
                    AlertSwal("Please fill all fields!");
                } else {
                    AlertSwal("الرجاء تعبئة كل الخانات");
                }

                is_all_fields_filled = false;
            }
        });

        if (!is_all_fields_filled) {
            return;
        }

        let body = {
            "periods": periods,
            "is_currently_mokhtar": $("#is_currently_mokhtar").is(':checked'),
            "lang": $language_code,
            "from_web": true
                //"action": "ids_calculate_eos"
        }

        console.log(body)

        $("#ids-loader").css({ "display": "inline-block" });

        $.ajax({
            url: $IntegrationAPIUrl + "/eos",
            type: "POST",
            data: body,
            dataType: "json"
        }).done(function(response) {
            console.log(response);
            $("#ids-loader").css({ "display": "none" });

            var msg = (response && response.message) ? response.message : null;
            if (!msg) {
                msg = ($language_code != "ar")
                    ? "Calculation completed, but no result was returned."
                    : "تم الاحتساب لكن لم يتم إرجاع نتيجة.";
            }
            AlertSwal(msg);
        }).fail(function(xhr) {
            console.log(xhr);
            $("#ids-loader").css({ "display": "none" });

            var msg = ($language_code != "ar")
                ? "Unable to calculate. Please try again."
                : "تعذر الاحتساب. الرجاء المحاولة مجدداً.";

            if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }

            AlertSwal(msg);
        });

    }

    function CalculateEndOfService() {

        var flag = true;

        var SelectedDays = [];

        for (var i = 0; i < Nodes.length; i++) {



            var Day = new Object();

            $('#' + Nodes[i]).find('input, select, textarea').each(function() {



                if ($(this).attr('name') == "StartDate") {



                    Day.from_date = $(this).val();

                    if (Day.from_date == null)

                        flag = false;

                }

                if ($(this).attr('name') == "EndDate") {



                    Day.to_date = $(this).val();

                    if (Day.to_date == null || Day.to_date == "")

                        flag = false;

                }



            });

            SelectedDays.push(Day)

        }

        if (!flag) {

            if ($language_code != "ar") {
                AlertSwal("Please fill all fields!");
            } else {
                AlertSwal("الرجاء تعبئة كل الخانات");
            }

        } else {



            var Url = $IntegrationAPIUrl + "EndOfService";

            let body = {
                "selected_days": SelectedDays,
                //"action": "ids_calculate_eos"
            }

            console.log(body)

            $("#ids-loader").css({ "display": "inline-block" });

            $.post($IntegrationAPIUrl + "/eos", body, function(response) {
                console.log(response);

                $("#ids-loader").css({ "display": "none" });


                if ($language_code != "ar") {

                    AlertSwal("Total Amount of your end of service is : " + response["Total"] + " L.L.");

                } else {

                    AlertSwal("القيمة الكلية لتعويض نهاية الخدمة : " + response["Total"] + " ل.ل.");



                }
            }).fail(function(error) {
                console.log(error);
            });

            /*$.ajax({

                url: Url,

                type: "Post",

                contentType: 'application/json',

                data: JSON.stringify(SelectedDays),

                success: function(result) {





                    if ($language_code == "en") {

                        AlertSwal("Total Amount of your end of service is : " + result + " L.L.");

                    } else {

                        AlertSwal("القيمة الكلية لتعويض نهاية الخدمة : " + result + " ل.ل.");



                    }

                }

            });*/

        }

    }

    var AlertSwal = function(msg) {

        var btnText = $language_code != "ar" ? "OK" : "حسنا";



        swal.fire({

            title: msg,

            confirmButtonText: btnText,

            allowEscapeKey: false,

            allowOutsideClick: false,

        });

    }

    var AlertSwalSucceeded = function(msg) {

        Swal.fire({

            type: 'success',

            title: msg,

            allowEscapeKey: false,

            allowOutsideClick: false,

            showConfirmButton: false,

            timer: 2000

        })

    }


});