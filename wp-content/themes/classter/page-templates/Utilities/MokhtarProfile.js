var id;

var table

jQuery(function($) {

    //BindProfile();

    //BindMokhtarApplications();



    function BindMokhtarApplications() {



        var Url = $IntegrationAPIUrl + "Applications?id=" + $LoggedInUserName + "&password=" + $Password + "&username=" + $Username;

        $.ajax({

            url: Url,

            type: "GET",



            async: false,

            success: function(result) {

                console.log(result)

                if (!$.fn.DataTable.isDataTable('#dtApplications'))

                    GeneratMasterTable(result);

                else {

                    table.clear().rows.add(result).draw()

                    selectedRow = null;

                }



            },

        });

    }



    function GeneratMasterTable(result) {

        table = $('#dtApplications').DataTable({

            "responsive": true,

            "serverSide": false,

            "data": result,

            "paging": true,

            "cache": false,

            "idSrc": 'id',

            "rowId": 'id',

            "searching": true,

            "bInfo": false,

            "bLengthChange": false,

            "direction": "ltr",

            "sort": false,

            "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',

            "language": {

                "paginate": {

                    "previous": "Previous",

                    "next": "Next"

                },

                "sSearch": "Search",

                "zeroRecords": "No Records Found"

            },

            "columnDefs": [{

                    "targets": [0],

                    "visible": false,

                },





            ],

            columns: [{

                    data: "id",

                    name: "Id"

                },



                {

                    data: "Application_Doc_No_",

                    width: "32%",

                },

                {

                    data: "Ward_Status",

                    width: "32%",



                }

            ],

            select: true,

        }).on("draw.dt", function() {

            $("#dtApplications input").addClass("form-control");

            $("#dtApplications select").addClass("form-control");



        });

    }



    function BindProfile() {



        var Url = $IntegrationAPIUrl + "Profile?CId=" + $LoggedInUserName;



        $.ajax({

            url: Url,

            type: "GET",

            contentType: 'application/json',

            success: function(result) {

                id = result.id;

                $('#txtFullName').val(result.name);

                $('#dtDateOfBirth').val(result.dob);

                $('#txtPhone').val(result.phone);

                $('#txtAddress').val(result.address);

                $('#txtCID').text(result.CID);







            }

        });





    }





    function ViewPassword() {

        var x = document.getElementById("myInput");

        if (x.type === "password") {

            x.type = "text";

        } else {

            x.type = "password";

        }

    }









    /*$('#btnSave').click(function() {
        var flag = true;

        var id = $("#btnSave").val();
        var Phone = $('#txtPhone').val();
        var DOB = $('#dtDateOfBirth').val();
        var FullName = $('#txtFullName').val();
        var Password = $('#txtPassword').val();
        //var Address = $('#txtAddress').val();

        if (!Phone || !DOB || !FullName)

            flag = false

        if (!flag) {

            alert("All fields are required");

            return;

        }



        var Profile = new Object();

        let body = {
            "wp_mokhtar_id": id,
            //"address": Address,
            "phone": Phone,
            "dob": DOB,
            "full_name": FullName,
            "password": Password,
            //"action": "ids_save_mokhtar_profile"
        }


        console.log(body)


        var Url = $IntegrationAPIUrl


        $.post($IntegrationAPIUrl + "/profile", body, function(response) {
            console.log(response);
        }).fail(function(error) {
            console.log(error);
        });
        /*$.ajax({

            url: Url,

            type: "POST",

            contentType: 'application/json',


            data: JSON.stringify(Profile),

            success: function(result) {

                alert(result);

            }

        });*/



});