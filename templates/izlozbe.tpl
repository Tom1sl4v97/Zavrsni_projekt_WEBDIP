<link rel="stylesheet" href="{$putanja}/CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/popUp_prozor.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/slider_slike.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/prikaz_izlozbe.css"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="../javascript/vlakoviAPI.js"></script>

<script src="{$putanja}/javascript/odabirIzlozbeZaVlak.js" ></script>

<style>
    p {
        font-size: 18px;
        padding-left: 40px;
    }
    form {
        width: 80%;
        padding: 0;
        padding-right: 10px;
        background-color: transparent;
        border: none;
    }

</style>

<section>
    <div id="slider">
        <figure>
            <img src="{$putanja}/multimedija/vlak1.jpg" class="prikazSlike">
            <img src="{$putanja}/multimedija/vlak2.jpg" class="prikazSlike">
            <img src="{$putanja}/multimedija/vlak3.jpg" class="prikazSlike">
            <img src="{$putanja}/multimedija/vlak4.jpg" class="prikazSlike">
            <img src="{$putanja}/multimedija/vlak5.jpg" class="prikazSlike">
        </figure>
    </div>
    {if isset($izlozba)}
        <div class="popUpPomocCSS" id="popUpPomoc">
            <div class="popUpPomocPozadina">
                <div id="odustani" class="odustani"> X </div>
                <h2 style="padding: 0">Odaberite željeni vlak za odabranu izložbu:</h2>
                <h2 id="textIzlozbe" style="padding: 0"></h2>
                <br>
                <div id="bezVlakova"></div>
                <form style="width: 80%;padding: 0; background-color: transparent; border: none" id="korisnikSaVlakom">
                    <select name="odabirVlakaZaPrijavu" id="prikazVlakovaKorisnika" class="prikazDropDown">
                        <option value="0" class="prikazDropDown">Odaberite</option>
                    </select>
                    <br><br>
                    <button type="submit" name="prijaviVlak" class="gumbPrijava" id="prijaviVlakNaIzlozbu">Prijavi vlak</button>
                </form>
            </div>
        </div> 

        <div id="prikazIzlozbe">
            <br><br>
            <h2>
                Dostupne izložbe:
            </h2>
            <br>
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
                            </div>
                            <div>
                                <button type="submit" class="dodaj" value="{$izlozba[i].id}" 
                                        onclick="prikaziDetalje({$izlozba[i].id}, '{$izlozba[i].naziv}', '{$izlozba[i].opis}', '{$izlozba[i].datum_pocetka}', {$izlozba[i].trenutni}, {$izlozba[i].broj_korisnika}, '{$naslovnaSlike}', '{$korisnickoImePrijavljenogKorisnika}', '{$izlozba[i].status}', '{$virtualniDatum}', '{$putanja}')">
                                    DETALJI IZLOŽBE
                                </button>
                                <br><br>
                                <div class="podkategorije">
                                    {$izlozba[i].datum_pocetka|date_format: "Datum početka: %d.%m.%Y.<br><br>Vrijeme početka: %H:%M"}<br><br>
                                    Popunjeno: {$izlozba[i].trenutni} / {$izlozba[i].broj_korisnika}<br><br>
                                    Status: {$izlozba[i].status}
                                </div>
                            </div>
                        </div>
                    </div>
                {/section}
            </div>
        </div>

        <div id="detaljiIzlozbe" style="display: none">
            <h2 class="naslovPravi" id="naslov">
            </h2>
            <img src="" style="width: 55%;margin: 0; padding-top:  30px;float: right" id="slikaNaslova">
            <br><br>
            <h2>
                Osnovne informacije o izložbi:
            </h2>
            <br>
            <h3>
                Datum početka izložbe:
            </h3>
            <p id="datumPocetka"></p>
            <h3>
                Vrijeme početka:
            </h3>
            <p id="vrijemePocetka"></p>

            <h3>
                Popunjenost izložbe:
            </h3>
            <p id="popunjenost"></p>
            <h3>
                Kratki opis o izložbi:
            </h3>
            <p id="opis"></p>
            <br>

            <button class="gumbPrijava" style="float: left;margin-left: 3%" id="natrag">
                Vrati se
            </button>

            <button type="submit" class="gumbPrijava" id="dodajVlak">
                Prijavi svoj vlak
            </button>

            <br><br><br><br>

            <div class="popUpPomocCSS" id="popUpPomocKodGlasovanja">
                <div class="popUpPomocPozadina">
                    <div id="odustaniGlasati" class="odustani"> X </div>
                    <h2 id="nazivVlaka" style="padding: 0"></h2>
                    <br>
                    <label>Ocijena (1 - 10): </label>
                    <input class="okvirForme" type="number" id="ocjena" min="1" max="10"><br><br>

                    <label>Komentar: </label>
                    <input class="okvirForme" type="text" id="komentar" ><br><br>

                    <button type="submit" class="gumbPrijava" id="glasovanje">Glasaj</button>
                </div>
            </div>

            <div class="popUpPomocCSS" id="popUpPomocKodDodavanjaMaterijala">
                <div class="popUpPomocPozadina">
                    <div id="odustaniOdDodavanjaMaterijala" class="odustani"> X </div>
                    <h2 style="padding: 0">Dodajte željene materijale:</h2>
                    <h2 id="textIzlozbe" style="padding: 0"></h2>
                    <br>

                    <form enctype="multipart/form-data" action="uploader.php" method="post">
                        <label for="odabirMaterijala">Odaberite vrstu materijala:</label><br>
                        <select name="odabirMaterijala" id="odabirMaterijala" class="prikazDropDown">
                            <option value="0" class="prikazDropDown">Odaberite</option>
                            {section name=i loop=$vrstaMaterijala}
                                <option value="{$vrstaMaterijala[i].id}" class="prikazDropDown">
                                    {$vrstaMaterijala[i].format}
                                </option>
                            {/section}
                        </select>
                        <br><br>
                        <input type="hidden" name="MAX_FILE_SIZE" value="3000000000" />
                        <input name="upload[]" type="file" multiple="multiple" class="prikazDropDown"/><br><br>

                        
                        <button type="submit" value="" class="gumbPrijava" name="posaljiMaterijale" id="posaljiMaterijale">Pošalji</button>
                    </form>

                </div>
            </div> 

            <div id="prikazVlakovaNaIzlozbi">
            </div>

            <br><br><br><br><br><br><br><br><br>
        </div>
    {/if}
</section>
