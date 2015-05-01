<?php
if ($action == "show") {
  list($admin_order) = get_settings("articles_admin_order", "row");
?>
<p>
  Seskupit články do skupin podle:&nbsp;<small>jak budou seskupeny roletkové seznamy článků při úpravách</small><br>
  <input type="radio" name="articles_admin_order" value="rok" id="rad_rok" <?php if ($admin_order == "rok") echo "checked"; ?>> <label for="rad_rok">roku</label><br>
  <input type="radio" name="articles_admin_order" value="mesic" id="rad_mesic" <?php if ($admin_order == "mesic") echo "checked"; ?>> <label for="rad_mesic">měsíce a roku</label><br>
  <input type="radio" name="articles_admin_order" value="kategorie" id="rad_kategorie" <?php if ($admin_order == "kategorie") echo "checked"; ?>> <label for="rad_kategorie">kategorie</label><br>
</p>
<?php
}

elseif ($action == "save")
  $global["articles_admin_order"] = $_POST["articles_admin_order"];
?>