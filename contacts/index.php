<?php 
require_once("../config.php");

$view["title"] = "Contacts";

require_once("{$site["path"]}/shared/view/header_view.php");?>

    <div class="container">
<?php require_once("{$site["path"]}/contacts/view/contact_view.php");?>

    </div> <!-- /container -->

<?php require_once("{$site["path"]}/shared/view/footer_view.php");?>
