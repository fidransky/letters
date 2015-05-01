<?php
echo bez_diakritiky("nenalezena.cz");
?>

<h1>Můj profil</h1>

<form class="lrs" method="post">
<p>
<label for="text1">Uživatelské jméno</label>
<input type="text" name="text1" id="text1" value="namka" class="input">
</p>

<p>
<label>IP adresa</label>
<span style="color: gray;">127.0.0.1</span>
</p>

<p>
<label for="select1">Role</label>
<select name="select1" id="select1">
  <option value="admin">Administrátor
  <option value="author">Přispěvatel
  <option value="reader">Čtenář
</select>
</p>

<input type="submit" value="Uložit">

<h3>Osobní údaje</h3>

<p>
<label for="text1">Jméno</label>
<input type="text" name="text1" id="text1" value="Pavel">
</p>

<p>
<label for="text1">Příjmení</label>
<input type="text" name="text1" id="text1" value="Fidranský">
</p>

<p>
<label for="text1">Přezdívka</label>
<input type="text" name="text1" id="text1" value="Ňamka">
</p>

<p>
<label for="select1">Zobrazit ve formátu</label>
<select name="select1" id="select1">
  <option value="admin">Pavel Fidranský
  <option value="author">Fidranský Pavel
</select>
<small>jak chcete jméno zobrazit na webu</small>
</p>

<p>
<label for="text">Osobní popis</label>
<textarea name="text" id="text" cols="100" rows="5">
&lt;strong&gt;Maecenas ut ante&lt;/strong&gt; eu velit laoreet tempor accumsan vitae nibh. Aenean commodo, tortor eu porta convolutpat elementum. Proin fermentum molestie erat eget vehicula. Aenean eget tellus mi. Fusce scelerisque odio quis ante bibendum sollicitudin. Suspendisse potenti. Vivamus quam odio, facilisis at ultrices nec, sollicitudin ac risus. Donec ut odio ipsum, sed tincidunt.
</textarea>
</p>

<input type="submit" value="Uložit">

<h3>Kontaktní údaje</h3>

<p>
<label for="text2">E-mail</label>
<input type="email" name="text2" id="text2" value="jsem@pavelfidransky.cz">
<small>kontaktní e-mail, nebude zobrazen</small>
</p>

<p>
<label for="text3">Web</label>
<input type="url" name="text2" id="text3" value="http://pavelfidransky.cz">
</p>

<input type="submit" value="Uložit">

<h3>Propojené účty</h3>

<p class="connected_account" style="width: 350px; height: 50px; padding: 15px; border: 1px solid #F3F3F3;"><img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-ash3/t5/49550_1199323067_6804_q.jpg" style="float: left; margin-right: 15px;">propojen s Facebook účtem <a href="http://www.facebook.com/pavel.fidransky">Pavel Fidranský</a><br><a href="http://test.letters.cz/letters/letters.php?page=uzivatele&amp;co=profil&amp;fb_logout#connected_accounts">zrušit propojení</a></p>

<h3>Nové heslo</h3>

<p>
<label for="text2">Nové heslo</label>
<input type="password" name="text2" id="text2">
<small>kontaktní e-mail, nebude zobrazen</small>
</p>

<p>
<label for="text3">Nové heslo znovu</label>
<input type="password" name="text2" id="text3">
<small>pro kontrolu napište nové heslo ještě jednou</small>
</p>

<input type="submit" value="Uložit">
</form>

<h3>Smazat účet</h3>

<form class="lrs" method="post">
<input type="submit" value="Smazat" disabled>
</form>

<?php
exit;

var_dump($_POST);
?>

<form class="lrs" method="post">
<p>
<label for="text1">Jméno</label>
<input type="text" name="text1" id="text1" value="whatever">
<small>uživatelské jméno pro přihlašování</small>
</p>

<p>
<label>IP adresa</label>
<span style="color: gray;">127.0.0.1</span>
</p>

<p>
<label for="select1">Role</label>
<select name="select1" id="select1">
  <option value="admin">Administrátor
  <option value="author">Přispěvatel
  <option value="reader">Čtenář
</select>
<small>role uživatele definuje jeho oprávnění</small>
</p>

<p>
<label for="radio1">Umístění</label>
<span>
  <label for="radio1"><input type="radio" name="radio1" id="radio1" name="position" value="nahore"><span></span> nahoře</label><br>
  <label for="radio2"><input type="radio" name="radio1" id="radio2" name="position" value="uprostred" checked><span></span> uprostřed</label><br>
  <label for="radio3"><input type="radio" name="radio1" id="radio3" name="position" value="dole"><span></span> dole</label>
</span>
<small>pozice avataru</small>
</p>

<p>
<label for="text2">Web</label>
<input type="url" name="text2" id="text2" value="http://pavelfidransky.cz" placeholder="http://">
</p>

<p>
<label for="checkbox1">Stav</label>
<label for="checkbox1"><input type="checkbox" name="checkbox1" id="checkbox1" value="checked"><span></span> schválený</label>
<small>pouze schválení uživatelé se mohou přihlásit</small>
</p>

<input type="submit" value="Uložit">
</form>