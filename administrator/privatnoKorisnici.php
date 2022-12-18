<?php

$putanja = dirname($_SERVER['REQUEST_URI'], 2);
$direktorij = dirname(getcwd());

$naslov = "Privatno / korisnici";
$opis = "";
include '../zaglavlje.php';


dohvatiSveKorisnike();

$smarty->display('privatnoKorisnici.tpl');
$smarty->display('podnozje.tpl');


function dohvatiSveKorisnike(){
    global $smarty;
    $sql = "SELECT k.*, tk.naziv FROM korisnik k"
            . " INNER JOIN tipkorisnika tk ON tk.id = k.tip_korisnika_id";
    $smarty->assign("prikazSvihKorisnika", dohvatiPodatke($sql));
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