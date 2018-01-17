<?php
echo "
    <input id='checkActive' type='checkbox' onchange='aktywuj(); włącz();'> Aktywuj/Dezaktywuj komunikator
    <div id='activate' style='visibility: hidden;'>
        <textarea readonly id='komunikator' rows='10' cols='30'></textarea><br>
        Twoje imię:
        <input id='imie' type='text'><br>
        Komunikat:
        <input id='komunikat' type='text'> <br>
        <input type='button' value='Wyślij' onclick='wyślij_komunikat(); usuń_komunikat();' >
    </div>
";
?>