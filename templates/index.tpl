<link rel="stylesheet" href="{$putanja}/CSS/popUp_prozor.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/prikaz_izlozbe.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/prikaz_teblice.css"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="{$putanja}/javascript/provjeraStatusaPrihvacenosti.js"></script>
<script src="{$putanja}/javascript/vlakoviAPI.js"></script>

<section>
    {if isset($podaci) and !isset($istekloVrijeme)}
        <div class="popUpPomocCSS" id="popUpPrihvaćanjaUvjetaKoristenja" status="{$podaci[2]}" trajanjeKolacica="{$podaci[3]}" virtualnoVrijeme="{$podaci[1]|date_format: "%Y-%m-%d %H:%M:%S"}" korisnik="{$podaci[0]}">
            <div class="popUpPomocPozadina">
                <div id="odustani" class="odustani"> X </div>
                <h2 style="padding: 0">Prihvaćate li uvjete korištenja, zapis podataka u kolačić o prijavi</h2>
                <h2 id="textIzlozbe" style="padding: 0"></h2>
                <br>
                <div id="bezVlakova"></div>
                <button class="gumbPrijava" id="nePrihvaćam" style="float: right">Ne prihvaćam</button>
                <form style="width: 80%;padding: 0; background-color: transparent; border: none" id="korisnikSaVlakom">
                    <button type="submit" name="prihvatiUvjete" class="gumbPrijava" id="prihvatiUvjete">Prihvati uvjete koristenja</button>
                </form>
            </div>
        </div>
    {/if}
    {if isset($istekloVrijeme)}
        {$istekloVrijeme}
    {else}
        <h2>
            Prikaz završenih izložbi i pobjednika izložbe
        </h2>
        <div class="resetka">
            {section name=i loop=$izlozba}
                <div style="margin-bottom: 30px">
                    <div class="izlozbaVlakova">
                        <div class="slika">
                            {assign "nemaSlike" "ne"}
                            {foreach from=$izborSlike key=key item=val}
                                {if $izlozba[i].naziv == $val}
                                    <img src="{$putanja}/multimedija/prikazTeme/{$key}.jpg" alt="{$key}" style="width: 100%;margin: 0; padding: 0;">
                                    {assign "nemaSlike" "da"}
                                    {assign "naslovnaSlike" $key}
                                {/if}
                            {/foreach}
                            {if $nemaSlike == "ne"}
                                <img src="{$putanja}/multimedija/prikazTeme/ostalo.jpg" alt="{$izlozba[i].naziv}" style="width: 100%;margin: 0; padding: 0;">
                            {/if}
                        </div>
                        <div class="naslov">
                            {$izlozba[i].naziv}
                            {if {$izlozba[i].trenutni} != 0}
                                <button onclick="kanalRSSPrikazPodataka({$izlozba[i].id})" class="rss" value="{$izlozba[i].id}"></button>
                            {/if}
                        </div>
                        <div>
                            <br>
                            <div class="podkategorije">
                                {$izlozba[i].datum_pocetka|date_format: "<b>Datum početka:</b> %d.%m.%Y.<br><br><b>Vrijeme početka:</b> %H:%M"}<br><br>
                                <b>Popunjeno:</b> {$izlozba[i].trenutni} / {$izlozba[i].broj_korisnika}<br><br>
                                <b>Status: </b> {$izlozba[i].status}
                                {if $podaciGlasanja[i] != NULL}
                                    <p><b>Pobijednik:</b> {$podaciGlasanja[i][0].ime} {$podaciGlasanja[i][0].prezime}</p>
                                    <p><b>Naziv vlaka:</b> {$podaciGlasanja[i][0].naziv}
                                    <form class="prikazForme">
                                        <button type="submit" name="detaljiPobjednika" class="dodaj" value="{$podaciGlasanja[i][0].IDPrijaveVlaka}">
                                            Detalji pobjednika
                                        </button>
                                    </form>
                                    <br><br>

                                    {section name=k loop=$podaciGlasanja[i]}
                                        {if $smarty.section.k.index == 0 and $smarty.section.k.total > 1}
                                            <p><b>Ostali sudionici:</b></p>
                                        {elseif $smarty.section.k.total > 1}
                                            {$smarty.section.k.index_next}. {$podaciGlasanja[i][k].ime} {$podaciGlasanja[i][k].prezime} - {$podaciGlasanja[i][k].korisnicko_ime}: {$podaciGlasanja[i][k].naziv}
                                        {/if}
                                    {/section}
                                {else}
                                    <p>Nitko nije glasao</p>
                                {/if}


                            </div>
                        </div>
                    </div>
                </div>
            {/section}
        </div>
        <div id="RSSKanal" style="display:none" >
            <br><br>
            <table class="prikazTablice" style="width: 95%">
                <caption style="font-size: 24px; padding: 0px 0px 15px 0px;">Detalji za odabrani red: </caption>
                <thead>
                    <tr>
                        <th>Ime</th>
                        <th>Prezime</th>
                        <th>Korisnicko ime</th>
                        <th>E-mail</th>
                        <th>Naziv vlaka</th>
                        <th>Naziv tematike izlozbe</th>
                    </tr>
                </thead>
                <tbody id="RSSTablica">
                </tbody>
            </table>
            <br><br>
        </div>

    {/if}
</section>

