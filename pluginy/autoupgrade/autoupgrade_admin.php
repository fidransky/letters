<h3>AutoUpgrade:</h3>

<?php
include_once (PLUGINS_DIR."autoupgrade/functions.php");
$verze = curl_file_get_contents(LETTERS_WEB_URL."/misc/aktualni_verze.txt");
  
// upgrade
if (isSet($_POST["posted"])) {
  $log .= logg("<b>začíná aktualizace na Letters ".$verze."</b>");
  $log .= logg("cURL je aktivní; verze PHP je vyšší 5.2.0");
  
  $stare_verze = explode("\n", curl_file_get_contents(LETTERS_WEB_URL."/misc/stare_verze.txt"));
  $stare_verze[] = $verze;

  foreach ($stare_verze as $i => $version)
    if ($lrs["letters_version"] < $version) $needed[$i] = $version;


  foreach ($needed as $i => $version) {
    $data = curl_file_get_contents(LETTERS_WEB_URL."/soubory/letters_".$version.".zip");

    if (!empty($data)) {
      $soubor = FOpen("../cache/letters_".$version.".zip", "w");   // vytvoří soubor
      @chmod("../cache/letters_".$version.".zip", 0774);   // nastaví práva pro zápis
      $save = FWrite($soubor, $data);
      FClose($soubor);

      if ($save == true) {
        global $dir, $log;
        $log .= logg("ZIP archiv Letters verze ".$version." byl úspěšně stažen do adresáře \"cache/\"");
      
        $zip = new ZipArchive;
        $res = $zip->open("../cache/letters_".$version.".zip");
        if ($res === TRUE) {
          $zip->extractTo("../cache/");
          $dir = $zip->getNameIndex(0);
          $zip->close();
          @chmod("../cache/".$dir, 0777);  //nastavení práv
          $log .= logg("ZIP archiv byl úspěšně rozbalen do složky \"cache/\"");
        }
      
        $delete = @unlink("../cache/letters_".$version.".zip");
        if ($delete == true) $log .= logg("ZIP archiv byl úspěšně smazán ze složky \"cache/\"");
      
        $log .= logg("spouštím aktualizaci součástí systému...");
        $dir = "../cache/letters_".$version;
        $move = move_tree($dir);
        if ($move == true) $log .= logg("součásti systému byly úspěšně aktualizovány");
      
        $delete2 = autoupgrade_delete_dir($dir);
        if ($delete2 == true) $log .= logg("složka aktualizace byla úspěšně smazána ze složky \"cache/\"");
      
        $delete3 = @unlink("../changelog-".substr($stare_verze[$i-1], 1).".txt");
        if ($delete3 == true) $log .= logg("changelog verze ".$stare_verze[$i-1]." byl úspěšně smazán");

        // pomocí cURL nechá proběhnout skript aktualizace
        $curl = @curl_init();
        curl_setopt($curl, CURLOPT_URL, $lrs["address"]."/instalace.php?step=update1");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($curl);
        curl_close($curl);
      
        $delete4 = @unlink("../instalace.php");
        if ($delete4 == true) $log .= logg("aktualizační skript byl úspěšně smazán");
      }
    }
  }
  $log .= logg("<b>aktualizace proběhla úspěšně</b>");
  
  echo "<div class=\"box\" style=\"width: 700px; overflow: scroll;\">".$log."</div>";
}

// úvodní zobrazení
?>

<div class="box">
<?php
echo ("Používáte Letters ".$lrs["letters_version"]."<br>");

if ($lrs["letters_version"] == $verze)
  echo "Používáte aktuální verzi.</div>";
else {
  echo "Je dostupná nová verze (".$verze."). <a href=\"".LETTERS_WEB_URL."/misc/download.php?f=letters_".$verze.".zip&t=letters\">Stáhnout&hellip;</a>";

  if (!empty($verze) and function_exists("curl_init") and phpversion() >= "5.2.0") {
?>
</div>

<div class="box">
  <form method="post">
  <input type="submit" name="posted" value="Aktualizovat na verzi <?php echo $verze; ?>">
  </form>
</div>
<?php
  }
  else
    echo "<p><small><strong>proč to nefunguje?</strong> pravděpodobně není splněna jedna z následujících podmínek: PHP vyšší verze 5.2.0, aktivní cURL, nezjištěna poslední verze</small></p>";
}
?>