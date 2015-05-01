<?php
/*================
úprava .htaccess
================*/

$contents = file_get_contents("cache/autoupgrade/new.htaccess");
$htaccess = fopen(".htaccess", "w");
$htaccess_write = fwrite($htaccess, str_replace("[INSERT_URL]", $address, $contents));
fclose($htaccess);

if ($htaccess_write === false) exit ("Chyba: úprava souboru \".htaccess\" se nezdařila.<br>");



/*================
úprava config.php
================*/

foreach (file("letters/config.php") as $row)
  if (preg_match("/mysql_connect\((.+)\)/", $row, $bracket)) break;
  
$data = ("<?php
define(\"DB_NAME\", \"".$db_jmeno."\");
define(\"DB_PREFIX\", \"\");
@mysql_connect($bracket[1]) or exit (\"<p>Prosim omluvte docasny vypadek webu. Nebylo navazano spojeni s databazi.</p>\");

@mysql_select_db(DB_NAME);
@mysql_query(\"SET NAMES utf8 COLLATE utf8_general_ci\");
if (phpversion() >= \"5.2.3\") @mysql_set_charset(\"utf8\");
?>");

$db_config = fopen("letters/config.php", "w");
$config_write = fwrite($db_config, $data);
fclose($db_config);

if ($config_write === false) exit ("Chyba: úprava souboru \"config.php\" se nezdařila.<br>");



/*================
nastavení, detaily
================*/

// uložení nastavení letters do pole
$lrs_settings = mysql_fetch_assoc(mysql_query("SELECT * FROM nastaveni"));

// uložení detailů webu do pole
$lrs = mysql_fetch_assoc(mysql_query("SELECT * FROM detaily"));


// mazání současných tabulek
@mysql_query("DROP TABLE IF EXISTS `detaily`") or exit ("Chyba: mazání tabulky \"detaily\" selhalo.<br>".mysql_error());
@mysql_query("DROP TABLE IF EXISTS `nastaveni`") or exit ("Chyba: mazání tabulky \"nastaveni\" selhalo.<br>".mysql_error());

// tvorba nové tabulky
@mysql_query("CREATE TABLE IF NOT EXISTS `nastaveni` (
`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(50) NOT NULL,
`value` longtext NOT NULL,
`group` tinytext NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8") or exit ("Chyba: tvorba nové tabulky \"nastaveni\" selhala.<br>".mysql_error());

// ukládání nastavení do nové tabulky
@mysql_query("INSERT INTO `nastaveni` (`id`, `name`, `value`, `group`) VALUES
(1, 'title', '".$lrs["titulek"]."', 'details'),
(2, 'description', '".$lrs["popis"]."', 'details'),
(3, 'keywords', '".$lrs["tagy"]."', 'details'),
(4, 'author', '".$lrs["autor"]."', 'details'),
(5, 'address', '".$lrs["adresa"]."', 'details'),
(6, 'language', '".$lrs["lang"]."', 'details'),
(7, 'timezone', '".$lrs["timezone"]."', 'details'),
(8, 'letters_version', '1.3', 'details'),
(9, 'sidebar_labels', '".$lrs_settings["menu_popisky"]."', ''),
(10, 'sidebar_split_boxes', '".$lrs_settings["panely_oddelit"]."', ''),
(11, 'sidebar_box_order', '".$lrs_settings["sidebar_poradi"]."', ''),
(12, 'template', '".$lrs_settings["template"]."', ''),
(13, 'template_index', '".str_replace(array("articles", "article", "the_latest", "page="), array("kategorie", "clanek", "posledni", null), $lrs_settings["uvodni"])."', ''),
(14, 'template_settings', '".$lrs_settings["template_settings"]."', ''),
(15, 'hide_admin_plugins', '0', ''),
(16, 'rights_roles', 'admin, author, reader, anonymous', ''),
(17, 'rights_admin', 'administrace=1&nastaveni=1&".str_replace(array(", ", "uzivatelske_profily"), array("&", "zobrazeni_profilu"), $lrs_settings["prava_admin"])."', ''),
(18, 'articles_admin_order', '".($lrs_settings["admin_razeni"] == "autor" ? "rok" : $lrs_settings["admin_razeni"])."', 'clanky'),
(19, 'articles_per_page', '".$lrs_settings["nahled_pocet_clanku"]."', 'clanky'),
(20, 'articles_from_subcategories', '".$lrs_settings["clanky_subcats"]."', 'clanky'),
(21, 'articles_preview_type', '".str_replace(array("word", "paragraph", "all"), array("words", "paragraphs", "article"), $lrs_settings["nahled_druh"])."', 'clanky'),
(22, 'articles_preview_count', '".$lrs_settings["nahled_pocet"]."', 'clanky'),
(23, 'articles_datetime_format', '".$lrs_settings["datetime_format"]."', 'clanky'),
(24, 'articles_search_type', '".$lrs_settings["searching_type"]."', 'clanky'),
(25, 'articles_rss_count', '".$lrs_settings["rss_pocet_polozek"]."', 'clanky'),
(26, 'articles_rss_formatting', '".$lrs_settings["rss_formatovani"]."', 'clanky'),
(27, 'comments', '".$lrs_settings["clanky_cmt"]."', 'komentare'),
(28, 'comments_approve', '".($lrs_settings["cmt_def_stav"] == "schvaleny" ? 0 : 1)."', 'komentare'),
(29, 'comments_order', '".$lrs_settings["cmt_razeni"]."', 'komentare'),
(30, 'comments_gravatars', '".$lrs_settings["cmt_gravatary"]."', 'komentare'),
(31, 'comments_gravatars_cache', '".$lrs_settings["cmt_gravatar_cache"]."', 'komentare'),
(32, 'comments_limit', '".$lrs_settings["cmt_omezeni"]."', 'komentare'),
(33, 'comments_limit_days', '".$lrs_settings["cmt_omezeni_dnu"]."', 'komentare'),
(34, 'comments_last_checked', '".$lrs_settings["last_cmt"]."', 'komentare'),
(35, 'rights_author', 'administrace=1".(!empty($lrs_settings["prava_author"]) ? "&".str_replace(array(", ", "uzivatelske_profily"), array("&", "zobrazeni_profilu"), $lrs_settings["prava_author"]) : null)."', 'uzivatele'),
(36, 'rights_reader', '".str_replace(array(", ", "uzivatelske_profily"), array("&", "zobrazeni_profilu"), $lrs_settings["prava_reader"])."', 'uzivatele'),
(37, 'rights_anonymous', '".str_replace(array(", ", "uzivatelske_profily"), array("&", "zobrazeni_profilu"), $lrs_settings["prava_anonym"])."', 'uzivatele'),
(38, 'users_registration', '".$lrs_settings["users_reg"]."', 'uzivatele'),
(39, 'users_approve', '".$lrs_settings["users_schvalovat"]."', 'uzivatele'),
(40, 'users_default_role', '".$lrs_settings["users_def_honour"]."', 'uzivatele'),
(41, 'users_check_via_email', '0', 'uzivatele'),
(42, 'files_allowed_types', '".$lrs_settings["files_allowed_types"]."', 'soubory'),
(43, 'files_max_size', '".$lrs_settings["files_max_size"]."', 'soubory'),
(44, 'files_sort', '".($lrs_settings["files_sort"] == "autor" ? "none" : $lrs_settings["files_sort"])."', 'soubory')");



// funkce pro aktualizaci doplňků

include ("funkce/bez_diakritiky.php");
include ("funkce/delete_dir.php");
include ("funkce/remote_file_get_contents.php");

function download_and_unzip($alias, $typ) {
  $data = remote_file_get_contents(LETTERS_WEB_URL."/misc/download.php?f=".$alias.".zip&t=".$typ."&letters_version=1.3&feature=upgrade");
  if (empty($data)) return false;
  
  $soubor = fopen("cache/".$alias.".zip", "w");
  $save = fwrite($soubor, $data);
  fclose($soubor);
  @chmod("cache/".$alias.".zip", 0777);
    
  if ($save !== false) {
    $zip = new ZipArchive;
    $res = $zip->open("cache/".$alias.".zip");

    // mazání současného pluginu
    if (file_exists($typ."y/".$alias)) @delete_dir($typ."y/".$alias);
    if (file_exists($typ."y/".$alias.".php")) @unlink($typ."y/".$alias.".php");
  
    if ($res === true) {
      $zip->extractTo($typ."y/");
      $dir .= $zip->getNameIndex(0);
      $zip->close();
      @chmod($typ."y/".$dir, 0777);
    }

    @unlink("cache/".$alias.".zip");
  }
  
  return true;
}

function add_menu($plugin, $def_podmenu, $text=null, $flag=false) {
  if (empty($text)) $data = $plugin;
  else $data = $plugin."; ".$text;

  if (file_exists("letters/content/menu.txt")) $old_data = file_get_contents("letters/content/menu.txt");
  if (preg_match("/".$data."/", $old_data)) return true;

  $soubor = FOpen("letters/content/menu.txt", "w");
  $zapis = FWrite ($soubor, $old_data.$data."\r\n");
  FClose ($soubor);
  if ($zapis == false) return false;
  
  // registrace do defaults
  $data = file("letters/content/defaults.php");

  $plugin = strtolower(bez_diakritiky($plugin, false));
  $new_row = "\$def[\"".$plugin."\"] = \"".$def_podmenu."\"; // ".$plugin."\r\n?>";

  $pocet = count($data) - 1;
  foreach ($data as $i => $row) {
    if ($i == $pocet) { $new_data .= $new_row; }       // poslední řádek
    else { $new_data .= $row; }
  }

  $soubor = FOpen("letters/content/defaults.php", "w");
  $zapis = FWrite($soubor, $new_data);
  FClose($soubor);

  if ($zapis == false) return false;

  return true;
}


function add_podmenu($typ, $plugin, $parent, $co=null, $flag=false) {
  if ($typ == "new" and empty($co)) $co = $plugin;

  $data = $typ."; ".$plugin."; ".$parent."; ".$co;

  if (file_exists("letters/content/podmenu.txt")) $staradata = file_get_contents("letters/content/podmenu.txt");
  if (preg_match("/".$data."/", $staradata)) return true;

  $soubor = FOpen("letters/content/podmenu.txt", "w");
  $zapis = FWrite($soubor, $staradata.$data."\r\n");
  FClose($soubor);
  if ($zapis == false) return false;
  
  return true;
}

function add_htaccess($alias) {
  $content = file_get_contents("pluginy/".$alias."/".$alias.".htaccess");
  
  if (preg_match("/#LAST/", $content))
    $matched = false;
  else {
    $data = null;
    $matched = false;
    
    foreach (file(".htaccess") as $row) {
      if ($matched == false and preg_match("/#LAST/", $row)) {
        $data .= $content.PHP_EOL;
        $matched = true;
      }
      $data .= $row;
    }
  }

  if ($matched) {
    $file = FOpen(".htaccess", "w");
    $write = FWrite($file, $data);
  }
  else {
    $file = FOpen(".htaccess", "a");
    $write = FWrite($file, PHP_EOL.$content);
  }
  FClose($file);
  
  return $write;
}


/*================
pluginy
================*/

@mysql_query("ALTER TABLE `pluginy`
CHANGE `jmeno`     `name` tinytext NOT NULL,
CHANGE `verze`     `version` varchar(10) NOT NULL,
CHANGE `kategorie` `category` varchar(100) NOT NULL,
CHANGE `popis`     `description` varchar(200) NOT NULL,
DROP `nastaveni`,
CHANGE `aktivni`   `active` tinyint(1) DEFAULT NULL,
CHANGE `jedinecny` `unique` varchar(100) DEFAULT NULL,
CHANGE `vyzaduje`  `requires` varchar(1000) NOT NULL,
CHANGE `lrs`       `min_lrs` varchar(15) DEFAULT NULL,
CHANGE `autor`     `author` varchar(100) DEFAULT NULL") or exit ("Chyba: konverze tabulky \"pluginy\" se nezdařila.<br>".mysql_error());

// aktualizace souborů menu.txt a podmenu.txt
rename("cache/autoupgrade/menu.txt", "letters/content/menu.txt");
rename("cache/autoupgrade/podmenu.txt", "letters/content/podmenu.txt");

// ukládání aktualizovaných pluginů
$upgrade_plugins_result = mysql_query("SELECT `id`, `alias` FROM `pluginy` ORDER BY `id` DESC");
while (list($id, $alias) = mysql_fetch_row($upgrade_plugins_result)) {
  // zvýšení ID pro uložení nových pluginů
  @mysql_query("UPDATE `pluginy` SET `id`='".($id + 4)."' WHERE `id`='".$id."'");

  // přidání pluginů do sidebaru
  if (in_array($id, explode(", ", $lrs_settings["sidebar_poradi"]))) {
    if ($alias == "prihlaseni") $sidebar_box_order[] = 3;
    else $sidebar_box_order[] = ($id + 4);
  }
  
  // dočasné uložení dat některých pluginů do cache
  if ($alias == "odkazy") @rename("pluginy/odkazy/odkazy.csv", "cache/odkazy.csv");
  if ($alias == "poznamky") @rename("pluginy/poznamky/poznamky.txt", "cache/poznamky.txt");

  
  // stažení nové verze pluginu
  $download_and_unzip = download_and_unzip($alias, "plugin");
  if ($download_and_unzip == false) continue;
  
  // vložení info.php souboru
  $include_info = @include ("pluginy/".$alias."/info.php");
  if ($include_info == false) continue;
  
  // procházení a ukládání nastavení do pole 
  if (array_key_exists("settings", $plugin)) {
    parse_str($plugin["settings"], $settings);

    foreach ($settings as $key => $value)
      $values[] = "('".$alias."_".$key."', '".$value."', '".$alias."')";
  }

  // ukládání vlastností pluginu
  @mysql_query("UPDATE `pluginy` SET `version`='".$plugin["version"]."', `category`='".$plugin["category"]."', `description`='".$plugin["description"]."' WHERE `alias`='".$alias."'");

  // ukládání jedinečnosti pluginu
  if (array_key_exists("unique", $plugin))
    @mysql_query("UPDATE `pluginy` SET `unique`='".$plugin["unique"]."' WHERE `alias`='".$alias."'");

  // ukládání vyžadovaných pluginů
  if (array_key_exists("requires", $plugin))
    @mysql_query("UPDATE `pluginy` SET `requires`='".$plugin["requires"]."' WHERE `alias`='".$alias."'");

  // ukládání min. verze letters
  if (array_key_exists("min_lrs", $plugin))
    @mysql_query("UPDATE `pluginy` SET `min_lrs`='".$plugin["min_lrs"]."' WHERE `alias`='".$alias."'");

  // přidání htaccess pravidel do hlavního htaccess souboru
  if (file_exists("pluginy/".$alias."/".$alias.".htaccess"))
    add_htaccess($alias);

  // upgrade DB dat pluginu
  if (file_exists("pluginy/".$alias."/".$alias."_upgrade_1.3.php")) {
    include ("pluginy/".$alias."/".$alias."_upgrade_1.3.php");
    @unlink("pluginy/".$alias."/".$alias."_upgrade_1.3.php");
  }
  
  unset($plugin);
}

// ukládání nastavení pořadí pluginů v sidebaru
if (!empty($sidebar_box_order))
  @mysql_query("UPDATE `nastaveni` SET `value`='".implode(", ", $sidebar_box_order)."' WHERE `name`='sidebar_box_order'");

// ukládání nastavení pluginů
if (!empty($values))
  @mysql_query("INSERT INTO `nastaveni` (`name`, `value`, `group`) VALUES ".implode(", ", $values));

// ukládání nových pluginů
@mysql_query("INSERT INTO `pluginy` (`id`, `name`, `alias`, `version`, `category`, `description`, `active`, `unique`, `requires`, `min_lrs`, `author`, `url`) VALUES
(1, 'Články',      'clanky', '1.0',      'page_clanky, head, uvodni, admin',            'Základní element Letters přidávající podporu článků.',         1, 'page_clanky', '',           '1.3', 'Pavel Fidranský', 'http://pavelfidransky.cz'),
(2, 'Komentáře',   'komentare', '1.0',   'head, pod textem, meta',                      'Základní element Letters přidávající podporu komentářů.',      1, 'pod textem', 'clanky',      '1.3', 'Pavel Fidranský', 'http://pavelfidransky.cz'),
(3, 'Uživatelé',   'uzivatele', '1.0',   'page_uzivatele, sidebar, login, meta, admin', 'Základní element Letters přidávající podporu více uživatelů.', 1, 'page_uzivatele, login', '', '1.3', 'Pavel Fidranský', 'http://pavelfidransky.cz'),
(4, 'Soubory',     'soubory', '1.0',     'page_soubory, admin',                         'Základní element Letters přidávající podporu souborů.',        1, 'page_soubory', '',          '1.3', 'Pavel Fidranský', 'http://pavelfidransky.cz')") or exit ("Chyba: instalace nových pluginů se nezdařila.<br>".mysql_error());



/*================
vzhledy
================*/

if (!function_exists("glob")) include ("funkce/glob_alternative.php");

$glob = (function_exists("glob") ? glob("vzhledy/*", GLOB_ONLYDIR) : glob_alternative("vzhledy/", "*", GLOB_ONLYDIR)); 
foreach ($glob as $template) {
  $template = str_replace("vzhledy/", null, $template);
  
  // stažení nové verze vzhledu
  $download_and_unzip = download_and_unzip($template, "vzhled");
  
  // pokud selže aktualizace současně zvoleného vzhledu, zobrazí hlášku a nastaví vzhled Primavera
  if ($download_and_unzip == false and $template == strtolower(bez_diakritiky($lrs_settings["template"]))) {
    echo "<p class=\"error\">Současný vzhled není možné aktualizovat, proto byl nastaven vzhled Primavera.</p>";
    @mysql_query("UPDATE `nastaveni` SET `value`='Primavera' WHERE `name`='template'");
  }
}



/*================
main-menu
================*/

@mysql_query("ALTER TABLE `main_menu`
CHANGE `popis`   `text` varchar(50) NOT NULL,
CHANGE `adresa`  `address` varchar(200) NOT NULL") or exit ("Chyba: konverze tabulky \"main_menu\" se nezdařila.<br>".mysql_error());

$add_order = @mysql_query("ALTER TABLE `main_menu` CHANGE `poradi` `order` tinyint(2) unsigned NOT NULL DEFAULT '1'");

if ($add_order === false)
  @mysql_query("ALTER TABLE `main_menu` ADD `order` tinyint(2) unsigned NOT NULL DEFAULT '1'") or exit ("Chyba: konverze tabulky \"main_menu\" se nezdařila.<br>".mysql_error());



/*================
články
================*/

// aktualizace autorů článků (zobrazení -> ID)
$upgrade_articles_result = mysql_query("SELECT `id`, `autor` FROM `clanky`");
while (list($id, $autor) = mysql_fetch_row($upgrade_articles_result)) {
  $autori = array();
  foreach (explode(", ", $autor) as $uzivatel) {
    $select_user_id = mysql_query("SELECT `id` FROM `uzivatele` WHERE `username`='".$uzivatel."' OR `zobrazeni`='".$uzivatel."'");
    $autori[] = array_shift(mysql_fetch_row($select_user_id));
  }
  @mysql_query("UPDATE `clanky` SET `autor`='".implode(", ", $autori)."' WHERE `id`='".$id."'");
}

// konverze tabulky
@mysql_query("ALTER TABLE `clanky`
CHANGE `autor`       `autori` varchar(20) DEFAULT NULL,
CHANGE `komentare`   `komentare` tinyint(1) NOT NULL DEFAULT '1',
CHANGE `cmt_omezeni` `komentare_limit` datetime NOT NULL") or exit ("Chyba: konverze tabulky \"clanky\" se nezdařila.<br>".mysql_error());



/*================
kategorie
================*/

// nic není potřeba



/*================
komentáře
================*/

@mysql_query("ALTER TABLE `komentare`
DROP `nadpis_clanku`,
DROP `highlight`") or exit ("Chyba: konverze tabulky \"komentare\" se nezdařila.<br>".mysql_error());



/*================
uživatelé
================*/

@mysql_query("ALTER TABLE `uzivatele`
CHANGE `hodnost`    `role` varchar(10) NOT NULL DEFAULT 'reader',
CHANGE `heslo`      `password` char(40) NOT NULL,
DROP   `openid`,
DROP   `facebook`,
DROP   `twitter`,
CHANGE `jmeno`      `name` varchar(20) DEFAULT NULL,
CHANGE `prijmeni`   `surname` varchar(50) DEFAULT NULL,
CHANGE `popis`      `text` varchar(2500) DEFAULT NULL,
CHANGE `zobrazeni`  `show_name` varchar(100) DEFAULT NULL,
CHANGE `stav`       `status` varchar(20) NOT NULL DEFAULT 'schvaleny',
CHANGE `datum_registrace`   `registration_date` datetime DEFAULT NULL,
CHANGE `overeni`    `verification_string` char(20) NOT NULL") or exit ("Chyba: konverze tabulky \"uzivatele\" se nezdařila.<br>".mysql_error());
?>