<?php
if (empty($_GET["id"])) exit();

include ("../../config.php");
mysql_query("UPDATE ".DB_PREFIX."nastaveni SET value='".implode(", ", $_GET["id"])."' WHERE name='sidebar_box_order'");
?>