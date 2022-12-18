<link rel="stylesheet" href="{$putanja}/CSS/prikaz_izlozbe.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/prikaz_teblice.css"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="../javascript/tablica.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<section>
    <h2>
        Prikaz postavki stranice
    </h2>
    <br>
    <div class="resetka">
        <div style="margin-bottom: 25px; min-width: 100%">
            <div class="izlozbaVlakova">
                <div class="naslov">
                    <p>
                        Izmjena trajnje sesije
                    </p>
                </div>
                <div>
                    <div class="podkategorije">
                        {if (isset($greskaSesije))}
                            <div id="greske" class="greska">
                                <p>{$greskaSesije}</p>
                            </div>
                        {/if}
                        <form class="prikazForme" method="post" name="promjenaTrajanjaSesije" action="{$smarty.server.PHP_SELF}">
                            <p>
                                Trenutno: {$trenutniZapisSesije} h
                            </p>
                            <input style="width: 97%; height: 25px" type="time" step="1" name="izmjeniSesiju"><br><br>
                            <input class="dodaj" type="submit" name="izmjenaTrajanjeSesije" value="Spremi">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 25px; min-width: 100%">
            <div class="izlozbaVlakova">
                <div class="naslov">
                    <p>
                        Izmjena virtualnog vrijemena<br>(sati)
                    </p>
                </div>
                <div>
                    <div class="podkategorije">
                        <form class="prikazForme" method="post" name="promjenaVirtualnogVremena" action="{$smarty.server.PHP_SELF}">
                            <p>
                                Trenutno: {$trenutniZapisVirtualnogVremena} h razlike
                            </p>
                            <a target="_blank" href="http://barka.foi.hr/WebDiP/pomak_vremena/vrijeme.html">
                                Postavi virtualno vrijeme
                            </a>
                            <br><br>
                            <input class="dodaj" type="submit" name="izmjenaVirtualnogVremena" value="Spremi">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 25px; min-width: 100%">
            <div class="izlozbaVlakova">
                <div class="naslov">
                    <p>
                        Izmjena trajnje kolacica<br>(dani)
                    </p>
                </div>
                <div>
                    <div class="podkategorije">
                        <form class="prikazForme" method="post" name="promjenaTrajanjaKolacica" action="{$smarty.server.PHP_SELF}">
                            {if (isset($greska))}
                                <div id="greske" class="greska">
                                    <p>{$greska}</p>
                                </div>
                            {/if}
                            <p>
                                Trenutno: {$trenutniZapisKolacica} dan/a
                            </p>
                            <input style="width: 97%; height: 25px" type="text" name="novoTrajanjeKolacica"><br><br>
                            <input class="dodaj" type="submit" name="izmjeniTrajanjeKolacica" value="Spremi">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 25px; min-width: 100%">
            <div class="izlozbaVlakova">
                <div class="naslov">
                    <p>
                        Resetiranje uvjeta korištenja<br>
                        svih korisnika (dani)
                    </p>
                </div>
                <div>
                    <div class="podkategorije">
                        <form class="prikazForme" method="post" name="resetiranjeUvjetaKoristenja" action="{$smarty.server.PHP_SELF}">
                            <input class="dodaj" type="submit" name="resetirajUvjete" value="Resetiraj uvjete" style="margin-top: 10px">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 25px; min-width: 100%">
            <div class="izlozbaVlakova">
                <div class="naslov">
                    <p>
                        Napravi sigurosnu kopoiju<br>svih vlakova i materijala
                    </p>
                </div>
                <div>
                    <div class="podkategorije">
                        <form class="prikazForme" method="post" name="napraviKopijuVlakovaIMaterijala" action="{$smarty.server.PHP_SELF}">
                            <input class="dodaj" type="submit" name="napraviKopiju" value="Napravi sigurosnu kopiju" style="margin-top: 10px">
                        </form>
                    </div>
                    <br><br>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 25px; min-width: 100%">
            <div class="izlozbaVlakova">
                <div class="naslov">
                    <p>
                        Postavi podatke iz sigurosne<br>kopije svi vlakova i materijala
                    </p>
                </div>
                <div>
                    <div class="podkategorije">
                        <form class="prikazForme" method="post" name="postaviKopijuPodataka" action="{$smarty.server.PHP_SELF}">
                            <input class="dodaj" type="submit" name="postaviKopiju" value="Postavi podatke iz kopije" style="margin-top: 10px">
                        </form>
                    </div>
                    <br><br>
                </div>
            </div>
        </div>
    </div>

    {if isset($blokiraniKorisnici)}

        <br><br>
        <h2>
            Lista blokiranih korisničkih računa
        </h2>
        <table id="myTable" class="prikazTablice" style="width: 95%;">
            <thead>
                <tr>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Korisničko ime</th>
                    <th>email</th>
                    <th></th>
                </tr>
            </thead>
            {section name=i loop=$blokiraniKorisnici}
                <tr>
                    <td>{$blokiraniKorisnici[i].ime}</td>
                    <td>{$blokiraniKorisnici[i].prezime}</td>
                    <td>{$blokiraniKorisnici[i].korisnicko_ime}</td>
                    <td>{$blokiraniKorisnici[i].email}</td>
                    <td>

                        {if $blokiraniKorisnici[i].broj_neuspijesnih_prijava < 3}
                            <form class="prikazGumbicaUredivanja">
                                <button type="submit" name="blokirajKorisnika" class="dodaj2" style="background-color: #D68B6F;color: black" value="{$blokiraniKorisnici[i].id}">Blokiraj</button>
                            </form>
                        {else}
                            <form class="prikazGumbicaUredivanja">
                                <button type="submit" name="prihvatiKorisnika" class="dodaj2" style="background-color: #93B080;color:black" value="{$blokiraniKorisnici[i].id}">Prihvati</button>
                            </form>
                        {/if}
                    </td>
                </tr>
            {/section}
            <tfoot>
                <tr>
                    <td colspan="3">Ukupno tematika vlakova:</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    {/if}
    {if isset($dnevnikKoristenjaStranice)}
        <br><br>
        <h2>
            Zapis dnevnika korištenja
        </h2>
        <div class="podkategorije">
            <form class="prikazForme" method="post" name="pretražiPoDatumu" action="{$smarty.server.PHP_SELF}">
                <label for="pocetniDatum">Pretraži dnevnik od datuma:</label><br>
                <input type="date" id="pocetniDatum" name="pocetniDatum" class="okvirForme2" style="width: 200px; height: 4px"><br><br>

                <label for="zavrsniDatum">Pretraži dnevnik do datuma:</label><br>
                <input type="date" id="zavrsniDatum" name="zavrsniDatum" class="okvirForme2" style="width: 200px; height: 4px"><br><br>

                <input class="gumbPrijava" type="submit" name="pretraži" value="Pretraži"><br><br>
            </form>
        </div>

        <table id="myTable2" class="prikazTablice" style="width: 95%;">
            <thead>
                <tr>
                    <th>ID dnevnika</th>
                    <th>Korisnik</th>
                    <th>E-mail</th>
                    <th>Stranica</th>
                    <th>Upit</th>
                    <th>Datum</th>
                    <th>Tip radnje</th>
                </tr>
            </thead>

            {section name=i loop=$dnevnikKoristenjaStranice}
                <tr>
                    <td>{$dnevnikKoristenjaStranice[i].id}</td>
                    <td>{$dnevnikKoristenjaStranice[i].ime} {$dnevnikKoristenjaStranice[i].prezime} {$dnevnikKoristenjaStranice[i].korisnicko_ime}</td>
                    <td>{$dnevnikKoristenjaStranice[i].email}</td>
                    <td>{$urlStranica[i]}</td>
                    <td>{$dnevnikKoristenjaStranice[i].upit}</td>
                    <td>{$dnevnikKoristenjaStranice[i].datum}</td>
                    <td>{$dnevnikKoristenjaStranice[i].opis}</td>
                </tr>
            {/section}
            <tfoot>
                <tr>
                    <td colspan="6">Ukupan broj zapisa:</td>
                    <td>{$smarty.section.i.total}</td>
                </tr>
            </tfoot>
        </table>
    {/if}

</section>
