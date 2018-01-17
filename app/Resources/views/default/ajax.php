<?php

if (!is_dir($_GET["nazwa_bloga"]."/komunikator")) {
    mkdir($_GET["nazwa_bloga"]."/komunikator", 0777);
}
usuń_ew();
if (strlen($_GET["imie"]) > 0){
    $plik_komunikat = fopen($_GET["nazwa_bloga"]."/komunikator"."/".time(), "w");
    fwrite($plik_komunikat, $_GET["imie"]."\n");
    fwrite($plik_komunikat, $_GET["komunikat"]);
    fclose($plik_komunikat);
}
wyślij();

function usuń_ew() {
    $komunikaty = array_filter(scandir($_GET["nazwa_bloga"]."/komunikator"), function($item) {
        return !is_dir($_GET["nazwa_bloga"]."/komunikator/" . $item);
    });
    if (count($komunikaty) > 10) {
        unlink($_GET["nazwa_bloga"]."/komunikator/".$komunikaty[2]);
    }
}

function wyślij() {
    $komunikaty = array_filter(scandir($_GET["nazwa_bloga"]."/komunikator"), function($item) {
        return !is_dir($_GET["nazwa_bloga"]."/komunikator/" . $item);
    });
    $wynik = "";
    foreach ($komunikaty as $komunikat) {
        $lines = file($_GET["nazwa_bloga"]."/komunikator/".$komunikat, FILE_IGNORE_NEW_LINES);
        $wynik = $wynik.$lines[0].": ";
        $wynik = $wynik.$lines[1]."\n";
    }
    echo $wynik;
}
?>