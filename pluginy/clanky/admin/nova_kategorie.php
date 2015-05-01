<h1>Kategorie</h1>

<?php
check_user2("tvorba_kategorii", true);

include (PLUGINS_DIR."clanky/scripts/list.php");

// ukládání kategorie
if (isSet($_POST["ulozit"])) {
  if (empty($_POST["jmeno"]))
    echo "<p class=\"error\">Musíte vyplnit jméno nové kategorie.</p>";
  else {
    include_once (FCE_DIR."check_alias.php");
  
    $data["jmeno"] = $_POST["jmeno"];
    $data["alias"] = check_alias(strtolower(bez_diakritiky($data["jmeno"])), "kategorie");
    $data["popis"] = $_POST["popis"];
    $data["parents"] = $_POST["parents"];

    $insert = save_data($data, "kategorie");
    if ($insert === true) echo "<p class=\"success\">Kategorie byla úspěšně přidána.</p>";
    else echo "<p class=\"error\">Kategorie nebyla přidána.</p>";
  }
}
?>

<form method="post">
<p>
Jméno:<br>
<input type="text" name="jmeno" size="30" class="input" required autofocus>
</p>

<p>
Popis:<br>
<input type="text" name="popis" size="80">
</p>

<p>
Nadřazená kategorie:<br>
<select name="parents">
  <option value="">(žádná)
  <?php options_categories("alias"); ?>
</select>
</p>

<input type="submit" name="ulozit" value="Uložit">
</form>