window.addEventListener("load", zatvoriPopUp);
window.addEventListener("load", unazad);

function odabirVlaka(id, naziv) {
    dodaj = document.getElementById("dodajVlak");
    if (dodaj) {
        dodaj.addEventListener("click", function (event) {
            prijaviVlak = document.getElementById("popUpPomoc");
            prijaviVlak.style.display = "block";

            document.getElementById("textIzlozbe").innerHTML = naziv;
            document.getElementById("prijaviVlakNaIzlozbu").value = id;
        }, false);
    }
}

function zatvoriPopUp() {
    odustani = document.getElementById("odustani");
    if (odustani) {
        odustani.addEventListener("click", function (event) {
            document.getElementById("popUpPomoc").style.display = "none";
        }, false);
    }
    odustani2 = document.getElementById("odustaniGlasati");
    if (odustani2) {
        odustani2.addEventListener("click", function (event) {
            document.getElementById("popUpPomocKodGlasovanja").style.display = "none";
        }, false);
    }
    odustani3 = document.getElementById("odustaniOdDodavanjaMaterijala");
    if (odustani3) {
        odustani3.addEventListener("click", function (event) {
            document.getElementById("popUpPomocKodDodavanjaMaterijala").style.display = "none";
        }, false);
    }
}

//ova funkcija sadrži infosmacije o izložbi
function prikaziDetalje(id, naziv, opis, datum, trenutni, maxKorisnika, naslovnaSlike, korisnickoImePrijavljenogKorisnika, status, virtualniDatum, putanja) {
    var vrijeme = datum.split(" ");
    var vrijemeDatuma = vrijeme[0].split("-");

    document.getElementById("slikaNaslova").src = putanja+"/multimedija/prikazTeme/" + naslovnaSlike + ".jpg";
    document.getElementById("slider").style.display = "none";
    document.getElementById("prikazIzlozbe").style.display = "none";
    document.getElementById("detaljiIzlozbe").style.display = "block";
    document.getElementById("naslov").innerHTML = naziv;
    document.getElementById("opis").innerHTML = opis;
    document.getElementById("datumPocetka").innerHTML = vrijemeDatuma[2] + "/" + vrijemeDatuma[1] + "/" + vrijemeDatuma[0]
    document.getElementById("vrijemePocetka").innerHTML = vrijeme[1] + " sati";
    document.getElementById("popunjenost").innerHTML = trenutni + " / " + maxKorisnika;

    if (datum <= virtualniDatum || trenutni >= maxKorisnika)
        document.getElementById("dodajVlak").style.display = "none";
    else
        document.getElementById("dodajVlak").style.display = "block";

    dodavanjeVlakovaNaIzlozbu(id, korisnickoImePrijavljenogKorisnika);
    odabirVlaka(id, naziv);
    prikazSvihVlakovaIzlozbe(id, korisnickoImePrijavljenogKorisnika, virtualniDatum, status == "Otvoreno glasovanje", putanja);
}

function dodavanjeMaterijala(id) {
    document.getElementById("popUpPomocKodDodavanjaMaterijala").style.display = "block";
    document.getElementById("posaljiMaterijale").value = id;
}

function unazad() {
    natrag = document.getElementById("natrag");
    natrag.addEventListener("click", function (event) {
        document.getElementById("detaljiIzlozbe").style.display = "none";
        document.getElementById("prikazIzlozbe").style.display = "block";
        document.getElementById("slider").style.display = "block";
    }, false);
}

function ocijeniVlak(prijavaVlakaId, naziv) {
    document.getElementById("popUpPomocKodGlasovanja").style.display = "block";
    document.getElementById("nazivVlaka").innerHTML = naziv;

    var glasovanje = $("#glasovanje");
    if (glasovanje) {
        glasovanje.one('click', function () {
            var ocjena = $("#ocjena").val();
            var komentar = $("#komentar").val();
            ocijeniPrijavuVlaka(prijavaVlakaId, ocjena, komentar)
        });
    }
}

