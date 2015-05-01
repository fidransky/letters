<?php
session_start();

// přihlášení
if (!isset($_SESSION["log"])) {
  header("Location: index.php?unauthorized");
  exit ("<a href=\"index.php?unauthorized\" title=\"continue\">&rarr;</a>");
}


// konfigurace databáze
(@include ("config.php")) or exit ("<p>The page you requested could not be loaded due to technical issues.<br><small>missing the \"config.php\" file</small></p>");


// definice konstant a vkládání funkcí
define("FCE_DIR", "../funkce/");
define("PLUGINS_DIR", "../pluginy/");
define("TEMPLATES_DIR", "../vzhledy/");
define("LETTERS_WEB_URL", "http://letters.cz");

include (FCE_DIR."get_data.php");
include (FCE_DIR."save_data.php");
include (FCE_DIR."delete_data.php");

include (FCE_DIR."get_settings.php");
include (FCE_DIR."save_settings.php");

include (FCE_DIR."get_count.php");
include (FCE_DIR."get_details.php");
include (FCE_DIR."get_template_info.php");

include (FCE_DIR."lang.php");
include (FCE_DIR."sklonuj.php");
include (FCE_DIR."check_user2.php");
include (FCE_DIR."bez_diakritiky.php");

include (FCE_DIR."strip_magic_slashes.php");
include (FCE_DIR."remote_file_get_contents.php");
include (FCE_DIR."curl_file_get_contents.php");
include (FCE_DIR."generate_password.php");
include (FCE_DIR."rel_time.php");
include (FCE_DIR."detectmobile.php");
if (!function_exists("glob")) include (FCE_DIR."glob_alternative.php");

include (FCE_DIR."get_menu.php");
include (FCE_DIR."flag.php");
include (FCE_DIR."include_plugins.php");
include (FCE_DIR."include_plugin_admin.php");

$lrs = get_details();
$template = get_template_info();

setLocale(LC_ALL, "Czech"); // nastavení lokálního času		
@date_default_timezone_set($lrs["timezone"]);

define("LANG", $lrs["language"]);

check_flag();
del_flag();

// aktualizace pluginů
if (isSet($_COOKIE["plugin_updates"]) or ($_GET["page"] == "pluginy" and $_GET["co"] == "aktualizace" and isSet($_GET["downloaded"]))) {
  $plugin_updates = 0;
  foreach (get_data("alias, version", "pluginy") as $plugin) {
    $data = remote_file_get_contents(LETTERS_WEB_URL."/misc/check_update.php?alias=".$plugin["alias"]."&t=plugin");
    if (!empty($data) and $plugin["version"] < $data) $plugin_updates++;
  }

  setcookie("plugin_updates", $plugin_updates);
}
?>


<!doctype html>
<html lang="cs" id="lrs_top">

<head>
<meta charset="utf-8">
<meta name="copyright" content="Pavel Fidranský">
<meta http-equiv="x-frame-options" content="deny">

<link href="style.css?version=<?php echo $lrs["letters_version"]; ?>" rel="stylesheet">

<script src="<?php echo FCE_DIR; ?>jquery.min.js"></script>
<script src="<?php echo FCE_DIR; ?>show_hide.js"></script>
<script src="<?php echo FCE_DIR; ?>screen_set_cookies.js"></script>
<script src="scripts/pagevisibility.js"></script>
<script>
function autologoutTimeout() {
  if (document[hidden])
    timer = setTimeout('window.location.replace("index.php?autologout")', 60*60*1000);     // 60 minut
  else {
    clearTimeout(timer);
    timer = 0;
  }
}

if (typeof document.addEventListener !== 'undefined' &&	typeof hidden !== 'undefined') {
  var timer = null;
  document.addEventListener(visibilityChange, autologoutTimeout, false);
}
else
  setTimeout('window.location.replace("index.php?autologout")', 60*60*1000);     // 60 minut
</script>

<title>Administrace - <?php echo $lrs["title"]; ?></title>
</head>

<?php check_user2("administrace", true); ?>

<body>
<div id="lrs_wrapper">

<!-- hlavní menu -->
<div id="lrs_menu">

<?php
include ("content/defaults.php");

$page = (isSet($_GET["page"]) ? $_GET["page"] : null);
if (empty($page)) $page = "nastenka";

function active($id) {
  global $page;
  if ($page == $id) return "active";
}
?>

<div id="lrs_fixed">
  <a href="letters.php?page=about" class="logo" title="Tyto Letters"><img src="logo.png"></a>

  <a href="letters.php" class="button <?php echo active("nastenka"); ?>" id="menu_nastenka">
    nástěnka
    <img src="icons/board.png" class="icon">
  </a>

  <div class="nav">
    <a href="<?php echo $lrs["address"]."/"; ?>" target="_blank" style="padding-top: 5px;">navštívit web</a>
    <?php get_podmenu("nastenka"); ?>
  </div>
</div>


<?php get_menu(); ?>


<a href="letters.php?page=pluginy&co=<?php echo $def["pluginy"]; ?>" class="button <?php echo active("pluginy"); ?>" id="menu_pluginy">pluginy
<img src="icons/plugins.png" class="icon">
</a>

<nav class="pluginy" <?php if ($page != "pluginy") echo "style=\"display: none\""; ?>>
  <a href="letters.php?page=pluginy&co=prehled">přehled</a>
  <a href="letters.php?page=pluginy&co=katalog">katalog</a>
  <a href="letters.php?page=pluginy&co=aktualizace">aktualizace <span class="number"><?php echo $_COOKIE["plugin_updates"]; ?></span></a>
  <a href="letters.php?page=pluginy&co=editor">editor</a>
  <?php get_podmenu("pluginy"); ?>
</nav>


<a href="letters.php?page=vzhled&co=<?php echo $def["vzhled"]; ?>" class="button <?php echo active("vzhled"); ?>" id="menu_vzhled">vzhled
<img src="icons/template.png" class="icon">
</a>

<nav class="vzhled" <?php if ($page != "vzhled") echo "style=\"display: none\""; ?>>
  <a href="letters.php?page=vzhled&co=tema">téma</a>
  <a href="letters.php?page=vzhled&co=index"><?php flag("vzhled->index"); ?>úvodní strana</a>
  <?php if ($template["main-menu"]) { ?><a href="letters.php?page=vzhled&co=mainmenu"><?php flag("vzhled->mainmenu"); ?>main-menu</a><?php } ?>
  <a href="letters.php?page=vzhled&co=layout"><?php flag("vzhled->layout"); ?>rozložení</a>
  <a href="letters.php?page=vzhled&co=aktualizace">aktualizace</a>
  <a href="letters.php?page=vzhled&co=katalog">katalog</a>
  <a href="letters.php?page=vzhled&co=editor">editor</a>
  <?php get_podmenu("vzhled"); ?>
</nav>


<a href="letters.php?page=nastaveni&co=<?php echo $def["nastaveni"]; ?>" class="button <?php echo active("nastaveni"); ?>" id="menu_nastaveni">nastavení
<?php flag("nastaveni"); ?>
<img src="icons/settings.png" class="icon">
</a>

<nav class="nastaveni" <?php if ($page != "nastaveni") echo "style=\"display: none\""; ?>>
  <a href="letters.php?page=nastaveni&co=detaily"><?php flag("nastaveni->detaily"); ?>detaily</a>
  <a href="letters.php?page=nastaveni&co=udrzba"><?php flag("nastaveni->udrzba"); ?>údržba</a>
  <a href="letters.php?page=nastaveni&co=administrace"><?php flag("nastaveni->administrace"); ?>administrace</a>
  <?php get_podmenu("nastaveni"); ?>
</nav>

</div>
<!-- konec menu -->

<script>
var active;
$('a.button').each(function(){
  if ($(this).hasClass('active')) active = $(this).attr('id').replace('menu_', '');
});
  
$('a.button').click(function(e){
  if (e.which != 1) return; // stop executing if not left click
  
  thiz = $(this).attr('id').replace('menu_', '');
  if (thiz == active) return;
  if (thiz != 'nastenka') e.preventDefault();
  
  $('a.button').not(this).removeClass('hover');
  $('nav').not('nav.'+ thiz).not('nav.'+ active).slideUp(200);

  $(this).toggleClass('hover');
  $('nav.'+ thiz).slideToggle(200);
});
</script>


<div id="lrs_content">

<span id="lrs_logout">
  <?php
  $logout_link = "<a href=\"index.php?logout\" title=\"odhlásit se z administrace\">odhlásit</a>";
  if (isSet($_COOKIE["show_name"]))
    echo "<a href=\"letters.php?page=uzivatele&co=profil\">".$_COOKIE["show_name"]."</a> (".$logout_link.")";
  else
    echo $logout_link;
  ?>
</span>

<noscript><p class="error" id="lrs_noscript">Pro úplnou a bezchybnou funkčnost administrace musíte ve vašem prohlížeči povolit javascript.</p></noscript>

<?php
include ("content.php");

if (isset($_GET["page"])) {
  include_plugin_admin();
  
  echo "<br class=\"cleaner\">";
  
  // nastavení výchozí stránky pro menu
  if (isSet($_GET["co"]) and $def[$_GET["page"]] != $_GET["co"] and check_user2("admin", 0)) {
    echo "<form method=\"post\" action=\"#lrs_set_default\" id=\"lrs_set_default\">";
    echo "<input type=\"hidden\" name=\"nastavit\" value=\"true\">";
    echo "<a href=\"#lrs_set_default\" onclick=\"$('#lrs_set_default').submit()\">nastavit jako výchozí</a> pro toto menu";

    if (isSet($_POST["nastavit"])) {
      $page = $_GET["page"];
      $co = $_GET["co"];
      $new_row = "\$def[\"".$page."\"] = \"$co\";\r\n";

      $data = file("content/defaults.php");
      foreach ($data as $row)
        $final_data .= (preg_match("/".$page."/", $row) ? $new_row : $row);
    
      $soubor = FOpen("content/defaults.php", "w");
      $zapis = FWrite($soubor, $final_data);
      FClose($soubor);

      if ($zapis !== false) echo " - <span class=\"success\">nastaveno</span>";
      else echo " - <span class=\"error\">nenastaveno</span>";
    }
  }
}
?>
</div>

</div>

<div id="lrs_bottom">
  <a href="#lrs_top">nahoru</a>
  <a href="<?php echo LETTERS_WEB_URL; ?>">Letters <?php echo $lrs["letters_version"]; ?></a>
</div>

<script>
$('small').each(function(){
  $(this).attr('title', $(this).text());
});
</script>

</body>
</html>