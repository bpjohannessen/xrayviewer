<?php
// counter
session_start();
$counter_name = "counter.txt";
// Check if a text file exists. If not create one and initialize it to zero.
if (!file_exists($counter_name)) {
    $f = fopen($counter_name, "w");
    fwrite($f,"0");
    fclose($f);
}
// Read the current value of our counter file
$f = fopen($counter_name,"r");
$counterVal = fread($f, filesize($counter_name));
fclose($f);
// Has visitor been counted in this session?
// If not, increase counter value by one
if(!isset($_SESSION['hasVisited'])){
    $_SESSION['hasVisited']="yes";
    $counterVal++;
    $f = fopen($counter_name, "w");
    fwrite($f, $counterVal);
    fclose($f);
}

//echo "You are visitor number $counterVal to this site";

?>
<!doctype html>
<html>
<meta charset='UTF-8'><meta name='viewport' content='width=device-width initial-scale=1'>
<head>
    <title>FETTENAJS XRAY VIEWER</title>
    <style type="text/css">

        * {
            font-family: Verdana;
        }

        div {
            display: none;
        }

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js">

    </script>
</head>
<body style="width: 1440px;">
<center>Please be advised: It may take some time to load this page (65.5 MB)</center>
<?php
/**
 * Created by PhpStorm.
 * User: bp
 * Date: 13.11.2016
 * Time: 22.05
 */

$src = "./src/";

$folders = array_diff(scandir($src), array('..', '.'));

sort($folders, SORT_NUMERIC);

echo "<table style='border: 1px solid black; width: 1400px;'>";

$key = 1;

foreach($folders as $folder) {
    $scanfile = array_diff(scandir($src.$folder), array('..', '.', 'desc.txt'));
    $fo = fopen($src.$folder."/desc.txt", "r");
        $fr = fread($fo, filesize($src.$folder."/desc.txt"));
        fclose($fo);

    echo "<tr><td colspan='2' style='border-bottom: 2px solid black; border-top: 2px solid black;'>";

    if(@$_GET["hidden"]==1) {
        $header = "<a href='#' onclick='$(hiddenText".$key.").show(); return false;'>".$key."</a><span style=\"display: none;\" id=\"hiddenText".$key."\"> - ".$fr."</span>";
    } else {
        
        $header = $folder." - ".$fr;


        //$header = $folder;
    }

    echo "<h3 id='".$key."'>".$header."</h3>";
    echo "</td></tr>";
    echo "<tr><td style='padding-bottom: 20px;'>";

    foreach($scanfile as $file) {


        // File and new size
        $filename = $src.$folder."/".$file;
        $percent = 0.2;

        list($width, $height) = getimagesize($filename);

        if($width > 600) {
            $newwidth = $width * $percent;
            $newheight = $height * $percent;
        } else {
            $newheight = $height;
            $newwidth = $width;
        }

        echo "<a href='".$filename."'><img src='".$src.$folder."/".$file."' height='".$newheight."'></a>";

    }
    echo "</td></tr>";
    //echo "<br>";
    $key++;

}

echo "</table>";

?>
</body>
</html>
