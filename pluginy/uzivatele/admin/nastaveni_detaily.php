<?php if ($action == "show") { ?>

<p>
<label for="author">Autor:</label> <small>vyberte profil uživatele, který bude zobrazen jako autor webu</small><br>
<select id="author" name="author" size="1">
  <?php
  foreach (get_data("id,username,show_name", "uzivatele") as $user)
    echo "<option value=\"".$user["id"]."\"".($lrs["author"] == $user["id"] ? " selected" : null).">".$user["show_name"]." [".$user["username"]."]";
  ?>
</select>
</p>

<?php
}

elseif ($action == "save")
  $global["author"] = $_POST["author"];
?>