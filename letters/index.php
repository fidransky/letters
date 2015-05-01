<?php
session_start();

// odhlášení
if ((isSet($_GET["logout"]) or isSet($_GET["autologout"])) and isSet($_SESSION["log"])) {
  // mazání sessions
  unset($_SESSION["log"], $_SESSION["id"], $_SESSION["role"]);
  @session_regenerate_id();
  
  // mazání cookies
  $cookie_path = str_replace("letters/index.php?logout", null, $_SERVER["REQUEST_URI"]);
  setcookie("show_name", null, time()-60, $cookie_path);
  setcookie("email", null, time()-60, $cookie_path);
  setcookie("permanent_login", null, time()-60, $cookie_path, null, false, true);
  
  header("Location: index.php?".(isSet($_GET["autologout"]) ? "auto" : null)."logout");
  exit ("<a href=\"index.php?".(isSet($_GET["autologout"]) ? "auto" : null)."logout\" title=\"continue\">&rarr;</a>");
}

// přihlášení
if (isSet($_SESSION["log"])) {
  header("Location: letters.php");
  exit ("<a href=\"letters.php\" title=\"continue\">&rarr;</a>");
}

// automatické přihlášení
if (isSet($_COOKIE["permanent_login"]) and !empty($_COOKIE["permanent_login"])) {
  header("Location: scripts/autologin.php");
  exit ("<a href=\"scripts/autologin.php\" title=\"continue\">&rarr;</a>");
}


// konfigurace databáze
(@include ("config.php")) or exit ("<p>The page you requested could not be loaded due to technical issues.<br><small>missing the \"config.php\" file</small></p>");


// definice konstant a vkládání funkcí
define("FCE_DIR", "../funkce/");
define("PLUGINS_DIR", "../pluginy/");

include (FCE_DIR."get_data.php");
include (FCE_DIR."lang.php");
include (FCE_DIR."include_plugins.php");
?><!doctype html>

<html>

<head>
<meta charset="utf-8">

<style>	
html { margin: 0; }

body {
  margin: 0 auto;
  width: 330px;
  font: 16px calibri; 
  color: black;
  border-top: 3px solid silver;
}

a { color: black; }

#top {
  width: 230px;
  padding: 60px 50px 60px 50px;
  text-align: center;
}

#top img {
  margin-bottom: 70px;
  border: 0;
}

#bottom {
  position: relative;
  background-color: #EEE;
  padding: 40px 50px 80px 50px;
}

.error { color: red; }

input,
select {
  font-size: 14px;
  padding: 3px 4px;
}

input { width: 218px; }

select { width: 230px; }

input[type=submit],
input[type=button] {
  float: right;
  width: auto;
  padding: 4px 6px;
}
</style>

<script src="<?php echo FCE_DIR; ?>jquery.min.js"></script>

<title>Administrace</title>
</head>

<body>

<div id="top">
  <img src="logo.png"><br>
  &laquo; <a href="../">hlavní strana</a><br>
</div>

<div id="bottom">
<?php
if (isSet($_GET["logout"])) echo "<p>Odhlášení proběhlo úspěšně.</p>";
elseif (isSet($_GET["autologout"])) echo "<p>Byl/a jste automaticky odhlášen/a.</p>";
elseif (isSet($_GET["wrong_password"])) echo "<p class=\"error\">Bylo zadáno špatné heslo.</p>";
elseif (isSet($_GET["unauthorized"])) echo "<p class=\"error\">Neoprávněný přístup.</p>";
elseif (isSet($_GET["no_user"])) echo "<p class=\"error\">Uživatel neexistuje.</p>";
elseif (isSet($_GET["stopped"])) echo "<p>Přihlášení bylo přerušeno uživatelem.</p>";

echo "<p id=\"login_switch\"></p>";
$count = include_plugins("login", array("action" => "admin"));
if ($count > 1)
  echo "<script src=\"scripts/multiple_login_forms.jquery.js\"></script>";
?>
</div>

</body>
</html>