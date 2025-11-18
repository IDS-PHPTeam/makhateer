jQuery(function($) {

    GetAllMakhateer();


    function GetAllMakhateer() {

        var Url = $IntegrationAPIUrl + "read";



        $.ajax({

            url: Url,

            async: false,

            type: "GET",

            success: function(result) {

                var Index = 0;

                var TripliCount = 0;

                var WestBekaa = 0;

                var Beirut = 0;

                var Tyre = 0;



                var Rashayya = 0;

                var zgharta = 0;

                var hermel = 0;

                var Batroun = 0;

                var Keserwan = 0;

                var Koura = 0;

                var Saida = 0;

                var Baalback = 0;

                var Jbeil = 0;

                var Menye = 0;

                var BintJbeil = 0;

                var Nabateyye = 0;

                var Jezzeine = 0;

                var Zahle = 0;

                var Akkar = 0;

                var Shouf = 0;

                var Aley = 0;

                var Bsharre = 0;

                var Marjeyoun = 0;

                var Hasbayya = 0;

                var Baabda = 0;

                $.map(result, function(val, i) {



                    $.map(val, function(val2, i2) {

                        if (val2.address == "قضاء طرابلس")

                            TripliCount++;

                        if (val2.address == "قضاء البقاع الغربي")

                            WestBekaa++;

                        if (val2.address == "بيروت")

                            Beirut++;

                        if (val2.address == "قضاء صور")

                            Tyre++;

                        if (val2.address == "قضاء راشيا")

                            Rashayya++;

                        if (val2.address == "قضاء زغرتا - الزاوية")

                            zgharta++;

                        if (val2.address == "قضاء الهرمل")

                            hermel++;

                        if (val2.address == "قضاء البترون")

                            Batroun++;

                        if (val2.address == "قضاء كسروان")

                            Keserwan++;

                        if (val2.address == "قضاء الكورة")

                            Koura++;

                        if (val2.address == "قضاء صيدا")

                            Saida++;

                        if (val2.address == "قضاء بعلبك")

                            Baalback++;

                        if (val2.address == "قضاء جبيل")

                            Jbeil++;

                        if (val2.address == "قضاء المنية الضنية")

                            Menye++;

                        if (val2.address == "قضاء بنت جبيل")

                            BintJbeil++;

                        if (val2.address == "قضاء النبطية")

                            Nabateyye++;

                        if (val2.address == "قضاء جزين")

                            Jezzeine++;

                        if (val2.address == "قضاء زحلة")

                            Zahle++;

                        if (val2.address == "قضاء عكار")

                            Akkar++;

                        if (val2.address == "قضاء الشوف")

                            Shouf++;

                        if (val2.address == "قضاء عاليه")

                            Aley++;

                        if (val2.address == "قضاء بشري")

                            Bsharre++;

                        if (val2.address == "قضاء مرجعيون")

                            Marjeyoun++;

                        if (val2.address == "قضاء حاصبيا")

                            Hasbayya++;

                        if (val2.address == "قضاء بعبدا")

                            Baabda++;







                    });

                    var Mokhtar = val[Index];







                    Index++;

                });



                $('#txtTyre').text(Tyre);

                $('#txtTripoliMale').text(TripliCount);

                $('#txtWestBekaa').text(WestBekaa);

                $('#txtBeirut').text(Beirut);

                $('#txtRashaya').text(Rashayya)





                $('#txtzgharta').text(zgharta)

                $('#txthermel').text(hermel)

                $('#txtBatroun').text(Batroun)

                $('#txtKeserwan').text(Keserwan)

                $('#txtKoura').text(Koura)

                $('#txtSaida').text(Saida)

                $('#txtBaalback').text(Baalback)

                $('#txtJbeil').text(Jbeil)

                $('#txtMenye').text(Menye)

                $('#txtBintJbeil').text(BintJbeil)

                $('#txtNabateyye').text(Nabateyye)

                $('#txtJezzeine').text(Jezzeine)

                $('#txtNabateyye').text(Nabateyye)

                $('#txtZahle').text(Zahle)

                $('#txtAkkar').text(Akkar)

                $('#txtShouf').text(Shouf)

                $('#txtAley').text(Aley)

                $('#txtBsharre').text(Bsharre)



                $('#txtMarjeyoun').text(Marjeyoun)

                $('#txtHasbayya').text(Hasbayya)

                $('#txtBaabda').text(Baabda)





            }

        })



    }

});