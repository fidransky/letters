<h1>Nastavení</h1>

<h2>Soubory</h2>

<?php
if (isSet($_POST["soubory_posted"])) {
  $data["files_allowed_types"] = strtolower($_POST["allowed_types"]);
  $data["files_max_size"] = $_POST["max_size"];
  $data["files_sort"] = $_POST["sort"];

  $save = save_settings($data);
  if ($save === true) echo "<p class=\"success\">Nastavení bylo úspěšně uloženo.</p>";
  else echo "<p class=\"error\">Nastavení nebylo uloženo.</p>";
}

// úvodní zobrazení
$set = get_settings("files_allowed_types, files_max_size, files_sort");

// vybere maximální možnou velikost uploadovaného souboru pro server (v MB)
function return_bytes($size_str) {
  switch (substr($size_str, -1)) {
    case 'M': case 'm': return (int)$size_str * 1048576;
    case 'K': case 'k': return (int)$size_str * 1024;
    case 'G': case 'g': return (int)$size_str * 1073741824;
    default: return $size_str;
  }
}

$max_upload = return_bytes(ini_get("upload_max_filesize"));
$max_post = return_bytes(ini_get("post_max_size"));
$memory_limit = return_bytes(ini_get("memory_limit"));
$upload_bytes = @min($max_upload, $max_post, $memory_limit);
?>

<form method="post">
<p>
<label for="max_size">Maximální velikost souborů:</label><br>
<input type="number" id="max_size" name="max_size" value="<?php echo $set["files_max_size"]; ?>" size="8" max="<?php echo $upload_bytes / 1024; ?>"> kB
</p>

<p>
<label for="allowed_types">Povolené soubory:</label><br>
<input type="text" id="allowed_types" name="allowed_types" value="<?php echo $set["files_allowed_types"]; ?>" size="80" maxlength="500">
</p>

<p>
<label for="rad_autor">Třídit do složek podle:</label><br>
  <input type="radio" name="sort" value="datum" id="rad_datum" <?php if ($set["files_sort"] == "datum") echo "checked"; ?>> <label for="rad_datum">roku / měsíce</label><br>
  <input type="radio" name="sort" value="none" id="rad_none" <?php if ($set["files_sort"] == "none") echo "checked"; ?>> <label for="rad_none">netřídit</label><br>
</p>

<input type="submit" value="Uložit" name="soubory_posted">
</form>