<?php

$putanja = dirname($_SERVER['REQUEST_URI']);
$direktorij = getcwd();

$naslov = "Početna stranica";
$opis = 'Ovo je početna stranica, koja prikazije tablicu o pticama, kreirana 18.3.2021.';
include './zaglavlje.php';

$virtualnoVrijeme = strtotime(ispis_konfiguracije($direktorij));
$datum = date("Y-m-d H:i:s", $virtualnoVrijeme);

//dohvaćivanje statusa korisnika o prihvaćenosti uvjeta koristenja 
if (isset($_SESSION["uloga"]) and $_SESSION["uloga"] < 4) {
    prihvacivanjeUvjetaKoristenja($smarty);
    zapisiUvjeteKoristenja();
}
//prijava novog korisnika kod aktivacijskog linka
provjeraNovogKorisnikaPrekoLinka($smarty);
//prikaz podataka početne stranice o izlozbi
prikaziPodatkeOIzlozbi($smarty);
//prikaz detalja pobjednika izlozbe
detaljiPobjednika();


$izborSlike = array("Moderni" => "Moderni vlak", "Brzi" => "Najbrži vlak", "Motorni" => "Motorni vlak",
    "Najmanji" => "Najmanji vlak", "Ogromni" => "Najveći vlak", "Parni" => "Lokomotive");

$smarty->assign("izborSlike", $izborSlike);
$smarty->display('index.tpl');
$smarty->display('podnozje.tpl');

function prikazRSSKanala() {
    global $smarty;
    $sql = "SELECT k.ime, k.prezime, k.korisnicko_ime, v.naziv AS 'nazivVlaka', t.naziv AS 'nazivTematike'"
            . " FROM prijavavlaka pv"
            . " INNER JOIN vlak v ON v.id = pv.vlak_id"
            . " INNER JOIN korisnik k ON k.id = v.vlasnik_id"
            . " INNER JOIN izlozba i ON i.id = pv.izlozba_id"
            . " INNER JOIN tematika t ON t.id = i.tematika_id"
            . " WHERE pv.status_id = 1 AND pv.izlozba_id = 41";
    $rezultat = dohvatiPodatke($sql);
}

function detaljiPobjednika() {
    if (isset($_GET["detaljiPobjednika"])) {
        global $putanja;
        $idPrijavePlaka = $_GET["detaljiPobjednika"];
        Sesija::kreirajIdKorisnika($idPrijavePlaka);
        echo "<script>window.location.href='$putanja/korisnik/prikazDetaljaKorisnika.php';</script>";
    }
}

function dohvatiPobjednika($idIzlozbe) {
    global $smarty;
    $sql = "SELECT k.ime, k.prezime, k.korisnicko_ime, k.email, v.naziv,"
            . " SUM(pv.status_id) AS 'ukupnoGlasova', SUM(o.ocjena_korisnika) AS 'ukupnoBodova', pv.id AS 'IDPrijaveVlaka'"
            . " FROM izlozba i"
            . " INNER JOIN prijavavlaka pv ON pv.izlozba_id = i.id"
            . " INNER JOIN ocjena o ON o.prijava_vlaka_id = pv.id"
            . " INNER JOIN vlak v ON v.id = pv.vlak_id"
            . " INNER JOIN korisnik k ON v.vlasnik_id = k.id"
            . " WHERE pv.izlozba_id = {$idIzlozbe}"
            . " GROUP BY k.ime, k.prezime, k.korisnicko_ime, k.email, pv.id, v.naziv"
            . " ORDER BY ukupnoGlasova DESC, ukupnoBodova DESC";
    $rezultat = dohvatiPodatke($sql);

    if (empty($rezultat))
        return null;
    else
        return $rezultat;
}

function prikaziPodatkeOIzlozbi($smarty) {
    global $datum;

    $sql = "SELECT i.id, t.naziv, t.opis, i.datum_pocetka, i.broj_korisnika, CASE WHEN SUM(pv.status_id=1) IS NULL THEN 0 ELSE SUM(pv.status_id=1) END AS 'trenutni',"
            . " getStatusIzlozbe(i.id, '{$datum}') AS 'status', g.id AS 'IDGlasovanja'"
            . " FROM izlozba i"
            . " INNER JOIN tematika t ON i.tematika_id = t.id"
            . " INNER JOIN glasovanje g ON g.izlozba_id = i.id"
            . " LEFT JOIN prijavavlaka pv ON pv.izlozba_id = i.id"
            . " WHERE getStatusIzlozbe(i.id, '2021-06-20') = 'Zatvoreno glasovanje'"
            . " GROUP BY i.id, t.naziv, t.opis, i.datum_pocetka, i.broj_korisnika, g.id"
            . " ORDER BY i.datum_pocetka";

    $rezultat = dohvatiPodatke($sql);
    $smarty->assign("izlozba", $rezultat);

    for ($i = 0; $i < count($rezultat); $i++) {
        $odgovor = dohvatiPobjednika($rezultat[$i]["id"]);
        if ($odgovor != null) {
            $podaci[$i] = $odgovor;
        } else {
            $podaci[$i] = null;
        }
    }
    $smarty->assign("podaciGlasanja", $podaci);
}

function provjeraNovogKorisnikaPrekoLinka($smarty) {
    global $virtualniDatumVrijeme;
    global $putanja;
    if (isset($_GET["noviKorisnik"])) {
        $korisnik = $_GET["noviKorisnik"];

        $sql = "SELECT * FROM korisnik WHERE korisnicko_ime = '{$korisnik}'";
        $rezultat = dohvatiPodatke($sql);

        $trajanjeAktivacijskogLinka = '14:00:00';

        $d0 = strtotime(date('Y-m-d 00:00:00'));
        $d1 = strtotime(date('Y-m-d ') . $trajanjeAktivacijskogLinka);

        $sumTime = strtotime($rezultat[0]["uvjeti_koristenja"]) + ($d1 - $d0);
        $vrijemeSaAktivacijskimLinkom = date("d.m.Y H:i:s", $sumTime);

        if ($vrijemeSaAktivacijskimLinkom <= $virtualniDatumVrijeme) {
            $smarty->assign("istekloVrijeme", "<h2>Žao nam je aktivacijski link Vam je istekao!</h2><br><br><h3>Molimo Vas da si napravite novi korisnički račun i otvrdite aktivacijski link u roku od 14 sati.</h3>");
            $sql = "DELETE FROM korisnik WHERE korisnicko_ime = '{$korisnik}'";
            //zapisiPodatke($sql);
        } else {
            aktivirajSvojRacun($korisnik);
            Sesija::kreirajKorisnika($korisnik, $virtualniDatumVrijeme, 3, "disabled", "disabled");
            echo "<script>window.location.href='$putanja/index.php';</script>";
        }
    }
}

function aktivirajSvojRacun($korisnik) {
    global $datum;
    $sql = "UPDATE korisnik SET datum_kreiranja = '{$datum}' WHERE korisnicko_ime = '{$korisnik}'";
    zapisiPodatke($sql);
}

function zapisiUvjeteKoristenja() {
    if (isset($_GET["prihvatiUvjete"])) {
        global $virtualniDatumVrijeme;
        $datume = date("Y-m-d H:i:s", strtotime($virtualniDatumVrijeme));
        $sql = "UPDATE korisnik SET status = 1, uvjeti_koristenja = '{$datume}' WHERE korisnicko_ime = '{$_SESSION["korisnik"]}'";
        zapisiPodatke($sql);
    }
}

function prihvacivanjeUvjetaKoristenja($smarty) {
    global $virtualniDatumVrijeme;
    global $direktorij;
    if (isset($_SESSION["korisnik"]) and $_SESSION["uloga"] < 4) {
        $sql = "SELECT status FROM korisnik WHERE korisnicko_ime = '{$_SESSION["korisnik"]}'";
        $rezultat = dohvatiPodatke($sql);

        $url = "$direktorij/izvorne_datoteke/trajanjeKolacica.txt";
        $fp = fopen($url, "r");
        $trajanjeKolacica = fread($fp, filesize($url));
        fclose($fp);

        $podaci[0] = $_SESSION["korisnik"];
        $podaci[1] = $virtualniDatumVrijeme;
        $podaci[2] = $rezultat[0]["status"];
        $podaci[3] = $trajanjeKolacica;

        $smarty->assign("podaci", $podaci);
    }
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
