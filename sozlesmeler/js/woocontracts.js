jQuery(document).ready(function ($) {
    if (window.location.href.indexOf("order-pay") == -1) {
        $(document).ajaxComplete(function (event, xhr, settings) {
            console.log(settings.url);
            // settings.url tells us what event this is, so we can choose what code we need to run
            if (settings.url.indexOf('update_order_review') > -1) {
                // update order form
                sehrimiz = $("#billing_state").val().replace('TR', '');
                var find = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33", "34", "35", "36", "37", "38", "39", "40", "41", "42", "43", "44", "45", "46", "47", "48", "49", "50", "51", "52", "53", "54", "55", "56", "57", "58", "59", "60", "61", "62", "63", "64", "65", "66", "67", "68", "69", "70", "71", "72", "73", "74", "75", "76", "77", "78", "79", "80", "81"];
                var replace = ["Adana", "Adıyaman", "Afyon", "Ağrı", "Amasya", "Ankara", "Antalya", "Artvin", "Aydın", "Balıkesir", "Bilecik", "Bingöl", "Bitlis", "Bolu", "Burdur", "Bursa", "Çanakkale", "Çankırı", "Çorum", "Denizli", "Diyarbakır", "Edirne", "Elazığ", "Erzincan", "Erzurum", "Eskişehir", "Gaziantep", "Giresun", "Gümüşhane", "Hakkari", "Hatay", "Isparta", "Mersin", "İstanbul", "İzmir", "Kars", "Kastamonu", "Kayseri", "Kırklareli", "Kırşehir", "Kocaeli", "Konya", "Kütahya", "Malatya", "Manisa", "K.Maraş", "Mardin", "Muğla", "Muş", "Nevşehir", "Niğde", "Ordu", "Rize", "Sakarya", "Samsun", "Siirt", "Sinop", "Sivas", "Tekirdağ", "Tokat", "Trabzon", "Tunceli", "Şanlıurfa", "Uşak", "Van", "Yozgat", "Zonguldak", "Aksaray", "Bayburt", "Karaman", "Kırıkkale", "Batman", "Şırnak", "Bartın", "Ardahan", "Iğdır", "Yalova", "Karabük", "Kilis", "Osmaniye", "Düzce"];
                sehrimiz = sehrimiz.replaceArray(find, replace);
                $(".urunlistesi").empty();
                $("#urunListesi").clone().appendTo(".urunlistesi").show();
                $(".musteriad").text($("#billing_first_name").val());
                $(".musterisoyad").text($("#billing_last_name").val());
                $(".musterifirma").text($("#billing_company").val());
                $(".tckimlik").text($("#billing_tc").val());
                $(".vergidairesi").text($("#billing_vergi_dairesi").val());
                $(".vergino").text($("#billing_vergi_no").val());
                $(".musteriadres1").text($("#billing_address_1").val());
                $(".musteriadres2").text($("#billing_address_2").val());
                $(".musteriposta").text($("#billing_postcode").val());
                $(".musteriilce").text($("#billing_city").val());
                $(".musteriil").text(String(sehrimiz));
                $(".musteriulke").text($("#billing_country").val());
                if ($('input#ship-to-different-address-checkbox').is(':checked')) {
                    kargosehrimiz = $("#shipping_state").val().replace('TR', '');
                    kargosehrimiz = kargosehrimiz.replaceArray(find, replace);
                    $(".kargoad").text($("#shipping_first_name").val());
                    $(".kargosoyad").text($("#shipping_last_name").val());
                    $(".kargofirma").text($("#shipping_company").val());
                    $(".kargoadres1").text($("#shipping_address_1").val());
                    $(".kargoadres2").text($("#shipping_address_2").val());
                    $(".kargoposta").text($("#shipping_postcode").val());
                    $(".kargoilce").text($("#shipping_city").val());
                    $(".kargoil").text(String(kargosehrimiz));
                    $(".kargoulke").text($("#shipping_country").val());
                } else {
                    $(".kargoad").text($("#billing_first_name").val());
                    $(".kargosoyad").text($("#billing_last_name").val());
                    $(".kargofirma").text($("#billing_company").val());
                    $(".kargoadres1").text($("#billing_address_1").val());
                    $(".kargoadres2").text($("#billing_address_2").val());
                    $(".kargoposta").text($("#billing_postcode").val());
                    $(".kargoilce").text($("#billing_city").val());
                    $(".kargoil").text(String(sehrimiz));
                    $(".kargoulke").text($("#billing_country").val());
                }
                $(".musteritel").text($("#billing_phone").val());
                $(".musterieposta").text($("#billing_email").val());
                $(".wooctarih").text(date);

            }
            //            else if (settings.url.indexOf('wc-ajax=checkout') > -1) {
            //                // Add messages after checkout here
            //            }
        });

        String.prototype.replaceArray = function (find, replace) {
            var replaceString = this;
            var regex;
            for (var i = 0; i < find.length; i++) {
                regex = new RegExp(find[i], "g");
                replaceString = replaceString.replace(regex, replace[i]);
            }
            return replaceString;
        };

        var today = new Date();
        var date = ("0" + today.getDate()).slice(-2) + '-' + ("0" + (today.getMonth() + 1)).slice(-2) + '-' + today.getFullYear();
        var sehrimiz = '';
        sehrimiz = $("#billing_state").val().replace('TR', '');
        var find = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33", "34", "35", "36", "37", "38", "39", "40", "41", "42", "43", "44", "45", "46", "47", "48", "49", "50", "51", "52", "53", "54", "55", "56", "57", "58", "59", "60", "61", "62", "63", "64", "65", "66", "67", "68", "69", "70", "71", "72", "73", "74", "75", "76", "77", "78", "79", "80", "81"];
        var replace = ["Adana", "Adıyaman", "Afyon", "Ağrı", "Amasya", "Ankara", "Antalya", "Artvin", "Aydın", "Balıkesir", "Bilecik", "Bingöl", "Bitlis", "Bolu", "Burdur", "Bursa", "Çanakkale", "Çankırı", "Çorum", "Denizli", "Diyarbakır", "Edirne", "Elazığ", "Erzincan", "Erzurum", "Eskişehir", "Gaziantep", "Giresun", "Gümüşhane", "Hakkari", "Hatay", "Isparta", "Mersin", "İstanbul", "İzmir", "Kars", "Kastamonu", "Kayseri", "Kırklareli", "Kırşehir", "Kocaeli", "Konya", "Kütahya", "Malatya", "Manisa", "K.Maraş", "Mardin", "Muğla", "Muş", "Nevşehir", "Niğde", "Ordu", "Rize", "Sakarya", "Samsun", "Siirt", "Sinop", "Sivas", "Tekirdağ", "Tokat", "Trabzon", "Tunceli", "Şanlıurfa", "Uşak", "Van", "Yozgat", "Zonguldak", "Aksaray", "Bayburt", "Karaman", "Kırıkkale", "Batman", "Şırnak", "Bartın", "Ardahan", "Iğdır", "Yalova", "Karabük", "Kilis", "Osmaniye", "Düzce"];
        sehrimiz = sehrimiz.replaceArray(find, replace);
        $(".musteriad").text($("#billing_first_name").val());
        $(".musterisoyad").text($("#billing_last_name").val());
        $(".musterifirma").text($("#billing_company").val());
        $(".tckimlik").text($("#billing_tc").val());
        $(".vergidairesi").text($("#billing_vergi_dairesi").val());
        $(".vergino").text($("#billing_vergi_no").val());
        $(".musteriadres1").text($("#billing_address_1").val());
        $(".musteriadres2").text($("#billing_address_2").val());
        $(".musteriposta").text($("#billing_postcode").val());
        $(".musteriilce").text($("#billing_city").val());
        $(".musteriil").text(String(sehrimiz));
        $(".musteriulke").text($("#billing_country").val());
        if ($('input#ship-to-different-address-checkbox').is(':checked')) {
            kargosehrimiz = $("#shipping_state").val().replace('TR', '');
            kargosehrimiz = kargosehrimiz.replaceArray(find, replace);
            $(".kargoad").text($("#shipping_first_name").val());
            $(".kargosoyad").text($("#shipping_last_name").val());
            $(".kargofirma").text($("#shipping_company").val());
            $(".kargoadres1").text($("#shipping_address_1").val());
            $(".kargoadres2").text($("#shipping_address_2").val());
            $(".kargoposta").text($("#shipping_postcode").val());
            $(".kargoilce").text($("#shipping_city").val());
            $(".kargoil").text(String(kargosehrimiz));
            $(".kargoulke").text($("#shipping_country").val());
        } else {
            $(".kargoad").text($("#billing_first_name").val());
            $(".kargosoyad").text($("#billing_last_name").val());
            $(".kargofirma").text($("#billing_company").val());
            $(".kargoadres1").text($("#billing_address_1").val());
            $(".kargoadres2").text($("#billing_address_2").val());
            $(".kargoposta").text($("#billing_postcode").val());
            $(".kargoilce").text($("#billing_city").val());
            $(".kargoil").text(String(sehrimiz));
            $(".kargoulke").text($("#billing_country").val());
        }
        $(".musteritel").text($("#billing_phone").val());
        $(".musterieposta").text($("#billing_email").val());
        $(".wooctarih").text(date);
        $(".urunlistesi").empty();
        $("#urunListesi").clone().appendTo(".urunlistesi").show();
        $("input").keyup(function () {
            sehrimiz = $("#billing_state").val().replace('TR', '');
            var find = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33", "34", "35", "36", "37", "38", "39", "40", "41", "42", "43", "44", "45", "46", "47", "48", "49", "50", "51", "52", "53", "54", "55", "56", "57", "58", "59", "60", "61", "62", "63", "64", "65", "66", "67", "68", "69", "70", "71", "72", "73", "74", "75", "76", "77", "78", "79", "80", "81"];
            var replace = ["Adana", "Adıyaman", "Afyon", "Ağrı", "Amasya", "Ankara", "Antalya", "Artvin", "Aydın", "Balıkesir", "Bilecik", "Bingöl", "Bitlis", "Bolu", "Burdur", "Bursa", "Çanakkale", "Çankırı", "Çorum", "Denizli", "Diyarbakır", "Edirne", "Elazığ", "Erzincan", "Erzurum", "Eskişehir", "Gaziantep", "Giresun", "Gümüşhane", "Hakkari", "Hatay", "Isparta", "Mersin", "İstanbul", "İzmir", "Kars", "Kastamonu", "Kayseri", "Kırklareli", "Kırşehir", "Kocaeli", "Konya", "Kütahya", "Malatya", "Manisa", "K.Maraş", "Mardin", "Muğla", "Muş", "Nevşehir", "Niğde", "Ordu", "Rize", "Sakarya", "Samsun", "Siirt", "Sinop", "Sivas", "Tekirdağ", "Tokat", "Trabzon", "Tunceli", "Şanlıurfa", "Uşak", "Van", "Yozgat", "Zonguldak", "Aksaray", "Bayburt", "Karaman", "Kırıkkale", "Batman", "Şırnak", "Bartın", "Ardahan", "Iğdır", "Yalova", "Karabük", "Kilis", "Osmaniye", "Düzce"];
            sehrimiz = sehrimiz.replaceArray(find, replace);
            $(".urunlistesi").empty();
            $("#urunListesi").clone().appendTo(".urunlistesi").show();
            $(".musteriad").text($("#billing_first_name").val());
            $(".musterisoyad").text($("#billing_last_name").val());
            $(".musterifirma").text($("#billing_company").val());
            $(".tckimlik").text($("#billing_tc").val());
            $(".vergidairesi").text($("#billing_vergi_dairesi").val());
            $(".vergino").text($("#billing_vergi_no").val());
            $(".musteriadres1").text($("#billing_address_1").val());
            $(".musteriadres2").text($("#billing_address_2").val());
            $(".musteriposta").text($("#billing_postcode").val());
            $(".musteriilce").text($("#billing_city").val());
            $(".musteriil").text(String(sehrimiz));
            $(".musteriulke").text($("#billing_country").val());
            if ($('input#ship-to-different-address-checkbox').is(':checked')) {
                kargosehrimiz = $("#shipping_state").val().replace('TR', '');
                kargosehrimiz = kargosehrimiz.replaceArray(find, replace);
                $(".kargoad").text($("#shipping_first_name").val());
                $(".kargosoyad").text($("#shipping_last_name").val());
                $(".kargofirma").text($("#shipping_company").val());
                $(".kargoadres1").text($("#shipping_address_1").val());
                $(".kargoadres2").text($("#shipping_address_2").val());
                $(".kargoposta").text($("#shipping_postcode").val());
                $(".kargoilce").text($("#shipping_city").val());
                $(".kargoil").text(String(kargosehrimiz));
                $(".kargoulke").text($("#shipping_country").val());
            } else {
                $(".kargoad").text($("#billing_first_name").val());
                $(".kargosoyad").text($("#billing_last_name").val());
                $(".kargofirma").text($("#billing_company").val());
                $(".kargoadres1").text($("#billing_address_1").val());
                $(".kargoadres2").text($("#billing_address_2").val());
                $(".kargoposta").text($("#billing_postcode").val());
                $(".kargoilce").text($("#billing_city").val());
                $(".kargoil").text(String(sehrimiz));
                $(".kargoulke").text($("#billing_country").val());
            }
            $(".musteritel").text($("#billing_phone").val());
            $(".musterieposta").text($("#billing_email").val());
            $(".wooctarih").text(date);
        });
    }
});
