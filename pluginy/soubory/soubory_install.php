<?php
// tvorba složky
if (!file_exists("../soubory")) {
  umask(0000);
  $create_dir = mkdir("../soubory", 0777);
  if ($create_dir == false) exit ("<p class=\"error\">Plugin nebyl nainstalován. Tvorba složky \"soubory/\" selhala.</p>");

  $chmod = chmod("../soubory", 0777);
  if ($chmod == false) echo ("<p class=\"error\">Při instalaci selhalo nastavení práv. Nastavte proto práva složky \"soubory/\" na \"777\".</p>");
}

// přidání sloupců nastavení
mysql_query("INSERT INTO `".DB_PREFIX."nastaveni` (`name`, `value`, `group`) VALUES
('files_allowed_types', 'jpg, png, bmp, gif, svg, webp, zip, rar, pdf, txt, rtf, doc, docx, ppt, pptx, pps, xls, xlsx, html, htm, swf, gz', 'soubory'),
('files_max_size', '4096', 'soubory'),
('files_sort', 'none', 'soubory')") or exit ("<p class=\"error\">Plugin nebyl nainstalován. Nastavení nebyla uložena.</p>");

// přidání kompletních práv administrátorům
mysql_query("UPDATE ".DB_PREFIX."nastaveni SET value=CONCAT(value, '&upload_souboru=1&zobrazeni_souboru=1') WHERE name='rights_admin'");

// přidání menu a podmenu
add_menu($plugin["name"], "novy_soubor", "Soubory", true);
add_podmenu("new", $plugin["name"], "soubory", "nový soubor");
add_podmenu("new", $plugin["name"], "soubory", "prohlížeč");
add_podmenu("new", $plugin["name"], "nastaveni", "soubory", true);
add_podmenu("exists", $plugin["name"], "nastaveni", "uzivatele");
?>