<?php
mysql_query("ALTER TABLE ".DB_PREFIX."clanky DROP menu") or exit ("<p class=\"error\">Úprava tabulky \"clanky\" se nezdaøila.</p>");
?>