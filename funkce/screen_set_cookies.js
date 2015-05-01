var date = new Date();
date.setTime(date.getTime() + 14*24*60*60*1000);
var expiration = date.toGMTString();

var i = 0;

if (document.cookie.indexOf('users_resolution') < 0) {
  document.cookie = 'users_resolution='+ screen.width +"x"+ screen.height +'; expires='+ expiration +'; path=/';
  i++;
}

if (document.cookie.indexOf('screen_colors') < 0) {
  document.cookie = 'screen_colors='+ screen.colorDepth +'; expires='+ expiration +'; path=/';
  i++;
}

if (document.cookie.indexOf('pixel_ratio') < 0) {
  document.cookie = 'pixel_ratio='+ window.devicePixelRatio +'; expires='+ expiration +'; path=/';
  i++;
}

if (i > 0) location.reload;