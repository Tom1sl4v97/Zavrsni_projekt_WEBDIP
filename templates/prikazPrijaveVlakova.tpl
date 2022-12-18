<link rel="stylesheet" href="{$putanja}/CSS/prikaz_teblice.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/prikaz_izlozbe.css"/>
<!-- linkovi za js -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="javascript/vlakoviAPI.js"></script>
<section>
    <script src="javascript/tablica.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>


    <h2>
        Popis svih Vaših prijava vlakova na izložbu:
    </h2>
    <br>
    {if isset($prikazTablice)}
        <table id="myTable" class="prikazTablice" style="width: 95%;">
            <thead>
                <tr>
                    <th>Redni broj</th>
                    <th colspan="3">Podaci korisnika</th>
                    <th>Naziv vlaka</th>
                    <th>Tematika</th>
                    <th>Datum početka</th>
                    <th>Status prijave</th>
                    <th>Potvrda</th>
                </tr>
            </thead>
            <tbody>
                {section name=i loop=$prikazTablice}
                    <tr 
                        {if $prikazTablice[i].status == 'Na čekanju'}
                            style="background-color: #93B080"
                        {elseif $prikazTablice[i].status == 'Odbijena'}
                            style="background-color: #D68B6F"
                        {/if}
                        >
                        <td>{$smarty.section.i.index_next}</td>
                        <td>{$prikazTablice[i].ime}</td>
                        <td>{$prikazTablice[i].prezime}</td>
                        <td>{$prikazTablice[i].korime}</td>
                        <td>{$prikazTablice[i].nazivVlaka}</td>
                        <td>{$prikazTablice[i].naziv}</td>
                        <td>{$prikazTablice[i].datum_pocetka}</td>
                        <td>{$prikazTablice[i].status}</td>
                        <td>
                            {if $prikazTablice[i].status == 'Potvrđena'}
                                <form class="prikazForme">
                                    <button type="submit" name="odbij" class="dodaj2" value="{$prikazTablice[i].id}">Odbij</button>
                                </form>
                            {elseif $prikazTablice[i].status == 'Odbijena'}
                                <form class="prikazForme">
                                    <button type="submit" name="prihvati" class="dodaj2" value="{$prikazTablice[i].id}">Prihvati</button>
                                </form>
                            {else}
                                <form class="prikazForme">
                                    <button type="submit" name="prihvati" class="dodaj2" value="{$prikazTablice[i].id}">Prihvati</button>
                                </form>
                                <form class="prikazForme">
                                    <button type="submit" name="odbij" class="dodaj2" value="{$prikazTablice[i].id}">Odbij</button>
                                </form>
                            {/if}
                        </td>
                    </tr>
                {/section}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8">Ukupno tematika vlakova:</td>
                    <td>{$brojPrijavaIzlozbe}</td>
                </tr>
            </tfoot>
        </table>
    {/if}
</section>
