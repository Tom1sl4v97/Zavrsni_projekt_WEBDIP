<?php

$putanja = dirname($_SERVER['REQUEST_URI']);
$direktorij = getcwd();
$prikazDirektorijaCSS = "http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2020%2Fzadaca_04%2Fttomiek%2Fautor.php&profile=css3svg&usermedium=all&warning=1&vextwarning=&lang=en";

$naslov = "Dokumentacija";
$opis = '';
include './zaglavlje.php';

$ispis = '<h2>Informacije o autoru:</h2>';
$smarty->assign("ispis", $ispis);


$smarty->display('dokumentacija.tpl');
$smarty->display('podnozje.tpl');

?>