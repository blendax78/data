<?php

require_once("{$site["path"]}/relations/controller/relation_controller.php");

if (array_key_exists('newRelation',$_POST)){
	saveRelationPOST($_POST);
}
print listRelations($object);
print drawNewRelationModal($object);
print drawNewDocumentModal(strtolower(get_class($object)), $object->id);
?>
