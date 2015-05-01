<?php
if ($kategorie == "head") {
  echo "<link rel=\"alternate\" type=\"application/rss+xml\" href=\"".$lrs["address"]."/komentare/rss\" title=\"RSS komentářů\">";

  if (isSet($_GET["clanek"]))
    echo "<link rel=\"alternate\" type=\"application/rss+xml\" href=\"".$lrs["address"]."/komentare/rss/".$_GET["clanek"]."\" title=\"RSS komentářů článku\">";
}

elseif ($kategorie == "pod textem")
  include ("frontend/comments.php");

elseif ($kategorie == "meta")
  $global["meta"]["komentare"] = get_count("komentare", "id_clanku='".$global["id"]."' AND stav='schvaleny'");
?>