<?php

$putanja = dirname($_SERVER['REQUEST_URI'], 2);
$direktorij = dirname(getcwd());

require "$direktorij/baza.class.php";
require "$direktorij/sesija.class.php";
require "$direktorij/vanjske_biblioteke/smarty-3.1.39/libs/Smarty.class.php";

Sesija::kreirajSesiju();
$smarty = new Smarty();

$userfile = $_FILES['upload']['tmp_name'];
$userfile_name = $_FILES['upload']['name'];
$userfile_size = $_FILES['upload']['size'];
$userfile_type = $_FILES['upload']['type'];
$userfile_error = $_FILES['upload']['error'];


$vrstaMaterijala = $_POST["odabirMaterijala"];
$idPrijaveVlaka = $_POST["posaljiMaterijale"];


switch ($vrstaMaterijala) {
    case 1:
        $vrsta = "slika";
        break;
    case 2:
        $vrsta = "audio";
        break;
    case 3:
        $vrsta = "video";
        break;
    case 4:
        $vrsta = "gif";
        break;
}


$url = "{$direktorij}/multimedija/{$_SESSION["korisnik"]}/{$vrsta}";
if (!file_exists($url)) {
    $oldmask = umask(0);
    mkdir($url, 0777, true);
    umask($oldmask);
}
$url = "{$direktorij}/multimedija/{$_SESSION["korisnik"]}/{$vrsta}/";



$files = array_filter($_FILES['upload']['name']);
for ($i = 0; $i < count($_FILES['upload']['name']); $i++) {
    $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
    if ($tmpFilePath != "") {
        $newFilePath = $url . $_FILES['upload']['name'][$i];
        if (move_uploaded_file($tmpFilePath, $newFilePath)) {
            zapisiMaterijale("{$putanja}/multimedija/{$_SESSION["korisnik"]}/{$vrsta}/" . $_FILES['upload']['name'][$i]);
        }
    }
}

function zapisiDnevnik($upit) {
    ispis_konfiguracije();
    $id = dohvatiIDKorisnika();
    $datum = date("Y-m-d H:i:s", strtotime($virtualniDatumVrijeme));
    $sql = "INSERT INTO dnevnik (stranica, upit, datum_pristupa, tip_dnevnika_id, korisnik_id)"
            . " VALUES ('{$_SERVER['REQUEST_URI']}', " . '"' . $upit . '"' . ", '{$datum}', 2, {$id})";
    zapisiPodatke($sql);
}

function ispis_konfiguracije() {
    global $direktorij;
    $url = "$direktorij/json/konfiguracija.json";
    $fp = fopen($url, "r");
    $string = fread($fp, filesize($url));
    $json = json_decode($string, false); //objekt
    $sati = $json->WebDiP->vrijeme->pomak->brojSati;
    fclose($fp);


    $vrijeme_servera = time();
    $virtualno_vrijeme = $vrijeme_servera + ($sati * 60 * 60);
    $virtualno_vrijeme = date('d.m.Y. H:i:s', $virtualno_vrijeme);

    return $virtualno_vrijeme;
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

function dohvatiIDKorisnika() {
    $sql = "SELECT id FROM korisnik WHERE korisnicko_ime = '{$_SESSION["korisnik"]}'";
    $rezultat = dohvatiPodatke($sql);
    return $rezultat[0]["id"];
}

function zapisiMaterijale($url) {
    global $idPrijaveVlaka;
    global $vrstaMaterijala;

    $sql = "INSERT INTO materijal (url, vrsta_materijala_id, prijava_vlaka_id) VALUES"
            . " ('{$url}', {$vrstaMaterijala}, {$idPrijaveVlaka})";
    zapisiDnevnik($sql);
    zapisiPodatke($sql);
}

function zapisiPodatke($sql) {
    $veza = new Baza;
    $veza->spojiDB();
    $veza->updateDB($sql);
    $veza->zatvoriDB();
}

echo "<script>window.location.href='$putanja/korisnik/izlozbe.php';</script>";
?>