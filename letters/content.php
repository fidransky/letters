<?php
if (!isset($_GET["page"])) include ("content/nastenka.php");

elseif ($_GET["page"] == "about") include ("content/about.php");

elseif ($_GET["page"] == "pluginy") {
  if ($_GET["co"] == "katalog") include ("content/pluginy_katalog.php");
  elseif ($_GET["co"] == "aktualizace") include ("content/pluginy_update.php");
  elseif ($_GET["co"] == "editor") include ("content/pluginy_editor.php");
  else include ("content/pluginy.php");
}

elseif ($_GET["page"] == "vzhled") {
  if ($_GET["co"] == "index") include ("content/nastaveni_index.php");
  elseif ($_GET["co"] == "mainmenu") include ("content/nastaveni_mainmenu.php");
  elseif ($_GET["co"] == "layout") include ("content/vzhled_rozlozeni.php");
  elseif ($_GET["co"] == "katalog") include ("content/vzhled_katalog.php");
  elseif ($_GET["co"] == "aktualizace") include ("content/vzhled_update.php");
  elseif ($_GET["co"] == "editor") include ("content/pluginy_editor.php");
  else include ("content/nastaveni_vzhled.php");
}

elseif ($_GET["page"] == "nastaveni" and check_user2("nastaveni", true)) {
  if ($_GET["co"] == "detaily") include ("content/nastaveni_detaily.php");
  elseif ($_GET["co"] == "udrzba") include ("content/nastaveni_udrzba.php");
  elseif ($_GET["co"] == "administrace") include ("content/nastaveni_administrace.php");
}

elseif ($_GET["page"] == "test") include ("content/test.php");
?>