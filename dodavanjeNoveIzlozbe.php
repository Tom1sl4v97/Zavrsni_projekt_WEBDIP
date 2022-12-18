<?php

$putanja = dirname($_SERVER['REQUEST_URI']);
$direktorij = getcwd();

$naslov = "Kreiranje nove izložbe vlakova";
$opis = "";
include './zaglavlje.php';
$greska = "";
$naziv = "";
$opis = "";
$popisTemeIzlozbe = "";
$urediTemu = "";
$urediDatum = array();
$urediMaxKorisnika = "";


//provjera pristupa korisnika
provjeraKorisnika($putanja);
//gumb odustani
odustani($putanja);
//prikaz podataka u  padajucim izbornicima
provjeraKorisnikaPrikazPodataka($smarty);
//dodavanje nove izložbe
dodajNovuTematiku();
//uređivanje zapisa izlozbe
urediZapisaIzlozbe($smarty);


$smarty->assign("greska", $greska);
$smarty->display('dodavanjeNoveIzlozbe.tpl');
$smarty->display('podnozje.tpl');

function provjeraKorisnikaPrikazPodataka($smarty) {
    if ($_SESSION["uloga"] == 1) {
        $sql = "SELECT id AS 'tematika_id', naziv AS 'tematika' FROM tematika";
        $rezultat = dohvatiPodatke($sql);
        $smarty->assign("popisTemeIzlozbe", $rezultat);
    } else
        dohvatiPodatkeTemeIzlozbe($smarty);
}

function urediZapisaIzlozbe($smarty) {
    if (isset($_SESSION["id"])) {
        $id = $_SESSION["id"];
        $sql = "SELECT id, datum_pocetka, broj_korisnika, tematika_id"
                . " FROM izlozba WHERE izlozba.id = {$id}";
        $rezultat = dohvatiPodatke($sql);

        $smarty->assign("urediTemu", $rezultat[0]["tematika_id"]);
        $smarty->assign("urediMaxKorisnika", $rezultat[0]["broj_korisnika"]);
        $urediDatum = explode(" ", $rezultat[0]["datum_pocetka"]);
        $smarty->assign("urediDatum", $urediDatum);

        $sql = "SELECT vazi_od, vazi_do FROM glasovanje WHERE izlozba_id={$rezultat[0]["id"]}";
        $rezultat2 = dohvatiPodatke($sql);
        if (!empty($rezultat2[0]["vazi_od"])) {
            $smarty->assign("pocetakGlasovanja", date("Y-m-d", strtotime($rezultat2[0]["vazi_od"])));
            $smarty->assign("zavrsetakGlasovanja", date("Y-m-d", strtotime($rezultat2[0]["vazi_do"])));
        }
    }
}

function citajBazuPodataka($sql) {
    global $putanja;
    $veza = new Baza();
    $veza->spojiDB();

    $rezultat = $veza->selectDB($sql);
    $veza->zatvoriDB();

    return $rezultat;
}

function dohvatiPodatkeTemeIzlozbe($smarty) {
    $datum = date("Y-m-d H:i:s");

    $sql = "SELECT moderatori.tematika_id, t_id.naziv AS 'tematika', moderatori.vazi_od, moderatori.vazi_do FROM moderatori"
            . " INNER JOIN korisnik m_id ON moderatori.moderator_id = m_id.id"
            . " INNER JOIN tematika t_id ON moderatori.tematika_id = t_id.id"
            . " WHERE m_id.korisnicko_ime = '{$_SESSION["korisnik"]}' AND moderatori.vazi_od <= '{$datum}'";

    $rezultat = citajBazuPodataka($sql);
    $redovi = array();
    while ($red = mysqli_fetch_array($rezultat)) {
        if (isset($red["vazi_do"]) and $red["vazi_do"] <= $datum)
            continue;
        else
            $redovi[] = $red;
    }

    if (!empty($redovi))
        $smarty->assign("popisTemeIzlozbe", $redovi);
}

function dodajNovuTematiku() {
    if (isset($_POST["dodajModeratoraTematike"])) {
        global $greska;
        $podaci = provjeriPodatke();
        if (empty($greska)) {
            zapisiNovuTematiku($podaci);
        }
    }
}

function zapisiNovuTematiku($podaci) {
    global $putanja;
    global $direktorij;
    $virtualnoVrijeme = strtotime(ispis_konfiguracije($direktorij));
    $datum = date("Y-m-d H:i:s", $virtualnoVrijeme);

    $sql = "SELECT id FROM korisnik WHERE korisnicko_ime = '{$_SESSION["korisnik"]}'";
    $rezultat = dohvatiPodatke($sql);
    $idModeratora = $rezultat[0]["id"];

    if (isset($_SESSION["id"])) {
        $idPromjeneZapisa = $_SESSION["id"];
        unset($_SESSION['id']);
    }
    if (isset($idPromjeneZapisa)) {
        $sql = "UPDATE izlozba SET"
                . " datum_pocetka = '{$podaci["datumPocetka"]}',"
                . " broj_korisnika = '{$podaci["maxBrojKorisnika"]}',"
                . " tematika_id = '{$podaci["odabirModeratoraTematike"]}',"
                . " datum_azuriranja = '{$datum}'"
                . " WHERE id = '{$idPromjeneZapisa}'";
        zapisiDnevnik($sql);
    } else {
        $sql = "INSERT INTO izlozba (datum_pocetka, broj_korisnika, tematika_id, moderator_id, datum_kreiranja) VALUES "
                . "('{$podaci["datumPocetka"]}','{$podaci["maxBrojKorisnika"]}','{$podaci["odabirModeratoraTematike"]}','{$idModeratora}','{$datum}')";
        zapisiDnevnik($sql);
    }
    zapisiPodatke($sql);
    if (isset($idPromjeneZapisa))
        zapisiUredenoGlasovanje($podaci);
    else
        zapisiNovoGlasovanje($podaci);
    echo "<script>window.location.href = '$putanja/izlozbaVlakova.php';</script>";
}

function zapisiNovoGlasovanje($podaci) {
    $idIzlozbe = dohvatiIdIzlozbe($podaci);
    $sql = "INSERT INTO glasovanje (vazi_od, vazi_do, izlozba_id) VALUES ('{$podaci["pocetakGlasovanja"]}', '{$podaci["zavrsetakGlasovanja"]}', {$idIzlozbe})";
    zapisiPodatke($sql);
    zapisiDnevnik($sql);
}

function zapisiUredenoGlasovanje($podaci) {
    $idIzlozbe = dohvatiIdIzlozbe($podaci);
    $sql = "UPDATE glasovanje SET vazi_od = '{$podaci["pocetakGlasovanja"]}', vazi_do = '{$podaci["zavrsetakGlasovanja"]}' WHERE izlozba_id = {$idIzlozbe}";
    zapisiPodatke($sql);
    zapisiDnevnik($sql);
}

function dohvatiIdIzlozbe($podaci) {
    $sql = "SELECT id FROM izlozba WHERE datum_pocetka = '{$podaci["datumPocetka"]}' AND broj_korisnika = '{$podaci["maxBrojKorisnika"]}'"
            . " AND tematika_id = '{$podaci["odabirModeratoraTematike"]}'";
    $id = dohvatiPodatke($sql);
    return $id[0]["id"];
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

function provjeriPodatke() {
    global $greska;
    $greske = array("odabirModeratoraTematike" => "temu izložbe", "datumPocetka" => "datum poćetka izložbe", "maxBrojKorisnika" => "maksimalan broj korisnika",
        "pocetakGlasovanja" => "pocetak glasovanja", "zavrsetakGlasovanja" => "zavrsetak glasovanja");
    foreach ($_POST as $k => $v) {
        $podaci[$k] = $v;
        if (empty($v)) {
            foreach ($greske as $key => $val) {
                if ($k === $key)
                    $greska .= "Niste popunili: " . $val . "<br>";
            }
        }
    }
    return $podaci;
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

function odustani($putanja) {
    if (isset($_POST["odustani"])) {
        unset($_SESSION['id']);
        echo "<script>window.location.href = '$putanja/izlozbaVlakova.php';</script>";
    }
}

function provjeraKorisnika($putanja) {
    if (!isset($_SESSION["uloga"])) {
        echo "<script>window.location.href = '$putanja/obrasci/prijava.php';</script>";
    } elseif (isset($_SESSION["uloga"]) && $_SESSION["uloga"] > 2) {
        echo "<script>window.location.href = '$putanja/index.php';</script>";
    }
}

?>