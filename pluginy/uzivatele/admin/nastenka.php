<?php
if (check_user2("upravy_uzivatelu")) {
  list($registration, $approve) = get_settings("users_registration, users_approve", "row");

  if ($registration == 1 and $approve == 1) {
    $count = get_count("uzivatele", "status='cekajici'");
    if ($count != 0) {
      echo "<div class=\"box\">";
      echo sprintf(sklonuj($count, "Na schválení čeká jeden uživatel.", "Na schválení čekají %d uživatelé.", "Na schválení čeká %d uživatelů.", false), $count);
      echo "<br><a href=\"letters.php?page=uzivatele&co=prehled\" class=\"small_label\">schválit uživatele &raquo;</a>";
      echo "</div>";
    }
  }
}
?>