<?php

$putanja = dirname($_SERVER['REQUEST_URI']);
$direktorij = getcwd();

$naslov = "Pregled prijave vlakova";
$opis = '';
include './zaglavlje.php';

$ispis = '<h2>Informacije o autoru:</h2>';
$smarty->assign("ispis", $ispis);
$redModeratora = '';


//provjera pristupa korisnika
provjeraKorisnika($putanja);
//prikaz tablice
prikazTablicePodataka();
//prihvacivanje ili odbijanje prijave vlakova sa izlozbe
prihvatiOdbij();


$smarty->assign("brojPrijavaIzlozbe", $brojPrijavaIzlozbe);
$smarty->assign("prikazTablice", $prikazTablice);
$smarty->display('prikazPrijaveVlakova.tpl');
$smarty->display('podnozje.tpl');

function dohvatiIzlozbu($idIzlozbe) {
    $sql = "SELECT i.trenutni_broj_korisnika, i.id"
            . " FROM izlozba i"
            . " INNER JOIN prijavavlaka pv ON pv.izlozba_id = i.id"
            . " WHERE pv.id = {$idIzlozbe}";
    $rezultat = dohvatiPodatke($sql);
    $redovi = array();
    while ($red = mysqli_fetch_array($rezultat)) {
        $redovi[] = $red;
    }
    return $redovi;
}

function prihvatiOdbij() {
    global $putanja;
    if (isset($_GET["prihvati"])) {
        $id = $_GET["prihvati"];
        if (provjeraBrojKorisnika($id)) {
            $sql = "UPDATE prijavavlaka SET status_id = 1 WHERE id = '{$id}';";
            zapisiDnevnik($sql);
            zapisiPodatke($sql);
        } else
            echo '<script>alert("Ne možete više prihvaćivati prijave kod ove izložbe!")</script>';
        echo "<script>window.location.href='$putanja/prikazPrijaveVlakova.php';</script>";
    }
    if (isset($_GET["odbij"])) {
        $id = $_GET["odbij"];
        $sql = "UPDATE prijavavlaka SET status_id = 2 WHERE id = '{$id}';";
        zapisiDnevnik($sql);
        zapisiPodatke($sql);
        echo "<script>window.location.href='$putanja/prikazPrijaveVlakova.php';</script>";
    }
}

function dohvatiIdKorisnika() {
    $sql = "SELECT id FROM korisnik WHERE korisnicko_ime = '{$_SESSION["korisnik"]}'";
    $rezultat = dohvatiPodatke($sql);
    $redovi = array();
    while ($red = mysqli_fetch_array($rezultat)) {
        $redovi[] = $red;
    }
    return $redovi[0]["id"];
}

function zapisiDnevnik($upit) {
    global $virtualniDatumVrijeme;
    $id = dohvatiIDKorisnika();
    $datum = date("Y-m-d H:i:s", strtotime($virtualniDatumVrijeme));
    $sql = "INSERT INTO dnevnik (stranica, upit, datum_pristupa, tip_dnevnika_id, korisnik_id)"
            . " VALUES ('{$_SERVER['REQUEST_URI']}', " . '"' . $upit . '"' . ", '{$datum}', 2, {$id})";
    zapisiPodatke($sql);
}

function provjeraBrojKorisnika($id) {
    $sql = "SELECT SUM(pv.status_id=1) AS 'trenutni', i.broj_korisnika"
            . " FROM prijavavlaka pv"
            . " INNER JOIN izlozba i ON i.id = pv.izlozba_id"
            . " WHERE i.id IN ( SELECT izlozba_id FROM prijavavlaka WHERE prijavavlaka.id = {$id})";
    $rezultat = dohvatiPodatke($sql);
    $redovi = array();
    while ($red = mysqli_fetch_array($rezultat)) {
        $redovi[] = $red;
    }
    if ($redovi[0]["trenutni"] >= $redovi[0]["broj_korisnika"])
        return false;
    else
        return true;
}

function prikazTablicePodataka() {
    //Kod uloga administratora (moze vidjeti sve tematike)
    if ($_SESSION["uloga"] == 1) {
        $sql = "SELECT prijavavlaka.id, prijavavlaka.vlak_id AS 'idVlaka', v_id.naziv AS 'nazivVlaka', k_id.id AS 'idKorisnika', k_id.ime, k_id.prezime, k_id.korisnicko_ime AS 'korime',"
                . " t_id.id AS 'idTematike', t_id.naziv, prijavavlaka.status_id AS 'idStatusPrijave', s_id.status, i_id.datum_pocetka"
                . " FROM prijavavlaka"
                . " INNER JOIN vlak v_id ON prijavavlaka.vlak_id = v_id.id"
                . " INNER JOIN korisnik k_id ON k_id.id = v_id.vlasnik_id"
                . " INNER JOIN izlozba i_id ON prijavavlaka.izlozba_id = i_id.id"
                . " INNER JOIN tematika t_id ON i_id.tematika_id = t_id.id"
                . " INNER JOIN statusprijave s_id ON s_id.id = prijavavlaka.status_id";
        ispisTablicePodataka($sql);
    } else {
        //Kod uloga moderatora (moze vidjeti samo smoje tematike)
        global $direktorij;
        $virtualnoVrijeme = strtotime(ispis_konfiguracije($direktorij));
        $datum = date("Y-m-d H:i:s", $virtualnoVrijeme);

        $sql = "SELECT prijavavlaka.id, prijavavlaka.vlak_id AS 'idVlaka', v_id.naziv AS 'nazivVlaka', k_id.id AS 'idKorisnika', k_id.ime,"
                . " k_id.prezime, k_id.korisnicko_ime AS 'korime', t_id.id AS 'idTematike', t_id.naziv, kor_id.id AS 'idModeratora',"
                . " kor_id.korisnicko_ime, prijavavlaka.status_id AS 'idStatusPrijave', s_id.status, i_id.datum_pocetka, m_id.vazi_od, m_id.vazi_do"
                . " FROM prijavavlaka"
                . " INNER JOIN vlak v_id ON prijavavlaka.vlak_id = v_id.id"
                . " INNER JOIN korisnik k_id ON k_id.id = v_id.vlasnik_id"
                . " INNER JOIN izlozba i_id ON prijavavlaka.izlozba_id = i_id.id"
                . " INNER JOIN tematika t_id ON i_id.tematika_id = t_id.id"
                . " INNER JOIN moderatori m_id ON m_id.tematika_id = t_id.id"
                . " INNER JOIN korisnik kor_id ON m_id.moderator_id = kor_id.id"
                . " INNER JOIN statusprijave s_id ON s_id.id = prijavavlaka.status_id"
                . " WHERE kor_id.korisnicko_ime = '{$_SESSION["korisnik"]}' AND m_id.vazi_od <= '{$datum}'";
        ispisTablicePodataka($sql, $datum);
    }
}

function ispisTablicePodataka($sql, $datum = '') {
    global $prikazTablice;
    global $brojPrijavaIzlozbe;

    $rezultat = dohvatiPodatke($sql);
    $redovi = array();
    while ($red = mysqli_fetch_array($rezultat)) {
        if (isset($red["vazi_do"]) and $red["vazi_do"] <= $datum)
            continue;
        else
            $redovi[] = $red;
    }
    $prikazTablice = $redovi;
    $brojPrijavaIzlozbe = count($redovi);
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
    $veza->zatvoriDB();
    return $rezultat;
}

function provjeraKorisnika($putanja) {
    if (!isset($_SESSION["uloga"])) {
        header("Location: $putanja/obrasci/prijava.php");
        exit();
    } elseif (isset($_SESSION["uloga"]) && $_SESSION["uloga"] > 2) {
        header("Location: $putanja/index.php");
        exit();
    }
}

?>