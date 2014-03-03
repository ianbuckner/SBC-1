<?php 

class Sermon {
	public $date;
	public $speaker;
	public $title;
	public $id;
	public $refs;
	public $audio;
	public $downloads = 0;
	public $duration = "";
	
	function getShortDate() {
		$a = explode(" ", $this->date);
		$y = $a[2];
		$d = $a[0];
		$m = $a[1];
		
		for ($i=1; $i<13; $i++) {
			$timestamp = mktime(0, 0, 0, $i, 1, 2010);
	    	$month = date("F", $timestamp);
			if ($month == $m) {
				$m = $i;
			}
		}
		
		return $y . "-" . $m . "-". $d;
	}
	
	function getYear() {
		$a = explode(" ", $this->date);
		return $a[2];
	}
	
	function getMonth() {
		$a = explode(" ", $this->date);
		return $a[1];
	}
	
	function getDay() {
		$a = explode(" ", $this->date);
		return $a[0];
	}
	
	function gettimestamp() {
		$a = explode(" ", $this->date);
		$y = $a[2];
		$d = $a[0];
		$m = $a[1];
		
		for ($i=1; $i<13; $i++) {
			$timestamp = mktime(0, 0, 0, $i, 1, 2010);
	    	$month = date("F", $timestamp);
			if ($month == $m) {
				$m = $i;
			}
		}
		return mktime(0, 0, 0, $m, $d, $y);
	}
	
	function getAudioFilename() {
		$d = $this->getDay();
		$m = $this->getMonth();
		$y = $this->getYear();
		$id = $this->id;
		return str_replace(" ", "-", "SBC-$d-$m-$y-$id.mp3");
	}
	
	function getAudioFullPath() {
		return dirname(__FILE__) . "/../mp3/" . $this->getAudioFilename();
	}
}

class SermonMgr {
	public $doc = NULL;
	protected $dataFile = "";
	
	function __construct() {
		$this->doc = new DOMDocument();
		
		$path = dirname(__FILE__);
		$path = substr($path, 0, strlen($path)-3);
		$this->dataFile = $path . 'data/sermons.xml';
		$this->doc->load($this->dataFile);  
	}
	
   
	function getSermonById($id) {
		$res = NULL;
		if ($this->doc != NULL) {	
			
			$s = $this->doc->getElementById($id);
			if ($s != NULL) {
				$res = $this->newSermonFromNode($s);
			}
		}
		
		return $res;
	}
	
	
	function deleteSermon($id) {
		$n = $this->doc->getElementById($id);

		if ($n != null) {
			$this->doc->documentElement->removeChild($n);
			$this->doc->save($this->dataFile);
		}
		
		return ($n != NULL);
	}
	
	function addSermon($sObj) {
		$this->insertSermon($sObj, "i" . time());
	}
	
	function updateSermon($sObj) {
		$sNode = $this->doc->getElementById($sObj->id);
		if ($sNode != NULL) {
			$this->doc->documentElement->removeChild($sNode);
			$this->insertSermon($sObj, $sObj->id);
		}
	}
	
	private function insertSermon($sObj, $id) {
		$sNode = $this->doc->createElement("sermon");
		$sNode->appendChild($this->doc->createElement("date", $sObj->date));

		// $sNode->appendChild($this->doc->createElement("title", $sObj->title));
		$e = $this->doc->createElement("title");
		$cde = $this->doc->createCDATASection($sObj->title);
		$e->appendChild($cde);
		$sNode->appendChild($e);
			
		$sNode->appendChild($this->doc->createElement("speaker", $sObj->speaker));
		
		// $sNode->appendChild($this->doc->createElement("summary", $sObj->summary));
		$e = $this->doc->createElement("summary");
		$cde = $this->doc->createCDATASection($sObj->summary);
		$e->appendChild($cde);
		$sNode->appendChild($e);
		
		// $sNode->appendChild($this->doc->createElement("refs", $sObj->refs));
		$e = $this->doc->createElement("refs");
		$cde = $this->doc->createCDATASection($sObj->refs);
		$e->appendChild($cde);
		$sNode->appendChild($e);
		
		$sNode->appendChild($this->doc->createElement("audio", $sObj->audio));
		$sNode->appendChild($this->doc->createElement("downloads", $sObj->downloads));
		$sNode->appendChild($this->doc->createElement("duration", $sObj->duration));
		$sNode->setAttribute("xml:id", $id);
	
		$sDate = strtotime($sObj->date);
		$inserted = false;
		$ss = $this->doc->getElementsByTagName("sermon");
		foreach($ss as $s) {
			$dComp = $s->getElementsByTagName("date")->item(0)->nodeValue;	
			$sD = strtotime($dComp);
			if ($sD < $sDate) {
				$this->doc->documentElement->insertBefore($sNode, $s);
				$inserted = true;
				break;
			}
		}
		if ($inserted == false) {
			$this->doc->documentElement->appendChild($sNode);
		}
		$this->doc->save($this->dataFile);	
	}

	
	
	public function newSermonFromNode($n) {
		$smn = NULL;
		if ($n != NULL) {
			$smn = new Sermon();
			$smn->id = $n->getAttribute("xml:id");
			$smn->date = $n->getElementsByTagName("date")->item(0)->nodeValue;	
			$smn->speaker = $n->getElementsByTagName("speaker")->item(0)->nodeValue;	
			$smn->title = $n->getElementsByTagName("title")->item(0)->nodeValue;
			$smn->summary = $n->getElementsByTagName("summary")->item(0)->nodeValue;	
			$smn->refs = $n->getElementsByTagName("refs")->item(0)->nodeValue;
			$smn->audio = $n->getElementsByTagName("audio")->item(0)->nodeValue;
			$smn->downloads = $n->getElementsByTagName("downloads")->item(0)->nodeValue;
			$smn->duration = $n->getElementsByTagName("duration")->item(0)->nodeValue;
		}
		
		return $smn;
	}
}

function xmlEncode($s) {
	$s = str_replace("&", "&amp;", $s);
	$s = str_replace("<", "&lt;", $s);
	$s = str_replace(">", "&gt;", $s);
	$s = str_replace("'", "&apos;", $s);
	$s = str_replace("\"", "&quot;", $s);
	
	return $s;
}



?>