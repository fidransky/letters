<h1><?php echo __("Uživatelé"); ?></h1>

<?php
if (!check_user2("zobrazeni_profilu")) {
  if ($_SESSION["log"])
    echo "<p class=\"error\">".__("Pro zobrazení nemáte dostatečná práva.")."</p>";
  else
    echo "<p>".__("Pro zobrazení se musíte <a href=\"uzivatele/registrace\">zaregistrovat</a>.")."</p>";
}
else {

// tabulka všech uživatelů
if ((!isSet($_GET["id"]) or empty($_GET["id"])) and !isSet($_GET["delete"])) {
  echo "<table class=\"users\">";
  echo "<thead><tr><th style=\"width: 280px;\">".__("Uživatel")."</th><th style=\"width: 100px;\">".__("Hodnost")."</th><th style=\"width: 230px;\">Datum registrace</th></tr></thead>";
  echo "<tbody>";

  foreach (get_data("id,username,role,show_name,registration_date", "uzivatele", array("order" => "id"), "assoc") as $user) {
    $trans = array("admin" => "administrátor", "author" => "přispěvatel", "reader" => "čtenář");

    $misc = null;
    if ($lrs["author"] == $user["id"]) $misc .= " (".__("autor").")";
    if ($_SESSION["id"] == $user["id"]) $misc .= " (".__("to jste vy").")";

    echo "<tr><td><a href=\"uzivatele/".$user["username"]."\">".$user["show_name"]."</a>".$misc."</td><td>".__(strtr($user["role"], $trans))."</td><td>".$user["registration_date"]."</td></tr>";
    $misc = null;
  }

  echo "</tbody></table>";
}

// samostatný profil
elseif ((isSet($_GET["id"]) and !empty($_GET["id"])) and !isSet($_GET["delete"])) {
  include_once (PLUGINS_DIR."uzivatele/scripts/get_user_info.php");
  $user = get_user_info($_GET["id"]);

  // role
  $trans = array("admin" => "administrátor", "author" => "přispěvatel", "reader" => "čtenář");

  // gravatar
  $default = $lrs["address"]."/soubory/gravatar.jpg";
  $gravatar_url = "http://www.gravatar.com/avatar.php?gravatar_id=".md5(strtolower($user["email"]))."&default=".urlencode($default)."&size=60";
  
  // popis
  include_plugins("text editor", array("action" => "modify"), $user);

  // zobrazení
  echo "<h2>".$user["show_name"]."</h2>";
  echo "<p>";
  echo "<img src=\"".$gravatar_url."\" class=\"left gravatar\">";
  echo "<small>";
  echo __("registrován:")." ".$user["registration_date"]."<br>";
  echo __("naposledy přihlášen:")." ".$user["last_login"]."<br>";
  echo "#".$user["id"].", ".__(strtr($user["role"], $trans));
  echo "</small>";
  echo "</p>";
  echo $user["text"];
  echo "<h3 class=\"cleaner\">".__("Kontaktní údaje")."</h3>";
  echo "web: ". (empty($user["web"]) ? "&ndash;" : "<a href=\"".$user["web"]."\" target=\"_blank\">".$user["web"]."</a>") ."<br>";
}

}

// mazání uživatele
if (isSet($_GET["delete"]) and isSet($_GET["hash"])) {
if (empty($_GET["id"]) or empty($_GET["hash"]))
  echo "<p class=\"error\">".__("Chybí údaje pro smazání účtu.")."</p>";
else {
  $id = intval($_GET["delete"]);
  $hash = mysql_real_escape_string($_GET["hash"]);

  $data = get_data("username, verification_string", "uzivatele", array("where" => "id='".$id."'"), "row");
  if ($data == false)
    echo "<p class=\"error\">".__("Uživatel se zadaným ID není v databázi.")."</p>";
  else {
    list($username, $overeni) = $data;
    
    if ($hash != $overeni)
      echo "<p class=\"error\">".__("Ověřovací řetězce navzájem nesouhlasí.")."</p>";
    else {
      $delete = delete_data("uzivatele", "id='".$id."'");
      if ($delete === true) echo "<p class=\"success\">".__("Účet byl úspěšně smazán.")."</p>";
      else echo "<p class=\"error\">".__("Účet nebyl smazán.")."</p>";
    }
  }
}}
?>