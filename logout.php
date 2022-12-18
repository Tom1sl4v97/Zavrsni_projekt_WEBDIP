<?php

$putanja = dirname($_SERVER['REQUEST_URI']);
$direktorij = getcwd();

$naslov = "Kreiranje nove izložbe vlakova";
$opis = "";
include './zaglavlje.php';


$id = dohvatiIDKorisnika();
var_dump($id);
zapisiDnevnik($id);

function dohvatiIDKorisnika(){
    $sql = "SELECT id FROM korisnik WHERE korisnicko_ime = '{$_SESSION["korisnik"]}'";
    $rezultat = dohvatiPodatke($sql);
    return $rezultat[0]["id"];
}
function zapisiDnevnik($id) {
    global $virtualniDatumVrijeme;
    global $direktorij;
    $datum = date("Y-m-d H:i:s", strtotime($virtualniDatumVrijeme));
    $sql = "INSERT INTO dnevnik (stranica, datum_pristupa, tip_dnevnika_id, korisnik_id) VALUES ('{$_SERVER['REQUEST_URI']}', '{$datum}', 1, '{$id}')";
    zapisiPodatke($sql);
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

Sesija::obrisiSesiju();
header("Location: $putanja/obrasci/prijava.php");
?>