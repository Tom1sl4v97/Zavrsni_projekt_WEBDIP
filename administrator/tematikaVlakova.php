<?php

$putanja = dirname($_SERVER['REQUEST_URI'], 2);
$direktorij = dirname(getcwd());

$naslov = "Tematika vlakova";
$opis = '';
include '../zaglavlje.php';

$ispis = '<h2>Informacije o autoru:</h2>';
$smarty->assign("ispis", $ispis);
$redModeratora = '';


//provjera pristupa korisnika
provjeraKorisnika($putanja);
//ucitavanje tablice sa baze podataka
prikazTematike($smarty);
//uredi odabrani zapis tematike vlakova
urediTematiku($putanja);
//obrisi odabrani zapis tematike vlakova
obrisiTematiku();
//dodavanje nove tematike
dodajTematiku($putanja);
//prikaz moderatora tematike vloakova
prikazTematikeModeratora($smarty);
//uredi moderatora za zadanu tematiku
urediModeratora($putanja);
//obrisi moderatora za zadanu tematiku
obrisiModeratora();
//dodavanje novog moderatora tematici vlakova
dodajModeratora($putanja);

$smarty->display('tematikaVlakova.tpl');
$smarty->display('podnozje.tpl');

// **** Tematika ***

function zapisiDnevnik($upit) {
    global $virtualniDatumVrijeme;
    $id = dohvatiIDKorisnika();
    $datum = date("Y-m-d H:i:s", strtotime($virtualniDatumVrijeme));
    $sql = "INSERT INTO dnevnik (stranica, upit, datum_pristupa, tip_dnevnika_id, korisnik_id)"
            . " VALUES ('{$_SERVER['REQUEST_URI']}', " . '"' . $upit . '"' . ", '{$datum}', 2, {$id})";
    zapisiUBazuPodatke($sql);
}

function dohvatiIDKorisnika() {
    $sql = "SELECT id FROM korisnik WHERE korisnicko_ime = '{$_SESSION["korisnik"]}'";
    $rezultat = dohvatiPodatke($sql);
    return $rezultat[0]["id"];
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

function urediTematiku($putanja) {
    if (isset($_GET["urediTematiku"])) {
        $idPromjeneZapisa = $_GET["urediTematiku"];
        Sesija::kreirajIdKorisnika($idPromjeneZapisa);
        echo "<script>window.location.href='$putanja/administrator/dodijeliTematiku.php';</script>";
    }
}

function obrisiTematiku() {
    if (isset($_GET['obrisiTematiku'])) {
        $idZapisaObrasca = $_GET['obrisiTematiku'];
        $sql = "DELETE FROM tematika WHERE id = {$idZapisaObrasca}";
        
        zapisiDnevnik($sql);
        zapisiUBazuPodatke($sql);
    }
}

function dodajTematiku($putanja) {
    if (isset($_GET["dodajTematiku"])) {
        unset($_SESSION['id']);
        echo "<script>window.location.href='$putanja/administrator/dodijeliTematiku.php';</script>";
    }
}


function prikazTematikeModeratora($smarty) {
    $veza = new Baza;
    $veza->spojiDB();

    $sql = "SELECT moderatori.id,a_id.korisnicko_ime AS 'administrator', m_id.korisnicko_ime AS 'moderator', "
            . "t_id.naziv AS 'tematika', moderatori.vazi_od, moderatori.vazi_do FROM moderatori "
            . "INNER JOIN korisnik a_id ON moderatori.administrator_id = a_id.id "
            . "INNER JOIN korisnik m_id ON moderatori.moderator_id = m_id.id "
            . "INNER JOIN tematika t_id ON moderatori.tematika_id = t_id.id "
            . "ORDER BY m_id.korisnicko_ime, t_id.naziv";
    $rezultat = $veza->selectDB($sql);
    while ($red = mysqli_fetch_array($rezultat)) {
        $redovi[] = $red;
    }
    if (!$rezultat) {
        var_dump("Problem kod upita na bazu podataka!");
        exit;
    } else {
        $smarty->assign("redModeratora", $redovi);
        $smarty->assign("brojModeratoraTematike", count($redovi));
    }
    $veza->zatvoriDB();
}


function urediModeratora($putanja){
    if (isset($_GET["urediModeratora"])) {
        $idPromjeneZapisa = $_GET["urediModeratora"];
        Sesija::kreirajIdKorisnika($idPromjeneZapisa);
        echo "<script>window.location.href='$putanja/administrator/dodjeliModeratoraTematici.php';</script>";
    }
}

function obrisiModeratora(){
    if (isset($_GET['obrisiModeratora'])) {
        $idZapisaObrasca = $_GET['obrisiModeratora'];
        $sql = "DELETE FROM moderatori WHERE id = {$idZapisaObrasca}";
        
        zapisiDnevnik($sql);
        zapisiUBazuPodatke($sql);
    }
}

function dodajModeratora($putanja) {
    if (isset($_GET["dodajModeratora"])) {
        unset($_SESSION['id']);
        echo "<script>window.location.href='$putanja/administrator/dodjeliModeratoraTematici.php';</script>";
    }
}

function zapisiUBazuPodatke($sql) {
    global $putanja;
    $veza = new Baza();
    $veza->spojiDB();
    $veza->updateDB($sql);
    $veza->zatvoriDB();
    echo "<script>window.location.href='$putanja/administrator/tematikaVlakova.php';</script>";
}

function prikazTematike($smarty) {
    $veza = new Baza;
    $veza->spojiDB();

    $sql = "SELECT id, naziv, opis FROM tematika ORDER BY naziv";
    $rezultat = $veza->selectDB($sql);
    while ($red = mysqli_fetch_array($rezultat)) {
        $redovi[] = $red;
    }
    if (!$rezultat) {
        var_dump("Problem kod upita na bazu podataka!");
        exit;
    } else {
        $smarty->assign("red", $redovi);
        $smarty->assign("brojTematike", count($redovi));
    }
    $veza->zatvoriDB();
}

function provjeraKorisnika($putanja) {
    if (!isset($_SESSION["uloga"])) {
        header("Location: $putanja/obrasci/prijava.php");
        exit();
    } elseif (isset($_SESSION["uloga"]) && $_SESSION["uloga"] > 1) {
        header("Location: $putanja/index.php");
        exit();
    }
}

?>