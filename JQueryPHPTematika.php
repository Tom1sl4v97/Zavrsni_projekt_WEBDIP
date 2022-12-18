<?php

$direktorij = getcwd();
require "$direktorij/baza.class.php";

function dohvatiTematiku($id) {
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT tematika.id, tematika.naziv, tematika.opis, korisnikKreirao.korisnicko_ime as 'kreirao korisnik', tematika.datum_kreiranja, korisnikAzurirao.korisnicko_ime, tematika.datum_azuriranja"
           . " FROM tematika INNER JOIN korisnik korisnikKreirao ON tematika.kreirao_korisnik_id = korisnikKreirao.id"
           . " LEFT JOIN korisnik korisnikAzurirao On tematika.azurirao_korisnik_id = korisnikAzurirao.id"
           . " WHERE tematika.id = {$id}";
    
    $rezultat = $veza->selectDB($sql);
    $row = $rezultat->fetch_assoc();
    $veza->zatvoriDB();
    echo json_encode($row);
}

if (isset($_POST["id"]) && !empty(isset($_POST["id"]))) {
    $id = $_POST["id"];
    dohvatiTematiku($id);
}
?>