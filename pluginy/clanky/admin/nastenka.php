<?php
if (check_user2("author")) {
  echo "<div class=\"box\">";

  $pocet = get_count("clanky");
  echo ($pocet == 0 ? "Nemáte" : "Máte celkem") ." <a href=\"letters.php?page=clanky&co=publikovane\">". sklonuj($pocet, "článek", "články", "článků") ."</a>";
  if ($pocet != 0) {
    $pocet_koncepty = get_count("clanky", "zverejneno='0' OR (zverejneno='1' AND cas>NOW())");
    echo ", z toho <a href=\"letters.php?page=clanky&co=koncepty\">".sklonuj($pocet_koncepty, "rozepsaný článek", "rozepsané články", "rozepsaných článků")."</a>";
  }
  echo ".<br><a href=\"letters.php?page=clanky&co=novy_clanek\" class=\"small_label\">napsat nový článek &raquo;</a>";

  echo "</div>";
}
?>