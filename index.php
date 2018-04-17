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
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>FETTENAJS X-ray viewer</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/skeleton.css">
  <link rel="stylesheet" href="css/magnific-popup.css">

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="images/favicon.png">
  
  <!-- JS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="js/magnificpopup.js"></script>
  <script>
$(document).ready(function() {
    $('.popup-gallery').each(function() {
    $(this).magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			titleSrc: function(item) {
				return item.el.attr('title') + '<small>FETTENAJS X-ray viewer</small>';
			}
		}
    });
});
});

</script>

</head>
<body>
<div class="container" style="margin-top: 20px;">
    <div class="row">
        <div class="two columns"></div>
        <div class="ten columns">
            <h4>FETTENAJS X-ray viewer</h4>
            <p style="font-weight: bold;">Please be advised: It may take some time to load this page (65.5 MB).</p>
            <p>PRO TIP #1: Click on the images for zoom and gallery function.</p>
            <?php
            if(@$_GET["hidden"]==0) {
                echo "<p>PRO TIP #2: <a href='?hidden=1'>Remove the diagnosis to the cases</a></p>";
            } else {
                echo "<p>PRO TIP #2: <a href='?hidden=0'>Show the diagnoses to the cases</a><br>";
                echo "PRO TIP #3: Click on the case #number to show the diagnosis</p>";
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="two columns"></div>
        <div class="ten columns">

<?php

$src = "./src/";
$folders = array_diff(scandir($src), array('..', '.'));
sort($folders, SORT_NUMERIC);

echo "<table>";

$key = 1;

foreach($folders as $folder) {
    $scanfile = array_diff(scandir($src.$folder), array('..', '.', 'desc.txt'));
    $fo = fopen($src.$folder."/desc.txt", "r");
        $fr = fread($fo, filesize($src.$folder."/desc.txt"));
        fclose($fo);
    echo "<thead>";
    echo "<tr><th colspan='2'>";

    if(@$_GET["hidden"]==1) {
        $header = "<a href='#' onclick='$(hiddenText".$key.").show(); return false;'>#".$key."</a><span style=\"display: none;\" id=\"hiddenText".$key."\"> - ".$fr."</span>";
        $imgTitle = "CASE #".$folder;
    } else {
        $header = "#".$folder." - ".$fr;
        $imgTitle = "CASE #".$folder." - ".$fr;
    }

    echo "<h5 id='".$key."'>".$header."</h5>";
    echo "</th></tr>";
    echo "</thead>";

    echo "<tbody class='popup-gallery'>";
    echo "<tr>";

    $x = count($scanfile);
    $y = $x;

    foreach($scanfile as $file) {

        $countXrays = count($scanfile);

        // File and new size
        $filename = $src.$folder."/".$file;

        echo "<td>";
  
        echo "<a href='".$filename."' title='".$imgTitle."'><img src='".$src.$folder."/".$file."' style='width: 100%; height: auto;'></a>";
        $y = $y - 1;
        if($y % 2 == 0) {
            echo "</td></tr>";
            if($y != 0) echo "<tr>";
        } else {
            echo "</td>";
        }

    }

    echo "</tbody>";
    $key++;

}

echo "</table>";

?>

</div>
</div>
</div>
</body>
</html>