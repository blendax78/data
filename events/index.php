<?php 
require_once("../config.php");

$view["title"] = "Events";

require_once("{$site["path"]}/shared/view/header_view.php");?>

    <div class="container">
<?php require_once("{$site["path"]}/events/view/event_view.php");?>

    </div> <!-- /container -->

<?php require_once("{$site["path"]}/shared/view/footer_view.php");?>
