<?php
if (check_user2("upravy_komentaru")) {
  // přehled
  echo "<div class=\"box\">";

  $pocet = get_count("komentare");
  echo ($pocet == 0 ? "Nemáte" : "Máte celkem") ." <a href=\"letters.php?page=komentare&co=vsechny\">". sklonuj($pocet, "komentář", "komentáře", "komentářů") ."</a>";
  if ($pocet != 0) {
    $pocet_cekajici = get_count("komentare", "stav='cekajici'");
    echo ", z toho <a href=\"letters.php?page=komentare&co=cekajici\">". sklonuj($pocet_cekajici, "čekající komentář", "čekající komentáře", "čekajících komentářů")."</a>";
  }
  echo ".<br><span class=\"small_label\"><a href=\"letters.php?page=komentare&co=".$def["komentare"]."\">spravovat komentáře &raquo;</a></span>";
  
  echo "</div>";


  // nové komentáře
  if (!isSet($_COOKIE["comments_last_checked"])) {
    list($comments_last_checked) = get_settings("comments_last_checked", "row");
    ?>
    <script>
    document.cookie = 'comments_last_checked=<?php echo $comments_last_checked; ?>';
    </script>
    <?php
  }
  else
    $comments_last_checked = $_COOKIE["comments_last_checked"];

  if (get_count("komentare", "id>'".$comments_last_checked."' AND NOT stav='spam'") != 0) {
    echo "<div class=\"box\" id=\"new_comments\">";
    foreach (get_data("k.id, k.jmeno, k.cas, c.nadpis, c.alias", "komentare k", array("join" => "clanky c ON k.id_clanku=c.id", "where" => "k.id>'".$comments_last_checked."' AND NOT k.stav='spam'"), "assoc") as $comment) {
      echo "<a href=\"letters.php?page=komentare&co=nove#".$comment["id"]."\">".$comment["jmeno"]."</a> ke článku <a href=\"".$lrs["address"]."/".$comment["alias"]."\">".$comment["nadpis"]."</a><br>";
      echo "<a href=\"".$lrs["address"]."/".$comment["alias"]."/#".$comment["id"]."\" target=\"_blank\" class=\"small_label\" style=\"float: right; color: #bfbfbf;\" title=\"trvalý odkaz\">".date("j. n. Y, H:i", strtotime($comment["cas"]))."</a><br>";
    }
    echo "<span class=\"small_label\"><a href=\"letters.php?page=komentare&co=nove\">spravovat nové komentáře &raquo;</a></span>";
    echo "</div>";
  }
}
?>