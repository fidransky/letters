<h1>Publikované</h1>

<?php
check_user2("upravy_clanku", true);

$now = date("Y-m-d H:i:s");


// ukládání článku
if (isSet($_POST["ulozit"])) {
if (empty($_POST["text"]) or empty($_POST["nadpis"]))
  echo "<p class=\"error\">Musíte vyplnit text.</p>";
else {
  $data["nadpis"]     = $_POST["nadpis"];
  $data["kategorie"]  = $_POST["kategorie"];
  $data["text"]       = $_POST["text"];
  $data["tagy"]       = $_POST["tagy"];
  $data["zobrazit"]   = (int)isSet($_POST["zobrazit"]);

  if (isSet($_POST["zmenit_heslo"]))
    $data["heslo"]    = (empty($_POST["heslo"]) ? null : sha1($_POST["heslo"]));
  
  include_plugin_admin(false, array("action" => "save"), $data, array("clanky"));
  
  $result = save_data($data, "clanky", "id='".$_POST["id"]."'");
  if ($result === true) echo "<p class=\"success\">Článek byl úspěšně uložen.</p>";
  else echo "<p class=\"error\">Článek nebyl uložen.</p>";
}}

// mazání článku
elseif (isSet($_POST["smazat"])) {
  $delete = delete_data("clanky", "id='".$_POST["id"]."'");
  if ($delete === true) echo "<p class=\"success\">Článek byl úspěšně smazán.</p>";
  else echo "<p class=\"error\">Článek nebyl smazán.</p>";
}


// úvodní zobrazení
if (get_count("clanky", "zverejneno=1 AND cas<=NOW()") == 0)
  echo "<p class=\"info\">Nemáte žádný článek.</p>";
else {

include (PLUGINS_DIR."clanky/scripts/list.php");
?>

<script src="<?php echo PLUGINS_DIR; ?>clanky/scripts/jquery.filter.js"></script>

<form method="post" style="float: left;">
<select name="id" size="1" id="select">
  <?php options_articles("id", "zverejneno=1 AND cas<=NOW()"); ?>
</select>

<input type="submit" name="upravit" value="upravit">
<input type="submit" name="smazat" value="smazat">
</form>

<br class="cleaner">

<?php
}

// úprava článku
if (isSet($_POST["upravit"])) {
  include (PLUGINS_DIR."clanky/scripts/get_article.php");
  $article = get_article("id='".$_POST["id"]."'");

  include (PLUGINS_DIR."clanky/admin/clanek_form.php");
?>

<p style="margin-top: 30px;">
<input type="submit" name="ulozit" value="Uložit">
</p>
</form>

<?php } ?>