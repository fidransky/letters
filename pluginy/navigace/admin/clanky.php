<?php
if ($action == "show")
  echo "<label for=\"menu\" class=\"small_label\">zobrazit v sidebaru?</label>&nbsp;<input type=\"checkbox\" name=\"menu\" value=\"1\" id=\"menu\" ".($article["menu"] == 1 ? "checked" : null)."><br>";

elseif ($action == "save")
  $global["menu"] = (int)isSet($_POST["menu"]);
?>