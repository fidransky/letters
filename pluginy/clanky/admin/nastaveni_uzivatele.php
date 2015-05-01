<?php if ($action == "show") { ?>

<fieldset>
<h4>Články</h4>
<label for="psani_clanku">psaní článků:</label> <input type="checkbox" id="psani_clanku" name="rights[psani_clanku]" value="1" <?php echo $rights["psani_clanku"]; ?>><br>
<label for="upravy_clanku">úpravy článků:</label> <input type="checkbox" id="upravy_clanku" name="rights[upravy_clanku]" value="1" <?php echo $rights["upravy_clanku"]; ?>><br>
<label for="clanky_s_heslem">vstup do článků s heslem:</label> <input type="checkbox" id="clanky_s_heslem" name="rights[clanky_s_heslem]" value="1" <?php echo $rights["clanky_s_heslem"]; ?>> <small>povolí vstup do článků chráněných heslem bez jeho zadávání</small><br>
</fieldset>

<fieldset>
<h4>Kategorie</h4>
<label for="tvorba_kategorii">tvorba kategorií:</label> <input type="checkbox" id="tvorba_kategorii" name="rights[tvorba_kategorii]" value="1" <?php echo $rights["tvorba_kategorii"]; ?>><br>
<label for="upravy_kategorii">úpravy kategorií:</label> <input type="checkbox" id="upravy_kategorii" name="rights[upravy_kategorii]" value="1" <?php echo $rights["upravy_kategorii"]; ?>><br>
</fieldset>

<?php } ?>