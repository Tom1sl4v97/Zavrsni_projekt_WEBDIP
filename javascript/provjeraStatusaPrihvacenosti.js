window.addEventListener("load", prikazivanjePopUpa);
window.addEventListener("load", zatvoriPopUp);

function zatvoriPopUp() {
    odustani = document.getElementById("odustani");
    if (odustani) {
        odustani.addEventListener("click", function (event) {
            document.getElementById("popUpPrihvaćanjaUvjetaKoristenja").style.display = "none";
        }, false);
        odustani2 = document.getElementById("nePrihvaćam");
        odustani2.addEventListener("click", function (event) {
            kreirajKolacic("nePrihvaća");
            document.getElementById("popUpPrihvaćanjaUvjetaKoristenja").style.display = "none";
        }, false);
    }
}


function kreirajKolacic(korisnickoIme) {
    var trajanjeKolacica = document.getElementById("popUpPrihvaćanjaUvjetaKoristenja").getAttribute("trajanjeKolacica");
    var vrijeme = new Date(document.getElementById("popUpPrihvaćanjaUvjetaKoristenja").getAttribute("virtualnoVrijeme"));
    vrijeme.setTime(vrijeme.getTime() + (trajanjeKolacica * 24 * 60 * 60 * 1000 - 2 * 60 * 60 * 1000));
    var istjece = "expires=" + vrijeme;
    document.cookie = "korisnik=" + korisnickoIme + ";" + istjece + ";path=/";
}
function dohvatiKolacic() {
    var dc = document.cookie;
    var prefix = "korisnik=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0)
            return null;
    } else
    {
        begin += 2;
        var end = document.cookie.indexOf(";", begin);
        if (end == -1) {
            end = dc.length;
        }
    }
    return decodeURI(dc.substring(begin + prefix.length, end));
}

function prikazivanjePopUpa() {
    var uvjetiKoristenjaPopUp = document.getElementById("popUpPrihvaćanjaUvjetaKoristenja");

    if (uvjetiKoristenjaPopUp) {
        var status = uvjetiKoristenjaPopUp.getAttribute("status");
        var korisnickoIme = uvjetiKoristenjaPopUp.getAttribute("korisnik");

        var korisnik = dohvatiKolacic();

        if (status == 0 && korisnik == null) {
            document.getElementById("popUpPrihvaćanjaUvjetaKoristenja").style.display = "block";
        } else if (status == 0 && korisnik != "nePrihvaća") {
            document.getElementById("popUpPrihvaćanjaUvjetaKoristenja").style.display = "block";
        } else if (korisnik == null) {
            promjenuUvjeteKoristenja(korisnickoIme);
        }

        var prihvati = document.getElementById("prihvatiUvjete");
        prihvati.addEventListener("click", function (event) {
            kreirajKolacic(korisnickoIme);
            dokument.getElementById("popUpPrihvaćanjaUvjetaKoristenja").style.display = "none";
        }, false);
    }
}

