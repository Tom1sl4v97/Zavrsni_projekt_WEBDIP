<script src="javascript/prikazPomoci.js"></script>
<link rel="stylesheet" href="{$putanja}/CSS/prikaz_izlozbe.css"/>

<section>
    <h3>
        Opis projektnog zadatka:
    </h3>
    <p>
        Kratak opis projekta:
        Sustav služi za upravljanje izložbom vlakova.<br>
        Uloge: Neregistrirani korisnik, Registrirani korisnik, Moderator, Administrator.<br>
        Za detaljnije upute poledajte PDF dokument.
    <p>
    <h3>
        Opis projektnog rješenja:
    </h3>
    <p>
        Što se tiče cijele funkcionalnosti projekta radi sve kako treba, sve glavne funkcionalnosti projekta sam uspio napraviti. Nedostaju neke sitnice
        poput nekih validacija klijent, zaboravljena lozinka, traženje pojmova po stranici, printanje PDF dokumenata, kreiranje grafova na početnoj stranici,
        vlastito rješenje za straničenja podataka, nekih filtriranja podataka, SEO links, zaštita XSS i SQL ubacivanje, izvještaj testiranja aplikacije
    <p>
    <h3>
        Bitne odrednice projektnog rješenja (ERA model):
    </h3>
    <div style="width: 100%;">
        <div>
            <img src="{$putanja}/multimedija/ERA_model.png" style="width: 100%;">
            <p>ERA Model</p>
        </div>
    </div>
    <h3>
        Popis i opis skripata, mapa mjesta, navigacijski dijagram:
    </h3>
    <p>
        Koristio sam sve vlastite java scripte, osim za dohvat podataka kod json formata. 
        <br>JS scripte: dodavanjeNovogVlaka.js je za prikazivanje popup prozora kod dodavanja korisnikovog vlaka na izložbu
        <br>JS scripte: odabirIzlozbeZaVlak.js je glavna skripa kod prikazivanja detalja o izlozbama na stranici izlozbe.php. Ona služi uglavnom za prosljeđivanje
        mi svih potrebnih podataka, te kontrolu za prikazivanje gumbića i slično.
        <br>JS scripte: prikazPodatakaKolacicaPrijava.js dohvaćuje mi samo podatke iz kolacica za ispunjavanje podataka na stranici prijava.php
        <br>JS scripte: prikazPomoci.js služi za pokretanje postepene pomoći, otvaranja i zatvaranje određenih blokova stranice. Klikom na upitnik u gornjem lijevom
        uglu stranice, pomoć mi se nalazi na stranicama: prijava, registracija i autor.
        <br>JS scripte: provjeraStatusaPrihvacenosti.js uglavnom mi služi za podešavanje cookia na stranici index.php kod prihvaćivanja uvjeta korištenja.
        <br>JS scripte: tablica služi samo za učitavanja gotovog rješenja DataTable za straničenje podataka.
        <br>JS scripte: vlakoviAPI uglavnom za izvršavanje AJAXovih zahtjeva i JQuery.
        <br><br> Mapa mjesta:
        <br> mapa CSS mi sadrži sve stilove i cijeli dizajn stranice.
        <br> mapa administrator mi sadrži php stranice koje se nadovezuju samo za administratora.
        <br> mapa izvorne_datoteke pohranjujem sve postavke stranice i sigurosne kopije stranice, osim json formata virtualnog vremena, koji se nalazi u mapi json.
        <br> mapa javascript mi se nalaziju sve js scripte.
        <br> mapa korisnik su php stranice koje se nadovezuju za registriranog korisnika.
        <br> mapa multimedija sadrži sav multimedijski zapis cijele stranice, razvrstano po korisničkim imenima i formatu zapisa.
        <br> mapa obrasci sadrži mi php stranice za prijavu i registraciju.
        <br> mapa templates mi sadrži sav HTML kod za svaku stranicu posebno.
        <br> Ostale php stranice mi se nalaziju u početnoj malpi ttomiek.
        <br><br> Alate koje sam koristio su NerBeans IDE, MySql Workbench, XAMP, FileZila, Putty i PHPMyAdmin
        <br><br> Koristio sam google recaptcha za potvrdu kod registracije računa.
        <br><br> Navigacijski dijagrami: 
    <p>
    <h3>
        Neregistrirani korisnik
    </h3>
    <div style="width: 100%;">
        <div>
            <img src="{$putanja}/multimedija/neregistrirani_korisnik.png" style="width: 100%;">
        </div>
    </div>
    <h3>
        Moderator
    </h3>
    <div style="width: 100%;">
        <div>
            <img src="{$putanja}/multimedija/moderator.png" style="width: 100%;">
        </div>
    </div>
    <h3>
        Administrator
    </h3>
    <div style="width: 100%;">
        <div>
            <img src="{$putanja}/multimedija/administrator.png" style="width: 100%;">
        </div>
    </div>


</section>
