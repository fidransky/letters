<?php check_user2("admin", true); ?>

<h1>Tyto Letters</h1>

<p>
<h3>Verze systému:</h3>
Letters <?php echo $lrs["letters_version"]; ?>
</p>

<?php
if (isSet($template)) {
  echo "<p><h3>Vzhled:</h3>";
  echo "název: ".$template["name"]."<br>";
  echo "autor: ".$template["autor"];
  if (!empty($template["url"])) echo " (<a href=\"".$template["url"]."\" target=\"_blank\">url</a>)";
  echo "</p>";
}
?>

<p>
<h3>Nainstalované pluginy:</h3>
<?php echo get_count("pluginy")." (z toho aktivní: ".get_count("pluginy", "active=1").")"; ?>
</p>

<p>
<h3>PHP:</h3>
<?php
echo "verze: ".phpversion()." (<a href=\"".FCE_DIR."phpinfo.php\" target=\"_blank\">zobrazit</a> PHP info)<br>";
echo "safe_mode: ". (ini_get("safe_mode") ? "ano" : "ne") ."<br>";
echo "register_globals: ". (ini_get("register_globals") ? "ano" : "ne") ."<br>";
echo "cURL: ". (function_exists("curl_init") ? "ano" : "ne") ."<br>";
?>
</p>

<p>
<h3>MySQL verze:</h3>
<?php echo mysql_get_server_info(); ?>
</p>

<p>
<h3>Operační systém:</h3>
<?php echo str_replace("WIN", "Windows ", PHP_OS); ?>
</p>

<p>
<h3>Převzaté technologie:</h3>
<a href="http://glyphicons.com">GLYPHICONS</a> (CC)
</p>