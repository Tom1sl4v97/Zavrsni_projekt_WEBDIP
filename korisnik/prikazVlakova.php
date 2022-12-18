<?php

$putanja = dirname($_SERVER['REQUEST_URI'], 2);
$direktorij = dirname(getcwd());

$naslov = "Vaši vlakovi";
$opis = "";
include '../zaglavlje.php';


//provjera ne registriranog korisnika
provjeraKorisnika($putanja);
//prikaz vlakova od korisnika
prikazVlakovaKorisnika($smarty);
//prikaz slike vlaka
prikazSlikeVlaka($smarty);
//dodavanje novog vlaka korisnika
dodavanjeNovogVlaka($putanja);
//brisanje odabranog vlaka od korisnika
obrisiVlakKorisnika();
//uređivanje odabranog vlaka
urediVlakKorisnika();


$smarty->display('prikazVlakova.tpl');
$smarty->display('podnozje.tpl');

function urediVlakKorisnika() {
    global $putanja;
    if (isset($_GET["urediVlakKorisnika"])) {
        $id = $_GET["urediVlakKorisnika"];
        Sesija::kreirajIdKorisnika($id);
        echo "<script>window.location.href='$putanja/korisnik/dodavanjeNovogVlaka.php';</script>";
    }
}

function dohvatiIdKorisnika() {
    $sql = "SELECT id FROM korisnik WHERE korisnicko_ime = '{$_SESSION["korisnik"]}'";
    $rezultat = dohvatiPodatke($sql);
    return $rezultat[0]["id"];
}

function zapisiDnevnik($upit) {
    global $virtualniDatumVrijeme;
    $id = dohvatiIDKorisnika();
    $datum = date("Y-m-d H:i:s", strtotime($virtualniDatumVrijeme));
    $sql = "INSERT INTO dnevnik (stranica, upit, datum_pristupa, tip_dnevnika_id, korisnik_id)"
            . " VALUES ('{$_SERVER['REQUEST_URI']}', " . '"' . $upit . '"' . ", '{$datum}', 2, {$id})";
    zapisiPodatke($sql);
}

function obrisiVlakKorisnika() {
    global $putanja;
    if (isset($_GET['obrisiVlakKorisnika'])) {
        $idZapisaObrasca = $_GET['obrisiVlakKorisnika'];
        $sql = "DELETE FROM vlak WHERE id = {$idZapisaObrasca}";
        
        zapisiDnevnik($sql);
        
        zapisiPodatke($sql);
        echo "<script>window.location.href='$putanja/korisnik/prikazVlakova.php';</script>";
    }
}

function dodavanjeNovogVlaka($putanja) {
    if (isset($_GET["dodavanjeNovogVlaka"])) {
        unset($_SESSION['id']);
        echo "<script>window.location.href='$putanja/korisnik/dodavanjeNovogVlaka.php';</script>";
    }
}

function prikazSlikeVlaka($smarty) {
    $sql = "SELECT m.id, m.url, v.id AS 'IDVlaka'"
            . " FROM vlak v"
            . " INNER JOIN korisnik k ON v.vlasnik_id = k.id"
            . " INNER JOIN prijavavlaka pv ON pv.vlak_id = v.id"
            . " INNER JOIN materijal m ON m.prijava_vlaka_id = pv.id"
            . " INNER JOIN vrstamaterijala vm ON m.vrsta_materijala_id = vm.id"
            . " WHERE k.korisnicko_ime = 'mmarulic' AND vm.format = 'Slike'"
            . " ORDER BY m.id DESC";
    
    $smarty->assign("slikaVlaka", dohvatiPodatke($sql));
}

function prikazVlakovaKorisnika($smarty) {
    $sql = "SELECT v.id, v.naziv, v.max_brzina, v.broj_sjedala, v.opis, vp.naziv_pogona"
            . " FROM vlak v INNER JOIN korisnik k ON v.vlasnik_id = k.id"
            . " INNER JOIN vrstapogona vp ON v.vrsta_pogona_id = vp.id"
            . " WHERE k.korisnicko_ime = '{$_SESSION["korisnik"]}'";
    $rezultat = dohvatiPodatke($sql);
    
    if (!empty($rezultat))
        $smarty->assign("listaVlakova", $rezultat);
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
    }
}

?>