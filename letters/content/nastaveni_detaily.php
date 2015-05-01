<h1>Nastavení</h1>

<h2>Detaily</h2>

<?php
if (isSet($_POST["ulozit"])) {
  $data["title"] = $_POST["title"];
  $data["description"] = $_POST["description"];
  $data["keywords"] = $_POST["keywords"];
  $data["timezone"] = $lrs["timezone"];
  
  include_plugin_admin(false, array("action" => "save"), $data);
  
  $save_details = save_settings($data);
  if ($save_details === true) {
    echo "<p class=\"success\">Detaily webu byly úspěšně aktualizovány.</p>";
    $lrs = get_details();
  }
  else
    echo "<p class=\"error\">Detaily webu nebyly aktualizovány.</p>";
}

// úvodní zobrazení
?>

<form method="post">
<p>
<label for="title">Titulek webu:</label> <small>titulek bude zobrazen v hlavičce</small><br>
<input type="text" id="title" name="title" value="<?php echo $lrs["title"]; ?>" size="30" maxlength="50" class="input" autofocus>
</p>

<p>
<label for="description">Popis:</label> <small>krátký text o zaměření webu</small><br>
<input type="text" id="description" name="description" value="<?php echo $lrs["description"]; ?>" size="50" maxlength="200">
</p>

<p>
<label for="keywords">Tagy:</label> <small>klíčová slova zadávejte s  <span class="help" title="dodržujte pravidlo mezery za každou čárkou">čárkami</span> mezi slovy</small><br>
<input type="text" id="keywords" name="keywords" value="<?php echo $lrs["keywords"]; ?>" size="50" maxlength="200">
</p>

<p>
<label for="timezone">Časová zóna:</label><br>
<select id="timezone" name="timezone">
  <option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein
  <option value="-11.0">(GMT -11:00) Midway Island, Samoa
  <option value="-10.0">(GMT -10:00) Hawaii
  <option value="-9.0">(GMT -9:00) Alaska
  <option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)
  <option value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)
  <option value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City
  <option value="-5.0">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima
  <option value="-4.0">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz
  <option value="-3.5">(GMT -3:30) Newfoundland
  <option value="-3.0">(GMT -3:00) Brazil, Buenos Aires, Georgetown
  <option value="-2.0">(GMT -2:00) Mid-Atlantic
  <option value="-1.0">(GMT -1:00) Azores, Cape Verde Islands
  <option value="0.0">(GMT) Western Europe Time, London, Lisbon, Casablanca
  <option value="1.0" selected>(GMT +1:00) Brussels, Copenhagen, Madrid, Paris
  <option value="2.0">(GMT +2:00) Kaliningrad, South Africa
  <option value="3.0">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg
  <option value="3.5">(GMT +3:30) Tehran
  <option value="4.0">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi
  <option value="4.5">(GMT +4:30) Kabul
  <option value="5.0">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent
  <option value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi
  <option value="5.75">(GMT +5:45) Kathmandu
  <option value="6.0">(GMT +6:00) Almaty, Dhaka, Colombo
  <option value="7.0">(GMT +7:00) Bangkok, Hanoi, Jakarta
  <option value="8.0">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong
  <option value="9.0">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk
  <option value="9.5">(GMT +9:30) Adelaide, Darwin
  <option value="10.0">(GMT +10:00) Eastern Australia, Guam, Vladivostok
  <option value="11.0">(GMT +11:00) Magadan, Solomon Islands, New Caledonia
  <option value="12.0">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka
</select>
</p>

<?php include_plugin_admin(false, array("action" => "show")); ?>

<input type="submit" value="Uložit" name="ulozit">
<input type="reset" value="Reset">
</form>
</p>