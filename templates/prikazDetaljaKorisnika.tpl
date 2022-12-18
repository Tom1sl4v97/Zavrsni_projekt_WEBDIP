<link rel="stylesheet" href="{$putanja}/CSS/prikaz_galerije.css"/>

<script src="../javascript/galerijaSljedecaPrethodna.js"></script>
<style>
    .prikazTeksta{
        padding: 0 0 0 30px;
    }
    .resetka {
        display: grid;
        grid-template-columns: auto auto auto;
    }
    section{
        font-size: 20px;
    }

    @media only screen and (min-width: 0px) and (max-width: 1300px){
        .resetka {
            display: grid;
            grid-template-columns: auto;
        }
    }
</style>

<section>
    {if isset($informacijeVlakaKorisnika)}
        <h2 class="naslovPravi">Informacije o prijavi vlaka</h2>
        <br><br>
        <div class="prikazTeksta, resetka">
            <div width="33%">
                <h2 style="padding-bottom: 10px">
                    <b>Korisnik:</b>
                </h2>
                <div class="prikazTeksta">
                    <b style="padding-right: 108px">Ime: </b>{$informacijeVlakaKorisnika[0].ime}<br><br>
                    <b style="padding-right: 67px">Prezime:</b> {$informacijeVlakaKorisnika[0].prezime}<br><br>
                    <b>Korisnicko ime:</b> {$informacijeVlakaKorisnika[0].korisnicko_ime}<br><br>
                    <address><b style="padding-right: 88px">E-mail:</b><a href="mailto:{$informacijeVlakaKorisnika[0].email}" style="text-decoration: none;">{$informacijeVlakaKorisnika[0].email}</a></address><br><br>
                </div>
            </div>
            <div width="33%">
                <h2 style="padding-bottom: 10px">
                    <b>Vlak:</b>
                </h2>
                <div class="prikazTeksta">
                    <b style="padding-right: 89px">Ime vlaka:</b> {$informacijeVlakaKorisnika[0].naziv}<br><br>
                    <b>Maksimalna brzina:</b> {$informacijeVlakaKorisnika[0].max_brzina} km/h<br><br>
                    <b style="padding-right: 66px">Broj sjedala:</b> {$informacijeVlakaKorisnika[0].broj_sjedala}<br><br>
                    <b>Opis vlaka:</b><br> {$informacijeVlakaKorisnika[0].opisVlaka}<br><br>
                </div>
            </div>
            <div width="33%">
                <h2 style="padding-bottom: 10px">
                    <b>Pogon:</b>
                </h2>
                <div class="prikazTeksta">
                    <b>Naziv pogona:</b> {$informacijeVlakaKorisnika[0].naziv_pogona}<br><br>
                    <b>Opis pogona:</b><br> {$informacijeVlakaKorisnika[0].opisPogona}<br><br>
                </div>
            </div>
        </div>
        <br><br>
        {if isset($slikeKorisnika)}
            <h3>
                Galerija slika korisnika
            </h3>
            <div class="mjestoKutije">
                {foreach from=$slikeKorisnika key=key item=val name=foo}
                    {if ($smarty.foreach.foo.index % 4) == 0 AND $smarty.foreach.foo.index != 0}
                    </div>
                    <div class="mjestoKutije">
                        <div class="kutija">
                            <img src="{$val.url}">
                            <p>{$nazivSlike[$key]}</p>
                        </div>
                    {else}
                        <div class="kutija">
                            <img src="{$val.url}">
                            <p>{$nazivSlike[$key]}</p>
                        </div>
                    {/if}
                {/foreach}
            </div>
            <br>
        {/if}

        {if isset($videoKorisnika)}
            <h3>
                Galerija videa korisnika
            </h3>
            <div class="mjestoKutije">
                {foreach from=$videoKorisnika key=key item=val name=foo}
                    {if ($smarty.foreach.foo.index % 4) == 0 AND $smarty.foreach.foo.index != 0}
                    </div>
                    <div class="mjestoKutije">
                        <div class="kutija">
                            <video controls><source src="{$val.url}" type="video/mp4" class="video"></video>
                            <p>{$nazivVidea[$key]}</p>
                        </div>
                    {else}
                        <div class="kutija">
                            <video controls><source src="{$val.url}" type="video/mp4" class="video"></video>
                            <p>{$nazivVidea[$key]}</p>
                        </div>
                    {/if}
                {/foreach}
            </div>
        {/if}
        {if isset($audioKorisnika)}
            <h3>
                Galerija audia korisnika
            </h3>
            <div class="mjestoKutije" style="height: 200px">
                {foreach from=$audioKorisnika key=key item=val name=foo}
                    {if ($smarty.foreach.foo.index % 4) == 0 AND $smarty.foreach.foo.index != 0}
                    </div>
                    <div class="mjestoKutije" style="height: 200px">
                        <div class="kutija">
                            <audio controls><source src="{$val.url}" type="video/mp4" class="video"></audio>
                            <p>{$nazivAudia[$key]}</p>
                        </div>
                    {else}
                        <div class="kutija">
                            <audio controls><source src="{$val.url}" type="video/mp4" class="video"></audio>
                            <p>{$nazivAudia[$key]}</p>
                        </div>
                    {/if}
                {/foreach}
            </div>
        {/if}
        {if isset($gifKorisnika)}
            <h3>
                Galerija gifova korisnika
            </h3>
            <div class="mjestoKutije">
                {foreach from=$gifKorisnika key=key item=val name=foo}
                    {if ($smarty.foreach.foo.index % 4) == 0 AND $smarty.foreach.foo.index != 0}
                    </div>
                    <div class="mjestoKutije" style="height: 200px">
                        <div class="kutija">
                            <img src="{$val.url}"/>
                            <p>{$nazivGifa[$key]}</p>
                        </div>
                    {else}
                        <div class="kutija">
                            <img src="{$val.url}"/>
                            <p>{$nazivGifa[$key]}</p>
                        </div>
                    {/if}
                {/foreach}
            </div>
        {/if}


    {/if}
</section>
