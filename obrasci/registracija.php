<?php

$putanja = dirname($_SERVER['REQUEST_URI'], 2);
$direktorij = dirname(getcwd());
$prikazDirektorijaCSS = "";

$naslov = "Registracija korisnika";
$opis = 'Ovo je stranica za registraciju korisnika, kreirana 28.05.2021.';
include '../zaglavlje.php';

$greska = "";

provjeraKorisnika($putanja);
vratiSe();

if (isset($_POST["registracija"])) {
    $podaci = array();
    provjeri();

    if (empty($greska)) {
        if ($podaci["lozinka1"] === $podaci["lozinka2"]) {
            zapisiNovogKorisnika($putanja);
        } else {
            $greska .= "Lozinke su Vam različite!";
        }
    }
}

function vratiSe() {
    global $putanja;
    if (isset($_POST["vratiSe"])) {
        echo "<script>window.location.href='$putanja/obrasci/prijava.php';</script>";
    }
}

function provjeraKorisnika($putanja) {
    if (isset($_SESSION["uloga"])) {
        header("Location: $putanja/korisnik/index.php");
        exit();
    }
}

function zapisiNovogKorisnika($putanja) {
    global $podaci;
    global $greska;
    global $direktorij;
    $virtualnoVrijeme = strtotime(ispis_konfiguracije($direktorij));
    $time = date("Y-m-d H:i:s", $virtualnoVrijeme);

    $veza = new Baza;
    $veza->spojiDB();

    $text = md5(uniqid(rand(), TRUE));
    $salt = substr($text, 0, 3);
    $lozinka = hash("sha256", $podaci["lozinka1"] . $salt);

    $sql = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka_sha1, email, uvjeti_koristenja, status, tip_korisnika_id, lozinka, salt)" .
            " VALUES ('{$podaci["ime"]}', '{$podaci["prezime"]}', '{$podaci["korisnicko_ime"]}', '{$lozinka}', '{$podaci["email"]}', '{$time}', 0, '3', '{$podaci["lozinka1"]}', '{$salt}')";

    if ($veza->updateDB($sql)) {
        $msg = "Molimo vas kliknite na sljedeci link za potvrdu korisnickom racuna, http://barka.foi.hr{$putanja}/index.php?noviKorisnik={$podaci["korisnicko_ime"]}";
        $msg = wordwrap($msg, 120);

        mail("{$podaci["email"]}", "Aktivacijski link", $msg);

        echo '<script>alert("Poslan Vam je aktivacijski kod na ' . $podaci["email"] . '");</script>';
    } else {
        $greska .= "Korisnicko ime je zauzeto, molimo vas pokušajte ponovno.";
    }


    $veza->zatvoriDB();
}

function provjeri() {
    global $greska;
    global $podaci;

    $greske = array("korisnicko_ime" => "korisničko ime", "email" => "e-mail", "lozinka1" => "prvu lozinku", "lozinka2" => "drugu lozinku");

    foreach ($_POST as $k => $v) {
        $podaci[$k] = $v;
        if ($k === "ime" OR $k === "prezime")
            continue;
        elseif (empty($v)) {
            foreach ($greske as $key => $val) {
                if ($key === $k) {
                    $greska .= "Niste popunili " . $val . "<br>";
                }
            }
        }
        if ($k === "lozinka1" and empty($greska)) {
            $uzorak = '/^(?!.*(.)\1{3})((?=.*[\d])(?=.*[A-Za-z])|(?=.*[^\w\d\s])(?=.*[A-Za-z])).{8,20}$/';
            if (!preg_match($uzorak, $v)) {
                $greska .= "Format: Lozinka ima manje od 8 znakova "
                        . "ili više od 20 znakova "
                        . "ili nema 1 alfanumerički znak "
                        . "ili nema najmanje 1 broj "
                        . "ili nema specijalni znak "
                        . "ili se ponavljaju 3 ista znaka!"
                        . "<br>";
            }
        }
    }

    $captcha = $_POST['g-recaptcha-response'];

    $secretKey = "6Le-b0YbAAAAAG97m7Ubmn8m4xryZknVMh1aCybd";
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) . '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response, true);
    if (!$responseKeys["success"]) {
        $greska .= "Niste riješili captcha-u";
    }
}

$smarty->assign("greska", $greska);
$smarty->display('registracija.tpl');
$smarty->display('podnozje.tpl');
?>