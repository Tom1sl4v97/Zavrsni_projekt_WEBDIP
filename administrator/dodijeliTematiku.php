<?php

$putanja = dirname($_SERVER['REQUEST_URI'], 2);
$direktorij = dirname(getcwd());

$naslov = "Dodijeli tematiku vlakova";
$opis = "";
include '../zaglavlje.php';
$greska = "";
$naziv = "";
$opis = "";

//provjera pristupa korisnika
provjeraKorisnika($putanja);
//gumb odustani
odustani($putanja);
//dodavanje nove tematike vlakova
azurirajTematiku();
//prikaz podataka kod uređivanja tematike
prikaziPodatke();

$smarty->assign("naziv", $naziv);
$smarty->assign("opis", $opis);
$smarty->assign("greska", $greska);
$smarty->display('azuriranjeTematike.tpl');
$smarty->display('podnozje.tpl');

function zapisiDnevnik($upit) {
    global $virtualniDatumVrijeme;
    $id = dohvatiIDKorisnika();
    $datum = date("Y-m-d H:i:s", strtotime($virtualniDatumVrijeme));
    $sql = "INSERT INTO dnevnik (stranica, upit, datum_pristupa, tip_dnevnika_id, korisnik_id)"
            . " VALUES ('{$_SERVER['REQUEST_URI']}', " . '"' . $upit . '"' . ", '{$datum}', 2, {$id})";
    zapisiPodatke($sql);
}

function dohvatiIDKorisnika() {
    $sql = "SELECT id FROM korisnik WHERE korisnicko_ime = '{$_SESSION["korisnik"]}'";
    $rezultat = dohvatiPodatke($sql);
    return $rezultat[0]["id"];
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

function prikaziPodatke() {
    if (isset($_SESSION["id"])) {
        $idPromjeneZapisa = $_SESSION["id"];

        $sql = "SELECT naziv, opis FROM tematika WHERE id = {$idPromjeneZapisa}";
        $rezultat = dohvatiPodatke($sql);

        global $naziv;
        global $opis;
        $naziv = $rezultat[0]["naziv"];
        $opis = $rezultat[0]["opis"];
    }
}

function azurirajTematiku() {
    if (isset($_POST["dodajTematiku"])) {
        global $greska;
        $podaci = provjeriPodatke();
        if (empty($greska)) {
            unesiTematiku($podaci);
        }
    }
}

function unesiTematiku($podaci) {
    global $putanja;
    $veza = new Baza();
    $veza->spojiDB();

    global $direktorij;
    $virtualnoVrijeme = strtotime(ispis_konfiguracije($direktorij));
    $time = date("Y-m-d H:i:s", $virtualnoVrijeme);

    if (isset($_SESSION["id"])) {
        $idPromjeneZapisa = $_SESSION["id"];
        unset($_SESSION['id']);
    }

    $sql = "SELECT id FROM korisnik WHERE korisnicko_ime = '{$_SESSION["korisnik"]}'";
    $rezultat = $veza->selectDB($sql);
    while ($red = mysqli_fetch_array($rezultat)) {
        $redovi[] = $red;
    }

    if (isset($idPromjeneZapisa)) {
        $sql = "UPDATE tematika SET"
                . " naziv = '{$podaci["nazivTematike"]}',"
                . " opis = '{$podaci["opisTematike"]}',"
                . " azurirao_korisnik_id = '{$redovi[0]["id"]}',"
                . " datum_azuriranja = '{$time}'"
                . " WHERE id = '{$idPromjeneZapisa}';";
        zapisiDnevnik($sql);
    } else {
        $sql = "INSERT INTO tematika (naziv, opis, kreirao_korisnik_id, datum_kreiranja) VALUES "
                . "('{$podaci["nazivTematike"]}','{$podaci["opisTematike"]}','{$redovi[0]["id"]}','{$time}')";
        zapisiDnevnik($sql);
    }

    $veza->updateDB($sql);
    $veza->zatvoriDB();

    echo "<script>window.location.href='$putanja/administrator/tematikaVlakova.php';</script>";
}

function provjeriPodatke() {
    global $greska;
    $greske = array("nazivTematike" => "naziv nove tematike", "opisTematike" => "opis nove tematike");
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

function odustani($putanja) {
    if (isset($_POST["odustani"])) {
        unset($_SESSION['id']);
        echo "<script>window.location.href='$putanja/administrator/tematikaVlakova.php';</script>";
    }
}

function provjeraKorisnika($putanja) {
    if (!isset($_SESSION["uloga"])) {
        echo "<script>window.location.href='$putanja/obrasci/prijava.php';</script>";
    } elseif (isset($_SESSION["uloga"]) && $_SESSION["uloga"] > 1) {
        echo "<script>window.location.href='$putanja/index.php';</script>";
    }
}

?>