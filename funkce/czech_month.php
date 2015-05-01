<?php
function czech_month($month) {
  if (is_numeric($month)) $trans = array(1 => "leden", "únor", "březen", "duben", "květen", "červen", "červenec", "srpen", "září", "říjen", "listopad", "prosinec");
  else $trans = array("January" => "leden", "February" => "únor", "March" => "březen", "April" => "duben", "May" => "květen", "June" => "červen", "July" => "červenec", "August" => "srpen", "September" => "září", "October" => "říjen", "November" => "listopad", "December" => "prosinec");

  return strtr($month, $trans);
}
?>