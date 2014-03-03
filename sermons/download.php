<?php

include("inc/data.inc");

$id = $_GET["id"];

$sMgr = new SermonMgr();
$s = $sMgr->getSermonById($id);

$s->downloads++;

$f = "mp3/" . $s->audio;
$file = "SBC-" . $s->date . "-" . $s->speaker . ".mp3";
$file = str_replace(" ", "-", $file);

// send headers to initiate a binary download

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=$file");
header("Content-Transfer-Encoding: binary");
header("Content-length: " . filesize($f));

readfile($f);

$sMgr->updateSermon($s);
?>