<link rel="stylesheet" href="{$putanja}/CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/prikaz_teblice.css"/>

<script src="{$putanja}/javascript/dodavanjeNovogVlaka.js" ></script>
<section id="sadrzajObrasca">
    <div>
        <br>
        <form id ="form1" method="post" name="dodavanjeNovogVlaka" action="{$smarty.server.PHP_SELF}" enctype="multipart/from-data">
            <div id="greske" class="greska">
                {if (isset({$greska}))}
                    <p>{$greska}</p>
                {/if}
            </div>

            <div id="informacijaVlaka">
                <label for="nazivVlaka">Naziv vlaka: </label>
                <input class="okvirForme" type="text" id="nazivVlaka" name="nazivVlaka"
                       {if isset($popunjavanjeKodUredivanja)}
                           value="{$popunjavanjeKodUredivanja[0].naziv}"
                       {/if}
                       ><br><br>

                <label for="maxBrzina">Maksimalna brzina vlaka (km/h): </label>
                <input class="okvirForme" type="text" id="maxBrzina" name="maxBrzina" 
                       {if isset($popunjavanjeKodUredivanja)}
                           value="{$popunjavanjeKodUredivanja[0].max_brzina}"
                       {/if}
                       ><br><br>

                <label for="brojSjedala">Broj sjedala vlaka: </label>
                <input class="okvirForme" type="text" id="brojSjedala" name="brojSjedala" 
                       {if isset($popunjavanjeKodUredivanja)}
                           value="{$popunjavanjeKodUredivanja[0].broj_sjedala}"
                       {/if}
                       ><br><br>

                <label for="opisVlaka">Opis vlaka: </label>
                <input class="okvirForme" type="text" id="opisVlaka" name="opisVlaka" 
                       {if isset($popunjavanjeKodUredivanja)}
                           value="{$popunjavanjeKodUredivanja[0].opis}"
                       {/if}
                       ><br><br>

                <label for="vrstaPognona">Vrsta pogona:</label><br>
                <select name="vrstaPognona" id="vrstaPognona" class="prikazDropDown">
                    <option value="0" class="prikazDropDown">Odaberite</option>
                    {section name=i loop=$vrstaPogona}
                        <option value="{$vrstaPogona[i].id}" class="prikazDropDown"
                                {if isset($popunjavanjeKodUredivanja) AND $popunjavanjeKodUredivanja[0].vrsta_pogona_id == $vrstaPogona[i].id}
                                    selected
                                {/if}
                                >
                            {$vrstaPogona[i].naziv_pogona}
                        </option>
                    {/section}
                </select>
                <br><br>
            </div>

            <div id="informacijeNovogPogona" style="display: none">

                <label for="noviNazivPogona">Naziv novog pogona: </label>
                <input class="okvirForme" type="text" id="noviNazivPogona" name="noviNazivPogona" value="nijePopunjeno"><br><br>

                <label for="noviOpisPogona">Opis novog pogona: </label>
                <input class="okvirForme" type="text" id="noviOpisPogona" name="noviOpisPogona" value="nijePopunjeno"><br><br>

            </div>

            <div class="gumbPrijava" style="float: right" onclick="dalje()" id="noviPogon">Želite li dodati novi pogon?</div>
            <div class="gumbPrijava" style="float: right;display: none;" id="natrag" onclick="unazad()">Vrati se</div>

            <input class="gumbPrijava" type="submit" name="dodavanjeNovogVlaka" value="Dodaj vlak">
            <input class="gumbPrijava" type="submit" name="odustani" value="Odustani">
            </p>
        </form>
        <br>
    </div>
</section>
