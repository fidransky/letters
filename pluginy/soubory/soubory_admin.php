<?php
switch ($_GET["co"]) {
  case "novy_soubor":
    include ("admin/novy_soubor.php");
    break;
  case "prohlizec":
    include ("admin/prohlizec.php");
    break;
  case "soubory":
    include ("admin/nastaveni_soubory.php");
    break;
  case "uzivatele":
    include ("admin/nastaveni_uzivatele.php");
    break;
}
?>