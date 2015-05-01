<!doctype html>

<html lang="<?php echo LANG; ?>">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width">

<?php include ("head.php"); ?>

<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Signika:300,600&subset=all"> <!-- subset=latin,latin-ext -->
<link rel="stylesheet" href="<?php echo $template["path"]; ?>style.css" media="all">

<!--[if lte IE 8]>
  <script src="<?php echo $template["path"]; ?>js/respond.min.js"></script>
  <script src="<?php echo $template["path"]; ?>js/html5shiv.js"></script>
<![endif]-->

<script src="<?php echo $template["path"]; ?>js/nav.js"></script>

<title><?php get_title($lrs); ?></title>
</head>


<body>
<?php include_plugins("nad strankou"); ?>

<!-- header -->
<div id="header">
  <!-- site title and description -->
  <h1><a href="<?php echo $lrs["address"]; ?>"><?php echo $lrs["title"]; ?></a></h1>
  <p><?php echo $lrs["description"]; ?></p>

  <!-- main menu -->
  <nav>
    <ul><?php include ("main_menu.php"); ?></ul>
  </nav>

  <!-- search input -->
  <?php
  if (get_count("pluginy", "name LIKE '%vyhledavani%' AND active=1") != 0)
    include (PLUGINS_DIR."vyhledavani/vyhledavani.php");
  ?>
</div>


<!-- content -->
<div id="content">
  <?php include ("content.php"); ?>
</div>

<div id="sidebar">
  <?php include ("sidebar.php"); ?>
</div>

<div id="footer" class="cleaner">
  <span class="left">&copy; <?php echo $lrs["title"]; ?>, <?php echo date("Y", time()); ?></span>
  <span class="right"><a href="http://letters.cz">Letters <?php echo $lrs["letters_version"]; ?></a></span>
</div>

<?php
include_plugins("pod strankou");
@mysql_close();
?>

</body>
</html>