<h1>Článek</h1>

<?php
check_user2("psani_clanku", true);

$now = date("Y-m-d H:i:s");


// ukládání článku
if (isSet($_POST["publikovat_dele"]) or isSet($_POST["publikovat"]) or isSet($_POST["ulozit"])) {
if (empty($_POST["text"]) or empty($_POST["nadpis"]))
  echo "<p class=\"error\">Musíte vyplnit nadpis i text.</p>";
else {
  include_once (FCE_DIR."check_alias.php");
  
  $data["id"]         = $_POST["id"];
  $data["nadpis"]     = $_POST["nadpis"];
  $data["alias"]      = check_alias(strtolower(bez_diakritiky($data["nadpis"])), "clanky");
  $data["kategorie"]  = $_POST["kategorie"];
  $data["text"]       = $_POST["text"];
  $data["tagy"]       = $_POST["tagy"];
  $data["heslo"]      = (!empty($_POST["heslo"]) ? sha1($_POST["heslo"]) : null);
  $data["menu"]       = (int)isSet($_POST["menu"]);
  $data["zobrazit"]   = (int)isSet($_POST["zobrazit"]);

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
  
  // vkládání/úprava
  $where = null;
  if (get_count("clanky", "id='".$data["id"]."'") != 0) {
    $where = "id='".$data["id"]."'";
    unset($data["id"]);
  }

  $result = save_data($data, "clanky", $where);
  if ($result === true) {
    echo "<p class=\"success\">";
    if (isSet($_POST["publikovat_dele"])) echo ("<a href=\"".$lrs["address"]."/".$data["alias"]."\" target=\"_blank\">Článek</a> byl uložen do pořadníku a bude publikován ".date("j. n. Y, H:i", strtotime($data["cas"])).".");
    elseif (isSet($_POST["publikovat"])) echo ("<a href=\"".$lrs["address"]."/".$data["alias"]."\" target=\"_blank\">Článek</a> byl publikován.");
    elseif (isSet($_POST["ulozit"])) echo ("<a href=\"".$lrs["address"]."/".$data["alias"]."\" target=\"_blank\">Článek</a> byl uložen do konceptů.");
    echo "</p>";
  
    unset($_POST);  // smazání předaného pole aby se stejný článek dále neukládal do konceptů v případě otevřeného okna
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
  if ($delete === true) {
    echo "<p class=\"success\">Článek byl úspěšně zrušen.</p>";
    unset($_POST);
  }
  else
    echo "<p class=\"error\">Článek nebyl zrušen.</p>";
}

// autosaving
elseif (isSet($_POST["autosave"])) {
  include_once (FCE_DIR."check_alias.php");
  
  $data["id"]         = $_POST["id"];
  $data["nadpis"]     = $_POST["nadpis"];
  $data["alias"]      = check_alias(strtolower(bez_diakritiky($data["nadpis"])), "clanky");
  $data["kategorie"]  = trim($_POST["kategorie"]);
  $data["text"]       = $_POST["text"];
  $data["tagy"]       = $_POST["tagy"];
  $data["cas"]        = (!empty($_POST["cas_zverejneni"]) ? $_POST["cas_zverejneni"] : $now);
  $data["zverejneno"] = 0;

  $where = null;
  if (get_count("clanky", "id='".$data["id"]."'") != 0) {
    $where = "id='".$data["id"]."'";
    unset($data["id"]);
  }

  @save_data($data, "clanky", $where);
}

// úvodní zobrazení
include (PLUGINS_DIR."clanky/scripts/list.php");

$article = array("id" => array_shift(array_shift(get_data("MAX(id)+1", "clanky", array(), "row"))), "zobrazit" => 1);
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
      timer = setInterval('autosave(\'letters.php?page=clanky&co=novy_clanek\')', 1*60*1000);  // 1 minuta
  }

  if (typeof document.addEventListener !== 'undefined' &&	typeof hidden !== 'undefined') {
    var timer = setInterval('autosave(\'letters.php?page=clanky&co=novy_clanek\')', 1*60*1000);  // 1 minuta
    document.addEventListener(visibilityChange, autosaveInterval, false);
  }
  else
    setInterval('autosave(\'letters.php?page=clanky&co=novy_clanek\')', 3*60*1000);  // 3 minuty
});
</script>

<p id="autosave_msg" class="success" style="display: none;"></p>

<?php include (PLUGINS_DIR."clanky/admin/clanek_form.php"); ?>

<p>
Zobrazit:<br>
<select name="viditelnost">
  <option value="vzdy">zobrazit
  <option value="heslo">zobrazit pouze s heslem
  <option value="nikdy">nezobrazit
</select>
</p>

Heslo: <small>může mít maximálně 20 znaků</small><br>
<span id="zmenit_heslo" <?php if (!isSet($article["heslo"])) echo "style=\"display: none;\""; ?>><label for="zmenit_heslo_checkbox" class="small_label">změnit?</label>&nbsp;<input type="checkbox" name="zmenit_heslo" value="1" id="zmenit_heslo_checkbox" onclick="hide('zmenit_heslo'),show('heslo'),$('#heslo').focus()"></span>
<input type="text" id="heslo" name="heslo" <?php if (isSet($article["heslo"])) echo "style=\"display: none;\""; ?> size="25" maxlength="20">


<p>
Publikovat:<br>
<select name="cas">
  <option value="hned">hned
  <option value="pozdeji">zadat datum a čas publikace
</select>
</p>

zadat datum a čas publikace: <small>zadávejte ve formě: RRRR-MM-DD HH:MM</small><br>
<input type="datetime-local" name="cas" size="19" maxlength="19">


<p style="margin-top: 30px;">
<input type="submit" name="publikovat" value="publikovat hned">
<input type="submit" name="ulozit" value="uložit">
<input type="submit" name="zrusit" value="zrušit" id="cancel" style="display: none;">
</p>
</form>