<h1>Prohlížeč</h1>

<?php
check_user2("zobrazeni_souboru", true);

function browse_folder($slozka) {
  if (!isSet($_GET["filter"])) { $filter = "*"; }
  else { $filter = "{".$_GET["filter"]."}"; }

  if (function_exists("glob")) { $glob = glob($slozka.$filter, GLOB_BRACE); }
  else { $glob = glob_alternative($slozka, $filter, GLOB_BRACE); }

  static $files = array();
  foreach ($glob as $file) {
    if (is_file($file)) {
      $files[] = $file;
    }
    elseif (is_dir($file)) {
      browse_folder($file."/");
    }
  }
  
  return $files;
}
?>

<p>
Zobrazeny jsou pouze soubory nahrávané jako &bdquo;Pouze upload&ldquo;.
</p>

<?php
$slozka = "../soubory/";

// mazání souboru
if (isSet($_GET["delete"])) {
  $cesta = $slozka.$_GET["delete"];
  $delete = @unlink($cesta);
  if ($delete == true) echo "<p class=\"success\">Soubor ".$_GET["delete"]." byl úspěšně smazán.</p>";
  else echo "<p class=\"error\">Soubor ".$_GET["delete"]." nebyl smazán.</p>";
}
?>


<p>
<strong>zobrazit pouze:</strong> <a href="letters.php?page=soubory&co=prohlizec">všechny</a> / <a href="letters.php?page=soubory&co=prohlizec&filter=*.jpg,*.bmp,*.gif,*.png,*.svg,*.webp">obrázky</a> / <a href="letters.php?page=soubory&co=prohlizec&filter=*.doc,*.docx,*.xls,*.xlsx,*.ppt,*.pptx,*.pps,*.pdf,*.rtf,*.txt">dokumenty</a> / <a href="letters.php?page=soubory&co=prohlizec&filter=*.mp3,*.mp4,*.mpg,*.3gp,*.avi,*.mov,*.wmv,*.wma">multimédia</a><br>
</p>

<?php
// úvodní zobrazení
if (isSet($_GET["order"]) and $_GET["order"] == "name" and !isSet($_GET["desc"])) $qs_name = "&desc";
else $qs_name = null;

if (isSet($_GET["order"]) and $_GET["order"] == "size" and !isSet($_GET["desc"])) $qs_size = "&desc";
else $qs_size = null;

if (isSet($_GET["order"]) and $_GET["order"] == "date" and !isSet($_GET["desc"])) $qs_date = "&desc";
else $qs_date = null;
?>

<table>
<thead><tr>
  <th style="min-width: 240px;"><a href="letters.php?page=soubory&co=prohlizec&order=name<?php echo $qs_name; ?>">jméno</a></th>
  <td style="min-width: 280px;">cesta</td>
  <td style="width: 100px;"><a href="letters.php?page=soubory&co=prohlizec&order=size<?php echo $qs_size; ?>">velikost</a></td>
  <td style="width: 140px;"><a href="letters.php?page=soubory&co=prohlizec&order=date<?php echo $qs_date; ?>">poslední změna</a></td>
</tr></thead>
<?php
$files = browse_folder($slozka);

if (isSet($_GET["order"])) {
  foreach ($files as $file) {
    $name = str_replace($slozka, null, $file);
    $size = filesize($file);
    $date = filemtime($file);
    $order[$file] = $$_GET["order"];
  }

  if (isSet($_GET["desc"])) arsort($order);
  else asort($order);

  $files = array();
  foreach ($order as $file => $foo)
    $files[] = $file;
}

foreach ($files as $file) {
  $name = str_replace($slozka, null, $file);
  $size = filesize($file) / 1024;
  $date = filemtime($file);
  echo ("<tr><td><a href=\"letters.php?page=soubory&co=prohlizec&delete=".$name."\" title=\"smazat soubor\"><img src=\"icons/cross.png\" style=\"float: right; margin-top: 2px; border: 0;\"></a>".$name."</td><td><a href=\"".$file."\" target=\"_blank\">".substr($file, 3)."</a></td><td>".round($size, 1)." kB</td><td>".date("j. n. Y v H:i", $date)."</td></tr>");
}
?>
</table>