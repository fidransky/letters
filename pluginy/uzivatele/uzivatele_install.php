<?php
// tvorba tabulky
mysql_query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."uzivatele` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` char(40) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'reader',
  `name` varchar(20) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `show_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `web` varchar(100) DEFAULT NULL,
  `text` varchar(2500) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'schvaleny',
  `registration_date` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `ip` char(15) NOT NULL,
  `verification_string` char(20) NOT NULL,
  `salt` char(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8") or exit ("<p class=\"error\">Plugin nebyl nainstalován. Tvorba tabulky \"uzivatele\" selhala.</p>");

// přidání sloupců nastavení
mysql_query("INSERT INTO `".DB_PREFIX."nastaveni` (`name`, `value`, `group`) VALUES
('rights_author', '', 'uzivatele'),
('rights_reader', '', 'uzivatele'),
('rights_anonymous', '', 'uzivatele'),
('users_registration', '1', 'uzivatele'),
('users_approve', '1', 'uzivatele'),
('users_default_role', 'reader', 'uzivatele'),
('users_check_via_email', '0', 'uzivatele')") or exit ("<p class=\"error\">Plugin nebyl nainstalován. Nastavení nebyla uložena.</p>");

// přidání kompletních práv administrátorům
mysql_query("UPDATE ".DB_PREFIX."nastaveni SET value=CONCAT(value, '&tvorba_uzivatelu=1&upravy_uzivatelu=1&zobrazeni_profilu=1') WHERE name='rights_admin'");

// přidání menu a podmenu
add_menu($plugin["name"], "prehled", "Uživatelé", true);
add_podmenu("new", $plugin["name"], "uzivatele", "profil");
add_podmenu("new", $plugin["name"], "uzivatele", "přehled");
add_podmenu("new", $plugin["name"], "uzivatele", "nový uživatel");
add_podmenu("new", $plugin["name"], "nastaveni", "uživatelé", true);
add_podmenu("exists", $plugin["name"], "nastenka", "uzivatele");
add_podmenu("exists", $plugin["name"], "nastaveni", "detaily");
add_podmenu("exists", $plugin["name"], "nastaveni", "index");
add_podmenu("exists", $plugin["name"], "nastaveni", "mainmenu");
add_podmenu("exists", $plugin["name"], "clanky", "novy_clanek");
add_podmenu("exists", $plugin["name"], "clanky", "publikovane");
add_podmenu("exists", $plugin["name"], "clanky", "koncepty");
?>