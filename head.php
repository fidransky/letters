<meta name="robots" content="all">

<meta name="keywords" content="<?php echo $lrs["keywords"]; ?>">
<meta name="description" content="<?php echo $lrs["description"]; ?>">
<meta name="generator" content="Letters <?php echo $lrs["letters_version"]; ?>">

<base href="<?php echo $lrs["address"]; ?>/">

<?php
$http_response_code = 200;
include_plugins("head");
?>

<script src="<?php echo FCE_DIR; ?>screen_set_cookies.js"></script>
<link rel="home" href="<?php echo $lrs["address"]; ?>" title="<?php echo __("Domovská stránka"); ?>">
