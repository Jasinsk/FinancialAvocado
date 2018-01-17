<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Projekt php</title>
    <?php
    echo "
<script src='zmiana_stylu.js' type='text/javascript'></script>
<link rel=\"stylesheet\" type=\"text/css\" title=\"podstawowy\" href=\"style.css\">
<link rel=\"alternate stylesheet\" type=\"text/css\" title=\"alternatywny\" href=\"alternateStyle.css\">
";

    ?>
    <script type='text/javascript' src='ajax_js.js'></script>
</head>
<body><?php
include 'menu.php';
$nazwa_bl = $_GET['nazwa'];
if(!($nazwa_bl=="") && file_exists("./".$nazwa_bl)){
    wyświetl($nazwa_bl);
}
else{
    if(!(file_exists("./".$nazwa_bl))) {
        echo "Nie istnieje blog o podanej nazwie...";
    }
    else{
        echo "Nie podano nazwy bloga...<br>";
        echo "Blogi dostępne:<br>";
        foreach(scandir('.') as $file)
            if(is_dir($file)) {
                echo "<ul>";
                if ($file != '.' && $file != '..') {
                    echo "<li>" . "<a href='blog.php?nazwa=" . $file . "'>" . $file . "</a> </li>";
                }
                echo "</ul>";
            }
    }
}

function wyświetl($nazwa_bl)
{
    echo "<h1> Blog " . $nazwa_bl . "</h1>";
    include 'ajaxHelp.php';
    echo "<input id='nazwa_bloga' type='hidden' value='".$nazwa_bl."'>";
    echo "<h2>Opis bloga: </h2>";
    $file = fopen('./' . $nazwa_bl . '/info', 'r');
    fgets($file);
    fgets($file);
    echo "<p>";
    while (!feof($file)) {
        echo fgets($file) . "<br>";
    }
    fclose($file);
    echo "</p>";
    foreach (scandir("./" . $nazwa_bl . "/") as $file) {
        if (strlen($file) == 16) {
            $czas = substr($file, 0, 4) . "-" . substr($file, 4, 2) . "-" . substr($file, 6, 2) . ", " . substr($file, 8, 2) . ":" . substr($file, 10, 2);
            echo "<h3> Wpis (" . $czas . ")</h3>";
            $fileop = fopen('./' . $nazwa_bl . '/'.$file, 'r');
            echo "<p>";
            while (!feof($fileop)) {
                echo fgets($fileop) . "<br>";
            }
            fclose($fileop);
            echo "</p>";
            foreach (scandir("./" . $nazwa_bl . "/") as $item) {
                if (substr($item, 0, 16) == $file && $item != $file && $item != $file.".k") {
                    echo "<a href='./".$nazwa_bl."/".$item . "'>"."Załącznik nr ".substr($item,16,1)."</a><br>";
                }
            }
            if (is_dir("./" . $nazwa_bl . "/" . $file . ".k")) {
                echo "<h4> Komentarze: </h4>";
                foreach (scandir("./" . $nazwa_bl . "/" . $file . ".k") as $comfile) {
                    if (is_numeric($comfile)) {
                        $commentfile = fopen("./" . $nazwa_bl . "/" . $file . ".k/" . $comfile, "r");
                        $typ = fgets($commentfile);
                        $data = fgets($commentfile);
                        $komentujący = fgets($commentfile);
                        $treść = fgets($commentfile);
                        fclose($commentfile);
                        echo "
                        Data: " . $data . "<br>
                        Typ: " . $typ . "<br>
                        Komentujący: " . $komentujący . "<br>
                        Treść: " . $treść . "
                    <br>
                    <br>
                ";
                    }

                }
            }
            echo "
    <form action='form3.php' method='get'>
        <input type='hidden' name='NAZWA_BLOGA' value='".$nazwa_bl."'/>
        <input type='hidden' name='NAZWA_WPISU' value='".$file."'/>
        <input type='submit' value='Skomentuj'/>
    </form>
    <br>
    ";
        }
    }
}
?>
</body>
</html>
