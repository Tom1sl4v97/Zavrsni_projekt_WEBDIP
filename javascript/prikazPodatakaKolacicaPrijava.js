window.addEventListener("load", popuniPodatke);

function dohvatiKolacic(ime) {
    var dc = document.cookie;
    var prefix = ime + "=";
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

function popuniPodatke() {
    var podaci = dohvatiKolacic("korisnik");
    var zapamti = dohvatiKolacic("zapamtiKorisnika");
    if (podaci != null && podaci != "nePrihvaća" && zapamti == "zapamcen"){
        document.getElementById("korime").value = podaci;
        document.getElementById("zapamtiMe").checked = true;
    }
}

