<?php

$direktorij = getcwd();
require "$direktorij/baza.class.php";

function dohvatiPrihvacenePrijaveVlakovaNaIzlozbi($id) {
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT v.id, v.naziv AS 'nazivVlaka',v.max_brzina,v.broj_sjedala, vp.naziv_pogona, k.id AS 'IDKorisnika', k.ime, k.prezime, k.korisnicko_ime AS 'korisnickoIme',"
            . " sp.status, pv.id AS 'IDPrijaveVlaka', i.datum_pocetka, "
            . "( SELECT m.url FROM materijal m WHERE m.prijava_vlaka_id = pv.id AND m.vrsta_materijala_id = 1 ORDER BY id DESC LIMIT 1) AS slikaUrl"
            . " FROM prijavavlaka pv"
            . "     INNER JOIN vlak v ON pv.vlak_id=v.id"
            . "     INNER JOIN vrstapogona vp ON v.vrsta_pogona_id = vp.id"
            . "     INNER JOIN korisnik k ON v.vlasnik_id = k.id"
            . "     INNER JOIN statusprijave sp ON pv.status_id = sp.id"
            . "     INNER JOIN izlozba i ON pv.izlozba_id = i.id"
            . "     INNER JOIN glasovanje gl ON gl.izlozba_id = i.id"
            . " WHERE pv.izlozba_id = {$id} AND pv.status_id = 1";
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

if (isset($_POST["id"]) && !empty(isset($_POST["id"]))) {
    $id = $_POST["id"];
    dohvatiPrihvacenePrijaveVlakovaNaIzlozbi($id);
}
?>