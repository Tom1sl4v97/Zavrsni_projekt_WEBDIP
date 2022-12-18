<link rel="stylesheet" href="{$putanja}/CSS/prikaz_forme.css"/>
<section id="sadrzajObrasca">
    <div>
        <br>
        <form id ="form1" method="post" name="dodavanjeNoveTematike" action="{$smarty.server.PHP_SELF}">
            <p>

            <div id="greske" class="greska">
                {if (isset({$greska}))}
                    <p>{$greska}</p>
                {/if}
            </div>

            <label for="odabirModeratoraTematike">Odaberite željenu temu izložbe vlaka:</label><br>
            <select name="odabirModeratoraTematike" id="odabirModeratoraTematike" class="prikazDropDown">
                <option value="0" class="prikazDropDown">Odaberite</option>
                {section name=i loop=$popisTemeIzlozbe}
                    <option value="{$popisTemeIzlozbe[i].tematika_id}" class="prikazDropDown" 
                            {if isset($urediTemu)}
                                {if $urediTemu == $popisTemeIzlozbe[i].tematika_id}}
                                    selected
                                {/if}
                            {/if}>
                        {$popisTemeIzlozbe[i].tematika}</option>
                    {/section}
            </select><br><br>
            <label for="datumPocetka">Unesite datum početka izložbe: </label>
            <input class="okvirForme2" type="datetime-local" id="datumPocetka" name="datumPocetka"
                   {if isset($urediDatum)}
                       value="{$urediDatum[0]}T{$urediDatum[1]}"
                   {/if}>
            <br><br>
            <label for="maxBrojKorisnika">Maksimalan broj korisnika: </label>
            <input class="okvirForme2" type="number" id="maxBrojKorisnika" name="maxBrojKorisnika"
                   {if isset($urediMaxKorisnika)}
                       value="{$urediMaxKorisnika}"
                   {/if}
                   ><br><br>
            
            <label for="pocetakGlasovanja">Početak glasovanja</label><br>
            <input type="date" id="datum" name="pocetakGlasovanja" class="okvirForme2"
                   {if isset($pocetakGlasovanja)}
                       value="{$pocetakGlasovanja}"
                   {/if}
                   ><br><br>
            <label for="zavrsetakGlasovanja">Završetak glasovanje</label><br>
            <input type="date" id="datum" name="zavrsetakGlasovanja" class="okvirForme2"
                   {if isset($zavrsetakGlasovanja)}
                       value="{$zavrsetakGlasovanja}"
                   {/if}
                   ><br><br>


            <input class="gumbPrijava" type="submit" name="dodajModeratoraTematike" value="Dodaj izložbu">
            <input class="gumbPrijava" type="submit" name="odustani" value="Odustani">
            </p>
        </form>
        <br>
    </div>
</section>
