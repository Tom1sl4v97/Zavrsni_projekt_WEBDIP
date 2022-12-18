<link rel="stylesheet" href="{$putanja}/CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/popUp_prozor.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/popUp_pomoc_registracije.css"/>

<script src="../javascript/prikazPomoci.js"></script>

<script src='https://www.google.com/recaptcha/api.js' async defer></script>
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
            Unos imena i prezimena, po želji.
        </div>
        <div id="pravokutnik4" class="pravokutnik4">
            Unos kor. imena, mora biti jedinstven.
        </div>
        <div id="pravokutnik5" class="pravokutnik5">
            Unos e-mail adrese.
        </div>
        <div id="pravokutnik6" class="pravokutnik6">
            Unos lozinke i moraju se podudarati.
        </div>
        <div id="pravokutnik7" class="pravokutnik7">
            Kada ste sve popunili, morate<br>kliknuti na gumb "Registriraj se" 
        </div>

        <form id ="form1" method="post" name="form1" action="{$smarty.server.PHP_SELF}">

            <div id="greske" class="greska">
                {if (isset({$greska}))}
                    <p>{$greska}</p>
                {/if}
            </div>

            <label for="ime">Ime: </label>
            <input class="okvirForme" type="text" id="ime" name="ime"><br><br>
            <label for="prezime">Prezime: </label>
            <input class="okvirForme" type="text" id="prez" name="prezime"><br><br>
            <label for="korisnicko ime">Korisničko ime: </label>
            <input class="okvirForme" type="text" id="korime" name="korisnicko_ime"><br><br>
            <label for="email">Email adresa: </label>
            <input class="okvirForme" type="email" id="email" name="email"><br><br>
            <label for="lozinka1">Lozinka: </label>
            <input class="okvirForme" type="password" id="lozinka1" name="lozinka1"><br><br>
            <label for="lozinka2">Ponovi pozinku: </label>
            <input class="okvirForme" type="password" id="lozinka2" name="lozinka2"><br><br>
            <div style="text-align: center;" class="g-recaptcha" data-sitekey="6Le-b0YbAAAAALtyWqFI9_XNew2WIFRaoq_3Y_Jg"></div><br>

            <input class="gumbPrijava" type="submit" style="width: 47%;padding: 2%;" name="registracija" value=" Registriraj se ">
            <input class="gumbPrijava" type="submit" style="width: 47%;padding: 2%;" name="vratiSe" value=" Vrati se "><br><br>
        </form>
    </div>
</section>
