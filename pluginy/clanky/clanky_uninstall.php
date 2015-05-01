<?php
mysql_query("DROP TABLE IF EXISTS ".DB_PREFIX."clanky") or exit ("<p class=\"error\">Mazání tabulky \"clanky\" selhalo.</p>");
mysql_query("DROP TABLE IF EXISTS ".DB_PREFIX."kategorie") or exit ("<p class=\"error\">Mazání tabulky \"kategorie\" selhalo.</p>");
?>