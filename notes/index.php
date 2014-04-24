<?php 
require_once("../config.php");

$view["title"] = "Notes";

require_once("{$site["path"]}/shared/view/header_view.php");?>

    <div class="container">
<?php require_once("{$site["path"]}/notes/view/note_view.php");?>

    </div> <!-- /container -->

<?php require_once("{$site["path"]}/shared/view/footer_view.php");?>
