<?php

$direktorij = getcwd();
require "$direktorij/baza.class.php";

function dohvatiTematiku($id) {
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT k.ime, k.prezime, k.korisnicko_ime, k.email, v.naziv AS 'nazivVlaka', t.naziv AS 'nazivTematike'"
            . " FROM prijavavlaka pv"
            . " INNER JOIN vlak v ON v.id = pv.vlak_id"
            . " INNER JOIN korisnik k ON k.id = v.vlasnik_id"
            . " INNER JOIN izlozba i ON i.id = pv.izlozba_id"
            . " INNER JOIN tematika t ON t.id = i.tematika_id"
            . " WHERE pv.status_id = 1 AND pv.izlozba_id = {$id}"
            . " ORDER BY pv.id DESC LIMIT 10";

    $rezultat = $veza->selectDB($sql);
    $veza->zatvoriDB();

    while ($red = mysqli_fetch_array($rezultat)) {
        $redovi[] = $red;
    }
    echo json_encode($redovi);
}

if (isset($_POST["id"]) && !empty(isset($_POST["id"]))) {
    $id = $_POST["id"];
    dohvatiTematiku($id);
}
?>