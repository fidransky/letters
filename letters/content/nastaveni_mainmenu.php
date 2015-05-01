<h1>Nastavení</h1>

<h2>Main-menu</h2>

<h3>Nový odkaz:</h3>
<?php
if (isSet($_POST["mainmenu_posted"])) {
  $type = $_POST["type"];
  if ($type == "page") {
    $pole = explode("; ", $_POST[$type]);
    $data["text"] = $pole[0];
    $data["address"] = $lrs["address"]."/".$pole[1];
  }
  elseif ($type == "other") {
    $data["text"] = strip_tags($_POST["text"]);
    $data["address"] = strip_tags($_POST["adresa"]);
  }
  else {
    $data = array();
    include_plugin_admin(false, array("action" => "save", "type" => $type), $data);
  }

  if (!isSet($data["text"]) or !isSet($data["address"]))
    echo "<p class=\"error\">Musíte vyplnit údaje nového odkazu.</p>";
  else {
    $data["order"] = get_count("main_menu") + 1;

    $save = save_data($data, "main_menu");
    if ($save === true) echo "<p class=\"success\">Odkaz byl úspěšně přidán.</p>";
    else echo "<p class=\"error\">Odkaz nebyl přidán.</p>";
  }
}
?>


<form method="post">
<p>
  <?php include_plugin_admin(false, array("action" => "radio")); ?>
  <input type="radio" name="type" value="page" id="rad_page" checked> <label for="rad_page">stránka</label><br>
  <input type="radio" name="type" value="other" id="rad_other" checked> <label for="rad_other">jiná adresa</label><br>
</p>

<div id="other">
  <label for="id_text">Text:</label><br>
  <input type="text" name="text" id="id_text" size="25" style="margin-bottom: 6px;"><br>

  <label for="id_adresa">Adresa:<br>
  <input type="url" name="adresa" id="id_adresa" size="50" style="margin-bottom: 6px;" placeholder="http://"><br>
</div>

<select name="page" id="page" size="1" style="display: none;">
  <option value="<?php echo "Úvod; "; ?>">Úvod
  <?php include_plugin_admin(false, array("action" => "page options")); ?>
</select>

<?php include_plugin_admin(false, array("action" => "select")); ?>

<input type="submit" name="mainmenu_posted" value="Uložit">
</form>

<script>
$('input[type=radio]').click(function(){
  var val = $(this).val();
  $('#'+ val).show();
  
  $('input[type=radio]').each(function(){
    if ($(this).val() != val) $('#'+ $(this).val()).hide();
  });
});
</script>


<h3>Změnit odkaz:</h3>
<?php
$pocet = get_count("main_menu");

// ukládání odkazu
if (isSet($_POST["ulozit_posted"])) {
  $data["text"] = $_POST["text"];
  $data["address"] = $_POST["address"];
  $where = "id='".$_POST["id"]."'";
  
  $order = $_POST["order"];
  if ($order < 1) $order = 1;             // pokud někdo zadá nižší 'pořadí' než jedna, 'pořadí' se přiřadí právě jedna - položka se tedy zařadí na začátek menu
  if ($order > $pocet) $order = $pocet;   // pokud někdo zadá vyšší 'pořadí' než je 'počet' položek, 'pořadí' se přiřadí právě 'počet' - položka se tedy zařadí na konec menu
  $order2 = $_POST["order2"];

  if ($order != $order2) {
    if ($order < $order2)
      for ($i = $order2; $i > $order; $i--) {
        if ($i == $order) continue;
        @save_data(array("order" => $i), "main_menu", "`order`='".($i-1)."'");
      }
    else
      for ($i = 1; $i < $order; $i++) {
        if ($i == $order) continue; 
        @save_data(array("order" => $i), "main_menu", "`order`='".($i+1)."'");
      }

    $data["order"] = $order;
  }
  
  $save = save_data($data, "main_menu", $where);
  if ($save === true) echo "<p class=\"success\">Odkaz byl úspěšně změněn.</p>";
  else echo "<p class=\"error\">Odkaz nebyl změněn.</p>";
}

// mazání odkazu
if (isSet($_POST["smazat_posted"])) {
  $id = $_POST["id"];

  $poradi = array_shift(array_shift(get_data("`order`", "main_menu", array("where" => "id='".$id."'"), "row")));
  if ($poradi < $pocet)
    for ($i = $poradi; $i <= $pocet; $i++)
      @save_data(array("order" => $i), "main_menu", "`order`='".($i+1)."'");

  $delete = delete_data("main_menu", "id='".$id."'");
  if ($delete === true) echo "<p class=\"success\">Odkaz byl úspěšně smazán.</p>";
  else echo "<p class=\"error\">Odkaz nebyl smazán.</p>";
}


// úvodní zobrazení 
if ($pocet == 0)
  echo "<p class=\"info\">Nemáte žádný odkaz na úpravu.</p>";
else {
?>

<form method="post">
<select name="id">
  <?php
  foreach (get_data("id, text", "main_menu", array("order" => "`order`"), "assoc") as $link)
    echo "<option value=\"".$link["id"]."\">".$link["text"];
  ?>
</select>

<input type="submit" name="upravit_posted" value="upravit"><input type="submit" name="smazat_posted" value="smazat">
</form>

<?php
}

// úprava odkazu
if (isSet($_POST["upravit_posted"])) {
  $id = $_POST["id"];

  list($popis, $adresa, $poradi) = array_shift(get_data("text, address, `order`", "main_menu", array("where" => "id='".$id."'"), "row"));
?>

<form method="post" action="#zmenit">
<p>
Popis:<br>
<input type="text" name="text" value="<?php echo $popis; ?>" size="25">
</p>

<p>
Adresa:<br>
<input type="text" name="address" value="<?php echo $adresa; ?>" size="50">
</p>

<p>
Pořadí:<br>
<input type="number" name="order" value="<?php echo $poradi; ?>" size="5" min="1" max="<?php echo $pocet; ?>">
</p>

<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="order2" value="<?php echo $poradi; ?>">

<input type="submit" name="ulozit_posted" value="Uložit">
</form>

<?php } ?>