<?php
// zpracování- mazání/schválení/spam
if (isSet($_REQUEST["akce"]) and !empty($_REQUEST["id"])) {
  $akce = $_REQUEST["akce"];
  $id = $_REQUEST["id"];

  if (is_array($id)) {
    $count = count($id);
    $id = implode(", ", $id);
    $where_id = "id IN (".$id.")";
  }
  else {
    $count = 1;
    $where_id = "id='".$id."'";
  }

  if ($count == 1) {
    $y = null;
    $msg_true = "Komentář byl";
    $msg_false = "Komentář nebyl";
  }
  else {
    $y = "y";
    $msg_true = "Komentáře byly";
    $msg_false = "Komentáře nebyly";
  }

  
  if ($akce == "smazat") {
    $del_cmt = delete_data("komentare", $where_id);
    if ($del_cmt === true) { echo ("<p class=\"success\">".$msg_true." smazán".$y.".</p>"); }
    else { echo "<p class=\"error\">".$msg_false." smazán".$y.".</p>"; }
  }

  elseif ($akce == "schvalit") {
    $upd_cmt = save_data(array("stav" => "schvaleny"), "komentare", $where_id);
    if ($upd_cmt === true) { echo ("<p class=\"success\">".$msg_true." schválen".$y.".</p>"); }
    else { echo "<p class=\"error\">".$msg_false." schválen".$y.".</p>"; }
  }

  elseif ($akce == "zamitnout") {
    $upd_cmt = save_data(array("stav" => "cekajici"), "komentare", $where_id);
    if ($upd_cmt === true) { echo ("<p class=\"success\">".$msg_true." zamítnut".$y.".</p>"); }
    else { echo "<p class=\"error\">".$msg_false." zamítnut".$y.".</p>"; }
  }

  elseif ($akce == "spam") {
    $upd_cmt = save_data(array("stav" => "spam"), "komentare", $where_id);
    if ($upd_cmt === true) { echo ("<p class=\"success\">".$msg_true." označen".$y." za spam.</p>"); }
    else { echo "<p class=\"error\">".$msg_false." označen".$y." za spam.</p>"; }
  }
}

// zpracování - odpověď
if (isSet($_POST["odpoved_posted"])) {
if (empty($_POST["text"]))
  echo "<p class=\"error\">Musíte zadat text.</p>";
else {
  include (FCE_DIR."screen_params.php");

  $data["stav"] = "schvaleny";
  $data["jmeno"] = $_COOKIE["show_name"];
  $data["email"] = $_COOKIE["email"];
  $data["web"] = $lrs["address"];
  $data["ip"] = $_SERVER["REMOTE_ADDR"];
  $data["browser"] = browser(true);
  $data["os"] = $operating_system;
  $data["cas"] = date("Y-m-j H:i:s");
  $data["text"] = $_POST["text"];
  $data["id_clanku"] = $_POST["id_clanku"];

  $save = save_data($data, "komentare");
  if ($save === true) echo "<p class=\"success\">Odpověď byla uložena.</p>";
  else echo "<p class=\"error\">Odpověď nebyla uložena.</p>";

  unset($data);
}}

// zpracování - úprava
if (isSet($_POST["uprava_posted"])) {
if (empty($_POST["jmeno"]) or empty($_POST["text"]))
  echo "<p class=\"error\">Musíte zadat všechny povinné údaje.</p>";
else {
  $id = $_POST["id"];
  $data["jmeno"] = $_POST["jmeno"];
  $data["web"] = $_POST["web"];
  $data["text"] = $_POST["text"];

  $save = save_data($data, "komentare", "id='".$id."'");
  if ($save === true) echo "<p class=\"success\">Komentář byl upraven.</p>";
  else echo "<p class=\"error\">Komentář nebyl upraven.</p>";

  unset($data);
}}


// úprava/odpověď
if (isSet($_GET["akce"]) and !empty($_GET["akce"]) and !empty($_GET["id"])) {
  $akce = $_GET["akce"];
  $id = $_GET["id"];

  if ($akce == "odpovedet") {
    list($jmeno, $id_clanku) = array_shift(get_data("jmeno, id_clanku", "komentare", array("where" => "id='".$id."'"), "row"));
    ?>
    
    <h3>Odpovědět na komentář</h3>

    <form method="post" action="letters.php?page=komentare&co=<?php echo $_GET["co"]; ?>#top" id="reply-comment">
    
    <?php include_plugins("text editor", array("action" => "show-over", "editor" => "forum")); ?>
    <textarea id="text" name="text" cols="85" rows="5" required autofocus>
    <?php
    foreach (get_data("id", "komentare", array("where" => "id_clanku='".$id_clanku."'"), "row") as $i => $comment)
      if ($id == $comment[0]) echo "[".($i+1)."] ".$jmeno.": ";
    ?>
    </textarea><br>
    <?php include_plugins("text editor", array("action" => "show-under", "editor" => "forum")); ?>

    <input type="hidden" name="id_clanku" value="<?php echo $id_clanku; ?>">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <input type="submit" name="odpoved_posted" value="Odeslat">
    <a href="letters.php?page=komentare&co=<?php echo $_GET["co"]."#".$id; ?>"><input type="button" value="Zrušit"></a>
    </form>
    
    <?php
    unset($jmeno, $id_clanku);
  }
  
  elseif ($akce == "upravit") {
    list($jmeno, $web, $text) = array_shift(get_data("jmeno, web, text", "komentare", array("where" => "id='".$id."'"), "row"));
    ?>
    
    <h3>Upravit komentář</h3>

    <form method="post" action="letters.php?page=komentare&co=<?php echo $_GET["co"]; ?>#top" id="edit-comment">
    
    <input type="text" name="jmeno" value="<?php echo $jmeno; ?>" size="25" required><br>
    <input type="url" name="web" value="<?php echo $web; ?>" placeholder="http://" size="25"><br>

    <?php include_plugins("text editor", array("action" => "show-over", "editor" => "forum")); ?>
    <textarea id="text" name="text" cols="85" rows="5" required autofocus><?php echo $text; ?></textarea><br>
    <?php include_plugins("text editor", array("action" => "show-under", "editor" => "forum")); ?>

    <input type="hidden" name="id" value="<?php echo $id; ?>">
    
    <input type="submit" name="uprava_posted" value="Uložit">
    <a href="letters.php?page=komentare&co=<?php echo $_GET["co"]."#".$id; ?>"><input type="button" value="Zrušit"></a>
    </form>
    
    <?php
    unset($jmeno, $web, $text);
  }
}


// úvodní zobrazení
if (get_count("komentare k", $where) == 0)
  echo "<p class=\"info\">".$nothing."</p>";
else {
  list($order, $gravatars_cache) = get_settings("comments_order, comments_gravatars_cache", "row");
?>

<form method="post" name="formular" id="formular">
<input type="checkbox" title="označit vše" class="uncheck_all checkbox" style="margin-bottom: 20px;">

<?php
$data = get_data("k.id,k.stav,k.jmeno,k.email,k.web,k.ip,k.browser,k.os,k.cas,k.text,k.id_clanku,c.nadpis,c.alias", "komentare k", array("join" => "clanky c ON k.id_clanku=c.id", "where" => $where, "order" => "k.cas ".$order), "row");
foreach ($data as $comment) { // to-do
  list($id, $stav, $jmeno, $email, $web, $ip, $browser, $OS, $cas, $text, $id_clanku, $nadpis, $alias) = $comment;

  // formátování adresy webu
  if (empty($web) or $web == "http://" or $web == "http:///" or $web == "https://" or $web == "ftp://")
    $web = null;
  else
    $web = "| <a href=\"".$web."\" target=\"_blank\">". (strlen($web) > 35 ? substr($web, 0, 35)."&hellip;" : $web) ."</a>";

  // gravatar
  $default = $lrs["address"]."/soubory/gravatar.jpg";

  if ($gravatars_cache == 0) $gravatar_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode($default)."&s=60";
  elseif ($gravatars_cache == 1) $gravatar_url = PLUGINS_DIR."komentare/gravatar_cache.php?gravatar_id=".md5(strtolower(trim($email)))."&d=".urlencode($default)."&s=60";

  // zobrazení
  echo "<div class=\"comment\" onmouseover=\"show('actions_".$id."')\" onmouseout=\"hide('actions_".$id."')\">";
  echo "<a name=\"".$id."\"></a>";
  echo "<img src=\"".$gravatar_url."\" class=\"gravatar\">";
  
  echo "<span class=\"meta\">".$jmeno." ".$web." | <a href=\"".$lrs["address"]."/".$alias."#".$id."\" class=\"permanent-link\" style=\"color: #000; text-decoration: none;\" title=\"trvalý odkaz\" target=\"_blank\">".date("j.n.Y v H:i", strtotime($cas))."</a> ";
  if (!empty($ip) and !empty($browser) and !empty($OS)) {
  ?>
  
  <span id="puvodni<?php echo $id; ?>" style="cursor: pointer;" onclick="hide('puvodni<?php echo $id; ?>'), show('detaily<?php echo $id; ?>')">| detaily&hellip;</span>
  <span id="detaily<?php echo $id; ?>" style="display: none;">
  <?php
  echo ("| <span title=\"IP adresa\">".$ip."</span> | <span title=\"prohlížeč\">".$browser."</span> | <span title=\"operační systém\">".$OS."</span>");
  ?>
  <img src="icons/cancel.png" class="close-comment-info" title="kliknutím zavřete" onclick="hide('detaily<?php echo $id; ?>'), show('puvodni<?php echo $id; ?>')">
  </span>
  
  <?php
  }
  echo "</span>";

  include_plugins("text editor", array("action" => "modify"), $comment);
  echo ($comment["formatted"] == true ? $text : "<p>".nl2br($text)."</p>");
  ?>

  <span class="url_clanek">článek: <a href="<?php echo ($lrs["address"]."/".$alias); ?>" target="_blank"><?php echo $nadpis; ?></a></span>

  <span id="actions_<?php echo $id; ?>" class="actions" style="display: none;">
    <input type="checkbox" name="id[]" value="<?php echo $id; ?>" class="checkbox">

    <a href="letters.php?page=komentare&co=<?php echo $_GET["co"]; ?>&akce=odpovedet&id=<?php echo $id; ?>#lrs_top">odpovědět</a>
    |&nbsp;<a href="letters.php?page=komentare&co=<?php echo $_GET["co"]; ?>&akce=upravit&id=<?php echo $id; ?>#lrs_top">upravit</a>
    <?php if ($stav == "schvaleny") { ?>
    |&nbsp;<a href="letters.php?page=komentare&co=<?php echo $_GET["co"]; ?>&akce=zamitnout&id=<?php echo $id; ?>">zamítnout</a>
    <?php } else { ?>
    |&nbsp;<a href="letters.php?page=komentare&co=<?php echo $_GET["co"]; ?>&akce=schvalit&id=<?php echo $id; ?>">schválit</a>
    <?php } if ($stav != "spam") { ?>
    |&nbsp;<a href="letters.php?page=komentare&co=<?php echo $_GET["co"]; ?>&akce=spam&id=<?php echo $id; ?>">spam</a>
    <?php } ?>
    |&nbsp;<a href="letters.php?page=komentare&co=<?php echo $_GET["co"]; ?>&akce=smazat&id=<?php echo $id; ?>">smazat</a>
  </span>

  <br class="cleaner">
  </div>
  <?php
  if ($id > $comments_last_checked) $comments_last_checked = $id;
}
?>

<input type="checkbox" title="označit vše" class="uncheck_all checkbox" style="margin: 10px 0 20px 0;"><br>

označené:&nbsp;
<?php if ($_GET["co"] != "schvalene") { ?>
<button type="submit" name="akce" value="schvalit">schválit</button>
<?php } if ($_GET["co"] != "cekajici" and $_GET["co"] != "spam") { ?>
<button type="submit" name="akce" value="zamitnout">zamítnout</button>
<?php } if ($_GET["co"] != "spam") { ?>
<input type="submit" name="akce" value="spam">
<?php } ?>
<input type="submit" name="akce" value="smazat">

</form>

<script>
$('.uncheck_all').click(function() {
  $('.checkbox').attr('checked', this.checked);
});

$('.checkbox:checked').click(function() {
  $('.uncheck_all:checked').attr('checked', this.checked);
});
</script>

<?php
}
?>