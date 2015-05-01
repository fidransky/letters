<form method="post" name="form">
<p>
Nadpis:<br>
<input type="text" id="nadpis" name="nadpis" value="<?php if (isSet($_POST["nadpis"])) echo $_POST["nadpis"]; elseif (isSet($article["nadpis"])) echo $article["nadpis"]; ?>" size="50" class="input" required autofocus>
</p>

<p>
Kategorie:<br>
<select id="kategorie" name="kategorie">
  <option value="">(žádná)
  <?php options_categories("alias", $article["kategorie"]); ?>
</select>
</p>

<p>
<?php include_plugins("text editor", array("action" => "show-over", "editor" => "admin")); ?>
<textarea name="text" id="text" style="width: 100%; height: 300px;"><?php if (isSet($_POST["text"])) echo $_POST["text"]; elseif (isSet($article["text"])) echo htmlSpecialChars($article["text"]); ?></textarea><br>
<?php include_plugins("text editor", array("action" => "show-under", "editor" => "admin")); ?>
</p>

<p>
Tagy: <small>klíčová slova zadávejte s <span class="help" title="dodržujte pravidlo mezery za každou čárkou">čárkami</span> mezi nimi</small><br>
<input type="text" id="tagy" name="tagy" value="<?php if (isSet($_POST["tagy"])) echo $_POST["tagy"]; elseif (isSet($article["tagy"])) echo $article["tagy"]; ?>" size="50" maxlength="200">
</p>

<?php
$global = array();
include_plugin_admin(false, array("action" => "show", "article" => $article), $global, array("clanky"));
?>

<label for="id_zobrazit" class="small_label">zobrazit ve výpisu článků?</label>&nbsp;<input type="checkbox" name="zobrazit" value="1" id="id_zobrazit" <?php if ($article["zobrazit"] == 1) echo "checked"; ?>><br>

<input type="hidden" name="id" id="id" value="<?php echo $article["id"]; ?>">