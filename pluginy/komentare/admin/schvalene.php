<h1>Schválené</h1>

<?php
check_user2("upravy_komentaru", true);

$where = "k.stav='schvaleny'";
$nothing = "Nemáte žádné schválené komentáře.";

include ("komentare_edit.php");
?>