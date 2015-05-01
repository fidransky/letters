<h1>Pořadník</h1>

<?php
check_user2("upravy_clanku", true);

$now = date("Y-m-d H:i:s");

// ukládání
if (isSet($_POST["publikovat"]) or isSet($_POST["ulozit"])) {
  $cas = $_POST["cas"];
  
  if (isSet($_POST["ulozit"]))
    $data["cas"] = (empty($cas) ? $now : $cas);
  elseif (isSet($_POST["publikovat"]))
    $data["cas"] = $now;
  
  @save_data($data, "clanky", "id='".$_POST["id"]."'");
}


// úvodní zobrazení
$where = "zverejneno=1 AND cas>NOW()";

if (get_count("clanky", $where) == 0)
  echo "<p class=\"info\">Nemáte žádný článek v pořadníku.</p>";
else {
  $articles = get_data("id,nadpis,alias,DATE_FORMAT(cas,'%Y-%m-%dT%H:%i') AS cas", "clanky", array("where" => $where, "order" => "cas"), "assoc");
  foreach ($articles as $article) {
?>

<h3><a href="<?php echo $lrs["address"]."/".$article["alias"]; ?>" target="_blank" title="zobrazit náhled"><?php echo $article["nadpis"]; ?></a></h3>

<form method="post">
<input type="datetime-local" value="<?php echo $article["cas"]; ?>" name="cas" size="19" maxlength="19">
<input type="submit" name="ulozit" value="uložit">

<input type="hidden" value="<?php echo $article["id"]; ?>" name="id">
</form>

<form method="post" action="letters.php?page=clanky&co=koncepty">
<input type="submit" name="upravit" value="upravit">
<input type="submit" name="publikovat" value="publikovat hned">

<input type="hidden" value="<?php echo $article["id"]; ?>" name="id">
</form>

<?php
  }
}
?>