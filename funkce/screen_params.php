<?php
function browser($version = false, $ua = null) {
  if (empty($ua)) $ua = $_SERVER["HTTP_USER_AGENT"];
  $ua = strtolower($ua);

  if (detect_mobile_device($ua) == false) {
    if (preg_match("/maxthon/", $ua)) { $browser = "Maxthon"; }
    elseif (preg_match("/msie/", $ua) or preg_match("/trident/", $ua)) { $browser = "Internet Explorer"; }
    elseif (preg_match("/firefox/", $ua)) { $browser = "Firefox"; }
    elseif (preg_match("/konqueror/", $ua)) { $browser = "Konqueror"; }
    elseif (preg_match("/opera/", $ua) or preg_match("/opr/", $ua)) { $browser = "Opera"; }
    elseif (preg_match("/chrome/", $ua)) { $browser = "Chrome"; }
    elseif (preg_match("/safari/", $ua)) { $browser = "Safari"; }
    else { $browser = "Neznámý"; }

    if ($version == true) $version = round(version($ua, $browser));
    if ($version == true) $browser .= " ".$version;
  }
  else {
    if (preg_match("/opera mini/", $ua)) { $browser = "Opera Mini"; }
    elseif (preg_match("/opera mobi/", $ua)) { $browser = "Opera Mobile"; }
    elseif (preg_match("/iphone/", $ua) or preg_match("/ipod/", $ua) or preg_match("/ipad/", $ua)) { $browser = "Safari mobile"; }
    elseif (preg_match("/msie/", $ua)) { $browser = "Internet Explorer mobile"; }
    elseif (preg_match("/firefox/", $ua)) { $browser = "Firefox mobile"; }
    elseif (preg_match("/chrome/", $ua)) { $browser = "Chrome mobile"; }
    elseif (preg_match("/tizen/", $ua)) { $browser = "Tizen browser"; }
    elseif (preg_match("/android/", $ua)) { $browser = "Android browser"; }
    elseif (preg_match("/bb10/", $ua) or preg_match("/blackberry/", $ua)) { $browser = "BlackBerry browser"; }
    elseif (preg_match("/symbian/", $ua)) { $browser = "Symbian browser"; }
    else { $browser = "Neznámý (m)"; }
  }
  
  return $browser;
}

function version($ua, $browser) {
  if ($browser == "Internet Explorer") {
    if (preg_match("/msie/", $ua)) preg_match("/msie ([\d\.]+)/", $ua, $shoda);
    else preg_match("/rv:([\d\.]+)/", $ua, $shoda);
  }
  elseif ($browser == "Opera") {
    if (preg_match("/opr/", $ua)) preg_match("/opr\/([\d\.]+)/", $ua, $shoda);
    else preg_match("/version\/([\d\.]+)/", $ua, $shoda);
  }
  elseif ($browser == "Safari") preg_match("/version\/([\d\.]+)/", $ua, $shoda);
  else preg_match("/".$browser."\/([\d\.]+)/i", $ua, $shoda);
  
  return $shoda[1];
}

// inspirovano skriptem http://crazydog.cz/pro-web/php-scripty/zjisteni-operacniho-systemu-v-php/
function operating_system($ua = null) {
  if (empty($ua)) $ua = $_SERVER["HTTP_USER_AGENT"];
  $ua = strtolower($ua);

  if (detect_mobile_device($ua) == false) {
    if (preg_match("/windows/", $ua)) {
      if ((preg_match("/windows 8.1/", $ua)) or (preg_match("/windows nt 6.3/", $ua))) { $os = "Windows 8.1"; }
      elseif ((preg_match("/windows 8/", $ua)) or (preg_match("/windows nt 6.2/", $ua))) { $os = "Windows 8"; }
      elseif ((preg_match("/windows 7/", $ua)) or (preg_match("/windows nt 6.1/", $ua))) { $os = "Windows 7"; }
      elseif ((preg_match("/windows vista/", $ua)) or (preg_match("/windows nt 6.0/", $ua))) { $os = "Windows Vista"; }
      elseif ((preg_match("/windows xp/", $ua)) or (preg_match("/windows nt 5.1/", $ua))) { $os = "Windows XP"; }
      elseif (preg_match("/windows nt 5.2/", $ua)) { $os = "Windows 2003"; }
      elseif ((preg_match("/windows 2000/", $ua)) or (preg_match("/windows nt 5.0/", $ua))) { $os = "Windows 2000"; }
      elseif ((preg_match("/win 9x 4.90/", $ua)) or (preg_match("/windows me/", $ua))) { $os = "Windows ME"; }
      elseif ((preg_match("/windows 98/", $ua)) or (preg_match("/windows 4.10/", $ua)) or (preg_match("/win98", $ua))) { $os = "Windows 98"; }
      elseif ((preg_match("/windows 95/", $ua)) or (preg_match("/win95/", $ua))) { $os = "Windows 95"; }
      elseif (preg_match("/windows ce/", $ua)) { $os = "Windows CE"; }
      else { $os = "Windows"; }
    }
    elseif (preg_match("/linux/", $ua)) {
      if (preg_match("/ubuntu/", $ua)) { $os = "Ubuntu Linux"; }
      elseif (preg_match("/kubuntu/", $ua)) { $os = "Kubuntu Linux"; }
      elseif (preg_match("/xubuntu/", $ua)) { $os = "Xubuntu Linux"; }
      elseif (preg_match("/mint/", $ua)) { $os = "Linux Mint"; }
      elseif (preg_match("/debian/", $ua)) { $os = "Debian Linux"; }
      elseif (preg_match("/fedora/", $ua)) { $os = "Fedora Linux"; }
      elseif (preg_match("/gentoo/", $ua)) { $os = "Gentoo Linux"; }
      elseif (preg_match("/suse/", $ua)) { $os = "SuSE Linux"; }
      elseif (preg_match("/mandriva/", $ua)) { $os = "Mandriva Linux"; }
      else { $os = "Linux"; }
    }
    elseif (preg_match("/mac/", $ua)) {
      if (preg_match("/10_9/", $ua)) { $os = "Mac OS X Mavericks"; }
      elseif (preg_match("/10_8/", $ua)) { $os = "Mac OS X Mountain Lion"; }
      elseif (preg_match("/10_7/", $ua)) { $os = "Mac OS X Lion"; }
      elseif (preg_match("/10_6/", $ua)) { $os = "Mac OS X Snow Leopard"; }
      elseif (preg_match("/10_5/", $ua)) { $os = "Mac OS X Leopard"; }
      elseif (preg_match("/10_4/", $ua)) { $os = "Mac OS X Tiger"; }
      elseif (preg_match("/10_3/", $ua)) { $os = "Mac OS X Panther"; }
      else { $os = "Mac OS X"; }
    }
    elseif (preg_match("/qnx/", $ua)) { $os = "QNX"; }
    elseif (preg_match("/beos/", $ua)) { $os = "BeOS"; }
    elseif (preg_match("/os\/2/", $ua)) { $os = "OS/2"; }
    elseif (preg_match("/nuhk|googlebot|google web preview|seznam|yammybot|openbot|slurp|msnbot|ask jeeves\/teoma|ia_archiver/", $ua)) { $os = "Search Bot"; }
    else { $os = "Neznámý"; }
  }
  else {
    if (preg_match("/iphone/", $ua) or preg_match("/ipod/", $ua) or preg_match("/ipad/", $ua)) { $os = "Apple iOS"; }
    elseif (preg_match("/tizen/", $ua)) { $os = "Tizen"; }
    elseif (preg_match("/android/", $ua)) { $os = "Android"; }
    elseif (preg_match("/windows phone/", $ua)) { $os = "Windows Phone"; }
    elseif (preg_match("/bb10/", $ua)) { $os = "BlackBerry 10"; }
    elseif (preg_match("/blackberry/", $ua)) { $os = "BlackBerry OS"; }
    elseif (preg_match("/firefox/", $ua)) { $os = "Firefox OS"; }
    elseif (preg_match("/symbian/", $ua) or preg_match("/series 60/", $ua)) { $os = "Symbian"; }
    elseif (preg_match("/ppc/", $ua) or preg_match("/wm/", $ua) or preg_match("/windows/", $ua)) { $os = "Windows Mobile"; }
    elseif (preg_match("/maemo/", $ua)) { $os = "Maemo"; }
    elseif (preg_match("/meego/", $ua)) { $os = "MeeGo"; }
    elseif (preg_match("/bada/", $ua)) { $os = "Bada"; }
    elseif (preg_match("/webos/", $ua)) { $os = "WebOS"; }
    else { $os = "Neznámý (m)"; }
  }

  return $os;
}
?>