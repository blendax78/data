<?php 
require_once("config.php");

$view["title"] = "Home";

require_once("{$site["path"]}/shared/view/header_view.php");?>

    <div class="container">

<?php require_once("{$site["path"]}/dashboard/view/dashboard_view.php");?>

    </div> <!-- /container -->

<?php require_once("{$site["path"]}/shared/view/footer_view.php");?>