<?php
if (isSet($_GET["co"])) {
  switch ($_GET["co"]) {
    case "novy_clanek":
      include ("admin/novy_clanek.php");
      break;
    case "publikovane":
      include ("admin/publikovane.php");
      break;
    case "koncepty":
      include ("admin/koncepty.php");
      break;
    case "poradnik":
      include ("admin/poradnik.php");
      break;
    case "nova_kategorie":
      include ("admin/nova_kategorie.php");
      break;
    case "upravit":
      include ("admin/upravit_kategorie.php");
      break;
    case "administrace":
      include ("admin/nastaveni_administrace.php");
      break;
    case "clanky":
      include ("admin/nastaveni_clanky.php");
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
?>