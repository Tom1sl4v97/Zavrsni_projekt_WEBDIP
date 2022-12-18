<?php

require "$direktorij/baza.class.php";
require "$direktorij/sesija.class.php";
require "$direktorij/vanjske_biblioteke/smarty-3.1.39/libs/Smarty.class.php";

Sesija::kreirajSesiju();
$smarty = new Smarty();

//virtualno vrijeme koje se koristi za datume i vrijeme
$virtualniDatumVrijeme = ispis_konfiguracije($direktorij);

if (isset($_SESSION["uloga"]) and $_SESSION["uloga"] < 4) {
    provjeraIstjecanjaSessije($direktorij, $virtualniDatumVrijeme, $putanja);
    //ucitavanje dizajna
    prikazPromjeneDizajna($smarty);
    //dark mode tema
    darkoModeTema($smarty);
    zapisiDnevnikStranice();
}

$smarty->assign("naslov", $naslov);
$smarty->assign("opis", $opis);
$smarty->assign("putanja", $putanja);

$konfiguracija = ispis_konfiguracije($direktorij);
$smarty->assign("konfiguracija", $konfiguracija);


$smarty->setTemplateDir("$direktorij/templates")
        ->setCompileDir("$direktorij/templates_c")
        ->setPluginsDir(SMARTY_PLUGINS_DIR)
        ->setCacheDir("$direktorij/cache")
        ->setConfigDir("$direktorij/configs");

$smarty->display('zaglavlje.tpl');


function zapisiDnevnikStranice() {
    $veza = new Baza;
    $veza->spojiDB();
    global $virtualniDatumVrijeme;
    $sql = "SELECT id FROM korisnik WHERE korisnicko_ime = '{$_SESSION["korisnik"]}'";
    $rezultat = $veza->selectDB($sql);
    $redovi = array();
    while ($red = mysqli_fetch_array($rezultat)) {
        $redovi[] = $red;
    }
    $datum = date("Y-m-d H:i:s", strtotime($virtualniDatumVrijeme));
    $sql = "INSERT INTO dnevnik (stranica, datum_pristupa, tip_dnevnika_id, korisnik_id)"
            . " VALUES ('{$_SERVER['REQUEST_URI']}', '{$datum}', 3, {$redovi[0]["id"]})";
    $veza->updateDB($sql);
    $veza->zatvoriDB();
}
function darkoModeTema($smarty) {
    $smarty->assign("darkmode", $_SESSION["darkmode"]);
    $dizajn = $_SESSION["dizajn"];
    $korisnik = $_SESSION["korisnik"];
    $uloga = $_SESSION["uloga"];
    $vrijeme = $_SESSION["vrijeme"];
    if (isset($_GET["promjenaDizajnaDarkMode"])) {
        if ($_SESSION["darkmode"] == "") {
            Sesija::kreirajKorisnika($korisnik, $vrijeme, $uloga, $dizajn, "disabled");
        } else
            Sesija::kreirajKorisnika($korisnik, $vrijeme, $uloga, $dizajn, "");
        $page = $_SERVER['PHP_SELF'];
        $sec = "0.01";
        header("Refresh: $sec; url=$page");
    }
}

function prikazPromjeneDizajna($smarty) {
    $smarty->assign("dizajn", $_SESSION["dizajn"]);
    $korisnik = $_SESSION["korisnik"];
    $uloga = $_SESSION["uloga"];
    $vrijeme = $_SESSION["vrijeme"];
    $darkoMode = $_SESSION["darkmode"];
    if (isset($_GET["promjenaDizajna"])) {
        if ($_SESSION["dizajn"] == "") {
            Sesija::kreirajKorisnika($korisnik, $vrijeme, $uloga, "disabled", $darkoMode);
        } else
            Sesija::kreirajKorisnika($korisnik, $vrijeme, $uloga, "", $darkoMode);
        $page = $_SERVER['PHP_SELF'];
        $sec = "0.01";
        header("Refresh: $sec; url=$page");
    }
}

function provjeraIstjecanjaSessije($direktorij, $virtualniDatumVrijeme, $putanja) {
    $url = "$direktorij/izvorne_datoteke/krajSesije.txt";
    $fp = fopen($url, "r");
    $trajanjeSesije = fread($fp, filesize($url));
    fclose($fp);

    $datum1 = explode(" ", $_SESSION["vrijeme"]);
    $datum2 = explode(" ", $virtualniDatumVrijeme);

    $pocetnoVrijeme = new DateTime($datum1[1]);
    $treutnoVrijeme = new DateTime($datum2[1]);
    $razlika = $pocetnoVrijeme->diff($treutnoVrijeme);
    $vrijemeOduzimanja = $razlika->format('%H:%I:%S');

    if ($trajanjeSesije < $vrijemeOduzimanja) {
        echo '<script>alert("Istekla Vam je Sessija!");</script>';
        echo "<script>window.location.href='$putanja/obrasci/prijava.php';</script>";
        Sesija::obrisiSesiju();
    } else {
        $_SESSION["vrijeme"] = $virtualniDatumVrijeme;
    }
}

function ispis_konfiguracije($direktorij) {
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
