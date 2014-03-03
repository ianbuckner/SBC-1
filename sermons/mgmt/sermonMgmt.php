<?php 

include("../inc/data.inc"); 
include("../inc/mp3tag.inc");

$speakerSelect = $_POST["speakerSelect"];
$day = $_POST["day"];
$month = $_POST["month"];
$year = $_POST["year"];
$title = utf8_encode(stripslashes(html_entity_decode($_POST["title"])));
$summary = utf8_encode(stripslashes(html_entity_decode($_POST["summary"])));
$refs = utf8_encode(stripslashes(html_entity_decode($_POST["refs"])));
$audio = null;
if (isset($_POST["audio"])) {
	$audio = $_POST["audio"];
}

if ($speakerSelect != "0") {
	// Existing speaker
	$speaker = $speakerSelect;
}
else {
	$speaker = $_POST["speaker"];	
	
	if ($speaker != NULL) {
		// New speaker provided
		$doc = new DOMDocument();
		$doc->load('../data/speakers.xml');  
		$s = $doc->createElement("speaker", $speaker);
		$doc->documentElement->appendChild($s);
		$doc->save('../data/speakers.xml');	
	}
}

if (($title != NULL) && ($speaker != NULL)) {
	$sMgr = new SermonMgr();
	
	$s = new Sermon();
	$s->date = $day . " " . $month . " " . $year;
	$s->title = $title;
	$s->speaker = $speaker;
	$s->summary = $summary;
	$s->refs = $refs;
	$s->audio = $audio;
	
	if (isset($_POST['id'])) {
		$s->id = $_POST['id'];
		
		$old = $sMgr->getSermonById($s->id);
		$audioFile =  $s->getAudioFilename();
		$audioPath = $s->getAudioFullPath();
		
		if (file_exists($audioPath)) {
			$s->audio = $audioFile;		
			$mp3 = new Mp3File($audioPath); 
			$mp3->setTitle($title);
			$mp3->setGenre("Speech");
			$mp3->setYear($s->getShortDate());
			$mp3->setArtist($speaker);
			$mp3->setCopyright($year . " Skipton Baptist Church");
			$mp3->setTitle3($refs);
			$mp3->setArtistURL("http://www.skiptonbaptistchirch.com/");
			$mp3->setAlbum("SBC Sermons");			
			$mp3->setComment($summary);
			$res = $mp3->saveFile();
		}
		$sMgr->updateSermon($s);
	}
	else {
		$sMgr->addSermon($s);
	}
}

Header("Location: index.php");

?>
