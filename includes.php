<?php
include (FCE_DIR."get_data.php");
include (FCE_DIR."get_settings.php");
include (FCE_DIR."get_count.php");
include (FCE_DIR."get_title.php");

include (FCE_DIR."get_details.php");
include (FCE_DIR."get_template_info.php");
include (FCE_DIR."get_address.php");

include (FCE_DIR."lang.php");
include (FCE_DIR."sklonuj.php");
include (FCE_DIR."check_user2.php");
include (FCE_DIR."bez_diakritiky.php");

include (FCE_DIR."strip_magic_slashes.php");
include (FCE_DIR."remote_file_get_contents.php");
include (FCE_DIR."curl_file_get_contents.php");
include (FCE_DIR."generate_password.php");
include (FCE_DIR."rel_time.php");
include (FCE_DIR."detectmobile.php");
if (!function_exists("glob")) include (FCE_DIR."glob_alternative.php");

include (FCE_DIR."include_plugins.php");

$lrs = get_details();
$template = get_template_info();

@date_default_timezone_set($lrs["timezone"]);

if (!empty($_REQUEST["lang"])) $lang = $_REQUEST["lang"];
elseif (!empty($_SESSION["lang"])) $lang = $_SESSION["lang"];
else $lang = $lrs["language"];
define("LANG", $lang);
?>