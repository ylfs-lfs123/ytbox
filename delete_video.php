<?php
include ( './includes/header.php' );
$videoid = $_GET['videoid'];

mysql_query("UPDATE videos SET deleted='yes' WHERE video_id='$videoid'") or die(mysql_error());
header("Location: my_videos.php");
?>