<?php

$putanja = dirname($_SERVER['REQUEST_URI'], 2);
$direktorij = dirname(getcwd());

$naslov = "Postavke stranice";
$opis = "";
include '../zaglavlje.php';


//provjera pristupa korisnika
provjeraKorisnika($putanja);
//postavljanje virtualnog vremena
promjenaVirtualnogVremena($direktorij, $smarty);
//promjena trajanja sesije
promjenaTrajanjeSesije($direktorij, $smarty);
//promjena trajanja kolacica
promjenaTrajanjeKolacica($direktorij, $smarty);
//resetiraj uvjete koristenja korisnika
resetirajUvjeteKoristenja();
//prikazivanje blokiranih korisnka
prikaziBlokiraneKorisnike($smarty);
//de-blokiraj odabranog korisnika
prihvatiBlokiranogKorisnika();
//blokiranje korisnika
blokirajKorisnika();
//postavljanje sigurosne kopije
kreirajSigurosnuKopiju();
//pretrazivanje po datumima 
prikazDvenvnikaPoDatumu();
//vraćanje podataka iz sigurosne kopije
vratiPodatkeIzKopije();

$smarty->display('postavke.tpl');
$smarty->display('podnozje.tpl');

function provjeraMaterijalaKorisnika() {
    global $direktorij;

    $sql = "SELECT m.*, k.korisnicko_ime, k.email, vm.format, t.naziv AS 'nazivTematike', i.datum_pocetka"
            . " FROM materijal m"
            . " INNER JOIN prijavavlaka pv ON m.prijava_vlaka_id = pv.id"
            . " INNER JOIN vlak v ON v.id = pv.vlak_id"
            . " INNER JOIN korisnik k ON k.id = v.vlasnik_id"
            . " INNER JOIN vrstamaterijala vm ON vm.id = m.vrsta_materijala_id"
            . " INNER JOIN izlozba i ON i.id = pv.izlozba_id"
            . " INNER JOIN tematika t ON t.id = i.tematika_id";
    $rezultat = dohvatiPodatke($sql);

    for ($i = 0; $i < count($rezultat); $i++) {
        $url = "{$direktorij}/multimedija/{$rezultat[$i]["korisnicko_ime"]}/{$rezultat[$i]["format"]}/";
        $fizickeDatoteke = scandir($url);
        $materijali = explode("/", $rezultat[$i]["url"]);
        $nazivMaterijala = $materijali[count($materijali) - 1];
        $pronaden = "ne";
        foreach ($fizickeDatoteke as $k => $v) {
            if ($nazivMaterijala == $v) {
                $pronaden = "da";
            }
        }
        if ($pronaden == "ne") {
            $msg = "Postovani {$rezultat[$i]["korisnicko_ime"]}\nMolimo vas postavite ponovno materijal: {$nazivMaterijala}, na tematiku: {$rezultat[$i]["nazivTematike"]}, pocetkom: {$rezultat[$i]["datum_pocetka"]}.\n Dogodila se greska kod vasih materijala.";
            $msg = wordwrap($msg, 1000);
            mail("{$rezultat[$i]["email"]}", "Problem kod materijala", $msg);
            echo '<script>alert("Poslali smo poruke korisnicima kojima su se materijali izgubili!");</script>';
        }
    }
}

function vratiPodatkeIzKopije() {
    if (isset($_POST["postaviKopiju"])) {
        global $putanja;

        $sql = "DELETE FROM vlak";
        zapisiPodatke($sql);
        vratiPodatkeIzKopijeVlakovi();
        vratiPodatkeIzKopijePrijavaVlaka();
        vratiPodatkeIzKopijeMaterijala();
        vratiPodatkeIzKopijeOcjena();

        provjeraMaterijalaKorisnika();

        echo '<script>alert("Uspiješno ste vratili podatke iz sigurosne kopije!");</script>';
        echo "<script>window.location.href='$putanja/administrator/postavke.php';</script>";
    }
}

function vratiPodatkeIzKopijeOcjena() {
    global $direktorij;
    $url = "{$direktorij}/izvorne_datoteke/sigurnosnuKopijuOcjena.txt";
    $sql = "INSERT INTO ocjena VALUES ";
    $zapisSigurnosnuKopiju = fopen($url, "r") or die("Nemoguce je otvoriti novi zapis!");
    $sql .= fread($zapisSigurnosnuKopiju, filesize($url));
    zapisiPodatke($sql);
}

function vratiPodatkeIzKopijeMaterijala() {
    global $direktorij;
    $url = "{$direktorij}/izvorne_datoteke/sigurnosnuKopijuMaterijala.txt";
    $sql = "INSERT INTO materijal VALUES ";
    $zapisSigurnosnuKopiju = fopen($url, "r") or die("Nemoguce je otvoriti novi zapis!");
    $sql .= fread($zapisSigurnosnuKopiju, filesize($url));
    zapisiPodatke($sql);
}

function vratiPodatkeIzKopijePrijavaVlaka() {
    global $direktorij;
    $url = "{$direktorij}/izvorne_datoteke/sigurnosnuKopijuPrijavaVlaka.txt";
    $sql = "INSERT INTO prijavavlaka VALUES ";
    $zapisSigurnosnuKopiju = fopen($url, "r") or die("Nemoguce je otvoriti novi zapis!");
    $sql .= fread($zapisSigurnosnuKopiju, filesize($url));
    zapisiPodatke($sql);
}

function vratiPodatkeIzKopijeVlakovi() {
    global $direktorij;
    $url = "{$direktorij}/izvorne_datoteke/sigurnosnuKopijuVlakova.txt";
    $sql = "INSERT INTO vlak VALUES ";
    $zapisSigurnosnuKopiju = fopen($url, "r") or die("Nemoguce je otvoriti novi zapis!");
    $sql .= fread($zapisSigurnosnuKopiju, filesize($url));
    zapisiPodatke($sql);
}

function prikazDvenvnikaPoDatumu() {
    if (isset($_POST["pretraži"])) {
        $odDatum = $_POST["pocetniDatum"];
        $doDatum = $_POST["zavrsniDatum"];
        if (!empty($odDatum) AND!empty($doDatum)) {
            prikaziDnevnikKoristenjaOdDoDatuma($odDatum, $doDatum);
        } elseif (!empty($doDatum)) {
            prikaziDnevnikKoristenjaDoDatuma($doDatum);
        } elseif (!empty($odDatum)) {
            prikaziDnevnikKoristenjaOdDatuma($odDatum);
        } else {
            //prikazivanje podataka iz tablice za dnevnik koristenja
            prikaziDnevnikKoristenja();
        }
    } else {
        //prikazivanje podataka iz tablice za dnevnik koristenja
        prikaziDnevnikKoristenja();
    }
}

function prikaziDnevnikKoristenjaOdDoDatuma($odDatum, $doDatum) {
    global $smarty;
    $sql = "SELECT d.id, d.stranica, d.upit, d.datum_pristupa AS 'datum', td.opis, k.ime, k.prezime, k.korisnicko_ime, k.email"
            . " FROM dnevnik d"
            . " INNER JOIN korisnik k ON d.korisnik_id = k.id"
            . " INNER JOIN tipdnevnika td ON td.id = d.tip_dnevnika_id"
            . " WHERE d.datum_pristupa >= '{$odDatum}' AND d.datum_pristupa <= '{$doDatum} 23:59:59'"
            . " ORDER BY tip_dnevnika_id";
    $rezultat = dohvatiPodatke($sql);

    for ($i = 0; $i < count($rezultat); $i++) {
        $stranica = explode("/", $rezultat[$i]["stranica"]);
        $url[$i] = implode(" /", $stranica);
    }
    $smarty->assign("urlStranica", $url);
    $smarty->assign("dnevnikKoristenjaStranice", $rezultat);
}

function prikaziDnevnikKoristenjaOdDatuma($odDatum) {
    global $smarty;
    $sql = "SELECT d.id, d.stranica, d.upit, d.datum_pristupa AS 'datum', td.opis, k.ime, k.prezime, k.korisnicko_ime, k.email"
            . " FROM dnevnik d"
            . " INNER JOIN korisnik k ON d.korisnik_id = k.id"
            . " INNER JOIN tipdnevnika td ON td.id = d.tip_dnevnika_id"
            . " WHERE d.datum_pristupa >= '{$odDatum}'"
            . " ORDER BY tip_dnevnika_id";
    $rezultat = dohvatiPodatke($sql);

    for ($i = 0; $i < count($rezultat); $i++) {
        $stranica = explode("/", $rezultat[$i]["stranica"]);
        $url[$i] = implode(" /", $stranica);
    }
    $smarty->assign("urlStranica", $url);
    $smarty->assign("dnevnikKoristenjaStranice", $rezultat);
}

function prikaziDnevnikKoristenjaDoDatuma($doDatum) {
    global $smarty;
    $sql = "SELECT d.id, d.stranica, d.upit, d.datum_pristupa AS 'datum', td.opis, k.ime, k.prezime, k.korisnicko_ime, k.email"
            . " FROM dnevnik d"
            . " INNER JOIN korisnik k ON d.korisnik_id = k.id"
            . " INNER JOIN tipdnevnika td ON td.id = d.tip_dnevnika_id"
            . " WHERE d.datum_pristupa <= '{$doDatum} 23:59:59'"
            . " ORDER BY tip_dnevnika_id";
    $rezultat = dohvatiPodatke($sql);

    for ($i = 0; $i < count($rezultat); $i++) {
        $stranica = explode("/", $rezultat[$i]["stranica"]);
        $url[$i] = implode(" /", $stranica);
    }
    $smarty->assign("urlStranica", $url);
    $smarty->assign("dnevnikKoristenjaStranice", $rezultat);
}

function prikaziDnevnikKoristenja() {
    global $smarty;

    $sql = "SELECT d.id, d.stranica, d.upit, d.datum_pristupa AS 'datum', td.opis, k.ime, k.prezime, k.korisnicko_ime, k.email"
            . " FROM dnevnik d"
            . " INNER JOIN korisnik k ON d.korisnik_id = k.id"
            . " INNER JOIN tipdnevnika td ON td.id = d.tip_dnevnika_id"
            . " ORDER BY tip_dnevnika_id";
    $rezultat = dohvatiPodatke($sql);

    for ($i = 0; $i < count($rezultat); $i++) {
        $stranica = explode("/", $rezultat[$i]["stranica"]);
        $url[$i] = implode(" /", $stranica);
    }
    $smarty->assign("urlStranica", $url);
    $smarty->assign("dnevnikKoristenjaStranice", $rezultat);
}

function kreirajSigurosnuKopiju() {
    if (isset($_POST["napraviKopiju"])) {
        global $putanja;
        kreirajSigurosnuKopijuVlakova();
        kreirajSigurosnuKopijuPrijaveVlakova();
        kreirajSigurosnuKopijuMaterijala();
        kreirajSigurosnuKopijuOcjene();

        echo '<script>alert("Uspiješno ste pohranili sigurosnu kopiju!");</script>';
        echo "<script>window.location.href='$putanja/administrator/postavke.php';</script>";
    }
}

function kreirajSigurosnuKopijuMaterijala() {
    global $direktorij;
    $sql = "SELECT * FROM materijal ORDER BY id";
    $rezultat = dohvatiPodatke($sql);

    $zapisSigurnosnuKopijuMaterijala = fopen("{$direktorij}/izvorne_datoteke/sigurnosnuKopijuMaterijala.txt", "w") or die("Nemoguce je kreirati novi zapis!");

    for ($i = 0; $i < count($rezultat); $i++) {
        if ($i === count($rezultat) - 1) {
            $tekst = "({$rezultat[$i]["id"]},'{$rezultat[$i]["url"]}','{$rezultat[$i]["vrsta_materijala_id"]}','{$rezultat[$i]["prijava_vlaka_id"]}')\n";
        } else {
            $tekst = "({$rezultat[$i]["id"]},'{$rezultat[$i]["url"]}','{$rezultat[$i]["vrsta_materijala_id"]}','{$rezultat[$i]["prijava_vlaka_id"]}'),\n";
        }
        fwrite($zapisSigurnosnuKopijuMaterijala, $tekst);
    }
    fclose($zapisSigurnosnuKopijuMaterijala);
}

function kreirajSigurosnuKopijuOcjene() {
    global $direktorij;
    $sql = "SELECT * FROM ocjena ORDER BY id";
    $rezultat = dohvatiPodatke($sql);

    $zapisSigurnosnuKopijuOcjena = fopen("{$direktorij}/izvorne_datoteke/sigurnosnuKopijuOcjena.txt", "w") or die("Nemoguce je kreirati novi zapis!");

    for ($i = 0; $i < count($rezultat); $i++) {
        if ($i === count($rezultat) - 1) {
            $tekst = "({$rezultat[$i]["id"]},'{$rezultat[$i]["ocjena_korisnika"]}','{$rezultat[$i]["komentar"]}', '{$rezultat[$i]["prijava_vlaka_id"]}','{$rezultat[$i]["korisnik_id"]}') \n";
        } else {
            $tekst = "({$rezultat[$i]["id"]},'{$rezultat[$i]["ocjena_korisnika"]}','{$rezultat[$i]["komentar"]}', '{$rezultat[$i]["prijava_vlaka_id"]}','{$rezultat[$i]["korisnik_id"]}'), \n";
        }
        fwrite($zapisSigurnosnuKopijuOcjena, $tekst);
    }
    fclose($zapisSigurnosnuKopijuOcjena);
}

function kreirajSigurosnuKopijuPrijaveVlakova() {
    global $direktorij;
    $sql = "SELECT * FROM prijavavlaka ORDER BY id";
    $rezultat = dohvatiPodatke($sql);

    $zapisSigurnosnuKopijuPrijavaVlakova = fopen("{$direktorij}/izvorne_datoteke/sigurnosnuKopijuPrijavaVlaka.txt", "w") or die("Nemoguce je kreirati novi zapis!");

    for ($i = 0; $i < count($rezultat); $i++) {
        if ($rezultat[$i]["azurirao_moderator_id"] == "") {
            $idModeratora = "null";
            $datumModeratora = "null";
        } else {
            $idModeratora = $rezultat[$i]["azurirao_moderator_id"];
            $datumModeratora = "'" . $rezultat[$i]["datum_azuriranja"] . "'";
        }
        if ($i === count($rezultat) - 1) {
            $tekst = "({$rezultat[$i]["id"]},'{$rezultat[$i]["vlak_id"]}','{$rezultat[$i]["izlozba_id"]}', {$idModeratora}, {$datumModeratora},'{$rezultat[$i]["status_id"]}') \n";
        } else {
            $tekst = "({$rezultat[$i]["id"]},'{$rezultat[$i]["vlak_id"]}','{$rezultat[$i]["izlozba_id"]}', {$idModeratora}, {$datumModeratora},'{$rezultat[$i]["status_id"]}'), \n";
        }
        fwrite($zapisSigurnosnuKopijuPrijavaVlakova, $tekst);
    }
    fclose($zapisSigurnosnuKopijuPrijavaVlakova);
}

function kreirajSigurosnuKopijuVlakova() {
    global $direktorij;
    $sql = "SELECT * FROM vlak ORDER BY id";
    $rezultat = dohvatiPodatke($sql);

    $zapisSigurnosnuKopijuVlakova = fopen("{$direktorij}/izvorne_datoteke/sigurnosnuKopijuVlakova.txt", "w") or die("Nemoguce je kreirati novi zapis!");

    for ($i = 0; $i < count($rezultat); $i++) {
        if ($i === count($rezultat) - 1) {
            $tekst = "({$rezultat[$i]["id"]},'{$rezultat[$i]["naziv"]}','{$rezultat[$i]["max_brzina"]}','{$rezultat[$i]["broj_sjedala"]}','{$rezultat[$i]["opis"]}','{$rezultat[$i]["vrsta_pogona_id"]}','{$rezultat[$i]["vlasnik_id"]}')\n";
        } else {
            $tekst = "({$rezultat[$i]["id"]},'{$rezultat[$i]["naziv"]}','{$rezultat[$i]["max_brzina"]}','{$rezultat[$i]["broj_sjedala"]}','{$rezultat[$i]["opis"]}','{$rezultat[$i]["vrsta_pogona_id"]}','{$rezultat[$i]["vlasnik_id"]}'),\n";
        }
        fwrite($zapisSigurnosnuKopijuVlakova, $tekst);
    }
    fclose($zapisSigurnosnuKopijuVlakova);
}

function blokirajKorisnika() {
    global $putanja;
    if (isset($_GET["blokirajKorisnika"])) {
        $idKorisnika = $_GET["blokirajKorisnika"];
        $sql = "SELECT * FROM korisnik WHERE id = {$idKorisnika}";
        $rezultat = dohvatiPodatke($sql);

        $msg = "Postovani\nBlokirani Vam je racun.";
        $msg = wordwrap($msg, 120);
        mail("{$rezultat[0]["email"]}", "Blokacija racuna", $msg);

        $sql = "UPDATE korisnik SET broj_neuspijesnih_prijava = 3 WHERE id = {$idKorisnika}";
        zapisiPodatke($sql);
        echo "<script>window.location.href='$putanja/administrator/postavke.php';</script>";
    }
}

function prihvatiBlokiranogKorisnika() {
    global $putanja;
    if (isset($_GET["prihvatiKorisnika"])) {
        $idKorisnika = $_GET["prihvatiKorisnika"];
        $sql = "SELECT * FROM korisnik WHERE id = {$idKorisnika}";
        $rezultat = dohvatiPodatke($sql);

        $msg = "Postovani\nDeblokirani Vam je racun, mozete se ponovno ulogirati.";
        $msg = wordwrap($msg, 120);
        mail("{$rezultat[0]["email"]}", "De-blokacija racuna", $msg);

        $sql = "UPDATE korisnik SET broj_neuspijesnih_prijava = 0 WHERE id = {$idKorisnika}";
        zapisiPodatke($sql);
        echo "<script>window.location.href='$putanja/administrator/postavke.php';</script>";
    }
}

function prikaziBlokiraneKorisnike($smarty) {
    $sql = "SELECT * FROM korisnik";
    $rezultat = dohvatiPodatke($sql);

    if (!empty($rezultat[0])) {
        $smarty->assign("blokiraniKorisnici", $rezultat);
    }
}

function resetirajUvjeteKoristenja() {
    global $putanja;
    if (isset($_POST["resetirajUvjete"])) {
        $sql = "UPDATE korisnik SET status = 0 WHERE tip_korisnika_id != 1";
        zapisiPodatke($sql);
        echo "<script>window.location.href='$putanja/administrator/postavke.php';</script>";
    }
}

function promjenaTrajanjeKolacica($direktorij, $smarty) {
    global $putanja;

    $url = "{$direktorij}/izvorne_datoteke/trajanjeKolacica.txt";
    $fp = fopen($url, "r");
    $trajanjeKolacica = fread($fp, filesize($url));
    fclose($fp);
    $smarty->assign("trenutniZapisKolacica", $trajanjeKolacica);

    if (isset($_POST["izmjeniTrajanjeKolacica"])) {
        $brojSati = $_POST["novoTrajanjeKolacica"];
        if (!preg_match("/^[+]?([.]\d+|\d+([.]\d+)?)$/", $brojSati))
            $smarty->assign("greska", "Niste unijeli pozitivan broj");
        else {
            $fp = fopen("$direktorij/izvorne_datoteke/trajanjeKolacica.txt", "w");
            fwrite($fp, $brojSati);
            fclose($fp);
            echo "<script>window.location.href='$putanja/administrator/postavke.php';</script>";
        }
    }
}

function promjenaTrajanjeSesije($direktorij, $smarty) {
    global $putanja;

    $url = "{$direktorij}/izvorne_datoteke/krajSesije.txt";
    $fp = fopen($url, "r");
    $trajanjeSesije = fread($fp, filesize($url));
    fclose($fp);
    $smarty->assign("trenutniZapisSesije", $trajanjeSesije);

    if (isset($_POST["izmjenaTrajanjeSesije"])) {
        $brojSati = $_POST["izmjeniSesiju"];

        if (empty($brojSati))
            $smarty->assign("greskaSesije", "Niste unijeli vrijeme sesije.");
        else {
            $fp = fopen("$direktorij/izvorne_datoteke/krajSesije.txt", "w");
            fwrite($fp, $brojSati);
            fclose($fp);
            echo "<script>window.location.href='$putanja/administrator/postavke.php';</script>";
        }
    }
}

function promjenaVirtualnogVremena($direktorij, $smarty) {
    global $direktorij;
    global $putanja;

    $url = "$direktorij/json/konfiguracija.json";
    $fp = fopen($url, "r");
    $string = fread($fp, filesize($url));
    $json = json_decode($string, false); //objekt
    $sati = $json->WebDiP->vrijeme->pomak->brojSati;
    fclose($fp);

    $smarty->assign("trenutniZapisVirtualnogVremena", $sati);

    if (isset($_POST["izmjenaVirtualnogVremena"])) {
        $url = "http://barka.foi.hr/WebDiP/pomak_vremena/pomak.php?format=json";

        if (!($fp = fopen($url, 'r'))) {
            echo "Problem: nije moguće otvoriti url: " . $url;
            exit;
        }

        $string = fread($fp, 10000);
        $json = json_decode($string, false); //objekt
        $sati = $json->WebDiP->vrijeme->pomak->brojSati;
        $sati = (is_numeric($sati)) ? $sati : 0;
        fclose($fp);

        $fp = fopen("$direktorij/json/konfiguracija.json", "w");
        fwrite($fp, $string);
        fclose($fp);
        echo "<script>window.location.href='$putanja/administrator/postavke.php';</script>";
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

function provjeraKorisnika($putanja) {
    if (!isset($_SESSION["uloga"])) {
        echo "<script>window.location.href='$putanja/obrasci/prijava.php';</script>";
    } elseif (isset($_SESSION["uloga"]) && $_SESSION["uloga"] != 1) {
        echo "<script>window.location.href='$putanja/index.php';</script>";
    }
}

?>