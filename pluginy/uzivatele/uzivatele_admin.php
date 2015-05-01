<?php
if (isSet($_GET["page"]) and $_GET["page"] == "clanky")
  include ("admin/clanky.php");

else {
  if (isSet($_GET["co"])) {
    switch ($_GET["co"]) {
      case "profil":
        include ("admin/profil.php");
        break;
      case "prehled":
        include ("admin/prehled.php");
        break;
      case "novy_uzivatel":
        include ("admin/novy_uzivatel.php");
        break;
      case "detaily":
        include ("admin/nastaveni_detaily.php");
        break;
      case "index":
        include ("admin/nastaveni_index.php");
        break;
      case "mainmenu":
        include ("admin/nastaveni_mainmenu.php");
        break;
      case "uzivatele":
        include ("admin/nastaveni_uzivatele.php");
        break;
    }
  }
  else
    include ("admin/nastenka.php");
}
?>