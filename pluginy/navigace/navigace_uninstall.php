<?php
mysql_query("ALTER TABLE ".DB_PREFIX."clanky DROP menu") or exit ("<p class=\"error\">�prava tabulky \"clanky\" se nezda�ila.</p>");
?>