<article>
<h2><a href="<?=$article["alias"]?>" rel="bookmark"><?=$article["nadpis"]?></a></h2>

<footer class="meta">
  <span class="posted" title="<?=$article["cas"]?>"><?=rel_time($article["cas"])?></span>
  <span class="comments"><a href="<?=$meta["permalink"]?>#komentare"><?=__(sklonuj($meta["komentare"], "komentář", "komentáře", "komentářů"))?></a></span>
  <span class="permalink"><a href="<?=$meta["permalink"]?>" rel="bookmark">trvalý odkaz</a></span>
</footer>

<?=$article["text"]?>
</article>