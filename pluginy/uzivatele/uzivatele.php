<?php
if ($kategorie == "head")
  echo "<link rel=\"author\" href=\"".$lrs["address"]."/uzivatele/".$lrs["author"]."\" title=\"".__("Autor")."\">";

elseif ($kategorie == "login")
  include (PLUGINS_DIR."uzivatele/login.php");

elseif ($kategorie == "uvodni") {
  if (preg_match("/uzivatele/", $uvodni)) {
    $stranka = str_replace("uzivatele=", null, $uvodni);

    if (empty($stranka)) include (PLUGINS_DIR."uzivatele/frontend/users.php");
    elseif ($stranka == "prihlaseni") include (PLUGINS_DIR."uzivatele/frontend/login.php");
    elseif ($stranka == "registrace") include (PLUGINS_DIR."uzivatele/frontend/registration.php");
    elseif ($stranka == "zapomenute_heslo") include (PLUGINS_DIR."uzivatele/frontend/lost_password.php");
  }
}

elseif ($kategorie == "sidebar") {
  if ($menu_popisky)
    echo "<h3><a href=\"uzivatele\">".__("Uživatelé")."</a></h3>";

  echo "<ul>";
  echo "<li><a href=\"uzivatele/prihlaseni\">".__("Přihlášení")."</a>";
  echo "<li><a href=\"uzivatele/registrace\">".__("Registrace")."</a>";
  echo "</ul>";
}

elseif ($kategorie == "meta") {
  include_once (PLUGINS_DIR."uzivatele/scripts/get_user_info.php");
  foreach (explode(", ", $global["autori"]) as $id)
    $global["meta"]["autori"][] = get_user_info($id, "id, username, show_name");
}

elseif ($kategorie == "page_uzivatele") {
  if ($action == "title") {
    if (isSet($_GET["stranka"])) {
      if ($_GET["stranka"] == "prihlaseni")
        echo "Přihlášení";
      elseif ($_GET["stranka"] == "registrace")
        echo "Registrace";
      elseif ($_GET["stranka"] == "zapomenute_heslo")
        echo "Zapomenuté heslo";
    }
    else {
      echo "Uživatelé";
      if (isSet($_GET["id"]))
        echo " &rsaquo; ". (string)array_shift(array_shift(get_data("show_name", "uzivatele", array("where" => "username='".mysql_real_escape_string($_GET["id"])."'"), "row")));
    }
  }
  else {
    if (isSet($_GET["stranka"])) {
      if ($_GET["stranka"] == "prihlaseni")
        include (PLUGINS_DIR."uzivatele/frontend/login.php");
      elseif ($_GET["stranka"] == "registrace")
        include (PLUGINS_DIR."uzivatele/frontend/registration.php");
      elseif ($_GET["stranka"] == "zapomenute_heslo")
        include (PLUGINS_DIR."uzivatele/frontend/lost_password.php");
    }
    else
      include (PLUGINS_DIR."uzivatele/frontend/users.php");
  }
}
?>