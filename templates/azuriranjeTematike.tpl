<link rel="stylesheet" href="{$putanja}/CSS/prikaz_forme.css"/>
<section id="sadrzajObrasca">
    <div>
        <form id ="form1" method="post" name="dodavanjeNoveTematike" action="{$smarty.server.PHP_SELF}">
            
            <div id="greske" class="greska">
                {if (isset({$greska}))}
                    <p>{$greska}</p>
                {/if}
            </div>

            <label for="nazivTematike">Naziv tematike: </label>
            <input class="okvirForme" type="text" id="nazivTematike" name="nazivTematike"
                   {if isset({$naziv})}
                       value="{$naziv}"
                   {/if}
                   ><br><br>
            <label for="OpisTematike">Opis tematike: </label>
            <input class="okvirForme" type="text" id="OpisTematike" name="opisTematike"
                   {if isset({$opis})}
                       value="{$opis}"
                   {/if}
                   ><br><br>

            <input class="gumbPrijava" type="submit" name="dodajTematiku" value="Spremi">
            <input class="gumbPrijava" type="submit" name="odustani" value="Odustani">
            
        </form>
    </div>
</section>
