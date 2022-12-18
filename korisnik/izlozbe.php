<?php

$putanja = dirname($_SERVER['REQUEST_URI'], 2);
$direktorij = dirname(getcwd());

$naslov = "Izložbe vlakova";
$opis = "";
include '../zaglavlje.php';

//provjera ne registriranog korisnika
provjeraKorisnika($putanja);
//prikaz izlozbi za mogucnost prijave
prikazIzlozbi($smarty);
//prijavljivanje vlaka na odabranu izlozbu
prijaviVlakNaIzlozbu($putanja);
//Prikaz detalja korisnika kada se klikne na gumb detalji, nakon otvaranja detalja izlozbi
prikaziDetaljeKorisnika($putanja);
//ocjenjivanje korisnika na određenoj izložbi
ocijeni();
//prikaz vrste materijala
prikazVrsteMaterijala($smarty);

$izborSlike = array("Moderni" => "Moderni vlak", "Brzi" => "Najbrži vlak", "Motorni" => "Motorni vlak",
    "Najmanji" => "Najmanji vlak", "Ogromni" => "Najveći vlak", "Parni" => "Lokomotive");

$smarty->assign("korisnickoImePrijavljenogKorisnika", $_SESSION["korisnik"]);
$smarty->assign("izborSlike", $izborSlike);
$smarty->display('izlozbe.tpl');
$smarty->display('podnozje.tpl');

function ocijeni() {
    if (isset($_GET["glasajZaKorisnika"])) {
        $idGlasovanja = $_GET["glasajZaKorisnika"];
        foreach ($_GET as $k => $v) {
            $podaciGlasanja[$k] = $v;
        }
        $sql = "INSERT INTO ocjena (ocjena_korisnika, komentar, glasovanje_id, korisnik_id) VALUES"
                . " ('{$podaciGlasanja["ocijenaKorisnika"]}', '{$podaciGlasanja["komentarKorisnika"]}', '{$idGlasovanja}', {$podaciGlasanja["prikazKorisnikaZaGlasati"]})";
        zapisiPodatke($sql);
        var_dump("Zapisano");
        echo "<script>window.location.href='$putanja/korisnik/korisnika.php';</script>";
    }
}

function prikazVrsteMaterijala($smarty) {
    global $vrstaMaterijala;
    $sql = "SELECT * FROM vrstamaterijala";
    $rezultat = dohvatiPodatke($sql);
    $smarty->assign("vrstaMaterijala", $rezultat);
}

function prikaziDetaljeKorisnika($putanja) {
    if (isset($_GET["prikazDetaljaKorisnika"])) {
        $id = $_GET["prikazDetaljaKorisnika"];
        Sesija::kreirajIdKorisnika($id);
        echo "<script>window.location.href='$putanja/korisnik/prikazDetaljaKorisnika.php';</script>";
    }
}

function prijaviVlakNaIzlozbu($putanja) {
    if (isset($_GET["prijaviVlak"])) {
        $idIzlozbe = $_GET["prijaviVlak"];

        $sql = "INSERT INTO prijavavlaka (vlak_id, izlozba_id) VALUES ({$_GET["odabirVlakaZaPrijavu"]}, {$idIzlozbe})";
        zapisiPodatke($sql);
        echo "<script>window.location.href='$putanja/korisnik/izlozbe.php';</script>";
    }
}

function prikazIzlozbi($smarty) {
    global $direktorij;
    $virtualnoVrijeme = strtotime(ispis_konfiguracije($direktorij));
    $datum = date("Y-m-d H:i:s", $virtualnoVrijeme);

    $sql = "SELECT i.id, t.naziv, t.opis, i.datum_pocetka, i.broj_korisnika, "
            . "     CASE WHEN SUM(pv.status_id=1) IS NULL THEN 0 ELSE SUM(pv.status_id=1) END AS 'trenutni', "
            . "     getStatusIzlozbe(i.id, '{$datum}') AS 'status', g.id AS 'IDGlasovanja'"
            . " FROM izlozba i "
            . "     INNER JOIN tematika t ON i.tematika_id = t.id "
            . "     INNER JOIN glasovanje g ON g.izlozba_id = i.id"
            . "     LEFT JOIN prijavavlaka pv ON pv.izlozba_id = i.id"
            . " GROUP BY i.id, t.naziv, t.opis, i.datum_pocetka, i.broj_korisnika, g.id"
            . " ORDER BY i.datum_pocetka";

    $rezultat = dohvatiPodatke($sql);

    $smarty->assign("virtualniDatum", $datum);
    $smarty->assign("izlozba", $rezultat);
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

function provjeraKorisnika($putanja) {
    if (!isset($_SESSION["uloga"])) {
        header("Location: $putanja/obrasci/prijava.php");
        exit();
    } elseif (isset($_SESSION["uloga"]) and $_SESSION["uloga"] > 3) {
        header("Location: $putanja/index.php");
        exit();
    }
}

?>