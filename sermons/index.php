<?php
include("inc/data.inc");

$idx = 0;
if (isset($_GET['id']))
{
	$idx = $_GET["id"];
}

$sMgr = new SermonMgr();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<title>Sermon Player</title>
<link rel="stylesheet" type="text/css"
		href="http://www.skiptonbaptistchurch.com/styles/sbc.css" />
<link rel="stylesheet"
		href="http://www.skiptonbaptistchurch.com/styles/menu.css" />
<link rel="stylesheet" href="css/sermons.css" />
<!-- Location of javascript. -->
<script language="JavaScript" type="text/javascript"
		src="http://www.skiptonbaptistchurch.com/scripts/menu.js"></script>
<script language="JavaScript" type="text/javascript"
		src="http://www.skiptonbaptistchurch.com/scripts/menu_items.js"></script>
<script language="JavaScript" type="text/javascript"
		src="http://www.skiptonbaptistchurch.com/scripts/menu_tpl.js"></script>
<script language="JavaScript" src="audio-player/audio-player.js"></script>
<script type="text/javascript">  
            AudioPlayer.setup("audio-player/player.swf", {  
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

            var popup = null;
            
            function wopen(url, name, w, h)
            {
                if (popup != null) {
					popup.close();
                }
              // Fudge factors for window decoration space.
              // In my tests these work well on all platforms & browsers.
              w += 32;
              h += 96;
              wleft = (screen.width - w) / 2;
              wtop = (screen.height - h) / 2;
              // IE5 and other old browsers might allow a window that is
              // partially offscreen or wider than the screen. Fix that.
              // (Newer browsers fix this for us, but let's be thorough.)
              if (wleft < 0) {
                w = screen.width;
                wleft = 0;
              }
              if (wtop < 0) {
                h = screen.height;
                wtop = 0;
              }
              popup = window.open(url,
                'popUpWindow', 'width=' + w + ',height=' + h + ',' +
                'left=' + wleft + ',top=' + wtop + ',scrollbars=yes' + ', ' +
                'location=no, menubar=no, ' +
                'status=no, toolbar=no, scrollbars="yes", resizable=no');
              // Just in case width and height are ignored
              popup.resizeTo(w, h);
              // Just in case left and top are ignored
              popup.moveTo(wleft, wtop);
               
              popup.focus();
              return false;
            }
        </script>
</head>



<body>


<div
		style="width: 960px; height: auto; margin-left: auto; margin-right: auto; font-family: Arial, Helvetica, sans-serif; padding-top: 2px; padding-bottom: 20px; position: relative;">

<img src="http://www.skiptonbaptistchurch.com/images/new_logo.png"
		width="290" height="85" alt="church name" style="float: left;" />


<div class="menustyle"><script type="text/javascript"
		language="JavaScript"> 
	<!--//
	new menu (MENU_ITEMS, MENU_POS, MENU_STYLES);
	//-->
</script></div>

<div
		style="width: 960px; height: 520px; background-color: #9d9d7c; position: absolute; left: 0px; top: 95px;"></div>
<img
		src="http://www.skiptonbaptistchurch.com/images/948px_box_bottom_brown_whole.png"
		style="position: absolute; left: 0px; top: 615px;" /> <!--160x300 box-->

<div style="position: absolute; left: 15px; top: 110px; width: 160px;">
<div><img
		src="http://www.skiptonbaptistchurch.com/images/160px_box_top_grey_whole.png"
		width="160" height="12" alt="top" /></div>
<div
		style="height: 300px; background-image: url(http://www.skiptonbaptistchurch.com/images/160px_box_centre_grey.png); background-repeat: repeat-y; padding-left: 10px;">

<!--Information starts here.--> <a class="menu" href="/index.html">Home<br />
</a> <a class="menu" href="../whos_who.html">The Team<br />
</a> <a class="menu" href="../this_weekend.html">This Weekend <br />
</a> <a class="menu" href="../small_groups.html">Small Groups <br />
</a> <a class="menu" href="../mens_ministry.html">For Men<br />
</a> <a class="menu" href="../womens_ministry.html">For Women<br />
</a> <a class="menu" href="../childrens_ministry.html">For Children<br />
</a> <a class="menu" href="../youth_ministry.html">For Youth<br />
</a> <a class="menu" href="../mission.html">Mission<br />
</a> <a class="menu" href="../community_action.html">Community Action<br />
</a> <a class="menu" href="../our_vision.html">Our Vision <br />
</a> <br />

<a class="menu" href="../how_to_find_us.html">How To Find Us <br />
</a> <a class="menu" href="../contact_us.html">Contact Us<br />
</a><br />

</div>



<div><img
		src="http://www.skiptonbaptistchurch.com/images/160px_box_bottom_grey_whole.png"
		width="160" height="12" /></div>
</div>



<!--380x300PX BOX -->
<div
		style="position: absolute; top: 110px; left: 179px; width: 380px; float: left; overflow: hidden;">
<div><img
		src="http://www.skiptonbaptistchurch.com/images/356px_box_top.png"
		width="380" height="12" alt="Box Top" /></div>
<div
		style="height: 450px; background-image: url(http://www.skiptonbaptistchurch.com/images/356px_centre_strip.png); background-repeat: repeat-y; padding-left: 10px; padding-right: 10px;">
<span class="bodycopy8">Sermons</span> 
	
<span class="bodycopy">

<form name="form1" method="post" action="index.php">
<p class="bodycopy">Currently looking at sermons in 

<?php 
	$selYear = date("Y");
	$selMonth = date("F");
	
	if ($idx != "0") {
		$s = $sMgr->getSermonById($idx);
		if ($s == null) {
			$idx = 0;
		}
		else {
			$selYear = $s->getYear();
			$selMonth = $s->getMonth();			
		}
	}
	else {
		if (isset($_POST["selYear"])) {
			$selYear = $_POST["selYear"];
		}
		if (isset($_POST["selMonth"])) {
			$selMonth = $_POST["selMonth"];
		}
	}
?>
<br/>
<SELECT NAME="selMonth" onChange="document.form1.submit();">
<?php
	for ($i=1; $i<13; $i++) {
		$timestamp = mktime(0, 0, 0, $i, 1, 2010);
	    $month = date("F", $timestamp);
		if ($month == $selMonth) {
			$selected = 'selected="selected"';
		}
		else {
			$selected = "";
		}
		echo '<OPTION ' . $selected . ' VALUE="' . $month . '">' . $month;
	}
?>
</SELECT>

<SELECT NAME="selYear" onChange="document.form1.submit();">
		<?php
		$y = date("Y");
		for ($year=$y;$year>$y-4;$year--) {
			if ($year == $selYear) {
				$selected = 'selected="selected"';
			}
			else {
				$selected = "";
			}
			echo '<OPTION ' . $selected . ' VALUE="' . $year . '">' . $year;
		}
		?>
</SELECT>

</p>

</form>

<!--Scroll Bar-->
<div style="width: 360px; height: 345px;">
		<!-- style="width: 367px; height: 345px; overflow-y: scroll; overflow-x: hidden"> -->




<p>
<!-- <table border="0" cellspacing="0" width="100%">  -->
<?php
$sermons = $sMgr->doc->getElementsByTagName("sermon");
$curMonth = "";
$count = 0;

foreach ($sermons as $s)
{
	$date = $s->getElementsByTagName("date")->item(0)->nodeValue;
	$month = explode(" ", $date);
	$year = $month[2];
	$month = $month[1];
	
	if (($month == $selMonth) && ($year == $selYear)) {
		$sermon = $sMgr->newSermonFromNode($s);
		$audio = $sermon->getAudioFilename(); //$s->getElementsByTagName("audio")->item(0)->nodeValue;
		if (($audio != null) && (file_exists("mp3/" . $audio))) {
			$speaker = $s->getElementsByTagName("speaker")->item(0)->nodeValue;
			$title = htmlentities($s->getElementsByTagName("title")->item(0)->nodeValue);
			$id = $s->getAttribute("xml:id");
				
			if ($idx == "0") {
				$idx = $id;
			}
	
			if ($id == $idx) {
				$selected = "class='selected'";
			}
			else {
				$selected = "class='item' onclick=\"window.location.href='index.php?id=" . $id . "'\"";
			}
	
			if ($month != $curMonth) {
				$curMonth = $month;
			}
			
			if ($count == 0) {
?>
			<p class="bodycopy" id="scroll">Move cursor over sermon and click to select.</p><br/>
<?php 
			}
?>
			<div <?php echo $selected;?> >
				<div class="title"><?php echo $date; ?></div>
				<div class="speaker"><?php echo $speaker;?></div>
				<div><?php echo $title;?></div>
			</div>
			<!-- 
			<tr <?php echo $selected;?> >
					<td><span style="font-weight: bold;"><?php echo $date; ?></span></td>
					<td><?php echo $speaker;?></td>
			</tr>
			<tr >
					<td colspan="2"><?php echo $title;?></td>
			</tr>
			 -->
<?php
			$count++;
		}
	}
	else {
		// drop out of scanning though all the sermon records after we stop finding matches
		// as the records are all ordered in reverse cronological order we know we are safe to
		// stop as soon as we stop finding matches. This is a fairly insignificant performance
		// enhancement but worth doing. 
		if ($count > 0) break;	
	}
}
?>
<!--   </table> -->

<?php 
if ($count == 0) {
?>
	<p class="bodycopy" style="font-weight: bold;">No sermons available for <?php echo $selMonth; ?> yet. Please select a different month.</p>
<?php 
} 
?>

</p>

</div>



</span> </span> 

<span class="bodycopy">
<A class="subscribe" HREF="subscribe.php" onClick="return wopen(this.href, 'popup', 630, 600)">Subscribe to sermon updates (free)</A>		
</span>	

<!--Information starts here.--></div>
<div><img
		src="http://www.skiptonbaptistchurch.com/images/356px_box_bottom.png"
		width="380" height="12" alt="" /></div>
</div>


<!--160x120 BOX --> 
	<div style="position:absolute; top:435px; left: 15px;  width:160px; padding-top:4px; padding-right: 4px; overflow:hidden;"> 
		<div><img src="http://www.skiptonbaptistchurch.com/images/160px_box_top_grey_whole.png" width="160" height="12" alt="top" /></div> 
		<div style="height:120px;background-image:url(http://www.skiptonbaptistchurch.com/images/160px_box_centre_grey.png); background-repeat:repeat-y; padding-left:10px;padding-right:10px;"> 
          
            <!--Information starts here.--> 
            <img src="images/new.gif" width="40" align="left" style=padding-right:10px;"/>
          
          <div class="bodycopy">We've added all the 2010 sermons we have. Let <a href="mailto:rob@skiptonbaptistchurch.com">Rob</a> know if there is something older you would like to hear again.
      </div></div> 
 
		<div><img src="http://www.skiptonbaptistchurch.com/images/160px_box_bottom_grey_whole.png" width="160" height="12" /></div> 
	</div> 


<!--380x300PX BOX -->
<div
		style="position: absolute; top: 110px; left: 564px; width: 380px; padding-left: 4px; overflow: hidden;">
<div><img
		src="http://www.skiptonbaptistchurch.com/images/356px_box_top.png"
		width="380" height="12" alt="Box Top" /></div>
<div
		style="height: 450px; background-image: url(http://www.skiptonbaptistchurch.com/images/356px_centre_strip.png); background-repeat: repeat-y; padding-left: 7px; padding-right: 5px;">

<?php
$s = $sMgr->getSermonById($idx);
if ($s != null) {
	?> <span class="bodycopy8"><?php echo htmlentities($s->title);?> </span> <br />
<span class="bodycopy" style="font-weight: bold;"><?php echo $s->speaker. ", " . $s->date; ?></span>
<br />
<span class="bodycopy" style="font-style: italic;"><?php echo htmlentities($s->refs); ?></span>

<p class="bodycopy"><?php echo htmlentities($s->summary);?></p>

	<?php
	if (($s->getAudioFilename() != null) && (file_exists("mp3/" . $s->getAudioFilename()))){
		?> <br />
<p class="bodycopy" id="audioplayer_0">It looks like
you don't have flash player installed. <a
		href="http://www.macromedia.com/go/getflashplayer">Click here</a> to
go to Macromedia download page.</p>
<script type="text/javascript">  
			AudioPlayer.embed("audioplayer_0", {soundFile: "mp3/<?php echo $s->getAudioFilename();?>"});  
		</script>
<p><input type="button" value="Download"
		onclick="location.href='download.php?id=<?php echo $s->id;?>'"></p>
		<?php
	} else {
		?>
<p class="bodycopy">(Audio not available at the moment)</p>
		<?php
	}
}
?> <!--Information starts here.--></div>
<div><img
		src="http://www.skiptonbaptistchurch.com/images/356px_box_bottom.png"
		width="380" height="12" alt="" /></div>
</div>
</div>



</body>
</html>
