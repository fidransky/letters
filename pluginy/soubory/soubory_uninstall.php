<?php
mysql_query("DELETE FROM ".DB_PREFIX."nastaveni WHERE group='soubory'") or exit ("<p class=\"error\">Mazání sloupců z tabulky \"nastaveni\" selhalo.</p>");
?>