<link rel="stylesheet" href="{$putanja}/CSS/prikaz_izlozbe.css"/>

<section>
    {if isset($izlozbe)}
        <h2>
            Prikaz vaših tema vlakova
        </h2>

        <div class="resetka">
            {section name=i loop=$izlozbe}
                <div style="margin-bottom: 20px; max-width: 400px">
                    <div class="izlozbaVlakova">
                        <div class="slika">
                            {assign "nemaSlike" "ne"}
                            {foreach from=$izborSlike key=key item=val}
                                {if $izlozbe[i].tematika == $val}
                                    <img src="{$putanja}/multimedija/prikazTeme/{$key}.jpg" alt="{$key}" width="100%">
                                    {assign "nemaSlike" "da"}
                                {/if}
                            {/foreach}
                            {if $nemaSlike == "ne"}
                                <img src="{$putanja}/multimedija/prikazTeme/ostalo.jpg" alt="{$izlozbe[i].tematika}" style="width: 100%;margin: 0; padding: 0;">
                            {/if}
                        </div>
                        <div class="naslov">
                            {$izlozbe[i].tematika}
                        </div>
                        <div>
                            <div class="podkategorije">
                                {$izlozbe[i].opis}
                            </div>
                            <div class="podkategorije2">
                                {if isset($izlozbe[i].vazi_do)}
                                    {if !empty({$izlozbe[i].vazi_do})}
                                        Ističe Vam do: {$izlozbe[i].vazi_do|date_format: "%d.%m.%Y."}
                                    {else}
                                        Nemate vremenski rok
                                    {/if}
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            {/section}
        </div>
        <br>
        {if isset($ukupanBrojTematike)}
            <h3>
                Ukupni broj tema vlakova iznosi: {$ukupanBrojTematike}
            </h3>
            <br>
        {/if}
    {/if}
    {if isset($izlozbeModeratora)}
        <h2>
            Prikaz Vaših izložba vlakova
            <form class="prikazForme" style="width: 21%; padding-right: 3%">
                <input class="dodaj" type="submit" name="dodajNovuIzlozbu" value="Dodaj novu izložbu">
            </form>
        </h2>
        <div class="resetka" style="margin-bottom: 20px;width: 100%">
            {section name=i loop=$izlozbeModeratora}
                <div style="margin-bottom: 20px; max-width: 400px">
                    <div class="izlozbaVlakova">
                        <div class="slika">
                            {assign "nemaSlike" "ne"}
                            {foreach from=$izborSlike key=key item=val}
                                {if $izlozbeModeratora[i].naziv == $val}
                                    <img src="{$putanja}/multimedija/prikazTeme/{$key}.jpg" alt="{$key}" style="width: 100%;margin: 0; padding: 0;">
                                    {assign "nemaSlike" "da"}
                                {/if}
                            {/foreach}
                            {if $nemaSlike == "ne"}
                                <img src="{$putanja}/multimedija/prikazTeme/ostalo.jpg" alt="{$izlozbeModeratora[i].naziv}" style="width: 100%;margin: 0; padding: 0;">
                            {/if}
                        </div>
                        <div class="naslov">
                            {$izlozbeModeratora[i].naziv}
                        </div>
                        <br>
                        <div>
                            <div class="podkategorije">
                                Datum početka izložbe: {$izlozbeModeratora[i].datum_pocetka|date_format: "%d.%m.%Y <br>otvaranje: %H:%M"}
                            </div>
                            <div class="podkategorije">
                                Maximalni broj korisnika: {$izlozbeModeratora[i].broj_korisnika}
                            </div>
                            <div class="podkategorije">
                                Status izlžbe: {$izlozbeModeratora[i].status}
                            </div>
                        </div>
                        {if $izlozbeModeratora[i].status == "Otvorene prijave"}
                            <div style="float: bottom">
                                <form class="prikazForme">
                                    <button type="submit" name="urediZapisIzlozbe" class="dodaj" value="{$izlozbeModeratora[i].id}">Uredi</button>
                                </form>
                                <form class="prikazForme">
                                    <button type="submit" name="izbrisiIzlozbu" class="dodaj" value="{$izlozbeModeratora[i].id}">Obrisi</button>
                                </form>
                            </div>
                        {/if}
                    </div>
                </div>
            {/section}
        </div>
        {if isset($ukupnoBrojIzlozbi)}
            <h3>
                Ukupni broj izložbi iznosi: {$ukupnoBrojIzlozbi}
            </h3>
        {/if}
    {/if}
</section>
