<?php

$direktorij = getcwd();
require "$direktorij/baza.class.php";
require "$direktorij/sesija.class.php";
Sesija::kreirajSesiju();

function dohvatiDetalje($prijavaVlakaId, $ocjena, $komentar, $korisnik) {
    $veza = new Baza();
    $veza->spojiDB();
    $ocjenaIdSql = "SELECT id FROM `ocjena` WHERE prijava_vlaka_id = {$prijavaVlakaId} AND korisnik_id IN (SELECT id FROM korisnik WHERE korisnicko_ime = '{$korisnik}')";
    $poruka = "";

    $ocjenaIdResult = $veza->selectDB($ocjenaIdSql);
    $row = $ocjenaIdResult->fetch_row();
    $ocjenaId = $row[0] ?? false;

    $idKorisnika = dohvatiIDKorisnika($korisnik);

    if ($ocjenaId === false) {
        $sql = "INSERT INTO ocjena (prijava_vlaka_id,komentar,ocjena_korisnika, korisnik_id) VALUES ({$prijavaVlakaId}, '{$komentar}',  {$ocjena},"
                . " (SELECT id FROM korisnik WHERE korisnicko_ime = '{$korisnik}' LIMIT 1))";
        $veza->updateDB($sql);
        $poruka = "Glasovanje je uspješno uneseno.";
        
        zapisiDnevnik($idKorisnika, $sql);
    } else {
        $sql = "UPDATE `ocjena` "
                . "SET  ocjena_korisnika={$ocjena}, "
                . "     komentar='{$komentar}' "
                . "WHERE prijava_vlaka_id = {$prijavaVlakaId} "
                . "     AND ocjena.id = {$ocjenaId}"
                . "     AND korisnik_id IN (SELECT id FROM korisnik WHERE korisnicko_ime = '$korisnik')";
        $veza->updateDB($sql);
        $poruka = "Glasovanje je uspješno ažurirano.";
        
        zapisiDnevnik($idKorisnika, $sql);
    }

    $veza->zatvoriDB();
    echo json_encode($poruka);
}

if (isset($_POST["prijavaVlakaId"]) && !empty(isset($_POST["prijavaVlakaId"]))) {
    $glasovanjeId = $_POST["prijavaVlakaId"];
    $ocjena = $_POST["ocjena"];
    $komentar = $_POST["komentar"];
    $korisnik = $_SESSION["korisnik"];
    dohvatiDetalje($glasovanjeId, $ocjena, $komentar, $korisnik);
}

function dohvatiIDKorisnika($korisnik) {
    $sql = "SELECT id FROM korisnik WHERE korisnicko_ime = '{$korisnik}'";
    $rezultat = dohvatiPodatke($sql);
    return $rezultat[0]["id"];
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