<?php
mysql_query("DROP TABLE ".DB_PREFIX."komentare") or exit ("<p class=\"error\">Mazání tabulky \"komentare\" se nezdaøilo.</p>");

mysql_query("ALTER TABLE ".DB_PREFIX."clanky
DROP komentare,
DROP komentare_limit") or exit ("<p class=\"error\">Úprava tabulky \"clanky\" se nezdaøila.</p>");
?>