<link rel="stylesheet" href="{$putanja}/CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/popUp_prozor.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/popUp_pomoc_prijave.css"/>

<script src="../javascript/prikazPomoci.js"></script>
<script src="{$putanja}/javascript/prikazPodatakaKolacicaPrijava.js"></script>

<section id="sadrzajObrasca">
    <div id="kutijaDizajnaPomoci">
        <button type="submit" style="background-color: transparent; border: none;">
            <img src="{$putanja}/multimedija/upitnik.png" class="accessibility"/>
        </button>
    </div>
    <div>
        <div class="popUpPomocCSS" id="popUpPomoc">
            <div class="popUpPomocPozadina">
                Trebate li pomoć?<br><br>
                <div id="pomocDa1" style="float: left;width: 100px;">
                    Da
                </div>
                <div id="pomocNe1" style="float: left;width: 100px">
                    Ne
                </div>
                <br>
            </div>
        </div> 
        <div id="pravokutnik1" class="pravokutnik1">
            Ovo je navigacija
        </div>
        <div id="pravokutnik2" class="pravokutnik2">
            Ovjde se vrši unos podataka.
        </div>
        <div id="pravokutnik3" class="pravokutnik3">
            Unos postojećeg korisničkog imena.
        </div>
        <div id="pravokutnik4" class="pravokutnik4">
            Unos lozinke korisnika.
        </div>
        <div id="pravokutnik5" class="pravokutnik5">
            Zapamćuje korisničko ime korisnika.
        </div>
        <div id="pravokutnik6" class="pravokutnik6">
            Ako korisnik ne posijeduje račun,<br> mora kliknuti na "nemate račun?"
        </div>
        <div id="pravokutnik7" class="pravokutnik7">
            Kada ste sve popunili, morate<br>kliknuti na gumb "Prijavi se" 
        </div>
        
        <form novalidate name="prijava" id ="form1" method="get" action="{$smarty.server.PHP_SELF}">
            <div id="greske" class="greska">
                {if (isset({$greska}))}
                    <p>{$greska}</p>
                {/if}
            </div>

            <label for="korime">Korisničko ime: </label>
            <input class="okvirForme" type="text" id="korime" name="korime">
            <br><br>
            <label for="lozinka">Lozinka: </label>
            <input class="okvirForme" type="password" id="lozinka" name="lozinka">
            <br><br>
            <input class="gumbPrijava" type="submit" name="registrirani_korisnik" value="Registrirani korisnik">
            <input class="gumbPrijava" type="submit" name="moderator"  value="Moderator">
            <input class="gumbPrijava" type="submit" name="administrator"  value="Administrator"><br><br>
            <label for="zapamtiMe"> Zapamti me</label>
            <input type="checkbox" id="zapamtiMe"  name="zapamtiMe" value="da" style="width: 18px;height: 18px">
            <br><br>

            <a href="{$putanja}/obrasci/registracija.php"> Nemate račun?</a>
            <br><br>

            <input class="gumbPrijava" type="submit" name="prijava_korisnika" value="Prijavi se">
            <br><br>
        </form>
    </div>
</section>
