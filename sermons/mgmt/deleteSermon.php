<?php 

include("../inc/data.inc"); 

$id = $_GET["id"];

if ($id != NULL) {
	$sMgr = new SermonMgr();
	
	$sMgr->deleteSermon($id);
}

Header("Location: index.php");

?>