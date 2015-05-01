<h1>Nastavení</h1>

<h2>Komentáře</h2>

<?php
if (isSet($_POST["komentare_posted"])) {
  $data["comments"] = (int)isSet($_POST["comments"]);
  $data["comments_approve"] = (int)isSet($_POST["approve"]);
  $data["comments_order"] = $_POST["order"];
  $data["comments_gravatars"] = (int)isSet($_POST["gravatars"]);
  $data["comments_gravatars_cache"] = (int)isSet($_POST["gravatars_cache"]);
  $data["comments_limit"] = (int)isSet($_POST["limit"]);
  $data["comments_limit_days"] = (int)$_POST["limit_days"];
  
  $save = save_settings($data);
  if ($save === true) echo "<p class=\"success\">Nastavení bylo úspěšně uloženo.</p>";
  else echo "<p class=\"error\">Nastavení nebylo uloženo.</p>";
}

// úvodní zobrazení
list($comments, $approve, $order, $gravatars, $gravatars_cache, $limit, $limit_days) = get_settings("group=komentare", "row");
?>
<form method="post">

<p>
<label for="comments">Povolit komentáře:</label><br>
<input type="checkbox" id="comments" name="comments" value="1" <?php if ($comments == 1) echo "checked"; ?>>
</p>

<p>
<label for="approve">Kontrolovat:</label><br>
<input type="checkbox" id="approve" name="approve" value="1" <?php if ($approve == 1) echo "checked"; ?>>
</p>

<p>
<label for="rad_desc">Řazení:</label><br>
<input type="radio" name="order" value="DESC" id="rad_desc" <?php if ($order == "DESC") echo "checked"; ?>> <label for="rad_desc">nové nahoře</label><br>
<input type="radio" name="order" value="ASC" id="rad_asc" <?php if ($order == "ASC") echo "checked"; ?>> <label for="rad_asc">nové dole</label><br>
</p>

<p>
<label for="gravatars">Vkládat gravatary:</label><br>
<input type="checkbox" id="gravatars" name="gravatars" value="1" onclick="show_hide('id_gravatars_cache', true)" <?php if ($gravatars == 1) echo "checked"; ?>>
</p>

<?php if (function_exists('curl_init')) { ?>
<p id="id_gravatars_cache" <?php if ($gravatars == 0) echo "style=\"display: none;\""; ?>>
<label for="gravatars_cache">Kešování gravatarů:</label><br>
<input type="checkbox" id="gravatars_cache" name="gravatars_cache" value="1" <?php if ($gravatars_cache == 1) echo "checked"; ?>><br>
<?php
if (!is_writable("../cache"))
  echo "<span class=\"small_label\">Vypadá to, že složka &bdquo;cache/&ldquo; pro ukládání gravatarů nemá práva k zápisu. Nastavte, prosím, práva na &bdquo;777&ldquo;.</span>";
?>
</p>
<?php } ?>

<p>
<label for="limit">Zakázat psaní komentářů</label> <?php echo sprintf("po %s dnech", "<input type=\"number\" name=\"limit_days\" value=\"".$limit_days."\" min=\"1\" max=\"60\" size=\"3\" maxlength=\"3\">"); ?><br>
<input type="checkbox" id="limit" name="limit" value="1" <?php if ($limit == 1) echo "checked"; ?>>
</p>

<input type="submit" name="komentare_posted" value="Uložit">
</form>