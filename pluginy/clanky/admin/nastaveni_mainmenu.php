<?php if ($action == "radio") { ?>

  <input type="radio" name="type" value="clanek" id="rad_clanek"> <label for="rad_clanek">článek</label><br>
  <input type="radio" name="type" value="kategorie" id="rad_kategorie"> <label for="rad_kategorie">kategorie</label><br>

<?php
}
elseif ($action == "select") {
  include (PLUGINS_DIR."clanky/scripts/list.php");
?>

  <select name="clanek" id="clanek" size="1" style="display: none;">
    <?php options_articles("alias", "zverejneno=1 AND cas<=NOW()"); ?>
  </select>
  
  <select name="kategorie" id="kategorie" size="1" style="display: none;">
    <?php options_categories("alias"); ?>
  </select>

<?php
}

elseif ($action == "save" and ($type == "clanek" or $type == "kategorie")) {
  if ($type == "clanek") {
    $nadpis = get_data("nadpis", "clanky", array("where" => "alias='".$_POST["clanek"]."'"), "row");
    $global["text"] = $nadpis[0][0];
    $global["address"] = $lrs["address"]."/".$_POST["clanek"];
  }
  elseif ($type == "kategorie") {
    $global["text"] = category_real_name($_POST["kategorie"]);
    $global["address"] = $lrs["address"]."/kategorie/".$_POST["kategorie"];
  }
}
?>