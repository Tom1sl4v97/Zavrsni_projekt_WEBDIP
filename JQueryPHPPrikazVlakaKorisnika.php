<?php

$direktorij = getcwd();
require "$direktorij/baza.class.php";

function dohvatiDetalje($id, $korisnik) {
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT v.id AS 'IDVlaka', v.naziv  "
            . "FROM vlak v "
            . "INNER JOIN korisnik k ON v.vlasnik_id = k.id "
            . "WHERE k.korisnicko_ime = '{$korisnik}' AND "
            . "     v.id NOT IN (SELECT pv.vlak_id "
            . "                  FROM prijavavlaka pv JOIN izlozba i ON i.id = pv.izlozba_id "
            . "                  WHERE pv.vlak_id = v.id AND pv.izlozba_id = {$id})";
    $rezultat = $veza->selectDB($sql);
    if ($rezultat->num_rows === 0) {
        echo json_encode([]);
    } else {
        while ($red = mysqli_fetch_array($rezultat)) {
            $redovi[] = $red;
        }
        echo json_encode($redovi);
    }
    $veza->zatvoriDB();
}

if (isset($_POST["prijavaVlakaId"]) && !empty(isset($_POST["prijavaVlakaId"]))) {
    $id = $_POST["prijavaVlakaId"];
    $korisnik = $_POST["korisnik"];
    dohvatiDetalje($id, $korisnik);
}
?>