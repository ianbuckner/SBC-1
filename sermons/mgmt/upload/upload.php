<?php
 
include("../../inc/data.inc"); 
include("../../inc/mp3tag.inc");
require_once('../../lib/getid3/getid3.php');

$id = NULL;
$name = NULL;
$op = NULL;
	
if (isset($_GET["id"])) {
	$id = $_GET["id"];
}
if (isset($_GET["name"])) {
	$name = $_GET["name"];
}
if (isset($_GET["op"])) {
	$op = $_GET["op"];
}

if (($id == NULL) || ($name == NULL) || ($op == NULL)) {
	echo "-1";
}
else {
	$filename = "partial/" . $id . ".mp3";
	
	if ($op == "size") {
		$size = 0;
		
		if (file_exists($filename)) {
			$size = filesize($filename);
		}
		echo $size;
	}
	if ($op == "upload") {
		$data = file_get_contents('php://input');
		$fh = fopen($filename, 'a');
		fwrite($fh, $data);
		fclose($fh);
		echo "blah";
	}
	if ($op == "done") {
		$sMgr = new SermonMgr();
		$s = $sMgr->getSermonById($id);
		
		if ($s != NULL) {
			$s->audio = $s->getAudioFilename();
			$finalFile = $s->getAudioFullPath();	
			rename($filename, $finalFile);	
				
			$mp3 = new Mp3File($finalFile); 				
			$mp3->setTitle($s->title);
			$mp3->setGenre("Speech");
			$mp3->setYear($s->getShortDate());
			$mp3->setArtist($s->speaker);
			$mp3->setCopyright($s->getYear() . " Skipton Baptist Church");
			$mp3->setTitle3($s->refs);
			$mp3->setArtistURL("http://www.skiptonbaptistchirch.com/");
			$mp3->setAlbum("SBC Sermons");			
			$mp3->setComment($s->summary);
			$res = $mp3->saveFile();
			
			$getID3 = new getID3;
			$ThisFileInfo = $getID3->analyze($finalFile);
			$s->duration = @$ThisFileInfo['playtime_string'];		
			$sMgr->updateSermon($s);
			
			echo "done";
		}
		else {
			echo "No sermon";
			unlink($filename);
		}
	}
}
?>

