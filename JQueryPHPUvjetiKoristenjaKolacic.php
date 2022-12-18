<?php

$direktorij = getcwd();
require "$direktorij/baza.class.php";
require "$direktorij/sesija.class.php";

function dohvatiDetalje($korisnik) {
    $veza = new Baza();
    $veza->spojiDB();
    
    $sql = "UPDATE korisnik SET status = 0 WHERE korisnicko_ime = '{$korisnik}'";
    
    $veza->updateDB($sql);
    $veza->zatvoriDB();
}

if (isset($_POST["korisnickoIme"]) && !empty(isset($_POST["korisnickoIme"]))) {
    $korisnik = $_POST["korisnickoIme"];
    dohvatiDetalje($korisnik);
}
?>