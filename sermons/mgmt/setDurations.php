<?php 

require_once('../inc/data.inc');
require_once('../lib/getid3/getid3.php');

$sMgr = new SermonMgr();
$sermons = $sMgr->doc->getElementsByTagName("sermon");
$getID3 = new getID3;

foreach ($sermons as $s)
{
	$sermon = $sMgr->newSermonFromNode($s);
	$file = $sermon->getAudioFullPath();
	if (file_exists($file)) {
		$ThisFileInfo = $getID3->analyze($file);
		$sermon->duration = @$ThisFileInfo['playtime_string'];
		$sMgr->updateSermon($sermon);
		echo "set duration for $file<br><br>";
	}
}
echo "done<br>";

?>