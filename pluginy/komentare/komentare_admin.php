<?php
if (isSet($_GET["page"]) and $_GET["page"] == "clanky")
  include ("admin/clanky.php");

else {
  if (isSet($_GET["co"])) {
    echo "<link rel=\"stylesheet\" href=\"".PLUGINS_DIR."komentare/admin/style.css\">";

    switch ($_GET["co"]) {
      case "vsechny":
        include ("admin/vsechny.php");
        break;
      case "nove":
        include ("admin/nove.php");
        break;
      case "schvalene":
        include ("admin/schvalene.php");
        break;
      case "cekajici":
        include ("admin/cekajici.php");
        break;
      case "spam":
        include ("admin/spam.php");
        break;
      case "komentare":
        include ("admin/nastaveni_komentare.php");
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