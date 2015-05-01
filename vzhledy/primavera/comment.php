<div class="comment cleaner <?=$class?>" id="<?=$comment["id"]?>">
  <?php
  if ($gravatars == 1) echo "<img src=\"".$gravatar_url."\" class=\"gravatar\">";

  if (empty($comment["web"])) echo "<span class=\"name\">".$comment["jmeno"]."</span>"; 
  else echo "<a href=\"".$comment["web"]."\" class=\"name\">".$comment["jmeno"]."</a>";
  ?>

  <?=$comment["text"]?>

  <a href="<?=$permalink?>" class="posted" title="trvalý odkaz" rel="bookmark"><?=date("j. n. Y v H:i", strtotime($comment["cas"]))?></a>
  <a href="javascript:<?=$reply_link?>" class="reply"><?=__("reagovat")?> &raquo;</a>
</div>