<?php
if ($action == "title")
  echo __("Soubory");

else {
  echo "<h1>".__("Soubory")."</h1>";

  // ověření hodnosti
  if (!check_user2("zobrazeni_souboru")) {
    if ($_SESSION["log"])
      echo "<p class=\"error\">".__("Pro zobrazení nemáte dostatečná práva.")."</p>";
    else
      echo "<p>".__("Pro zobrazení se musíte <a href=\"uzivatele/registrace\">zaregistrovat</a>.")."</p>";
  }
  else {
    list($sort) = get_settings("files_sort", "row");

    function browse_folder($slozka) {
      if (!isSet($_GET["filter"])) $filter = "*";
      else $filter = "{".$_GET["filter"]."}";

      if (function_exists("glob")) $glob = glob($slozka.$filter, GLOB_BRACE);
      else $glob = glob_alternative($slozka, $filter, GLOB_BRACE);

      foreach ($glob as $soubor) {
        if (is_file($soubor)) {
          $name = str_replace($slozka, null, $soubor);
          $size = filesize($soubor) / 1024;
          echo ("<tr><td>".$name."</td><td><a href=\"".$soubor."\" target=\"_blank\">".$soubor."</a></td><td>".round($size, 1)." kB</td></tr>");
        }
        elseif (is_dir($soubor))
          browse_folder($soubor."/");
      }
    }

    $slozka = "soubory/";
    $filter = (!isSet($_GET["filter"]) ? "*" : "{".$_GET["filter"]."}");

    echo "<table><thead><tr><th style=\"width: 240px;\">".__("Jméno", "name")."</th><td style=\"width: 200px;\">".__("Cesta")."</td><td style=\"width: 100px;\">".__("Velikost")."</td></tr></thead><tbody>";
    browse_folder($slozka);
    echo "</tbody></table>";
  }
}
?>