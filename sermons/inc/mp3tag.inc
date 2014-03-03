<?php


class Mp3File {
	private $tags;

	function __construct($f) {
		$this->filename = $f;
	}

	function setTitle($title) {
		$this->tags["TIT2"] = $title;
	}

	function setTitle3($title) {
		$this->tags["TIT3"] = $title;
	}

	function setGenre($genre) {
		$this->tags["TCON"] = $genre;
	}

	function setYear($year) {
		$this->tags["TDRC"] = $year;
	}

	function setArtist($artist) {
		$this->tags["TPE1"] = $artist;
	}

	function setComment($s) {
		$this->tags["COMM"] = $s;
	}

	function setAlbum($s) {
		$this->tags["TALB"] = $s;
	}

	function setCopyright($s) {
		$this->tags["TCOP"] = $s;
	}

	function setArtistURL($s) {
		$this->tags["WOAR"] = $s;
	}

	function saveFile() {
		$res = false;

		if (file_exists($this->filename)) {
			$f = fopen($this->filename, "rb");

			if ($f != NULL) {
				$ident = fread($f, 3);

				if ($ident = "ID3") {
					// its got an IDv3 tag section at the start
					$maj = fread($f, 1);
					$min = fread($f, 1);
					$flags = fread($f, 1);
					$size = fread($f, 4);
					$a = unpack("N", $size);
					$size = $a[1];
					//echo "header size: $size<br>";
					// move past the ID3 tag
					fseek($f, $size, SEEK_CUR);
					$filesize = filesize($this->filename);
					$contents = fread($f, $filesize-$size-10);
					fclose($f);
					 
					// calculate size of new header
					$tagSize = 0;
					foreach ($this->tags as $k => $v) {
						// each tag frame has a 10 byte header and a value which is preceeded
						// by a zero byte
						$tagSize += 10 + strlen($v) + 1;
						if ($k == "COMM") {
							$tagSize += 5; // add on 3 byte language and null separator
						}
					}
					// echo "tag length: $tagSize<br>";
					$o = fopen("/tmp/temp.mp3", "wb");

					// tag header
					fwrite($o, "ID3");
					fwrite($o, "\4");  		// major
					fwrite($o, "\0");		// minor
					fwrite($o, $flags);
					fwrite($o, pack("N", 2128));
					 
					// frames in order
					$this->writeFrame($o, "TIT2");
					$this->writeFrame($o, "TALB");
					$this->writeFrame($o, "TCON");
					$this->writeFrame($o, "TPE1");
					$this->writeFrame($o, "TDRC");
					$this->writeFrame($o, "TIT3");
					$this->writeFrame($o, "WOAR");
					$this->writeFrame($o, "TCOP");
					$this->writeFrame($o, "COMM");
						
					for ($i=0; $i<2128-$tagSize;$i++) {
						fwrite($o, "\0");
					}
					 
					// audio content
					fwrite($o, $contents);
					fclose($o);
					//	rename("/tmp/temp.mp3", $this->filename);
					 
					$res = true;
				}
			}
		}
		return $res;
	}

	function writeFrame($o, $k) {
		$v = $this->tags[$k];

		if ($v != NULL) {
			$framelen = strlen($v)+1;
			if ($k == "COMM") {
				$framelen += 5; // add on 3 byte language and null separator
			}
			fwrite($o, $k);
			fwrite($o, pack("N", $framelen));
			fwrite($o, "\0"); // flags
			fwrite($o, "\0"); // flags
			fwrite($o, "\0"); // null start
			if ($k == "COMM") {
				// prefix data with language code
				fwrite($o, "eng"); 	// language code
				fwrite($o, "\0"); 	// null separator
				fwrite($o, $v);
				fwrite($o, "\0"); 	// null separator
			}
			else {
				fwrite($o, $v);
			}
		}
	}


	function getDuration() {
		$duration = "unknown";

		if (file_exists($this->filename)) {
			$f = fopen($this->filename, "rb");
			
			if ($f != NULL) {
				$frameOffset = 0;
				$ident = fread($f, 3);
				
				if ($ident = "ID3") {
					// its got an IDv3 tag section at the start
					$maj = fread($f, 3); // major, minor version and flags
					$size = fread($f, 4);
					$a = unpack("N", $size);
					$frameOffset = $a[1] + 10; // 10 byte ID3 header
				}
				
				fseek(fseek($f, -128, SEEK_END));
				$tag = fread($f, 3);
				if ($tag == "TAG") {
					$frameOffset += 128;
				}			
				
				$vlookup = array("00" => "2.5", "10" => "2", "11" => "1");
				$llookup = array("01" => 3, "10" => 2, "11" => 1);
				$blookup = array(
					"1" => array( // only supporting MPEG v2 at the moment
    						"1" =>array("0001" => 32, "0010" => 64, "0011" => 96, "0100" => 128, "0101" => 160, "0110" => 192, "0111" => 224,
    							"1000" => 256, "1001" => 288, "1010" => 320, "1011" => 352, "1100" => 384, "1101" => 416, "1110" => 448),
    						"2" =>array("0001" => 32, "0010" => 48, "0011" => 56, "0100" => 64, "0101" => 80, "0110" => 96, "0111" => 112,
    							"1000" => 128, "1001" => 160, "1010" => 192, "1011" => 224, "1100" => 256, "1101" => 320, "1110" => 384),
    						"3" =>array("0001" => 32, "0010" => 40, "0011" => 48, "0100" => 56, "0101" => 64, "0110" => 80, "0111" => 96,
    							"1000" => 112, "1001" => 128, "1010" => 160, "1011" => 192, "1100" => 224, "1101" => 256, "1110" => 320)
							),
					"2" => array( // only supporting MPEG v2 at the moment
    						"1" =>array("0001" => 32, "0010" => 48, "0011" => 56, "0100" => 64, "0101" => 80, "0110" => 96, "0111" => 112,
    							"1000" => 128, "1001" => 144, "1010" => 160, "1011" => 176, "1100" => 192, "1101" => 224, "1110" => 256),
    						"2" =>array("0001" => 8, "0010" => 16, "0011" => 24, "0100" => 32, "0101" => 40, "0110" => 48, "0111" => 56,
    							"1000" => 64, "1001" => 80, "1010" => 96, "1011" => 112, "1100" => 128, "1101" => 144, "1110" => 160),
    						"3" =>array("0001" => 8, "0010" => 16, "0011" => 24, "0100" => 32, "0101" => 40, "0110" => 48, "0111" => 56,
    							"1000" => 64, "1001" => 80, "1010" => 96, "1011" => 112, "1100" => 128, "1101" => 144, "1110" => 160)
							)
				);
				$flookup = array(
					"1" =>array("00" => 44100, "01" => 48000, "10" => 32000),
					"2" =>array("00" => 22050, "01" => 24000, "10" => 16000),
					"2.5" =>array("00" => 11025, "01" => 12000, "10" => 8000)
				);
				
				$fileSize = filesize($this->filename);
				$data = file_get_contents($this->filename);
		//		for ($i=0; $i<$fileSize; $i++) {
				for ($i=$fileSize-5000; $i>0; $i--) {
					if (ord($data[$i]) == 0xff) {
						$fHeader = $data[$i] . $data[$i+1] . $data[$i+2] . $data[$i+3];						
						$fHeader = unpack("V", $fHeader);
						$fHeader = $fHeader[1];						
						$le = decbin($fHeader);
						$be = "";
						for ($a=0; $a<4; $a++) {
							$be = substr($le, $a*8, 8) . $be;
						}
					
						$pos = 	strpos($be, "11111111111");
	
						if (($pos !== false) && ($pos == 0)) { // 11 bits set for frame sync
							$ver = $vlookup[substr($be, 11, 2)];
							$layer = $llookup[substr($be, 13, 2)];
							$bitsBR = substr($be, 16, 4);
							$bitsF = substr($be, 20, 2);
							
							if (($bitsBR != "1111") && ($bitsF != "11")) {
															echo($be . "<br>");
								$bitrate = $blookup[$ver][$layer][$bitsBR];
								$frequency = $flookup[$ver][$bitsF];
								echo "filesize= $fileSize<br>";		
								echo "[$i]" . ord($data[$i]) . " ". ord($data[$i+1]) . " " . ord($data[$i+2]) . " " . ord($data[$i+3]) . "<br>";
								echo "version= $ver<br>";		
								echo "layer= $layer<br>";	
								echo "bitrate= $bitrate ($bitsBR)<br>";		
								echo "frequency= $frequency ($bitsF)<br>";		 
								$KBps = ($bitrate*1000)/8;
								$length = ($fileSize-$frameOffset) / $KBps;
								$duration = sprintf("%d:%02d", ($length /60), $length %60 );															
								break;
							}
						}
					}
				}
				
				fclose($f);
				return $duration;
				
				 
				$ident = fread($f, 3);

				if ($ident = "ID3") {
					// its got an IDv3 tag section at the start
					$maj = fread($f, 3); // major, minor version and flags
					$size = fread($f, 4);
					$a = unpack("N", $size);
					$size = $a[1];
					echo("tag length = $size<br>");
					// move past the ID3 tag
					 
					for ($i=0; $i<$size; $i+=4) {
						$frameHeader = fread($f, 4);
						$frameHeader = unpack("i", $frameHeader);
						$frameHeader = $frameHeader[1];
						$le = decbin($frameHeader);
						//echo("[$i] " . sprintf("here %s	", $le) . "<br>");
					}
					 
					//	fseek($f, $size, SEEK_CUR);
				}
				$framedataportion = filesize($this->filename) - ftell($f);

				$frameHeader = fread($f, 4);
				$frameHeader = unpack("i", $frameHeader);
				$frameHeader = $frameHeader[1];
				$le = decbin($frameHeader);
				$be = "";
				for ($i=0; $i<4; $i++) {
					$be = substr($le, $i*8, 8) . $be;
				}
				echo("data = " . $be . "<br>");
				$pos = 	strpos($be, "11111111111");
				//echo("pos = " . $pos . "<br>");
				if (($pos !== false) && ($pos == 0)) { // 11 bits set for frame sync
					//echo("here" . "<br>");
				$ver = $vlookup[substr($be, 11, 2)];
				$layer = $llookup[substr($be, 13, 2)];
				$bitrate = $blookup[$layer][substr($be, 16, 4)];
				$frequency = $flookup[$ver][substr($be, 20, 2)];
				 
				$KBps = ($bitrate*1000)/8;
				$length = $framedataportion / $KBps;
				$duration = sprintf("%d:%02d", ($length /60), $length %60 );
				}
				fclose($f);
			}
		}
		return $duration;
	}
}

?>