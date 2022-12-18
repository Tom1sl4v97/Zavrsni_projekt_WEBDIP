<link rel="stylesheet" href="{$putanja}/CSS/prikaz_teblice.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/prikaz_forme.css"/>
<!-- linkovi za js -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="../javascript/vlakoviAPI.js"></script>
<section>
    <script src="../javascript/tablica.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>


    <h2>
        Tematike vlakova:
        <form class="prikazForme">
            <input class="gumbPrijava2" type="submit" name="dodajTematiku" value="Dodaj novu tematiku" style="float: right">
        </form>
    </h2>
    <table id="myTable" class="prikazTablice" style="width: 95%;">
        <thead>
            <tr>
                <th>Naziv tematike</th>
                <th>Opis tematike</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        {if isset($red)}
            <tbody>
                {section name=i loop=$red}
                    <tr>
                        <td>{$red[i].naziv}</td>
                        <td>{$red[i].opis}</td>
                        
                        <td>
                            <button onclick="prikazTematike({$red[i].id})" class="prikazGumbicaUredivanja" value="{$red[i].id}">Detalji</button>
                        </td> 
                        <td>
                            <form class="prikazGumbicaUredivanja">
                                <button type="submit" name="urediTematiku" class="prikazGumbicaUredivanja" value="{$red[i].id}">Uredi</button>
                            </form>
                        </td>
                        <td>
                            <form class="prikazGumbicaUredivanja">
                                <button type="submit" name="obrisiTematiku" class="prikazGumbicaUredivanja" value="{$red[i].id}">Obrisi</button>
                            </form>
                        </td>
                    </tr>
                {/section}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Ukupno tematika vlakova:</td>
                    <td colspan="2">{$brojTematike}</td>
                </tr>
            </tfoot>
        </table>
    {/if}
    <div id="detaljiZapisa" style="display:none" >
        <br><br>
        <table class="prikazTablice" style="width: 95%">
            <caption style="font-size: 24px; padding: 0px 0px 15px 0px;">Detalji za odabrani red: </caption>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Naziv tematike</th>
                    <th>Opis tematike</th>
                    <th>Kreirao korisnik</th>
                    <th>Datum kreiranja</th>
                    <th>Ažurirao korisnik</th>
                    <th>Datum ažuriranja</th>
                </tr>
            </thead>
            <tbody id="detalji">
            </tbody>
        </table>
        <br><br>
    </div>

    <br><br>
    <h2>
        Moderatori
        <form class="prikazForme">
            <input class="gumbPrijava2" type="submit" name="dodajModeratora" value="Dodaj novog moderatora" style="float: right">
        </form>
    </h2>
    {if isset($redModeratora)}
    <table id="myTable2" class="prikazTablice" style="width: 95%;">
        <thead>
            <tr>
                <th>Administrator</th>
                <th>Moderator</th>
                <th>Tematika</th>
                <th>Vazi od</th>
                <th>Vazi do</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
            <tbody>
                {section name=i loop=$redModeratora}
                    <tr>
                        <td>{$redModeratora[i].administrator}</td>
                        <td>{$redModeratora[i].moderator}</td>
                        <td>{$redModeratora[i].tematika}</td>
                        <td>{$redModeratora[i].vazi_od|date_format:"d.m.Y."}</td>
                        <td>{$redModeratora[i].vazi_do|date_format:"d.m.Y."}</td>
                        <td>
                            <form class="prikazGumbicaUredivanja">
                                <button type="submit" name="urediModeratora" class="prikazGumbicaUredivanja" value="{$redModeratora[i].id}">Uredi</button>
                            </form>
                        </td>
                        <td>
                            <form class="prikazGumbicaUredivanja">
                                <button type="submit" name="obrisiModeratora" class="prikazGumbicaUredivanja" value="{$redModeratora[i].id}">Obrisi</button>
                            </form>
                        </td>
                    </tr>
                {/section}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Ukupni broj moderatora tematike vlakova:</td>
                    <td colspan="3">{$brojModeratoraTematike}</td>
                </tr>
            </tfoot>
        </table>
    {/if}

</section>
