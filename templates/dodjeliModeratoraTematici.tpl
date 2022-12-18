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

            <label for="odabirModeratoraTematike">Odaberite moderatora za tematiku vlakova:</label><br>
            <select name="odabirModeratoraTematike" id="odabirModeratoraTematike" class="prikazDropDown">
                <option value="0" class="prikazDropDown">Odaberite</option>
                {section name=i loop=$redModeratora}
                    <option value="{$redModeratora[i].id}" class="prikazDropDown"
                            {if isset($moderatorTematikeVlakova)}
                                {if $moderatorTematikeVlakova == $redModeratora[i].id}}
                                    selected
                                {/if}
                            {/if}
                            >
                        {$redModeratora[i].ime} {$redModeratora[i].prezime} - {$redModeratora[i].korisnicko_ime}</option>
                    {/section}
            </select><br><br>

            <label for="odabirTematike">Odaberite postojeću tematiku vlakova:</label><br>
            <select name="odabirTematike" id="odabirTeamtike" class="prikazDropDown">
                <option value="0" class="prikazDropDown">Odaberite</option>
                {section name=i loop=$redTematike}
                    <option value="{$redTematike[i].id}" class="prikazDropDown"
                            {if isset($tematikaVlakova)}
                                {if $tematikaVlakova == $redTematike[i].id}}
                                    selected
                                {/if}
                            {/if}
                            >{$redTematike[i].naziv}</option>
                {/section}
            </select><br><br>

            <label for="datumOd">Unesite datum od kada vrijedi zadani moderator:</label><br>
            <input type="date" id="datum" name="datumOd" class="okvirForme2"
                   {if isset($datumOd)}
                       value="{$datumOd}"
                   {/if}
                   ><br><br>

            <label for="datumDo">Unesite datum do kada vrijedi zadani moderator:</label><br>
            <input type="date" id="datum" name="datumDo" class="okvirForme2"
                   {if isset($datumDo)}
                       value="{$datumDo}"
                   {/if}
                   >
            <br><br><br>


            <input class="gumbPrijava" type="submit" name="spremi" value="Spremi">
            <input class="gumbPrijava" type="submit" name="odustani" value="Odustani">
            </p>
        </form>
        <br>
    </div>
</section>
