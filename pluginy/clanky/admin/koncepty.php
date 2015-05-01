<h1>Koncepty</h1>

<?php
check_user2("upravy_clanku", true);

$now = date("Y-m-d H:i:s");


// ukládání článku
if (isSet($_POST["publikovat_dele"]) or isSet($_POST["publikovat"]) or isSet($_POST["ulozit"])) {
if (empty($_POST["text"]) or empty($_POST["nadpis"]))
  echo "<p class=\"error\">Musíte vyplnit nadpis i text.</p>";
else {
  $data["nadpis"]     = $_POST["nadpis"];
  $data["kategorie"]  = $_POST["kategorie"];
  $data["text"]       = $_POST["text"];
  $data["tagy"]       = $_POST["tagy"];
  $data["zobrazit"]   = (int)isSet($_POST["zobrazit"]);

  if ($data["nadpis"] != $_POST["nadpis2"]) {
    include_once (FCE_DIR."check_alias.php");
    $data["alias"]    = check_alias(strtolower(bez_diakritiky($data["nadpis"])), "clanky");
  }
  
  if (isSet($_POST["zmenit_heslo"]))
    $data["heslo"]    = (empty($_POST["heslo"]) ? null : sha1($_POST["heslo"]));
  
  if (isSet($_POST["ulozit"])) {
    $data["cas"] = $now;
    $data["zverejneno"] = 0;
  }
  elseif (isSet($_POST["publikovat"])) {
    $data["cas"] = $now;
    $data["zverejneno"] = 1;
  }
  elseif (isSet($_POST["publikovat_dele"]) and !empty($_POST["cas_zverejneni"])) {
    $data["cas"] = $_POST["cas_zverejneni"];
    $data["zverejneno"] = 1;
  }
  
  include_plugin_admin(false, array("action" => "save"), $data, array("clanky"));

  $result = save_data($data, "clanky", "id='".$_POST["id"]."'");
  if ($result === true) {
    $alias = strtolower(bez_diakritiky($data["nadpis"]));
    echo "<p class=\"success\">";
    if (isSet($_POST["publikovat_dele"])) echo ("<a href=\"".$lrs["address"]."/".$alias."\" target=\"_blank\">Článek</a> byl uložen do pořadníku a bude publikován ".date("j. n. Y, H:i", strtotime($data["cas"])).".");
    elseif (isSet($_POST["publikovat"])) echo ("<a href=\"".$lrs["address"]."/".$alias."\" target=\"_blank\">Článek</a> byl publikován.");
    elseif (isSet($_POST["ulozit"])) echo ("<a href=\"".$lrs["address"]."/".$alias."\" target=\"_blank\">Článek</a> byl uložen do konceptů.");
    echo "</p>";
  }
  else {
    echo "<p class=\"error\">";
    if (isSet($_POST["ulozit"])) echo "Článek nebyl uložen.";
    elseif (isSet($_POST["publikovat"])) echo "Článek nebyl publikován.";
    elseif (isSet($_POST["publikovat_dele"])) echo "Článek nebyl uložen.";
    echo "</p>";
  }
}}

// zrušení článku
elseif (isSet($_POST["zrusit"])) {
  $delete = delete_data("clanky", "id='".$_POST["id"]."'");
  if ($delete === true) echo "<p class=\"success\">Článek byl úspěšně zrušen.</p>";
  else echo "<p class=\"error\">Článek nebyl zrušen.</p>";
}

// autosaving
elseif (isSet($_POST["autosave"])) {
  $data["nadpis"]     = $_POST["nadpis"];
  $data["kategorie"]  = $_POST["kategorie"];
  $data["text"]       = $_POST["text"];
  $data["tagy"]       = $_POST["tagy"];
  $data["cas"]        = (!empty($_POST["cas_zverejneni"]) ? $_POST["cas_zverejneni"] : $now);
  $data["zverejneno"] = 0;
  
  @save_data($data, "clanky", "id='".$_POST["id"]."'");
}


// úvodní zobrazení
if (get_count("clanky", "zverejneno=0 OR (zverejneno=1 AND cas>NOW())") == 0)
  echo "<p class=\"info\">Nemáte rozepsaný žádný článek.</p>";
else {

include (PLUGINS_DIR."clanky/scripts/list.php");
?>

<script src="<?php echo PLUGINS_DIR; ?>clanky/scripts/filter.jquery.js"></script>

<form method="post" style="float: left;">
<select name="id" size="1" id="select">
  <?php options_articles("id", "zverejneno=0 OR (zverejneno=1 AND cas>NOW())"); ?>
</select>

<input type="submit" name="upravit" value="upravit">
<input type="submit" name="zrusit" value="zrušit">
</form>

<br class="cleaner">

<?php
}

// úprava článku
if (isSet($_POST["upravit"])) {
  include (PLUGINS_DIR."clanky/scripts/get_article.php");
  $article = get_article("id='".$_POST["id"]."'");

  if ($article["zverejneno"] == 1 and $article["cas"] > $now)
    echo "<p><b>Článek již byl zařazen do pořadníku, ale zatím nebyl publikován. Vlastnosti můžete měnit do ".date("j. n. Y, H:i", strtotime($article["cas"])).".</b></p>";
  
  if ($article["zverejneno"] == 0)
    $article["cas"] = null;
?>

<!-- autosaving -->
<script src="scripts/pagevisibility.js"></script>
<script src="<?php echo PLUGINS_DIR; ?>clanky/scripts/jquery.autosave.js"></script>
<script>
$(document).ready(function(){
  function autosaveInterval() {
    if (document[hidden]) {
      clearInterval(timer);
      timer = 0;
    }
    else
      timer = setInterval('autosave(\'letters.php?page=clanky&co=koncepty\')', 1*60*1000);  // 1 minuta
  }

  if (typeof document.addEventListener !== 'undefined' &&	typeof hidden !== 'undefined') {
    var timer = setInterval('autosave(\'letters.php?page=clanky&co=koncepty\')', 1*60*1000);  // 1 minuta
    document.addEventListener(visibilityChange, autosaveInterval, false);
  }
  else
    setInterval('autosave(\'letters.php?page=clanky&co=koncepty\')', 3*60*1000);  // 3 minuty
});
</script>

<p id="autosave_msg" class="success" style="display: none;"></p>

<?php include (PLUGINS_DIR."clanky/admin/clanek_form.php"); ?>

<input type="hidden" name="nadpis2" value="<?php echo $article["nadpis"]; ?>">

<p style="margin-top: 30px;">
<input type="submit" name="publikovat" value="publikovat hned">
<input type="submit" name="ulozit" value="uložit">
</p>

<p>
<strong>nebo&hellip;</strong><br>
zadat datum a čas publikace: <small>zadávejte ve formě: RRRR-MM-DD HH:MM</small><br>
<input type="datetime-local" id="cas_zverejneni" name="cas_zverejneni" size="19" maxlength="19" value="<?php if ($article["cas"] > $now) echo str_replace(" ", "T", $article["cas"]); ?>">&nbsp;<input type="submit" name="publikovat_dele" value="publikovat později">
</p>
</form>

<?php } ?>