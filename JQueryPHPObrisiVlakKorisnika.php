<?php

$direktorij = getcwd();
require "$direktorij/baza.class.php";

function dohvatiDetalje($id) {
    $sql = "DELETE FROM prijavavlaka WHERE id = {$id}";
    
    $idKoirnsika = dohvatiIDKorisnika($id);
    zapisiDnevnik($idKoirnsika, $sql);
    
    zapisiPodatke($sql);
}

if (isset($_POST["id"]) && !empty(isset($_POST["id"]))) {
    $id = $_POST["id"];
    dohvatiDetalje($id);
}

function dohvatiIDKorisnika($id){
    $sql = "SELECT v.vlasnik_id"
            . " FROM vlak v"
            . " INNER JOIN prijavavlaka pv ON pv.vlak_id = v.id"
            . " WHERE pv.id = {$id}";
    $rezultat = dohvatiPodatke($sql);
    return $rezultat[0]["vlasnik_id"];
}
function zapisiDnevnik($idKoirnsika, $upit) {
    $datum = date("Y-m-d H:i:s", strtotime(ispis_konfiguracije()));
    $sql = "INSERT INTO dnevnik (stranica, upit, datum_pristupa, tip_dnevnika_id, korisnik_id)"
            . " VALUES ('{$_SERVER['REQUEST_URI']}', " . '"' . $upit . '"' . ", '{$datum}', 2, {$idKoirnsika})";
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