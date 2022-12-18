<?php

$putanja = dirname($_SERVER['REQUEST_URI'], 2);
$direktorij = dirname(getcwd());

$naslov = "Dodavanje novog vlaka";
$opis = "";
include '../zaglavlje.php';
$greska = "";


//popunjavanje podataka kod uređivanja odabranog vlaka i uredivanje slika
if (isset($_SESSION["id"]))
    popunjavanjePodatakaKodUredivanjaVlaka($smarty);

//provjera pristupa korisnika
provjeraKorisnika($putanja);
//zapisivanje materijala u bazu
dodavanjeMaterijala();
//prikaz pogona za vlak kod padajuceć izbornika
prikazPogona($smarty);
//gumb za odustajanja
odustani($putanja);

$smarty->assign("greska", $greska);
$smarty->display('dodavanjeNovogVlaka.tpl');
$smarty->display('podnozje.tpl');

function prikazPogona($smarty) {
    $sql = "SELECT * FROM vrstapogona";
    $smarty->assign("vrstaPogona", dohvatiPodatke($sql));
}

function popunjavanjePodatakaKodUredivanjaVlaka($smarty) {
    $sql = "SELECT v.id, v.naziv, v.max_brzina, v.broj_sjedala, v.opis, v.vrsta_pogona_id"
            . " FROM vlak v WHERE v.id = {$_SESSION["id"]}";
    $smarty->assign("popunjavanjeKodUredivanja", dohvatiPodatke($sql));
}

function dohvatiIdKorisnika() {
    $sql = "SELECT id FROM korisnik WHERE korisnicko_ime = '{$_SESSION["korisnik"]}'";
    $rezultat = dohvatiPodatke($sql);
    return $rezultat[0]["id"];
}

function dodajNoviVlak($podaciObrasca) {
    global $putanja;
    $id = dohvatiIdKorisnika();

    if (isset($_SESSION["id"])) {
        $sql = "UPDATE vlak SET"
                . " naziv = '{$podaciObrasca["nazivVlaka"]}',"
                . " max_brzina = {$podaciObrasca["maxBrzina"]},"
                . " broj_sjedala = {$podaciObrasca["brojSjedala"]},"
                . " opis = '{$podaciObrasca["opisVlaka"]}',"
                . " vrsta_pogona_id = {$podaciObrasca["vrstaPognona"]},"
                . " vlasnik_id = {$id}"
                . " WHERE id = {$_SESSION["id"]}";
        zapisiDnevnik($sql);
    } else {
        $sql = "INSERT INTO vlak (naziv, max_brzina, broj_sjedala, opis, vrsta_pogona_id, vlasnik_id) VALUES"
                . " ('{$podaciObrasca["nazivVlaka"]}', {$podaciObrasca["maxBrzina"]}, {$podaciObrasca["brojSjedala"]},"
                . " '{$podaciObrasca["opisVlaka"]}', {$podaciObrasca["vrstaPognona"]}, {$id})";
        zapisiDnevnik($sql);
    }
    zapisiPodatke($sql);
    echo "<script>window.location.href='$putanja/korisnik/prikazVlakova.php';</script>";
}

function dodavanjeMaterijala() {
    if (isset($_POST["dodavanjeNovogVlaka"])) {
        global $greska;
        $podaciObrasca = provjeriPodatke();
        if (empty($greska)) {
            if ($podaciObrasca["noviNazivPogona"] == 'nijePopunjeno' and $podaciObrasca["noviOpisPogona"] == 'nijePopunjeno') {
                dodajNoviVlak($podaciObrasca);
            } else {
                dodajNoviVlakSaNovimPogonom($podaciObrasca);
            }
        }
    }
}

function zapisiDnevnik($upit) {
    global $virtualniDatumVrijeme;
    $id = dohvatiIDKorisnika();
    $datum = date("Y-m-d H:i:s", strtotime($virtualniDatumVrijeme));
    $sql = "INSERT INTO dnevnik (stranica, upit, datum_pristupa, tip_dnevnika_id, korisnik_id)"
            . " VALUES ('{$_SERVER['REQUEST_URI']}', " . '"' . $upit . '"' . ", '{$datum}', 2, {$id})";
    zapisiPodatke($sql);
}

function dodajNoviVlakSaNovimPogonom($podaciObrasca) {
    global $putanja;
    $id = dohvatiIdKorisnika();
    $sql = "INSERT INTO vrstapogona (naziv_pogona, opis) VALUES ('{$podaciObrasca["noviNazivPogona"]}', '{$podaciObrasca["noviOpisPogona"]}')";
    zapisiDnevnik($sql);
    zapisiPodatke($sql);
    $sql = "SELECT id FROM vrstapogona WHERE naziv_pogona = '{$podaciObrasca["noviNazivPogona"]}' AND opis = '{$podaciObrasca["noviOpisPogona"]}'";
    $rezultat = dohvatiPodatke($sql);
    if (isset($_SESSION["id"])) {
        $sql = "UPDATE vlak SET"
                . " naziv = '{$podaciObrasca["nazivVlaka"]}',"
                . " max_brzina = {$podaciObrasca["maxBrzina"]},"
                . " broj_sjedala = {$podaciObrasca["brojSjedala"]},"
                . " opis = '{$podaciObrasca["opisVlaka"]}',"
                . " vrsta_pogona_id = {$rezultat[0]["id"]},"
                . " vlasnik_id = {$id}"
                . " WHERE id = {$_SESSION["id"]}";
        zapisiDnevnik($sql);
    } else {
        $sql = "INSERT INTO vlak (naziv, max_brzina, broj_sjedala, opis, vrsta_pogona_id, vlasnik_id) VALUES"
                . " ('{$podaciObrasca["nazivVlaka"]}', {$podaciObrasca["maxBrzina"]}, {$podaciObrasca["brojSjedala"]},"
                . " '{$podaciObrasca["opisVlaka"]}', {$rezultat[0]["id"]}, {$id})";
        zapisiDnevnik($sql);
    }
    zapisiPodatke($sql);
    echo "<script>window.location.href='$putanja/korisnik/prikazVlakova.php';</script>";
}

function provjeriPodatke() {
    global $greska;
    $greske = array("nazivVlaka" => "naziv vlaka", "maxBrzina" => "maksimalnu brzinu vlaka",
        "brojSjedala" => "broj sjedala vlaka", "opisVlaka" => "opis vlaka",
        "vrstaPognona" => "odabrali vrstu pogona", "noviNazivPogona" => "naziv novog pogona",
        "noviOpisPogona" => "opis novog pogona");

    foreach ($_POST as $k => $v) {
        $podaci[$k] = $v;
        if (empty($v)) {
            foreach ($greske as $key => $val) {
                if ($k === $key)
                    $greska .= "Niste popunili: " . $val . "<br>";
            }
        } elseif ($k == "maxBrzina" and!preg_match('/^[0-9]+$/', $v))
            $greska .= "Molimo Vas da unesete pozitivan prirodni broj za maksimalnu brzinu vlaka. <br>";
        elseif ($k == "brojSjedala" and!preg_match('/^[0-9]+$/', $v))
            $greska .= "Molimo Vas da unesete pozitivan prirodni broj za broj sjedala vlaka. <br>";
    }

    return $podaci;
}

function odustani($putanja) {
    if (isset($_POST["odustani"])) {
        unset($_SESSION['id']);
        echo "<script>window.location.href='$putanja/korisnik/prikazVlakova.php';</script>";
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
    }
}

?>