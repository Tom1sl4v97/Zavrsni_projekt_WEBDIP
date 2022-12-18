<?php

$putanja = dirname($_SERVER['REQUEST_URI'], 2);
$direktorij = dirname(getcwd());

$naslov = "Prikaz detalja korisnika";
$opis = "";
include '../zaglavlje.php';

$idPrijavaVlaka = $_SESSION["id"];

//prikaz potrebnih informacija o vlaku i njegovom korisniku
informacijeOKorisniku($smarty, $idPrijavaVlaka);

prikazMaterijalaKorisnika();

$smarty->display('prikazDetaljaKorisnika.tpl');
$smarty->display('podnozje.tpl');

function prikazMaterijalaKorisnika() {
    prikazSlikaKorisnika();
    prikazVideaKorisnika();
    prikazAudiaKorisnika();
    prikazGifaKorisnika();
}

function prikazGifaKorisnika() {
    global $idPrijavaVlaka;
    global $smarty;

    $sql = "SELECT id, url FROM materijal WHERE prijava_vlaka_id = {$idPrijavaVlaka} AND vrsta_materijala_id = 4";
    $rezultat = dohvatiPodatke($sql);

    for ($i = 0; $i < count($rezultat); $i++) {
        $url = explode("/", $rezultat[$i]["url"]);
        $pc = count($url);

        $naziv[$i] = $url[$pc - 1];
    }
    if (!empty($rezultat[0]["id"])) {
        $smarty->assign("nazivGifa", $naziv);
        $smarty->assign("gifKorisnika", $rezultat);
    }
}

function prikazAudiaKorisnika() {
    global $idPrijavaVlaka;
    global $smarty;

    $sql = "SELECT id, url FROM materijal WHERE prijava_vlaka_id = {$idPrijavaVlaka} AND vrsta_materijala_id = 2";
    $rezultat = dohvatiPodatke($sql);

    for ($i = 0; $i < count($rezultat); $i++) {
        $url = explode("/", $rezultat[$i]["url"]);
        $pc = count($url);

        $naziv[$i] = $url[$pc - 1];
    }
    if (!empty($rezultat[0]["id"])) {
        $smarty->assign("nazivAudia", $naziv);
        $smarty->assign("audioKorisnika", $rezultat);
    }
}

function prikazVideaKorisnika() {
    global $idPrijavaVlaka;
    global $smarty;

    $sql = "SELECT id, url FROM materijal WHERE prijava_vlaka_id = {$idPrijavaVlaka} AND vrsta_materijala_id = 3";
    $rezultat = dohvatiPodatke($sql);

    for ($i = 0; $i < count($rezultat); $i++) {
        $url = explode("/", $rezultat[$i]["url"]);
        $pc = count($url);

        $naziv[$i] = $url[$pc - 1];
    }
    if (!empty($rezultat[0]["id"])) {
        $smarty->assign("nazivVidea", $naziv);
        $smarty->assign("videoKorisnika", $rezultat);
    }
}

function prikazSlikaKorisnika() {
    global $idPrijavaVlaka;
    global $smarty;

    $sql = "SELECT id, url FROM materijal WHERE prijava_vlaka_id = {$idPrijavaVlaka} AND vrsta_materijala_id = 1";
    $rezultat = dohvatiPodatke($sql);


    for ($i = 0; $i < count($rezultat); $i++) {
        $url = explode("/", $rezultat[$i]["url"]);
        $pc = count($url);

        $naziv[$i] = $url[$pc - 1];
    }
    if (!empty($rezultat[0]["id"])) {
        $smarty->assign("nazivSlike", $naziv);
        $smarty->assign("slikeKorisnika", $rezultat);
    }
}

function informacijeOKorisniku($smarty, $idPrijavaVlaka) {
    $sql = "SELECT v.naziv, v.max_brzina, v.broj_sjedala, v.opis AS 'opisVlaka', k.ime, k.prezime, k.korisnicko_ime, k.email, vp.naziv_pogona, vp.opis AS 'opisPogona'"
            . " FROM prijavavlaka pv"
            . " INNER JOIN vlak v ON pv.vlak_id = v.id"
            . " INNER JOIN korisnik k ON v.vlasnik_id = k.id"
            . " INNER JOIN vrstapogona vp ON v.vrsta_pogona_id = vp.id"
            . " WHERE pv.id = {$idPrijavaVlaka}";
    $rezultat = dohvatiPodatke($sql);
    $smarty->assign("informacijeVlakaKorisnika", $rezultat);
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