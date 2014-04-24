<!DOCTYPE html>
<html lang="en">
  <head>	
    <meta charset="utf-8">
    <title>Brain Bank - <?php echo $view["title"];?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

	<link href="<?php echo $site["url"];?>/web/css/data.css" rel="stylesheet">
    <!-- Le styles -->
    <link href="<?php echo $site["url"];?>/web/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="<?php echo $site["url"];?>/web/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?php echo $site["url"];?>/web/bootstrap/ico/favicon.ico">
  </head>

<body>
<?php require_once("{$site["path"]}/shared/view/menubar_view.php");?>