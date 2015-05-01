<?php
mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."komentare`") or exit ("<p class=\"error\">Mazání tabulky \"komentare\" se nezdařilo.</p>");

@mysql_query("ALTER TABLE `".DB_PREFIX."clanky`
DROP `komentare`,
DROP `komentare_limit`");
?>