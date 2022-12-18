<link rel="stylesheet" href="{$putanja}/CSS/prikaz_teblice.css"/>

<section>
    <h2>
        Prikaz svih korisnika stranice:
    </h2>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
    <script src="../javascript/tablica.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

    <table id="myTable" class="prikazTablice" style="width: 95%;">
        <thead>
            <tr>
                <th>Ime</th>
                <th>Prezime</th>
                <th>Korisnicko ime</th>
                <th>E-mail</th>
                <th>Lozinka</th>
                <th>Uloga</th>
            </tr>
        </thead>
        {if isset($prikazSvihKorisnika)}
            <tbody>
                {section name=i loop=$prikazSvihKorisnika}
                    <tr>
                        <td>{$prikazSvihKorisnika[i].ime}</td>
                        <td>{$prikazSvihKorisnika[i].prezime}</td>
                        <td>{$prikazSvihKorisnika[i].korisnicko_ime}</td>
                        <td>{$prikazSvihKorisnika[i].email}</td>
                        <td>{$prikazSvihKorisnika[i].lozinka}</td>
                        <td>{$prikazSvihKorisnika[i].naziv}</td>
                    </tr>
                {/section}
            </tbody>
        {/if}
        <tfoot>
            <tr>
                <td colspan="4">Ukupno tematika vlakova:</td>
                <td colspan="2">{$smarty.section.i.total}</td>
            </tr>
        </tfoot>
    </table>
</section>