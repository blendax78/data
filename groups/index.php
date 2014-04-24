<?php 
require_once("../config.php");

$view["title"] = "Groups";

require_once("{$site["path"]}/shared/view/header_view.php");?>

    <div class="container">
<?php require_once("{$site["path"]}/groups/view/group_view.php");?>

    </div> <!-- /container -->

<?php require_once("{$site["path"]}/shared/view/footer_view.php");?>
