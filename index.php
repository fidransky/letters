<?php
// přesměrování na instalaci
if (file_exists("instalace.php") and !file_exists("letters/config.php"))
  header("Location: instalace.php");

session_start();

// automatické přihlášení
if (!isSet($_SESSION["log"]) and isSet($_COOKIE["permanent_login"]) and !empty($_COOKIE["permanent_login"])) {
  header("Location: letters/scripts/autologin.php?return2web");
  exit ("<a href=\"letters/scripts/autologin.php?return2web\" title=\"continue\">&rarr;</a>");
}

// konfigurace databáze
(@include ("letters/config.php")) or exit ("<p>The page you requested could not be loaded due to technical issues.<br><small>missing the \"config.php\" file</small></p>");

// definice konstant a vkládání funkcí
define("FCE_DIR", "funkce/");
define("PLUGINS_DIR", "pluginy/");
define("TEMPLATES_DIR", "vzhledy/");

include ("includes.php");
include ($template["path"]."index.php");
?>