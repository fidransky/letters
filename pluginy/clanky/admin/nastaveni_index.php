<?php if ($action == "radio") { ?>

  <input type="radio" name="type" value="clanek" id="id_clanek" <?php if (preg_match("/clanek/", $uvodni)) echo "checked"; ?>> <label for="id_clanek">článek</label><br>
  <input type="radio" name="type" value="kategorie" id="id_kategorie" <?php if (preg_match("/kategorie/", $uvodni)) echo "checked"; ?>> <label for="id_kategorie">kategorie</label><br>

<?php
}
elseif ($action == "select") {
  include (PLUGINS_DIR."clanky/scripts/list.php");
?>

  <select name="clanek" id="clanek" <?php if (!preg_match("/clanek/", $uvodni)) echo "style=\"display: none;\""; ?>>
    <option value="posledni">nejnovější
    <?php options_articles("id", "zverejneno=1 AND cas<=NOW()", "cas DESC", str_replace("clanek=", null, $uvodni)); ?>
  </select>

  <select name="kategorie" id="kategorie" <?php if (!preg_match("/kategorie/", $uvodni)) echo "style=\"display: none;\""; ?>>
    <option value="">ze všech kategorií
    <?php options_categories("alias", str_replace("kategorie=", null, $uvodni)); ?>
  </select>

<?php } ?>