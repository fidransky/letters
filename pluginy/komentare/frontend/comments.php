<?php
list($comments, $approve, $order, $gravatars, $gravatars_cache) = get_settings("group=komentare", "row");
$now = date("Y-m-d H:i:s");

if ($comments == 1 and $article["komentare"] != 2) {

echo "<h2 class=\"komentare noprint cleaner\" id=\"komentare\">".__("Komentáře")."</h2>";

// ukládání
if (isSet($_POST["komentar_posted"])) {
if (empty($_POST["text"]) or empty($_POST["jmeno"]))
  echo "<p class=\"error\">".__("Musíte vyplnit všechny povinné údaje.")."</p>";
else {
if (!empty($_POST["email"]) and !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
  echo "<p class=\"error\">".__("Zadaný e-mail není platný.")."</p>";
else {
  $data["jmeno"] = $_POST["jmeno"];
  $data["email"] = $_POST["email"];
  $data["web"] = filter_var($_POST["web"], FILTER_VALIDATE_URL);
  $data["text"] = $_POST["text"];
  
  $default_stav = ($approve == 1 ? "cekajici" : "schvaleny");
  $include = include_plugins("captcha", array("action" => "check", "default_stav" => $default_stav), $data);
  if ($include == false) $data["stav"] = $default_stav;

  if (!array_key_exists("stav", $data))
    echo "<p class=\"error\">".__("Komentář nebyl přidán.")."</p>";
  else {
    include_once (FCE_DIR."screen_params.php");
    
    $data["ip"] = $_SERVER["REMOTE_ADDR"];
    $data["browser"] = browser(true);
    $data["os"] = operating_system();
    $data["cas"] = $now;
    $data["id_clanku"] = $article["id"];

    include_once (FCE_DIR."save_data.php");
    $save = save_data($data, "komentare");
    if ($save === true) {
      if ($approve == 0) echo "<p class=\"success\">".__("Komentář byl úspěšně přidán.")."</p>";
      else echo "<p class=\"success\">".__("Komentář byl úspěšně přidán a předán ke schválení.")."</p>";
      $_POST = null;
    }
    if ($save === false) echo "<p class=\"error\">".__("Komentář nebyl přidán.")."</p>";
  }
}}}


// zobrazení
if (get_count("komentare", "id_clanku='".$article["id"]."' AND stav='schvaleny'") == 0)
  echo "<p>".__("Zatím není vložen žádný komentář. Buďte první!")."</p>";
else {

echo "<script src=\"".PLUGINS_DIR."komentare/addtext.js\"></script>";

// jednotlivé komentáře
$odd = true;
$data = get_data("id, jmeno, email, web, browser, os, cas, text", "komentare", array("where" => "id_clanku='".$article["id"]."' AND stav='schvaleny'", "order" => "cas ".$order), "assoc");
foreach ($data as $i => $comment) {
  $permalink = $lrs["address"]."/".$article["alias"]."#".$comment["id"];

  $reply_link = "addtext('[".($i+1)."] ".$comment["jmeno"].": ');";

  // gravatary
  $default = $lrs["address"]."/soubory/gravatar.jpg";
  if ($gravatars_cache == 0) $gravatar_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($comment["email"])))."?d=".urlencode($default)."&s=50";
  elseif ($gravatars_cache == 1) $gravatar_url = PLUGINS_DIR."komentare/gravatar_cache.php?gravatar_id=".md5(strtolower(trim($comment["email"])))."&d=".urlencode($default)."&s=50";
  
  include_plugins("text editor", array("action" => "modify"), $comment);
  if (!array_key_exists("formatted", $comment)) $comment["text"] = "<p>".nl2br($comment["text"])."</p>";

  // text - převod odkazů na jiné komentáře na odkazy klikatelné
  $shody = array();
  @preg_match_all("/\[(\d{1,})\]{1}( [a-žA-Ž0-9_ ]{0,}[:]{1})?/", $comment["text"], $shody);
  $pocet_shod = count($shody[0]);
  
  if ($pocet_shod != 0) {
  for ($j=0; $j < $pocet_shod; $j++) {   // pozor na název proměnné! '$i' nelze použít
    $sel_id = get_data("id", "komentare", array("where" => "id_clanku='".$article["id"]."'", "order" => "cas ".$order, "limit" => $shody[1][$j]), "row");
    if ($sel_id != false) {
      $foo_id = array_shift($sel_id[$shody[1][0] - 1]);
      
      $pattern[$j] = "/\[".$shody[1][$j]."\]".$shody[2][$j]."/";
      $replace[$j] = "<a href=\"".$article["alias"]."#".$foo_id."\">".$shody[0][$j]."</a>";
    }
    $foo_id = null;
  }
  $comment["text"] = preg_replace($pattern, $replace, $comment["text"]);
  }

  // speciální CSS třídy
  $class = null;
  if ($odd === true) {  // lichý komentář
    $class .= "odd ";
    $odd = false;
  }
  else {
    $class .= "even ";
    $odd = true;
  }

  // zobrazení komentáře
  if (file_exists($template["path"]."comment.php")) include ($template["path"]."comment.php");
  else var_dump($comment);
}}


// zobrazení - formulář
echo "<h2 class=\"komentare noprint cleaner\">".__("Přidat komentář")."</h2>";

if ($article["komentare"] == 0)
  echo "<p>".__("Komentáře jsou pod tímto příspěvkem zakázány.")."</p>";
else {

// ověření, zda může neregistrovaný psát komentáře
if (!check_user2("psani_komentaru"))
  echo "<p>".__("Pro psaní komentářů nemáte dostatečné oprávnění.")."</p>";
else {

// pokud komentáře nejsou časově omezeny
if (!empty($article["komentare_limit"]) and $article["komentare_limit"] != 0 and $article["komentare_limit"] <= $now)
  echo "<p>".__("Přidávání komentářů je uzavřeno.")."</p>";
else {
?>

<form method="post" action="<?php echo get_address(); ?>#komentare" name="form" id="add-comment" class="noprint">
<p>
<label for="jmeno"><?php echo __("Jméno"); ?>:</label> <small><?php echo __("povinné"); ?></small><br>
<input type="text" name="jmeno" value="<?php if (isSet($_POST["jmeno"])) echo $_POST["jmeno"]; else echo $_COOKIE["show_name"]; ?>" id="jmeno" size="40" required>
</p>

<p>
<label for="email">E-mail:</label> <small><?php echo __("e-mail pro gravatary, nebude zobrazen"); ?></small><br>
<input type="email" name="email" value="<?php if (isSet($_POST["email"])) echo $_POST["email"]; else echo $_COOKIE["email"]; ?>" id="email" size="40">
</p>

<p>
<label for="web">Web:</label><br>
<input type="url" name="web" value="<?php if (isSet($_POST["web"])) echo $_POST["web"]; ?>" placeholder="http://" id="web" size="40">
</p>

<p>
<?php include_plugins("text editor", array("action" => "show-over", "editor" => "forum")); ?>
<textarea name="text" id="text" cols="62" rows="10"><?php if (isSet($_POST["text"])) echo $_POST["text"]; ?></textarea><br>
<?php include_plugins("text editor", array("action" => "show-under", "editor" => "forum")); ?>
</p>

<?php include_plugins("captcha", array("action" => "show")); ?>

<input type="submit" name="komentar_posted" value="<?php echo __("Odeslat"); ?>" title="Ctrl+Enter">
</form>

<script>
document.getElementById('text').onkeydown = function(e){
  if (e.keyCode === 17) {
    this.onkeydown = function(e){
      if (e.keyCode === 13) {
        document.form.submit();
        return false;
      }
    }
  }
}

/*
$('#text').keydown(function(e) {
  if (e.ctrlKey && e.keyCode === 13) {
    e.preventDefault();
    $('#add-comment').submit();
  }
});
*/
</script>

<?php
}}}
}
?>