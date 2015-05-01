<h1><?php echo __("Přihlášení"); ?></h1>

<script src="<?php echo FCE_DIR; ?>jquery.min.js"></script>

<?php
if ($_SESSION["log"])
  echo "<p>".__("Jste přihlášen/a jako")." ".$_COOKIE["show_name"]." (<a href=\"letters/index.php?logout\">".__("odhlásit")."</a>).</p>";
else
  echo "<div id=\"login_switch\"></div>";

echo "<div style=\"position: relative;\">";
$count = include_plugins("login", array("action" => "content"));
if ($count > 1)
  echo "<script src=\"letters/scripts/multiple_login_forms.jquery.js\"></script>";
echo "</div>";
?>

<!-- DONT CACHE ME PLEASE -->