<?php
if ($action == "show") {
  $set = get_settings("comments, comments_limit, comments_limit_days");
  
  if ($set["comments"] == 0)
    echo "<p class=\"info\" style=\"margin-bottom: 10px;\">Zobrazování komentářů je globálně vypnuto. (<a href=\"letters.php?page=nastaveni&co=komentare\">nastavit</a>)</p>";
?>

<p>
<label for="komentare">Komentáře:</label>
<input type="checkbox" id="komentare" name="komentare" value="1" <?php echo ((!isSet($article["komentare"]) or $article["komentare"] == 1) ? "checked" : null); ?> onclick="show_hide('komentare_on'),show_hide('komentare_off')"><br>

<span id="komentare_on" <?php if ($set["comments"] == 0 or (isSet($article["komentare"]) and $article["komentare"] != 1)) echo "style=\"display: none;\""; ?>>
  <span id="id_limit" <?php if ((!isSet($article["komentare_limit"]) and $set["comments_limit"] == 1) or (isSet($article["komentare_limit"]) and $article["komentare_limit"] != 0)) echo "style=\"display: none;\""; ?>>
    <label for="komentare_limit" class="small_label">časově omezit?</label>&nbsp;<input type="checkbox" id="komentare_limit" name="komentare_limit" value="1" onclick="hide('id_limit'),show('id_limit_days')"><br>
  </span>

  <span id="id_limit_days" <?php if ((!isSet($article["komentare_limit"]) and $set["comments_limit"] == 0) or (isSet($article["komentare_limit"]) and $article["komentare_limit"] == 0)) echo "style=\"display: none;\""; ?>>
    <input type="datetime-local" name="komentare_limit_days" size="19" maxlength="19" value="<?php if (isSet($article["komentare_limit"])) echo str_replace(" ", "T", $article["komentare_limit"]); else echo date("Y-m-d\TH:i", mktime(date("H"), date("i"), date("s"), date("m"), date("d")+$set["comments_limit_days"], date("Y"))); ?>"> <small>datum a čas zadávejte ve formě: RRRR-MM-DD HH:MM</small>
  </span>
</span>

<span id="komentare_off" <?php if (!isSet($article["komentare"]) or (isSet($article["komentare"]) and $article["komentare"] == 1)) echo "style=\"display: none;\""; ?>>
  <label for="komentare_show" class="small_label">vůbec nezobrazovat?</label>&nbsp;<input type="checkbox" id="komentare_show" name="komentare_show" <?php echo ($article["komentare"] == 2 ? "checked" : null); ?>>
</span>
</p>

<?php
}

elseif ($action == "save") {
  $global["komentare"] = (int)isSet($_POST["komentare"]);
  
  if ($global["komentare"] == 1) {
    if ($_POST["komentare_limit_days"] != 0)
      $global["komentare_limit"] = $_POST["komentare_limit_days"];
  }
  else
    $global["komentare"] = (isSet($_POST["komentare_show"]) ? 2 : 0);
}

elseif (isSet($_POST["smazat"]))
  @delete_data("komentare", "id_clanku='".$_POST["id"]."'");
?>