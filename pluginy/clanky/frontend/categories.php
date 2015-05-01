<?php
echo "<h1>".__("Kategorie")."</h1>";

foreach (get_data("jmeno, alias, popis, parents", "kategorie") as $kategorie) {
  $path = (empty($kategorie["parents"]) ? null : $kategorie["parents"]."/").$kategorie["alias"];
  $desc = (empty($kategorie["popis"]) ? null : " &ndash; ".$kategorie["popis"]);
  
  echo "<a href=\"kategorie/".$path."\">".$kategorie["jmeno"]."</a>".$desc."<br>";
}
?>