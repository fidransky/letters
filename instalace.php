<!doctype html>

<html lang="cs">
<head>
<meta charset="utf-8">

<?php
include ("funkce/get_address.php");
$address = str_replace("/".array_pop(explode("/", $_SERVER["REQUEST_URI"])), null, get_address());

define("LETTERS_WEB_URL", "http://letters.cz");
$verze = "1.3";
$predchozi_verze = "1.2";
?>

<style>
html { background-color: #F7F7F7; }
body { width: 560px; min-height: 510px; margin: 0 auto; font-size: 16px; padding: 40px 30px; line-height: 1.2; background-color: white; }
#logo { margin-bottom: 40px; border: 0; }
h1 { font: 26px Garamond, Times, serif; color: black; width: 250px; margin-top: 0; border-bottom: 1px solid #BFBFBF; }
a { color: black; }
ul { margin-top: -10px; padding-left: 30px; }
small { color: #BFBFBF; font-style: italic; margin-left: 8px; }

.error { color: red; font-weight: bold; }
.help { cursor: help; border-bottom: 1px dotted black; }
</style>

<title>Instalace Letters <?php echo $verze; ?></title>
</head>

<body>
<a href="<?php echo LETTERS_WEB_URL; ?>"><img src="letters/logo.png" id="logo" title="přejít na web Letters"></a><br>

<?php
if (!isSet($_GET["step"])) {
?>

<p>
Vítejte v instalačním procesu redakčního systému Letters <?php echo $verze; ?>
</p>

<p>
Systém můžete:
<ul>
  <li><a href="instalace.php?step=install">instalovat</a> <small>nová instalace</small></li>
  <li><a href="instalace.php?step=update">aktualizovat</a> <small>aktualizace z předchozí verze (<?php echo $predchozi_verze; ?>)</small></li>
</ul>
</p>

<?php
}

/*================
NOVÁ INSTALACE
================*/
elseif ($_GET["step"] == "install") {
?>

<h1>Instalace</h1>

<p>
Tento průvodce vás provede instalací redakčního systému Letters <?php echo $verze; ?>.
</p>

<p>
V případě nejasností zkuste zabrousit do <a href="<?php echo LETTERS_WEB_URL; ?>/jak_instalovat/" target="_blank">oficiálního návodu na instalaci</a>, jako poslední záchrana slouží <a href="<?php echo LETTERS_WEB_URL; ?>/forum/" target="_blank">podpůrné fórum</a>.
</p>

<p>
Před instalací si pro jistotu přečtěte <a href="readme.txt">readme</a>. Taky se můžete mrknout do <a href="changelog-<?php echo $verze; ?>.txt">changelogu</a>.
</p>

<a href="instalace.php?step=install1"><button>Další</button></a>
<?php
}

/*==================
Instalace - Krok 1
==================*/
elseif ($_GET["step"] == "install1") {
?>
<form action="instalace.php?step=install2" method="post">

<h1>Databáze MySQL</h1>
<p>
Zadejte prosím přihlašovací údaje k databázi MySQL.
</p>

<p>
<label for="ucet">MySQL účet:</label> <small>přihlašovací jméno pro přístup do databáze</small><br>
<input type="text" name="mysql_ucet" id="ucet" size="20" maxlength="50" required autofocus>
</p>

<p>
<label for="heslo">MySQL heslo:</label> <small>heslo pro přístup do databáze</small><br>
<input type="password" name="mysql_heslo" id="heslo" size="20" maxlength="50" required>
</p>

<p>
<label for="server">MySQL server:</label> <small>doména, na které je umístěna databáze</small><br>
<input type="text" name="mysql_server" id="server" size="50" maxlength="50" placeholder="localhost" required>
</p>

<p>
<label for="jmeno">Jméno databáze:</label><br>
<input type="text" name="db_jmeno" id="jmeno" size="50" maxlength="50" required>
</p>

<p>
<label for="prefix">Prefix tabulek:</label><br>
<input type="text" name="db_prefix" id="prefix" value="lrs_" size="20" maxlength="20" placeholder="lrs_">
</p>
<br>

<?php
if (ini_get("safe_mode") == true)
  echo "<p>Pokud jste tak již neudělal/a, <a href=\"".LETTERS_WEB_URL."/index.php?clanek=8#safemode\" target=\"_blank\" class=\"help\">nastavte atributy</a> na \"777\" u složek: \"cache/\", \"letters/\", \"soubory/\", \"pluginy/\" a \"vzhledy/\".</p>";
?>

<input type="submit" value="Další">
</form>
<?php
}

/*==================
Instalace - Krok 2
==================*/
elseif ($_GET["step"] == "install2") {
  // úprava .htaccess
  $contents = file_get_contents(".htaccess");
  $htaccess = fopen(".htaccess", "w");
  $htaccess_write = fwrite($htaccess, str_replace("[INSERT_URL]", $address, $contents));
  fclose($htaccess);

  if ($htaccess_write === false) exit ("Chyba: úprava souboru \".htaccess\" se nezdařila.<br>");


  // tvorba souboru config.php
  $mysql_ucet = $_POST["mysql_ucet"];
  $mysql_heslo = $_POST["mysql_heslo"];
  $mysql_server = $_POST["mysql_server"];
  $db_jmeno = $_POST["db_jmeno"];
  $db_prefix = $_POST["db_prefix"];

  $data = ("<?php
  define(\"DB_NAME\", \"$db_jmeno\");
  define(\"DB_PREFIX\", \"$db_prefix\");
  @mysql_connect(\"$mysql_server\", \"$mysql_ucet\", \"$mysql_heslo\") or exit (\"<p>Prosim omluvte docasny vypadek webu. Nebylo navazano spojeni s databazi.</p>\");

  @mysql_select_db(DB_NAME);
  @mysql_query(\"SET NAMES utf8 COLLATE utf8_general_ci\");
  if (phpversion() >= \"5.2.3\") @mysql_set_charset(\"utf8\");
  ?>");

  $db_config = fopen("letters/config.php", "w");
  $config_write = fwrite($db_config, $data);
  fclose($db_config);

  if ($config_write === false) exit ("Chyba: tvorba souboru \"config.php\" se nezdařila.<br>");


  // vložení souboru config.php
  (@include ("letters/config.php")) or exit ("<p>The page you requested could not be loaded due to technical issues.<br><small>missing the \"config.php\" file</small></p>");

  // nastavení kódování databáze
  @mysql_query("ALTER DATABASE ".DB_NAME." DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci");

  // tvorba DB tabulek
  @mysql_query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."pluginy` (
  `id` tinyint(250) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `alias` tinytext NOT NULL,
  `version` varchar(10) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `unique` varchar(100) DEFAULT NULL,
  `requires` varchar(1000) NOT NULL,
  `min_lrs` varchar(15) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8") or exit ("Chyba: tvorba nové tabulky \"pluginy\" se nezdařila.<br>".mysql_error());

  @mysql_query("INSERT INTO `".DB_PREFIX."pluginy` (`id`, `name`, `alias`, `version`, `category`, `description`, `active`, `unique`, `requires`, `min_lrs`, `author`, `url`) VALUES
  (1, 'Články',      'clanky', '1.0',      'page_clanky, head, uvodni, admin',            'Základní element Letters přidávající podporu článků.',         1, 'page_clanky', '',           '1.3', 'Pavel Fidranský', 'http://pavelfidransky.cz'),
  (2, 'Komentáře',   'komentare', '1.0',   'head, pod textem, meta',                      'Základní element Letters přidávající podporu komentářů.',      1, 'pod textem', 'clanky',      '1.3', 'Pavel Fidranský', 'http://pavelfidransky.cz'),
  (3, 'Uživatelé',   'uzivatele', '1.0',   'page_uzivatele, sidebar, login, meta, admin', 'Základní element Letters přidávající podporu více uživatelů.', 1, 'page_uzivatele, login', '', '1.3', 'Pavel Fidranský', 'http://pavelfidransky.cz'),
  (4, 'Soubory',     'soubory', '1.0',     'page_soubory, admin',                         'Základní element Letters přidávající podporu souborů.',        1, 'page_soubory', '',          '1.3', 'Pavel Fidranský', 'http://pavelfidransky.cz'),
  (5, 'Navigace',    'navigace', '1.0',    'sidebar', 'Jednoduchá navigace mezi články a kategoriemi do sidebaru.', 1, '', '', '', '', ''),
  (6, 'Vyhledávání', 'vyhledavani', '1.0', 'sidebar', 'Zobrazí v sidebaru vyhledávací formulář, se kterým je nalezení hledaného článku hračka.',          1, '', 'clanky',                '1.3', '', ''),
  (7, 'oEmbed',      'oembed', '1.0',      'text editor', 'Převede URL adresy podporovaných serverů na hezké HTML zobrazení.', 1, '', '', '', '', ''),
  (8, 'AutoUpgrade', 'autoupgrade', '1.2', 'none', 'Aktualizace systému na jedno kliknutí.', 1, '', '', '', '', '')");

  
  @mysql_query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."nastaveni` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` longtext NOT NULL,
  `group` tinytext NOT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8") or exit ("Chyba: tvorba nové tabulky \"nastaveni\" se nezdařila.<br>".mysql_error());

  @mysql_query("INSERT INTO `".DB_PREFIX."nastaveni` (`id`, `name`, `value`, `group`) VALUES
  (1, 'title', 'Můj skvělý nový web', 'details'),
  (2, 'description', '', 'details'),
  (3, 'keywords', '', 'details'),
  (4, 'author', '1', 'details'),
  (5, 'address', '".$address."', 'details'),
  (6, 'language', 'cs', 'details'),
  (7, 'timezone', 'Europe/Prague', 'details'),
  (8, 'letters_version', '1.3', 'details'),
  (9, 'sidebar_labels', '1', ''),
  (10, 'sidebar_split_boxes', '1', ''),
  (11, 'sidebar_box_order', '5, 3, 6', ''),
  (12, 'template', 'Primavera', ''),
  (13, 'template_index', 'kategorie=', ''),
  (14, 'template_settings', '', ''),
  (15, 'hide_admin_plugins', '0', ''),
  (16, 'rights_roles', 'admin, author, reader, anonymous', ''),
  (17, 'rights_admin', 'administrace=1&nastaveni=1&psani_clanku=1&upravy_clanku=1&tvorba_kategorii=1&upravy_kategorii=1&psani_komentaru=1&upravy_komentaru=1&tvorba_uzivatelu=1&upravy_uzivatelu=1&zobrazeni_profilu=1&upload_souboru=1&zobrazeni_souboru=1', ''),
    (18, 'articles_admin_order', 'mesic', 'clanky'),
    (19, 'articles_per_page', '5', 'clanky'),
    (20, 'articles_from_subcategories', '1', 'clanky'),
    (21, 'articles_preview_type', 'article', 'clanky'),
    (22, 'articles_preview_count', '3', 'clanky'),
    (23, 'articles_datetime_format', 'j. n. Y v H:i', 'clanky'),
    (24, 'articles_search_type', 'classic', 'clanky'),
    (25, 'articles_rss_count', '5', 'clanky'),
    (26, 'articles_rss_formatting', '1', 'clanky'),
  (27, 'comments', '1', 'komentare'),
  (28, 'comments_approve', '1', 'komentare'),
  (29, 'comments_order', 'ASC', 'komentare'),
  (30, 'comments_gravatars', '1', 'komentare'),
  (31, 'comments_gravatars_cache', '1', 'komentare'),
  (32, 'comments_limit', '0', 'komentare'),
  (33, 'comments_limit_days', '10', 'komentare'),
  (34, 'comments_last_checked', '0', 'komentare'),
    (35, 'rights_author', 'administrace=1&psani_clanku=1&upravy_clanku=1&psani_komentaru=1&zobrazeni_profilu=1&upload_souboru=1&zobrazeni_souboru=1', 'uzivatele'),
    (36, 'rights_reader', 'administrace=1&psani_komentaru=1&zobrazeni_profilu=1&zobrazeni_souboru=1', 'uzivatele'),
    (37, 'rights_anonymous', 'psani_komentaru=1', 'uzivatele'),
    (38, 'users_registration', '1', 'uzivatele'),
    (39, 'users_approve', '1', 'uzivatele'),
    (40, 'users_default_role', 'reader', 'uzivatele'),
    (41, 'users_check_via_email', '0', 'uzivatele'),
  (42, 'files_allowed_types', 'jpg, png, bmp, gif, svg, webp, zip, rar, pdf, txt, rtf, doc, docx, ppt, pptx, pps, xls, xlsx, html, htm, swf, gz', 'soubory'),
  (43, 'files_max_size', '4096', 'soubory'),
  (44, 'files_sort', 'none', 'soubory'),
    (45, 'navigace_show_categories', '1', 'navigace'),
    (46, 'navigace_show_count', '1', 'navigace'),
    (47, 'navigace_hide_empty', '0', 'navigace'),
  (48, 'oembed_width', '700', 'oembed')");


  @mysql_query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."main_menu` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `order` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8") or exit ("Chyba: tvorba nové tabulky \"main_menu\" se nezdařila.<br>".mysql_error());

  @mysql_query("INSERT INTO `".DB_PREFIX."main_menu` (`id`, `text`, `address`, `order`) VALUES
  (1, 'Úvod', '".$address."', 1),
  (2, 'Administrace', '".$address."/letters', 2)");


  @mysql_query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."clanky` (
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
  `autori` varchar(20) DEFAULT NULL,
  `komentare` tinyint(1) NOT NULL DEFAULT '1',
  `komentare_limit` datetime NOT NULL,
  `menu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `fulltext` (`nadpis`,`text`,`tagy`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8") or exit ("Chyba: tvorba nové tabulky \"clanky\" se nezdařila.<br>".mysql_error());
  
  @mysql_query("INSERT INTO `".DB_PREFIX."clanky` (`id`, `cas`, `nadpis`, `alias`, `kategorie`, `text`, `zverejneno`, `zobrazit`, `heslo`, `tagy`, `autori`, `menu`, `komentare`, `komentare_limit`) VALUES
  (1, NOW(), 'Ukázkový článek', 'ukazkovy_clanek', '', '<p>Při instalaci byl vytvořen také tento článek. Slouží pro prvotní poznání systému a zkoušení funkcí.<br>Pro přihlášení do administrace využijte formulář nalevo nebo odkaz v patičce webu.</p><p>Chcete-li o systému získat více informací, navštivte <a href=\"".LETTERS_WEB_URL."\">oficiální stránky</a> nebo <a href=\"".LETTERS_WEB_URL."/forum/\">fórum</a>.</p>', 1, 1, '', '', '1', 0, 1, '0000-00-00 00:00:00')");


  @mysql_query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."kategorie` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(50) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `popis` varchar(200) NOT NULL,
  `parents` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8") or exit ("Chyba: tvorba nové tabulky \"kategorie\" se nezdařila.<br>".mysql_error());


  @mysql_query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."komentare` (
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
  `id_clanku` smallint(5) NOT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8") or exit ("Chyba: tvorba nové tabulky \"komentare\" se nezdařila.<br>".mysql_error());
  

  @mysql_query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."uzivatele` (
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
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8") or exit ("Chyba: tvorba nové tabulky \"uzivatele\" se nezdařila.<br>".mysql_error());

  @mysql_query("INSERT INTO `".DB_PREFIX."uzivatele` (`id`, `username`, `password`, `role`, `name`, `surname`, `nickname`, `show_name`, `email`, `web`, `text`, `status`, `registration_date`, `last_login`, `ip`, `verification_string`, `salt`) VALUES
  (1, 'admin', '863a60e3e37be9da8d36083d6d32f80a31645896', 'admin', '', '', '', 'admin', '', '".$address."', '', 'schvaleny', NOW(), '0000-00-00 00:00:00', '".$_SERVER["REMOTE_ADDR"]."', '6e6dcaf3fe1d99bb58a1', 'kNpzl')");


  // safe mode
  if (ini_get("safe_mode") == false) {
    umask(0000);      // nastaví masku pro chmod
    $chmod1 = chmod("cache", 0777);
    $chmod2 = chmod("soubory", 0777);
    $chmod3 = chmod("letters", 0777);
    $chmod4 = chmod("pluginy", 0777);
    $chmod5 = chmod("vzhledy", 0777);

    if ($chmod1 == false or $chmod2 == false or $chmod3 == false or $chmod4 == false or $chmod5 == false)
      echo "<p class=\"error\">Automatické nastavení práv složkám selhalo. Proveďte tak, prosím, <a href=\"".LETTERS_WEB_URL."/index.php?clanek=8#safemode\" target=\"_blank\">ručně</a>.</p>";
  }
  
  // smazání aktualizačního skriptu
  @unlink("upgrade.php");
  
  // smazání složky pro autoupgrade
  include ("funkce/delete_dir.php");
  @delete_dir("cache/autoupgrade");
?>

<h1>Závěr</h1>
<p>
A je to za námi.<br>
<strong>Ještě několik zásadních informací pro začátek&hellip;</strong>
</p>

<p>
Hlavní stránka webu se nachází na vaší <a href="<?php echo $address; ?>" target="_blank">webové doméně</a>.<br>
Administrace probíhá na adrese <a href="<?php echo $address."/letters/"; ?>" target="_blank"><?php echo $address."/letters/"; ?></a>.
</p>

<p>
<strong>Přihlašovací údaje</strong> jsou:<br>
<ul>
  <li>uživatel: admin</li>
  <li>heslo: admin</li>
</ul>

<strong>Okamžitě</strong> po prvním přihlášení do administrace si <strong>změňte heslo</strong> na nějaké bezpečnější.
</p>

<p>
Chcete-li od systému něco víc (a <a href="<?php echo LETTERS_WEB_URL; ?>/doplnky/" target="_blank">doplňky</a> to nevyřeší), nabízí se <a href="<?php echo LETTERS_WEB_URL; ?>/placena_podpora" target="_blank">placená podpora</a>.
</p>

<?php
}

/*==================
AKTUALIZACE
==================*/
elseif ($_GET["step"] == "update") {
  (@include ("letters/config.php")) or exit ("<p>The page you requested could not be loaded due to technical issues.<br><small>missing the \"config.php\" file</small></p>");

  $result = mysql_query("SELECT `verze` FROM `detaily`");
  list($soucasna_verze) = mysql_fetch_row($result);

  echo "<h1>Aktualizace</h1>";
  if ($soucasna_verze == $verze)
    echo "<p>Verze Letters nyní nainstalovaná na vašem webu je shodná s touto, aktualizovat tedy nemá sebemenší smysl.</p>";
  else {
    if ($soucasna_verze != $predchozi_verze) {
      echo "<p>";
      echo "Je mi líto, ale verzi Letters nyní nainstalovanou na vašem webu nelze aktualizovat.<br>";
      echo "Nejdřív musíte obnovit databázi pomocí aktualizačního procesu předchozí verze. Například pokud nyní máte verzi 1.0, budete muset nejdřív projít aktualizačním procesem verze 1.1 a pak teprve tímto.<br>";
      echo "Soubory na FTP přitom (pokud není řečeno jinak) nemusíte obnovovat pro každou verzi zvlášť. To platí pouze u poslední aktualizace.<br>";
      echo "</p>";
    }
    else {
      echo "<p>";
      echo "Tento průvodce Vás provede aktualizací redakčního systému Letters na verzi ".$verze.".<br>";
      echo "Průvodce obstará aktualizaci databáze, zbytek je na vás.";
      echo "</p>";
      echo "<p>";
      echo "Je třeba přes FTP obnovit veškeré soubory, <strong>výjimkou</strong> je pouze soubor \"config.php\" nacházející se ve složce \"letters/\". Ten <strong>nemažte ani nepřepisujte</strong>.";
      echo "</p>";
      echo "<a href=\"instalace.php?step=update1\"><button>Aktualizovat</button></a>";
    }
  }
}

/*==================
Aktualizace - Krok 1
==================*/
elseif ($_GET["step"] == "update1") {
  (@include ("letters/config.php")) or exit ("<p>The page you requested could not be loaded due to technical issues.<br><small>missing the \"config.php\" file</small></p>");

  include ("upgrade.php");

  @delete_dir("cache/autoupgrade");
  @unlink("upgrade.php");
?>

<h1>Závěr</h1>
<p>
<strong>Ještě několik zásadních informací&hellip;</strong><br>
Systém byl právě aktualizován na Letters verze <?php echo $verze; ?>. Hlavní strana webu, adresa administrace i přihlašovací údaje zůstavají stejné.<br>
</p>

<p>
V případě jakéhokoli problému <a href="<?php echo LETTERS_WEB_URL; ?>/forum/">využijte fórum</a>.<br>
přejít <a href="<?php echo $address; ?>">na hlavní stranu</a> / <a href="<?php echo $address."/letters/"; ?>">na administraci</a>
</p>

<?php } ?>

</body>
</html>