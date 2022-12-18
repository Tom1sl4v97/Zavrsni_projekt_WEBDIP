<link rel="stylesheet" href="{$putanja}/CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/popUp_prozor.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/slider_slike.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/prikaz_izlozbe.css"/>


<section>
    {if isset($listaVlakova)}
        <h2 style="padding-bottom: 10px">
            <form style="background-color: transparent; border: none; float: right;">
                <button type="submit" name="dodavanjeNovogVlaka" class="gumbPrijava" value="">Dodaj novi vlak</button>
            </form>
            Prikaz vaših dodanih vlakova:
        </h2>

        <div class="resetka">
            {section name=i loop=$listaVlakova}
                <div style="margin-bottom: 20px; max-width: 400px">
                    <div class="izlozbaVlakova">
                        <div class="naslov">
                            {$listaVlakova[i].naziv}
                        </div>
                        <div>
                            <div class="podkategorije">
                                <b>Opis vlaka:</b> <br><br> &nbsp;&nbsp; {$listaVlakova[i].opis}<br><br>
                                <b>Maximalna brzina vlaka: </b>{$listaVlakova[i].max_brzina} km/h<br><br>
                                <b>Ukupan broj sjedala: </b>{$listaVlakova[i].broj_sjedala}<br><br>
                                <b>Naziv pogona: </b>{$listaVlakova[i].naziv_pogona}
                            </div>
                            <form class="prikazForme" style="margin-bottom: 10px">
                                <button type="submit" name="urediVlakKorisnika" class="dodaj" value="{$listaVlakova[i].id}">Uredi vlak</button>
                                <button type="submit" name="obrisiVlakKorisnika" class="dodaj" value="{$listaVlakova[i].id}">Obriši vlak</button>
                            </form>
                            {assign "provjera" "da"}
                            {section name=k loop=$slikaVlaka}
                                {if isset($slikaVlaka) AND $slikaVlaka[k].IDVlaka ==  $listaVlakova[i].id AND $provjera == 'da'}
                                    <div>
                                        <img src="{$putanja}/multimedija/vlak1.jpg" alt="{$slikaVlaka[k].url}" style="width: 100%">
                                    </div>
                                    {assign "provjera" "ne"}
                                {/if}
                            {/section}
                        </div>
                    </div>
                </div>
            {/section}
        </div>
    {else}
        <h2>
            Niste prijavili niti jedan vlak
            <form style="background-color: transparent; border: none; float: right;">
                <button type="submit" name="dodavanjeNovogVlaka" class="gumbPrijava" value="">Dodaj novi vlak</button>
            </form>
        </h2>
    {/if}
</section>
