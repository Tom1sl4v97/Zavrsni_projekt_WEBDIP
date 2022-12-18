<?php

$putanja = dirname($_SERVER['REQUEST_URI'], 2);
$direktorij = dirname(getcwd());
$prikazDirektorijaCSS = "http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2020%2Fzadaca_04%2Fttomiek%2Fobrasci%2Fprijava.php&profile=css3svg&usermedium=all&warning=1&vextwarning=&lang=en";

$naslov = "Prijava korisnika";
$opis = 'Ovo je početna stranica, koja prikazije tablicu o pticama, kreirana 18.3.2021.';
include '../zaglavlje.php';

$greska = "";


if (isset($_GET['registrirani_korisnik'])) {
    Sesija::kreirajKorisnika("mmarulic", $virtualniDatumVrijeme, 3, "disabled", "disabled");
}
if (isset($_GET['moderator'])) {
    Sesija::kreirajKorisnika("dtokic", $virtualniDatumVrijeme, 2, "disabled", "disabled");
}
if (isset($_GET['administrator'])) {
    Sesija::kreirajKorisnika("ttomiek", $virtualniDatumVrijeme, 1, "disabled", "disabled");
}

provjeraKorisnika();

prijaviKorisnika();

$smarty->assign("greska", $greska);

$smarty->display('prijava.tpl');
$smarty->display('podnozje.tpl');

function provjeraKorisnika() {
    global $putanja;
    if (isset($_SESSION["uloga"])) {
        header("Location: $putanja/index.php");
        exit();
    }
}

function prijaviKorisnika() {
    if (isset($_GET['prijava_korisnika'])) {
        global $greska;
        $podaci = provjeriPodatke();
        if (empty($greska)) {
            ulogirajKorisnika($podaci);
        }
    }
}

function provjeriPodatke() {
    global $greska;
    $greske = array("korime" => "korisničko ime", "lozinka" => "lozinku");
    foreach ($_GET as $k => $v) {
        $podaci[$k] = $v;
        if (empty($v)) {
            foreach ($greske as $key => $val) {
                if ($key == $k) {
                    $greska .= "Niste popunili " . $val . "<br>";
                }
            }
        }
        if ($k === "lozinka" and empty($greska)) {
            $uzorak = '/^(?!.*(.)\1{3})((?=.*[\d])(?=.*[A-Za-z])|(?=.*[^\w\d\s])(?=.*[A-Za-z])).{8,20}$/';
            if (!preg_match($uzorak, $v)) {
                $greska .= "Format: Lozinka ima manje od 8 znakova "
                        . "ili više od 20 znakova "
                        . "ili nema 1 alfanumerički znak "
                        . "ili nema najmanje 1 broj "
                        . "ili nema specijalni znak "
                        . "ili se ponavljaju 3 ista znaka!"
                        . "<br>";
            }
        }
    }
    if (isset($podaci["zapamtiMe"])) {
        setcookie("zapamtiKorisnika", "zapamcen", time() + (86400 * 30), "/");
    } else {
        setcookie("zapamtiKorisnika", "zaboravljen", time() + (86400 * 30), "/");
    }

    return $podaci;
}

function ulogirajKorisnika($podaci) {
    global $greska;
    global $virtualniDatumVrijeme;
    $sql = "SELECT * FROM korisnik WHERE korisnicko_ime='{$podaci["korime"]}'";
    $rezultat = dohvatiPodatke($sql);

    if (!empty($rezultat[0]["korisnicko_ime"])) {
        if ($rezultat[0]["datum_kreiranja"] != "") {
            $lozinka = hash("sha256", $podaci["lozinka"] . $rezultat[0]["salt"]);
            if ($rezultat[0]["lozinka_sha1"] == $lozinka and $rezultat[0]["broj_neuspijesnih_prijava"] <= 3) {
                $autenticiran = true;
                Sesija::kreirajKorisnika($rezultat[0]["korisnicko_ime"], $virtualniDatumVrijeme, $rezultat[0]["tip_korisnika_id"], "disabled", "disabled");
                resetirajBrojPokusaja($rezultat);
                zapisiDnevnik($rezultat);
                provjeraKorisnika();
            } elseif ($rezultat[0]["broj_neuspijesnih_prijava"] >= 3) {
                $greska .= "Korisniči račun  {$rezultat[0]["korisnicko_ime"]} Vam je blokiran. Molimo Vas kontaktirajte administratora";
            } else {
                povecajBrojPokusaja($rezultat);
            }
        } else
            $greska .= "Niste aktivirali svoj račun preko aktivacijskog koda";
    } else
        $greska .= "Nepostojeće korisnićko ime";
}

function zapisiDnevnik($rezultat) {
    global $virtualniDatumVrijeme;
    global $direktorij;
    $datum = date("Y-m-d H:i:s", strtotime($virtualniDatumVrijeme));
    $sql = "INSERT INTO dnevnik (stranica, datum_pristupa, tip_dnevnika_id, korisnik_id) VALUES ('{$_SERVER['REQUEST_URI']}', '{$datum}', 1, '{$rezultat[0]["id"]}')";
    zapisiPodatke($sql);
}

function resetirajBrojPokusaja($podaci) {
    $sql = "UPDATE korisnik SET broj_neuspijesnih_prijava = '0' WHERE korisnicko_ime = '{$podaci[0]["korisnicko_ime"]}'";
    zapisiPodatke($sql);
}

function povecajBrojPokusaja($podaci) {
    global $greska;
    $broj = $podaci[0]["broj_neuspijesnih_prijava"];
    $broj++;
    $sql = "UPDATE korisnik SET broj_neuspijesnih_prijava = {$broj} WHERE korisnicko_ime = '{$podaci[0]["korisnicko_ime"]}'";
    zapisiPodatke($sql);
    if ($broj >= 3) {
        $greska .= "Korisniči račun  {$podaci[0]["korisnicko_ime"]} Vam je blokiran. Molimo Vas kontaktirajte administratora";
    } else
        $greska .= "Pogrešna lozinka molimo pokušajte ponovno";
}

function zapisiPodatke($sql) {
    $veza = new Baza;
    $veza->spojiDB();
    $rezultat = $veza->updateDB($sql);
    $veza->zatvoriDB();
}

function dohvatiPodatke($sql) {
    $veza = new Baza;
    $veza->spojiDB();
    $rezultat = $veza->selectDB($sql);
    $redovi = array();
    while ($red = mysqli_fetch_array($rezultat)) {
        $redovi[] = $red;
    }
    $veza->zatvoriDB();
    return $redovi;
}

?>