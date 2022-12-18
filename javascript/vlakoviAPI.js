// ******** Tematika ********** 
function prikazTematike(id) {
    var data = {};
    data.id = id;

    $.ajax({
        url: "../JQueryPHPTematika.php",
        type: "POST",
        cache: false,
        dataType: 'json',
        data: data,
        success: function (result) {
            $("#detaljiZapisa").css("display", "block");
            var detalji = $("#detalji");
            detalji.children().remove();
            Object.keys(result).forEach(function (k) {
                detalji.append("<td>" + result[k] + "</td>");
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + "\n" + textStatus + "\n" + errorThrown);
        }
    });
}

// ******** Prikaz RSS kanala na početnoj stranici ********** 
function kanalRSSPrikazPodataka(id) {
    var data = {};
    data.id = id;

    $.ajax({
        url: "JQueryPHPRSSKanalIndex.php",
        type: "POST",
        cache: false,
        dataType: 'json',
        data: data,
        success: function (result) {
            $("#RSSKanal").css("display", "block");
            var detalji = $("#RSSTablica");
            detalji.children().remove();
            Object.keys(result).forEach(function (k) {
                detalji.append(
                        "<tr>" +
                        "<td>" + result[k].ime + "</td>" +
                        "<td>" + result[k].prezime + "</td>" +
                        "<td>" + result[k].korisnicko_ime + "</td>" +
                        "<td>" + result[k].email + "</td>" +
                        "<td>" + result[k].nazivVlaka + "</td>" +
                        "<td>" + result[k].nazivTematike + "</td>" +
                        "</tr>"
                        );
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + "\n" + textStatus + "\n" + errorThrown);
        }
    });
}

// ******** Kolacic ********** 
function promjenuUvjeteKoristenja(korisnickoIme) {
    var data = {};
    data.korisnickoIme = korisnickoIme;

    $.ajax({
        url: "JQueryPHPUvjetiKoristenjaKolacic.php",
        type: "POST",
        cache: false,
        data: data,
        success: function (result) {
            //location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + "\n" + textStatus + "\n" + errorThrown);
        }
    });
}
// ******** Vlakovi ********** 
function obrisiVlakKorisnika(id) {
    var data = {};
    data.id = id;

    $.ajax({
        url: "../JQueryPHPObrisiVlakKorisnika.php",
        type: "POST",
        cache: false,
        data: data,
        success: function () {
            location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + "\n" + textStatus + "\n" + errorThrown);
        }
    });
}

function dodavanjeVlakovaNaIzlozbu(prijavaVlakaId, korisnik) {
    var data = {};
    data.prijavaVlakaId = prijavaVlakaId;
    data.korisnik = korisnik;

    var vlakovi = $("#prikazVlakovaKorisnika");
    $("#bezVlakova").children().remove();
    vlakovi.css("display", "block");
    $("#prijaviVlakNaIzlozbu").css("display", "block");
    vlakovi.children().remove();
    $.ajax({
        url: "../JQueryPHPPrikazVlakaKorisnika.php",
        type: "POST",
        cache: false,
        dataType: 'json',
        data: data,
        success: function (result) {
            if (!result.length) {
                vlakovi.css("display", "none");
                $("#prijaviVlakNaIzlozbu").css("display", "none");
                $("#bezVlakova").append("<h3>Nemate dodanog vlaka ili su Vam svi vlakovi već dodani na izložbu</h3>");
            } else {
                Object.keys(result).forEach(function (k) {
                    vlakovi.append('<option value="' + result[k].IDVlaka + '" class="prikazDropDown">' + result[k].naziv + '</option>');
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + "\n" + textStatus + "\n" + errorThrown);
        }
    });
}

function ocijeniPrijavuVlaka(prijavaVlakaId, ocjena, komentar) {
    var data = {};
    data.prijavaVlakaId = prijavaVlakaId;
    data.ocjena = ocjena;
    data.komentar = komentar;

    $('#ocjena').val("");
    $('#komentar').val("");
    $.ajax({
        url: "../JQueryPHPOcijeniPrijavuVlaka.php",
        type: "POST",
        cache: false,
        dataType: 'json',
        data: data,
        success: function (result) {

            $("#popUpPomocKodGlasovanja").css("display", "none");
            alert(result);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + "\n" + textStatus + "\n" + errorThrown);
        }
    });
}

function prikazSvihVlakovaIzlozbe(id, korisnik, virtualniDatum, otvorenoGlasovanje, putanja) {
    var data = {};
    data.id = id;
    var detalji = $("#prikazVlakovaNaIzlozbi");
    detalji.children().remove();

    $.ajax({
        url: "../JQueryPHPPrikazVlakovaNaIzlozbi.php",
        type: "POST",
        cache: false,
        dataType: 'json',
        data: data,
        success: function (result) {
            var brojac = 1;
            if (result.length)
                detalji.append("<h2>Prihvaćeni vlakovi na izložbi:</h2>");
            Object.keys(result).forEach(function (k) {
                var slikaUrl = result[k]["slikaUrl"] ? result[k]["slikaUrl"] : putanja + "/multimedija/vlak1.jpg";
                var ocjenjivanje = otvorenoGlasovanje ? '<button type="submit" id="gumbGlasovanje" onclick="ocijeniVlak(' + result[k]["IDPrijaveVlaka"] + ",'" + result[k]["nazivVlaka"] + "'" + ')"class="gumbPrijava">Ocijeni</button>' : '';

                if (brojac % 2 == 1) {
                    detalji.append(
                            '<div class="prikazKorisnika">' +
                            '<div class="kutija">' +
                            '<img src="' + slikaUrl + '" alt="' + result[k]["nazivVlaka"] + '" class="slikaKorisnikaLijeva">' +
                            '<div class="prikazTeksataLijevi" id="prikazInformacijaVlasnika' + brojac + '">' +
                            "<a><b>Informacije o vlasniku:</b> " + result[k]["ime"] + " " + result[k]["prezime"] + " - " + result[k]["korisnickoIme"] + "</a>" +
                            "<br><br>" +
                            "<a><b>Naziv vlaka:</b> " + result[k]["nazivVlaka"] + "</a>" +
                            "<br><br>" +
                            "<a><b>Maksimalna brzina:</b> " + result[k]["max_brzina"] + "&nbsp;&nbsp;&nbsp;&nbsp;<b>Broj sjedala vlaka:</b> " + result[k]["broj_sjedala"] + "</a>" +
                            "<br><br>" +
                            "<a><b>Vrsta pogona:</b> " + result[k]["naziv_pogona"] +
                            "<br><br>" +
                            '<form style="float: right; width: 100px;"><button type="submit" class="gumbPrijava" name="prikazDetaljaKorisnika" value="' + result[k]["IDPrijaveVlaka"] + '">Detalji</button></form>' +
                            ocjenjivanje +
                            "</div>" +
                            "<br><br>" +
                            "</div>"

                            );
                } else {
                    detalji.append(
                            '<div class="prikazKorisnika">' +
                            '<div class="kutija">' +
                            '<img src="' + slikaUrl + '" alt="' + result[k]["nazivVlaka"] + '" class="slikaKorisnikaDesna">' +
                            '<div class="prikazTeksataDesni" id="prikazInformacijaVlasnika' + brojac + '">' +
                            "<a><b>Informacije o vlasniku:</b> " + result[k]["ime"] + " " + result[k]["prezime"] + " - " + result[k]["korisnickoIme"] + "</a>" +
                            "<br><br>" +
                            "<a><b>Naziv vlaka:</b> " + result[k]["nazivVlaka"] + "</a>" +
                            "<br><br>" +
                            "<a><b>Maksimalna brzina:</b> " + result[k]["max_brzina"] + "&nbsp;&nbsp;&nbsp;&nbsp;<b>Broj sjedala vlaka:</b> " + result[k]["broj_sjedala"] + "</a>" +
                            "<br><br>" +
                            "<a><b>Vrsta pogona:</b> " + result[k]["naziv_pogona"] +
                            "<br><br>" +
                            '<form style="float: right; width: 100px;"><button type="submit" class="gumbPrijava" name="prikazDetaljaKorisnika" value="' + result[k]["IDPrijaveVlaka"] + '">Detalji</button></form>' +
                            ocjenjivanje +
                            '</div>' +
                            "<br><br>" +
                            "</div>"
                            );
                }
                if (result[k]["korisnickoIme"] == korisnik) {
                    var obrisi = $("#prikazInformacijaVlasnika" + brojac);
                    if (result[k]["datum_pocetka"] >= virtualniDatum)
                        obrisi.append(
                                '<button type="submit" class="gumbPrijava" onclick="obrisiVlakKorisnika(' + result[k]["IDPrijaveVlaka"] + ')">Obrisi vlak</button>' +
                                '<button type="submit" class="gumbPrijava" onclick="dodavanjeMaterijala(' + result[k]["IDPrijaveVlaka"] + ')">Dodaj materijale</button>');
                }
                brojac++;
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + "\n" + textStatus + "\n" + errorThrown);
        }
    });
}