<?php
mysql_query("DROP TABLE ".DB_PREFIX."komentare") or exit ("<p class=\"error\">Maz�n� tabulky \"komentare\" se nezda�ilo.</p>");

mysql_query("ALTER TABLE ".DB_PREFIX."clanky
DROP komentare,
DROP komentare_limit") or exit ("<p class=\"error\">�prava tabulky \"clanky\" se nezda�ila.</p>");
?>