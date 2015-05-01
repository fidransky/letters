<h1>Kategorie</h1>

<?php
check_user2("upravy_kategorii", true);

include (PLUGINS_DIR."clanky/scripts/list.php");

// ukládání kategorie
if (isSet($_POST["ulozit"])) {
  if (empty($_POST["jmeno"]))
    echo "<p class=\"error\">Nevyplnil/a jste jméno kategorie.</p>";
  else {
    include_once (FCE_DIR."check_alias.php");
  
    $data["jmeno"] = $_POST["jmeno"];
    $data["alias"] = check_alias(strtolower(bez_diakritiky($data["jmeno"])), "kategorie");
    $data["popis"] = $_POST["popis"];
    $data["parents"] = $_POST["parents"];

    $id = $_POST["id"];
    $update = save_data($data, "kategorie", "id='".$id."'");

    $data["parents"] .= "/".$data["alias"];
    $former_parents = $_POST["former_parents"];
    @save_data(array("parents" => $data["parents"]), "kategorie", "parents='".$former_parents."'");
    
    $update_clanky = save_data(array("kategorie" => $data["parents"]), "clanky", "kategorie='".$former_parents."'");

    if ($update === true and $update_clanky === true) echo "<p class=\"success\">Kategorie byla úspěšně změněna.</p>";
    else echo "<p class=\"error\">Kategorie nebyla změněna.</p>";
  }
}

// mazání kategorie
elseif (isSet($_POST["smazat"])) {
  $id = $_POST["id"];

  $kategorie = array_shift(get_data("alias,parents", "kategorie", array("where" => "id='".$id."'"), "assoc"));
  $alias = (empty($kategorie["parents"]) ? $kategorie["alias"] : ($kategorie["parents"]."/".$kategorie["alias"]));

  @delete_data("kategorie", "parents='".$alias."'");  // mazání podřazených kategorií
  $delete = delete_data("kategorie", "id='".$id."'"); // mazání zvolené kategorie
  $update = save_data(array("kategorie" => null), "clanky", "kategorie='".$alias."'");  // změna kategorie u článků

  if ($delete === true and $update === true) echo "<p class=\"success\">Kategorie byla úspěšně smazána.</p>";
  else echo "<p class=\"error\">Kategorie nebyla smazána.</p>";
}


// úvodní zobrazení
if (get_count("kategorie") == 0)
  echo "<p class=\"info\">Nemáte vytvořenou žádnou kategorii.</p>";
else {
?>

<form method="post">
<select name="id" size="1">
  <?php options_categories("id"); ?>
</select>

<input type="submit" name="upravit" value="upravit">
<input type="submit" name="smazat" value="smazat">
</form>

<?php
}

// úprava kategorie
if (isSet($_POST["upravit"])) {

$id = $_POST["id"];
$kategorie = array_shift(get_data("*", "kategorie", array("where" => "id='".$id."'"), "assoc"));
?>

<form method="post">
<p>
Jméno:<br>
<input type="text" size="30" name="jmeno" value="<?php echo $kategorie["jmeno"]; ?>" class="input" required autofocus>
</p>

<p>
Popis:<br>
<input type="text" size="80" name="popis" value="<?php echo $kategorie["popis"]; ?>">
</p>

<p>
Nadřazená kategorie:<br>
<select name="parents">
  <option value="">(žádná)
  <?php options_categories("alias", $kategorie["parents"], (empty($kategorie["parents"]) ? $kategorie["alias"] : ($kategorie["parents"]."/".$kategorie["alias"]))); ?>
</select>
</p>

<input type="hidden" name="former_parents" value="<?php echo ($kategorie["parents"]."/".$kategorie["alias"]); ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">

<input type="submit" name="ulozit" value="Uložit">
</form>

<?php } ?>