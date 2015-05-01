<h1>Nastavení</h1>

<h2>Články</h2>

<?php
if (isSet($_POST["clanky_posted"])) {
  $data["articles_per_page"] = $_POST["per_page"];
  $data["articles_from_subcategories"] = (int)isSet($_POST["from_subcategories"]);
  $data["articles_preview_type"] = $_POST["preview_type"];
  $data["articles_preview_count"] = $_POST["preview_count"];
  $data["articles_datetime_format"] = ($_POST["datetime_format"] == "simple" ? "simple" : $_POST["datetime_format_custom"]);
  $data["articles_search_type"] = $_POST["search_type"];
  $data["articles_rss_count"] = $_POST["rss_count"];
  $data["articles_rss_formatting"] = (int)isSet($_POST["rss_formatting"]);

  $save = save_settings($data);
  if ($save === true) echo "<p class=\"success\">Nastavení bylo úspěšně uloženo.</p>";
  else echo "<p class=\"error\">Nastavení nebylo uloženo.</p>";
}


// úvodní zobrazení
list(, $per_page, $from_subcategories, $preview_type, $preview_count, $datetime_format, $search_type, $rss_count, $rss_formatting) = get_settings("group=clanky", "row");
?>

<form method="post">
<p>
<label for="per_page">Článků na stránce:</label><br>
<input type="number" id="per_page" name="per_page" value="<?php if (!empty($per_page)) echo $per_page; elseif (isSet($_POST["per_page"])) echo $_POST["per_page"]; else echo 5; ?>" size="5" maxlength="5">
</p>

<p>
<label for="from_subcategories">Články z podřazených kategorií:</label> <small>v kategorii budou zobrazeny i články ze všech podřazených kategorií</small><br>
<input type="checkbox" id="from_subcategories" name="from_subcategories" value="1" <?php if ($from_subcategories == 1) echo "checked"; ?>>
</p>

<p style="margin-bottom: 5px;">
<label for="preview_type">Náhled článku:</label><br>
<select id="preview_type" name="preview_type">
  <option value="words" <?php if ($preview_type == "words") echo "selected"; ?> onclick="show('preview_count')">počet slov
  <option value="paragraphs" <?php if ($preview_type == "paragraphs") echo "selected"; ?> onclick="show('preview_count')">počet odstavců
  <option value="article" <?php if ($preview_type == "article") echo "selected"; ?> onclick="hide('preview_count')">celý článek
</select>&nbsp;
<input type="number" name="preview_count" id="preview_count" value="<?php if (isSet($_POST["preview_count"])) echo $_POST["preview_count"]; else echo $preview_count; ?>" size="5" maxlength="5" <?php if ($preview_type == "article") echo "style=\"display: none;\""; ?>>
</p>

<p>
<label for="rad_simple">Formát data a času:</label><br>
<input type="radio" name="datetime_format" value="simple" id="rad_simple" onclick="hide('custom_format')" <?php if ($datetime_format == "simple") echo "checked"; ?>> <label for="rad_simple">zjednodušený</label> <small>např. včera, před 3 měsíci</small><br>
<input type="radio" name="datetime_format" value="custom_format" id="rad_custom_format" onclick="show('custom_format')" <?php if ($datetime_format != "simple") echo "checked"; ?>> <label for="rad_custom_format">vlastní formátování</label><br>

<span id="custom_format" <?php if ($datetime_format == "simple") echo "style=\"display: none\""; ?>><input type="text" name="datetime_format_custom" value="<?php if (!empty($datetime_format) and $datetime_format != "simple") echo $datetime_format; elseif (isSet($_POST["datetime_format_custom"])) echo $_POST["datetime_format_custom"]; else echo "j. n. Y, H:i"; ?>" size="10"> <small>formát data a času pište podle <a href="http://php.net/manual/en/function.date.php" target="_blank">specifikace</a></small></span>
</p>

<h3>Vyhledávání:</h3>
<p>
<label for="rad_classic">Typ vyhledávání:</label><br>
<input type="radio" name="search_type" value="classic" id="rad_classic" <?php if ($search_type == "classic") echo "checked"; ?>> <label for="rad_classic">klasické</label><br>
<input type="radio" name="search_type" value="instant" id="rad_instant" <?php if ($search_type == "instant") echo "checked"; ?>> <label for="rad_instant">dynamické</label><br>
</p>

<h3>RSS zdroj:</h3>
<p>
<label for="rss_count">Maximální počet položek v RSS:</label><br>
<input type="number" id="rss_count" name="rss_count" value="<?php if (isSet($_POST["rss_count"])) echo $_POST["rss_count"]; elseif (!empty($rss_count)) echo $rss_count; else echo 10; ?>" size="5" maxlength="5" min="1">
</p>

<p>
<label for="rss_formatting">Povolit HTML formátování v RSS:</label><br>
<input type="checkbox" id="rss_formatting" name="rss_formatting" value="1" <?php if ($rss_formatting == 1) echo "checked"; ?>>
</p>

<input type="submit" value="Uložit" name="clanky_posted">
</form>