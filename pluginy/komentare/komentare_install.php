<?php
// tvorba a změny tabulek
mysql_query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."komentare` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `stav` varchar(20) NOT NULL,
  `jmeno` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `web` varchar(100) NOT NULL,
  `ip` char(15) NOT NULL,
  `browser` varchar(50) NOT NULL,
  `os` varchar(50) NOT NULL,
  `cas` datetime NOT NULL,
  `text` text NOT NULL,
  `id_clanku` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8") or exit ("<p class=\"error\">Plugin nebyl nainstalován. Tvorba tabulky \"komentare\" selhala.</p>");

mysql_query("ALTER TABLE ".DB_PREFIX."clanky
  ADD `komentare` tinyint(1) NOT NULL DEFAULT '1',
  ADD `komentare_limit` datetime NOT NULL") or exit ("<p class=\"error\">Plugin nebyl nainstalován. Úprava tabulky \"clanky\" se nezdařila.</p>");

// přidání sloupců nastavení
mysql_query("INSERT INTO `".DB_PREFIX."nastaveni` (`name`, `value`, `group`) VALUES
('comments', '1', 'komentare'),
('comments_approve', '1', 'komentare'),
('comments_order', 'ASC', 'komentare'),
('comments_gravatars', '1', 'komentare'),
('comments_gravatars_cache', '1', 'komentare'),
('comments_limit', '0', 'komentare'),
('comments_limit_days', '10', 'komentare'),
('comments_last_checked', '10', 'komentare')") or exit ("<p class=\"error\">Plugin nebyl nainstalován. Nastavení nebyla uložena.</p>");

// přidání kompletních práv administrátorům
mysql_query("UPDATE ".DB_PREFIX."nastaveni SET value=CONCAT(value, '&psani_komentaru=1&upravy_komentaru=1') WHERE name='rights_admin'");

// přidání menu a podmenu
add_menu($plugin["name"], "vsechny", "Komentáře", true);
add_podmenu("new", $plugin["name"], "komentare", "všechny");
add_podmenu("new", $plugin["name"], "komentare", "nové");
add_podmenu("new", $plugin["name"], "komentare", "schválené");
add_podmenu("new", $plugin["name"], "komentare", "čekající");
add_podmenu("new", $plugin["name"], "komentare", "spam");
add_podmenu("new", $plugin["name"], "nastaveni", "komentáře", true);
add_podmenu("exists", $plugin["name"], "nastenka", "komentare");
add_podmenu("exists", $plugin["name"], "clanky", "novy_clanek");
add_podmenu("exists", $plugin["name"], "clanky", "publikovane");
add_podmenu("exists", $plugin["name"], "clanky", "koncepty");
add_podmenu("exists", $plugin["name"], "nastaveni", "uzivatele");
?>