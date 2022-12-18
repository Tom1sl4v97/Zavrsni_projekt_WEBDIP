        <footer>
            <br>
            <a>Kontakt: </a>
            <a href="{$putanja}/autor.php">
                <b>Tomislav Tomiek</b>
            </a>
            <a>&copy; 2021</a>
            <br>
            <span>
                <strong>Trenutna razina izvještavanja: </strong>
                {error_reporting()}
                |
                <strong>Vrijeme sustava: </strong>
                {date("d.m.Y. H:i:s")}
                |
                <strong>Virtualno vrijeme sustava: </strong>
                {$konfiguracija}
            </span>
            
            <br>
            <a href="">
                <img src = "{$putanja}/multimedija/HTML5.png" alt = "HTML validator" width = "40" style = "margin:0px 5px">
            </a>
            <a href = "">
                <img src = "{$putanja}/multimedija/CSS3.png" alt = "CSS validator" width = "40" style = "margin:0px 5px">
            </a>
            <br><br>
        </footer>
    </body>
</html>