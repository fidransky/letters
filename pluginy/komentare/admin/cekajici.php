<h1>Čekající</h1>

<?php
check_user2("upravy_komentaru", true);

$where = "k.stav='cekajici'";
$nothing = "Nemáte žádné čekající komentáře.";

include ("komentare_edit.php");
?>