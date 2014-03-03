<?php 

include("../inc/data.inc"); 

$sMgr = new SermonMgr();

$id = NULL;
if (isset($_GET['id']))
{
	$id = $_GET["id"];
}
$d = NULL;
$m = NULL;
$y = NULL;
$sm = NULL;
$t = "";
$r = "";
$s = "";
$sp ="";
$pageTitle = "New Sermon";
$buttonTitle = "Add Sermon";

if ($id == NULL) {
	$d = date('d', strtotime('next Sunday'));
	$m = date('M', strtotime('next Sunday'));
	$y = date('Y', strtotime('next Sunday'));
}
else {
	$sm = $sMgr->getSermonById($id);
    if ($sm != NULL) {
		$t = $sm->title;	
		$s = $sm->summary;	
		$r = $sm->refs;	
		$sp = $sm->speaker;	
		$pageTitle = "Edit Sermon";
		$buttonTitle = "Update";
		$dateExplosion = explode(" ", $sm->date);
		$d = $dateExplosion[0];
		$m = $dateExplosion[1];
		$y = $dateExplosion[2];
	}
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Sermon Management</title>
		<link rel="stylesheet" type="text/css" href="http://www.skiptonbaptistchurch.com/styles/sbc.css"/>
		<link rel="stylesheet" href="http://www.skiptonbaptistchurch.com/styles/menu.css" />
		<script type="text/javascript">
			function deleteSermon()
			{
				var r=confirm("Delete this sermon?");
				if (r==true)
				{
  					window.location.href = "deleteSermon.php?id=<?php echo $id;?>";
  				}
			}
		</script>
		<script language="JavaScript" src="../audio-player/audio-player.js"></script>
		 <script type="text/javascript">  
            AudioPlayer.setup("../audio-player/player.swf", {  
                width: "100%",
                transparentpagebg: "yes",
                tracker: "92117e",
                track: "9d9d7c",
                text: "FFFFFF",
                border: "663300",
                loader: "92117e",
                bg: "c8c6b7",
                leftbg: "9d9d7c",
                leftbghover: "9d9d7c",
                lefticon: "663300",
                lefticonhover: "c8c6b7",
                rightbg: "9d9d7c",
                rightbghover: "9d9d7c",
                righticon: "663300",
                righticonhover: "c8c6b7",                               
                volslider: "663300",
                voltrack: "c8c6b7"
            });  
        </script>
	</head>
<body>
  <div style="width:960px; height:auto; margin-left:auto; margin-right:auto; font-family: Arial, Helvetica, sans-serif;padding-top:2px; padding-bottom:20px;position:relative;">
    <img src="http://www.skiptonbaptistchurch.com/images/new_logo.png" width="290" height="85" alt="church name" style="float:left; " />
	<div style="width: 960px; height: 520px; background-color:#9d9d7c; position:absolute; left: 0px; top: 95px;"></div>
	<img src= "http://www.skiptonbaptistchurch.com/images/948px_box_bottom_brown_whole.png" style="position: absolute; left: 0px; top: 615px;" />

	<!--160x300 box-->
    <div style="position:absolute;left:15px;top:110px;width:160px;">
      <div><img src="http://www.skiptonbaptistchurch.com/images/160px_box_top_grey_whole.png" width="160" height="12" alt="top" /></div>
      <div style="height:300px;background-image:url(http://www.skiptonbaptistchurch.com/images/160px_box_centre_grey.png); background-repeat:repeat-y; padding-left:10px;">
	    <!--Information starts here.-->
        <a class="menu" href="http://www.skiptonbaptistchurch.com/">Home<br />
        </a>
      </div>
	  <div><img src="http://www.skiptonbaptistchurch.com/images/160px_box_bottom_grey_whole.png" width="160" height="12" /></div>
	</div>
	
	<?php if ($sm != null) { ?>
	<div style="padding-top:10px;padding-left:10px;position:absolute;left:15px;top:440px;width:150px;height:110px;background-color:#c8c6b7;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;">
	<applet archive="FileUploader.jar" code="com.sbc.sermons.FileUploader.class" width=140 height=100>
		<param  name="id" value="<?php echo $id; ?>"/>
		<param  name="chunksize" value="51200"/>
		<param  name="retries" value="10"/>
		<param  name="retrySleep" value="10"/>
	</applet>
    </div>  
    <?php } ?>

<!--380x300PX BOX -->
<div style="position:absolute;top:110px;left:179px;width:380px; float: left; overflow: hidden;">
<div><img src="http://www.skiptonbaptistchurch.com/images/356px_box_top.png" width="380" height="12" alt="Box Top" /></div>
<div style="height:450px;background-image:url(http://www.skiptonbaptistchurch.com/images/356px_centre_strip.png); background-repeat:repeat-y; padding-left:10px;padding-right:10px;">
	<span class="bodycopy8"><?php echo $pageTitle;?></span><br />
	<!--Information starts here.-->

	<form method="post" action="sermonMgmt.php"> 
		<span class="bodycopy">
		<p>Date</p>
		<SELECT NAME="day">
<?php
	for ($i=1; $i<32; $i++) {
		if ($i == $d) {
			$selected = 'selected="selected"';
		}
		else {
			$selected = "";
		}
		echo '<OPTION ' . $selected . ' VALUE="' . $i . '">' . $i;
	}
?>		
		</SELECT>
		<SELECT NAME="month">
<?php 
	for ($i=1; $i<13; $i++) {
		$timestamp = mktime(0, 0, 0, $i, 1, 2010);
    	$month = date("F", $timestamp);
		if ($month == $m) {
			$selected = 'selected="selected"';
		}
		else {
			$selected = "";
		}
		echo '<OPTION ' . $selected . ' VALUE="' . $month . '">' . $month;
	}
?>
		</SELECT>
		<SELECT NAME="year">
<?php 
	$yy = date("Y");
	for ($year=$yy-3;$year<$yy+2;$year++) {
		if ($year == $y) {
			$selected = 'selected="selected"';
		}
		else {
			$selected = "";
		}
		echo '<OPTION ' . $selected . ' VALUE="' . $year . '">' . $year;
	}
?>
		</SELECT>
		<p>Title *</p>
		<input type="text" name="title" size="40" value="<?php echo $t;?>">
		<p>Scripture References (e.g. Acts 2:1, Luke 6:5-7)</p>
		<input type="text" name="refs" size="40"  value="<?php echo $r;?>">
		<p>Summary</p>
		<textarea name="summary" rows="6" cols="40"><?php echo $s;?></textarea>
		<p>Speaker *</p>		
		<SELECT NAME="speakerSelect">
			<OPTION VALUE="0">--Select existing or type new name--
<?php 
	$doc = new DOMDocument();
	$doc->load('../data/speakers.xml');  
	$speakers = $doc->getElementsByTagName("speaker");

	foreach ($speakers as $s)
	{
		if ($s->nodeValue == $sp) {
			$selected = 'selected="selected"';
		}
		else {
			$selected = "";
		}
		echo '<OPTION ' . $selected . ' VALUE="' . $s->nodeValue . '">' . $s->nodeValue;
	}
?>
			
		</SELECT>
		<input type="text" name="speaker" size="30">
		<p>
			<input type="submit" value="<?php echo $buttonTitle;?>">
<?php 
	if ($sm != NULL) {
?>
			<input type="button" value="Cancel" onclick="JavaScript:window.location.href='index.php'" />
			<input type="button" value="Delete" onclick="JavaScript:deleteSermon()" />
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="audio" value="<?php echo $sm->getAudioFilename(); ?>">
<?php 
	}
?>
		</p>
		</span>		
	</form>
	
</div>

<div><img src="http://www.skiptonbaptistchurch.com/images/356px_box_bottom.png" width="380" height="12" alt="" /></div>
</div>

<!--380x300PX BOX -->
<div style="position:absolute;top:110px;left:564px;width:380px; padding-left: 4px;overflow: hidden;">
<div><img src="http://www.skiptonbaptistchurch.com/images/356px_box_top.png" width="380" height="12" alt="Box Top" /></div>
<div style="height:450px;background-image:url(http://www.skiptonbaptistchurch.com/images/356px_centre_strip.png); background-repeat:repeat-y; padding-left:7px;padding-right:5px;">

<span class="bodycopy8">Current Sermons</span>

<!--Scroll Bar-->

<div style="width:367px; height:425px; overflow-y:scroll;overflow-x:hidden">

<span class="bodycopy">
<p>
<table border="0" width="100%">
<?php  
	$sermons = $sMgr->doc->getElementsByTagName("sermon");	
	$sidx = 1;
	foreach ($sermons as $s)
	{
		$sm = $sMgr->newSermonFromNode($s);
		$date = $sm->date;
		$speaker = $sm->speaker;	
		$title = htmlentities($sm->title);
		$refs = htmlentities($sm->refs);
		$id = $sm->id;
		$audio = $sm->getAudioFilename();
?>
		<tr>
			<td>
				<span style="font-weight:bold;"><?php echo $date; ?></span>
			</td>
			<td>
				<?php echo $speaker;?>
			</td>
			<td>
				<a href="index.php?id=<?php echo $id;?>">Edit</a>
			</td>
		</tr>
		<tr>
			<td colspan="3"><?php echo $title;?></td>
		</tr>
		<tr>
			<td colspan="3" style="font-style:italic;"><?php echo $refs;?></td>
		</tr>
		<tr>
			<td colspan="3">
				<?php if (($audio != null) && (file_exists("../mp3/" . $audio))){?>		
				<p id="audioplayer_<?php echo $sidx; ?>">Player</p>
					<script type="text/javascript">  
        				AudioPlayer.embed("audioplayer_<?php echo $sidx; ?>", {soundFile: "../mp3/<?php echo $audio;?>"});  
       				 </script> 
																
				<?php } ?>
			</td>
		</tr>
<?php 	
		$sidx++;
	}
?>
</table></p>
</span>

</div>

<!--Information starts here.-->
</div>

<div><img src="http://www.skiptonbaptistchurch.com/images/356px_box_bottom.png" width="380" height="12" alt="" /></div>
</div>
</div>
</body
></html>
