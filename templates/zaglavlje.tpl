<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html lang="hr">
    <head>
        <title>{$naslov}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="title" content="{$naslov}">
        <meta name="author" content="Tomislav Tomiek">
        <meta name="description" content="{$opis}">
        <meta name="keywords" content="">
        <link rel="icon" href="{$putanja}/multimedija/iconica.png">

        <link rel="stylesheet" href="{$putanja}/CSS/ttomiek.css"/>
        <link rel="stylesheet" href="{$putanja}/CSS/ttomiek_accesibility.css" 
              {if isset($dizajn)}
                  {$dizajn}
              {else}
                  disabled
              {/if}
              />
        <link rel="stylesheet" href="{$putanja}/CSS/darkmode.css" 
              {if isset($darkmode)}
                  {$darkmode}
              {else}
                  disabled
              {/if}
              />
    </head>
    {if isset($dizajn)}
        <div id="kutijaDizajnaAccessibility">
            <form style="border: none;background-color: transparent;">
                <button type="submit" name="promjenaDizajna" class="slikaGumbaAccessibility" value="disable"></button>
            </form>
        </div>
    {/if}
    {if isset($darkmode)}
        <div id="kutijaDizajnaDarkMode">
            <form style="border: none;background-color: transparent;">
                <button class="slikaGumbaDarkMode" type="submit" name="promjenaDizajnaDarkMode"></button>
            </form>
        </div>
    {/if}
    <body>
        <header>
            <h1> 
                <img src="{$putanja}/multimedija/train.png" alt="vlak" width="70" style="float:left;margin:0px;padding: 0px" />
                <a href="#sekcija_sadržaj">{$naslov}</a>
            </h1>
        </header>
        <nav>
            <ul>
                {if isset($smarty.session.uloga) && $smarty.session.uloga < 4}
                    <li><a href="{$putanja}/index.php">Početna stranica</a></li>
                    <li><a href="{$putanja}/autor.php">Autor</a></li>
                    <li><a href="{$putanja}/korisnik/izlozbe.php">Izložbe</a></li>
                    <li><a href="{$putanja}/korisnik/prikazVlakova.php">Vaši vlakovi</a></li>
                    <li><a href="{$putanja}/dokumentacija.php">dokumentacija</a></li>
                {else}
                    <li><a href="{$putanja}/index.php">Početna stranica</a></li>
                    <li><a href="{$putanja}/obrasci/prijava.php">Prijava</a></li>
                    <li><a href="{$putanja}/autor.php">Autor</a></li>
                    <li><a href="{$putanja}/dokumentacija.php">dokumentacija</a></li>
                    <li><a href="{$putanja}/administrator/privatnoKorisnici.php">Privatno / korisnici</a></li>
                    {/if}

                {if isset($smarty.session.uloga) && $smarty.session.uloga < 3}
                    <li><a href="{$putanja}/izlozbaVlakova.php">Uređivanje izložba</a></li>
                    <li><a href="{$putanja}/prikazPrijaveVlakova.php">Pregled prijava</a></li>

                {/if}
                {if isset($smarty.session.uloga) && $smarty.session.uloga == 1}
                    <li><a href="{$putanja}/administrator/tematikaVlakova.php">Tematika vlakova</a></li>
                    <li><a href="{$putanja}/administrator/postavke.php">Postavke</a></li>
                {/if}

                {if isset($smarty.session.uloga) && $smarty.session.uloga < 4}
                    <li><a href="{$putanja}/logout.php">logout</a></li>

                {/if}
            </ul>
        </nav>
