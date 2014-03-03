<?php 
include("../inc/data.inc");

$sMgr = new SermonMgr();

header("Content-Type: application/xml; charset=ISO-8859-1"); 
?>

<rss version="2.0"> 
	<channel>
		<title>Skipton Baptist Church Sermons</title>
		<link>http://www.skiptonbaptistchurch.com/</link>
		<description>This is a feed of recent sermons from Skipton Baptist Church. We are a group of people of all ages and backgrounds who want to Meet God, Meet Friends, and Make a Difference.
Based in the heart of this busy market town, we try to find ways as a group of Christians to live out what we believe and to get involved in serving our local community.
One of the things that a lot of people notice on their first visit is the large number of young families who worship at the church. But please don't let that put you off visiting if you are a little older or single, we have services and activities for all ages and you will find a warm welcome whoever you are.</description>
		<language>en-gb</language>
		<copyright>&#x2117; &amp; &#xA9; 2010 Skipton Baptist Church</copyright>
		<image>
			<url>http://<?php echo $_SERVER['SERVER_NAME'];?>/sermons/images/300x300.jpg</url>
			<title>Skipton Baptist Church</title>
			<link>http://www.skiptonbaptistchurch.com/</link>
		</image>
	
<?php  
	$sermons = $sMgr->doc->getElementsByTagName("sermon");	
	
	foreach ($sermons as $s)
	{		
		$ss = $sMgr->newSermonFromNode($s);
		$file = "../mp3/" . $ss->audio;
		
		if (($ss->audio != null) && (file_exists($file))) {
			$d = date("r", $ss->gettimestamp());
			$size = filesize($file);
			
			if ($ss->refs != null) {
				$summary = trim($ss->refs) . ". " . trim($ss->summary);
			}
			else {
				$summary = trim($ss->summary);
			}
?>
		<item>		
			<title><?php echo xmlEncode($ss->title);?></title>
			<description><?php echo xmlEncode($summary);?></description>
			<pubDate><?php echo $d;?></pubDate>		
			<enclosure url="http://<?php echo $_SERVER['SERVER_NAME'];?>/sermons/mp3/<?php echo $ss->audio;?>" length="<?php echo $size; ?>" type="audio/mpeg" />		
			<guid><?php echo $ss->id;?></guid>
			<link>http://<?php echo $_SERVER['SERVER_NAME'];?>/sermons/mp3/<?php echo $ss->audio;?></link>
		</item>
<?php 
		}
	}
?>	
	</channel>
</rss>
