// Login & registration link
jQuery(document).ready(function() {
    "use strict";


    var errorMessage = "";
    var authenticationErrorMessage = "";

    var languageCode = jQuery("html:first").attr("lang");

    if (languageCode == "ar") {
        errorMessage = "بعض الخانات فارغة!";
    } else {
        errorMessage = "Some fields are empty!";
    }

    if (location.href.indexOf("authenticated=false") > -1) {
        if (languageCode == "ar") {
            authenticationErrorMessage = "!اسم المستخدم أو كلمة المرور خاطئة";
        } else {
            authenticationErrorMessage = "Incorrect username or password!";
        }

        // Hanlde Authentication Failure Start.

        var max_length_authentication = 1;

        let error = true;

        error = formValidate(jQuery('#loginForm form'), {
            error_message_show: true,
            error_message_time: 4000,
            error_message_class: 'sc_infobox sc_infobox_style_error',
            error_fields_class: 'msError',
            exit_after_first_error: true,
            rules: [{
                field: "authenticated",
                min_length: { value: 4, message: authenticationErrorMessage },
                max_length: { value: max_length_authentication, message: authenticationErrorMessage }
            }]
        });
    }

    // Hanlde Authentication Failure End.


    jQuery('#loginForm .sendEnter').click(function(e) {
        "use strict";
        jQuery('#loginForm form input').removeClass('msError');
        var error = false;

        max_length_authentication = 500;

        if (!jQuery("#login").val() || !jQuery("#password").val()) {
            error = true;

            error = formValidate(jQuery('#loginForm form'), {
                error_message_show: true,
                error_message_time: 4000,
                error_message_class: 'sc_infobox sc_infobox_style_error',
                error_fields_class: 'msError',
                exit_after_first_error: true,
                rules: [{
                        field: "log",
                        min_length: { value: 1, message: errorMessage },
                        max_length: { value: 60, message: errorMessage }
                    },
                    {
                        field: "pwd",
                        min_length: { value: 4, message: errorMessage },
                        max_length: { value: 20, message: errorMessage }
                    },
                    {
                        field: "authenticated",
                        min_length: { value: 4, message: authenticationErrorMessage },
                        max_length: { value: max_length_authentication, message: authenticationErrorMessage }
                    }
                ]
            });
        }
        /*var error = formValidate(jQuery('#loginForm form'), {
            error_message_show: true,
            error_message_time: 4000,
            error_message_class: 'sc_infobox sc_infobox_style_error',
            error_fields_class: 'msError',
            exit_after_first_error: true,
            rules: [{
                    field: "log",
                    min_length: { value: 1, message: errorMessage },
                    max_length: { value: 60, message: errorMessage }
                },
                {
                    field: "pwd",
                    min_length: { value: 4, message: errorMessage },
                    max_length: { value: 20, message: errorMessage }
                }
            ]
        });*/
        if (!error) {
            document.forms.login_form.submit();
        }
        e.preventDefault();
        return false;
    });

    jQuery('#registerForm .sendEnter').click(function(e) {
        "use strict";
        jQuery('#registerForm form input').removeClass('msError');
        var error = formValidate(jQuery("#registerForm form"), {
            error_message_show: true,
            error_message_time: 4000,
            error_message_class: "sc_infobox sc_infobox_style_error",
            error_fields_class: "msError",
            exit_after_first_error: true,
            rules: [{
                    field: "registration_username",
                    min_length: { value: 1, message: errorMessage },
                    max_length: { value: 60, message: errorMessage }
                },
                {
                    field: "registration_email",
                    min_length: { value: 7, message: errorMessage },
                    max_length: { value: 60, message: errorMessage },
                    mask: { value: "^([a-z0-9_\\-]+\\.)*[a-z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$", message: THEMEREX_EMAIL_NOT_VALID }
                },
                {
                    field: "registration_pwd",
                    min_length: { value: 4, message: errorMessage },
                    max_length: { value: 20, message: errorMessage }
                },
                {
                    field: "registration_pwd2",
                    equal_to: { value: 'registration_pwd', message: THEMEREX_PASSWORD_NOT_EQUAL }
                }
            ]
        });
        if (!error) {
            jQuery.post(THEMEREX_ajax_url, {
                action: 'registration_user',
                nonce: THEMEREX_ajax_nonce,
                user_name: jQuery('#registerForm #registration_username').val(),
                user_email: jQuery('#registerForm #registration_email').val(),
                user_pwd: jQuery('#registerForm #registration_pwd').val(),
            }).done(function(response) {
                var rez = JSON.parse(response);
                var result_box = jQuery('#registerForm .result');
                result_box.toggleClass('sc_infobox_style_error', false).toggleClass('sc_infobox_style_success', false);
                if (rez.error === '') {
                    result_box.addClass('sc_infobox_style_success').html(THEMEREX_REGISTRATION_SUCCESS);
                    setTimeout(function() {
                        jQuery('.loginFormTab').trigger('click');
                    }, 2000);
                } else {
                    result_box.addClass('sc_infobox_style_error').html(THEMEREX_REGISTRATION_FAILED + ' ' + rez.error);
                }
                result_box.fadeIn();
                setTimeout(function() { jQuery('#registerForm .result').fadeOut(); }, 2000);
            });
        }
        e.preventDefault();
        return false;
    });

});