<?php
if ($_GET["page"] == "clanky")
  include (PLUGINS_DIR."navigace/admin/clanky.php");
elseif ($_GET["page"] == "nastaveni")
  include (PLUGINS_DIR."navigace/admin/nastaveni_sidebar.php");
?>