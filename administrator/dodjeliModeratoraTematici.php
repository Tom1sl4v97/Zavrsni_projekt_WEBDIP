<?php

$putanja = dirname($_SERVER['REQUEST_URI'], 2);
$direktorij = dirname(getcwd());

$naslov = "Dodijeli moderatora tematici vlakova";
$opis = "";
include '../zaglavlje.php';
$greska = "";
$tematikaVlakova = "";
$moderatorTematikeVlakova = "";
$datumOd = "";
$datumDo = "";

//provjera pristupa korisnika
provjeraKorisnika($putanja);
//gumb odustani
odustani($putanja);
//dodavanje nove tematike vlakova
dodajNovogModeratoraTematike($putanja);
//prikaz moderatora u padajucem izborniku
prikazModeratora($smarty);
//prikaz promjene zapisa moderatora tematike vlakova
prikazPromjeneZapisaModeratora();


$smarty->assign("datumDo", $datumDo);
$smarty->assign("datumOd", $datumOd);
$smarty->assign("moderatorTematikeVlakova", $moderatorTematikeVlakova);
$smarty->assign("tematikaVlakova", $tematikaVlakova);
$smarty->assign("greska", $greska);
$smarty->display('dodjeliModeratoraTematici.tpl');
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

function prikazPromjeneZapisaModeratora() {
    if (isset($_SESSION["id"])) {
        $idPromjeneZapisa = $_SESSION["id"];
        prikazPromjeneZapisaModeratoraTematikeSql($idPromjeneZapisa);
    }
}

function prikazPromjeneZapisaModeratoraTematikeSql($idPromjeneZapisa) {
    $sql = "SELECT * FROM moderatori WHERE id = {$idPromjeneZapisa}";
    $rezultat = dohvatiPodatke($sql);

    $vrijemeDo = $rezultat[0]["vazi_do"];
    $vrijemeOd = $rezultat[0]["vazi_od"];

    global $tematikaVlakova;
    global $moderatorTematikeVlakova;
    global $datumDo;
    global $datumOd;

    $moderatorTematikeVlakova = $rezultat[0]["moderator_id"];
    $tematikaVlakova = $rezultat[0]["tematika_id"];
    $datumOd = date("Y-m-d", strtotime($vrijemeOd));
    if (isset($rezultat[0]["vazi_do"]))
        $datumDo = date("Y-m-d", strtotime($vrijemeDo));
}

function prikazModeratora($smarty) {
    $sql = "SELECT id, ime, prezime, korisnicko_ime FROM korisnik WHERE tip_korisnika_id = 2";
    $rezultat = dohvatiPodatke($sql);
    $sql2 = "SELECT id, naziv FROM tematika ORDER BY naziv";
    $rezultat2 = dohvatiPodatke($sql2);

    $smarty->assign("redModeratora", $rezultat);
    $smarty->assign("redTematike", $rezultat2);
}

function dodajNovogModeratoraTematike($putanja) {
    if (isset($_POST["spremi"])) {
        global $greska;
        $podaci = provjeriPodatke();
        if (empty($greska)) {
            spremiModeratora($podaci, $putanja);
        }
    }
}

function spremiModeratora($podaci, $putanja) {
    if (isset($_SESSION["id"])) {
        $idPromjeneZapisa = $_SESSION["id"];
        unset($_SESSION['id']);
    }

    $sql = "SELECT id FROM korisnik WHERE korisnicko_ime = '{$_SESSION["korisnik"]}'";
    $rezultat = dohvatiPodatke($sql);

    $datumDo = empty($podaci["datumDo"]) ? "NULL" : $podaci["datumDo"];

    if (isset($idPromjeneZapisa)) {
        $sql = "UPDATE moderatori SET"
                . " administrator_id = '{$rezultat[0]["id"]}',"
                . " moderator_id = '{$podaci["odabirModeratoraTematike"]}',"
                . " tematika_id = '{$podaci["odabirTematike"]}',"
                . " vazi_od = '{$podaci["datumOd"]}',"
                . " vazi_do = '{$datumDo}'"
                . " WHERE id = '{$idPromjeneZapisa}';";
        zapisiDnevnik($sql);
    } else {
        $sql = "INSERT INTO moderatori (administrator_id, moderator_id, tematika_id, vazi_od, vazi_do) VALUES "
                . "({$rezultat[0]["id"]},{$podaci["odabirModeratoraTematike"]},{$podaci["odabirTematike"]},"
                . "'{$podaci["datumOd"]}', '{$datumDo}')";
        zapisiDnevnik($sql);
    }
    zapisiPodatke($sql);

    echo "<script>window.location.href='$putanja/administrator/tematikaVlakova.php';</script>";
}

function provjeriPodatke() {
    global $greska;
    $greske = array("odabirTematike" => "tematiku vlakova", "odabirModeratoraTematike" => "moderatora tematike vlakova", "datumOd" => "od kada vrijedi moderator tematike");
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