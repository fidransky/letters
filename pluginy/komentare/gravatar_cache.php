<?php
// Cache for gravatar.com by dgx

include ("../../funkce/remote_file_get_contents.php");

// config
$gravatarURI = "http://www.gravatar.com/avatar/";
$cacheDir = "../../cache";
$expiration = 2*24*60*60; // 2 days
$default = @remote_file_get_contents($_GET['d']);

// is avatar cached?
$cacheFile = $cacheDir.'/'.$_GET['gravatar_id'];
$isCached = is_file($cacheFile);
$isExpired = $isCached && (time() - filemtime($cacheFile) > $expiration);
$img = NULL;

if (!$isCached || $isExpired)
{
    // download avatar
    $img = @remote_file_get_contents($gravatarURI.$_GET['gravatar_id'].'?d='.urlencode($_GET['d']).'&s='.$_GET['s']);

    // put into cache
    if ($img != NULL) {
      file_put_contents($cacheFile, $img);
    }
    // for PHP4 use: fwrite(fopen($cacheFile, 'wb'), $img);
}

// load from cache or empty GIF
if ($img == NULL) {
    $img = $isCached ? file_get_contents($cacheFile) : $default;
}

// send cache header
header("Cache-Control: max-age=".$expiration);

// detect correct mimetype
if ($img[1] == 'P') { header('Content-Type: image/png'); }
elseif ($img[1] == 'I') { header('Content-Type: image/gif'); }
else { header('Content-Type: image/jpeg'); }

// send image
echo $img;
?>