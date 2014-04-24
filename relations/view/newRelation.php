<?php
require_once("../../config.php");
require_once("{$site["path"]}/relations/controller/relation_controller.php");
print newRelation($object);
?>