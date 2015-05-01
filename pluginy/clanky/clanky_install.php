<?php
// tvorba tabulek
mysql_query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."clanky` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cas` datetime DEFAULT NULL,
  `nadpis` tinytext NOT NULL,
  `alias` tinytext NOT NULL,
  `kategorie` char(250) NOT NULL,
  `text` text,
  `zverejneno` tinyint(1) DEFAULT NULL,
  `zobrazit` tinyint(1) NOT NULL DEFAULT '1',
  `heslo` char(50) NOT NULL,
  `tagy` char(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `fulltext` (`nadpis`,`text`,`tagy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8") or exit ("<p class=\"error\">Plugin nebyl nainstalován. Tvorba tabulky \"clanky\" selhala.</p>");

mysql_query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."kategorie` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(50) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `popis` varchar(200) NOT NULL,
  `parents` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8") or exit ("<p class=\"error\">Plugin nebyl nainstalován. Tvorba tabulky \"kategorie\" selhala.</p>");

// přidání sloupců nastavení
mysql_query("INSERT INTO `".DB_PREFIX."nastaveni` (`name`, `value`, `group`) VALUES
('articles_admin_order', 'rok', 'clanky'),
('articles_per_page', '5', 'clanky'),
('articles_from_subcategories', '1', 'clanky'),
('articles_preview_type', 'article', 'clanky'),
('articles_preview_count', '3', 'clanky'),
('articles_datetime_format', 'j. n. Y v H:i', 'clanky'),
('articles_search_type', 'classic', 'clanky'),
('articles_rss_count', '5', 'clanky'),
('articles_rss_formatting', '1', 'clanky')") or exit ("<p class=\"error\">Plugin nebyl nainstalován. Nastavení nebyla uložena.</p>");

// přidání kompletních práv administrátorům
mysql_query("UPDATE ".DB_PREFIX."nastaveni SET value=CONCAT(value, '&psani_clanku=1&upravy_clanku=1&tvorba_kategorii=1&upravy_kategorii=1') WHERE name='rights_admin'");

// přidání menu a podmenu
add_menu($plugin["name"], "novy_clanek", "Články");
add_menu($plugin["name"], "nova_kategorie", "Kategorie");
add_podmenu("new", $plugin["name"], "clanky", "nový článek");
add_podmenu("new", $plugin["name"], "clanky", "publikované");
add_podmenu("new", $plugin["name"], "clanky", "koncepty");
add_podmenu("new", $plugin["name"], "clanky", "pořadník");
add_podmenu("new", $plugin["name"], "kategorie", "nová kategorie");
add_podmenu("new", $plugin["name"], "kategorie", "upravit");
add_podmenu("new", $plugin["name"], "nastaveni", "články");
add_podmenu("exists", $plugin["name"], "nastaveni", "administrace");
add_podmenu("exists", $plugin["name"], "nastaveni", "index");
add_podmenu("exists", $plugin["name"], "nastaveni", "mainmenu");
add_podmenu("exists", $plugin["name"], "nastaveni", "uzivatele");
add_podmenu("exists", $plugin["name"], "nastenka", "články");
?>