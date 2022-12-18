<?php

$putanja = dirname($_SERVER['REQUEST_URI']);
$direktorij = getcwd();

$naslov = "Uređivanje izložba";
$opis = "";
include './zaglavlje.php';
$naziv = "";
$opis = "";


//provjera pristupa korisnika
provjeraKorisnika($putanja);
//dodavanje nove izlozbe od prijavljenog moderatora
dodajNovuIzlozbu($putanja);
//brisanje tematike od moderatora
izbrisiIzlozbuTematikeModeratora($putanja);
//uređivanje postojeceg zapisa o izložbi vlaka po moderatoru
urediZapisaIzlozbeTematikeModeratora($putanja);
//prikaz podataka za administratora
prikaziPodatke();

$izborSlike = array("Moderni" => "Moderni vlak", "Brzi" => "Najbrži vlak", "Motorni" => "Motorni vlak",
    "Najmanji" => "Najmanji vlak", "Ogromni" => "Najveći vlak", "Parni" => "Lokomotive");

$smarty->assign("izborSlike", $izborSlike);

$smarty->assign("virtualniDatum", $virtualniDatumVrijeme);
$smarty->assign("ukupanBrojTematike", $ukupanBrojTematike);
$smarty->assign("ukupnoBrojIzlozbi", $ukupanBrojIzlozbi);
$smarty->assign("izlozbeModeratora", $izlozbeModeratora);
$smarty->assign("izlozbe", $redovi);
$smarty->assign("naziv", $naziv);
$smarty->assign("opis", $opis);

$smarty->display('izlozbaVlakova.tpl');
$smarty->display('podnozje.tpl');

function prikaziPodatke() {
    if ($_SESSION["uloga"] == 1) {
        prikazSvihTematikaAdministrator();
        prikazSvihIzlozbiAdministrator();
    } else {
        //prikazuje sve tematike izlozbe vlakove, koje mu je administrator dodijelio
        prikazTematikeIzlozbeModeratora();
        //pregled izlozbe moderatora
        pregledIzlozbeModeratoru();
    }
}

function prikazSvihIzlozbiAdministrator() {
    global $izlozbeModeratora;
    global $ukupanBrojIzlozbi;
    global $direktorij;
    $virtualnoVrijeme = ispis_konfiguracije($direktorij);
    $datum = date("Y-m-d H:i:s", strtotime($virtualnoVrijeme));

    $sql = "SELECT izlozba.id, izlozba.datum_pocetka, izlozba.broj_korisnika, t_id.naziv, t_id.opis, izlozba.moderator_id AS 'IDModeratora',"
            . " m_id.korisnicko_ime, getStatusIzlozbe(izlozba.id, '{$datum}') AS 'status'"
            . " FROM izlozba"
            . " INNER JOIN tematika t_id ON t_id.id = izlozba.tematika_id"
            . " INNER JOIN korisnik m_id ON izlozba.moderator_id = m_id.id ORDER BY t_id.naziv";
    $rezultat = dohvatiPodatke($sql);
    while ($red = mysqli_fetch_array($rezultat)) {
        $redovi[] = $red;
    }

    $izlozbeModeratora = $redovi;
    $ukupanBrojIzlozbi = count($redovi);
}

function prikazSvihTematikaAdministrator() {
    global $redovi;
    global $ukupanBrojTematike;

    $sql = "SELECT tematika.naziv AS 'tematika', tematika.opis FROM tematika";
    $rezultat = dohvatiPodatke($sql);
    while ($red = mysqli_fetch_array($rezultat)) {
        $redovi[] = $red;
    }
    $ukupanBrojTematike = count($redovi);
}

function urediZapisaIzlozbeTematikeModeratora($putanja) {
    if (isset($_GET["urediZapisIzlozbe"])) {
        $id = $_GET["urediZapisIzlozbe"];
        Sesija::kreirajIdKorisnika($id);
        echo "<script>window.location.href='$putanja/dodavanjeNoveIzlozbe.php';</script>";
    }
}

function izbrisiIzlozbuTematikeModeratora($putanja) {
    if (isset($_GET["izbrisiIzlozbu"])) {
        $idBrisanjaTematike = $_GET["izbrisiIzlozbu"];

        $sql = "DELETE FROM izlozba WHERE id = {$idBrisanjaTematike}";
        zapisiDnevnik($sql);
        zapisiPodatke($sql);
        
        echo "<script>window.location.href='$putanja/izlozbaVlakova.php';</script>";
    }
}

function zapisiPodatke($sql) {
    $veza = new Baza;
    $veza->spojiDB();
    $rezultat = $veza->updateDB($sql);
    $veza->zatvoriDB();
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

function dodajNovuIzlozbu($putanja) {
    if (isset($_GET["dodajNovuIzlozbu"])) {
        unset($_SESSION['id']);
        echo "<script>window.location.href='$putanja/dodavanjeNoveIzlozbe.php';</script>";
    }
}

function pregledIzlozbeModeratoru() {
    global $izlozbeModeratora;
    global $ukupanBrojIzlozbi;
    global $direktorij;
    $virtualnoVrijeme = ispis_konfiguracije($direktorij);
    $datum = date("Y-m-d H:i:s", strtotime($virtualnoVrijeme));

    $sql = "SELECT izlozba.id, izlozba.datum_pocetka, izlozba.broj_korisnika, t_id.naziv, t_id.opis,izlozba.moderator_id AS 'IDModeratora',"
            . " getStatusIzlozbe(izlozba.id, '{$datum}') AS 'status', k_id.korisnicko_ime"
            . " FROM izlozba"
            . " INNER JOIN tematika t_id ON t_id.id = izlozba.tematika_id"
            . " INNER JOIN moderatori m_id ON m_id.tematika_id = t_id.id"
            . " INNER JOIN korisnik k_id ON k_id.id = m_id.moderator_id"
            . " WHERE k_id.korisnicko_ime = '{$_SESSION["korisnik"]}' AND m_id.vazi_od <= '{$datum}'";

    $rezultat = dohvatiPodatke($sql);
    $redovi = array();
    while ($red = mysqli_fetch_array($rezultat)) {
        $redovi[] = $red;
    }
    $izlozbeModeratora = $redovi;
    $ukupanBrojIzlozbi = count($redovi);
}

function prikazTematikeIzlozbeModeratora() {
    global $redovi;
    global $direktorij;
    global $ukupanBrojTematike;
    $virtualnoVrijeme = ispis_konfiguracije($direktorij);
    $datum = date("Y-m-d H:i:s", strtotime($virtualnoVrijeme));

    $sql = "SELECT moderatori.moderator_id AS 'IDModeratora' ,m_id.korisnicko_ime AS 'moderator', moderatori.tematika_id AS 'IDTematike',"
            . " t_id.naziv AS 'tematika', t_id.opis AS 'opis', moderatori.vazi_od, moderatori.vazi_do "
            . " FROM moderatori "
            . " INNER JOIN korisnik m_id ON moderatori.moderator_id = m_id.id "
            . " INNER JOIN tematika t_id ON moderatori.tematika_id = t_id.id "
            . " WHERE m_id.korisnicko_ime = '{$_SESSION["korisnik"]}' AND moderatori.vazi_od <= '{$datum}'";

    $rezultat = dohvatiPodatke($sql);
    while ($red = mysqli_fetch_array($rezultat)) {
        if (isset($red["vazi_do"]) and $red["vazi_do"] <= $datum)
            continue;
        else
            $redovi[] = $red;
    }
    $ukupanBrojTematike = count($redovi);
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
        echo "<script>window.location.href='$putanja/obrasci/prijava.php';</script>";
    } elseif (isset($_SESSION["uloga"]) && $_SESSION["uloga"] > 2) {
        echo "<script>window.location.href='$putanja/index.php';</script>";
    }
}

?>