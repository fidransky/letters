<?php
// změny tabulky
mysql_query("ALTER TABLE `clanky`
CHANGE `menu` `menu` tinyint(1) DEFAULT NULL") or exit ("<p class=\"error\">Plugin nebyl nainstalován. Úprava tabulky \"clanky\" se nezdařila.</p>");

// přidání menu a podmenu
add_podmenu("exists", $plugin["name"], "clanky", "novy_clanek");
add_podmenu("exists", $plugin["name"], "clanky", "publikovane");
add_podmenu("exists", $plugin["name"], "clanky", "koncepty");
add_podmenu("exists", $plugin["name"], "nastaveni", "sidebar");
?>