<?php
include ( './includes/header.php' );
$videoid = $_GET['videoid'];

$check = mysql_query("SELECT * FROM videos WHERE video_id='$videoid'");
if (mysql_num_rows($check) == 1) {
while($row = mysql_fetch_assoc($check)) {  // This data is only specific to the video itself
           $id = $row['id'];
           $video_title = $row['video_title'];
           $video_description = $row['video_description'];
           $video_keywords = $row['video_keywords'];
           $uploaded_by = $row['uploaded_by'];
           $privacy = $row['privacy'];
           $date_uploaded = $row['date_uploaded'];
           $views = $row['views'];
           $video_id = $row['video_id'];
           $videosrc = $row['file_location'];
           $newviews = $views + 1;
           $updateviews = mysql_query("UPDATE videos SET views='$newviews' WHERE video_id='$videoid'") or die(mysql_error());
}
?>
<h2><?php echo $video_title; ?></h2>
<div style="float: left;">
<video width="480" height="320" controls>
<source src="<?php echo $videosrc; ?>" type="video/mp4">
Your browser doesn't support the HTML5 video tag.
</video>
</div>
<div style="float: left;margin: 0px 0px 0px 5px;border: 1px solid #ccc;background-color: #f2f2f2;padding: 5px;min-height: 100px;width: 300px;">
<p><strong>Video Description:</strong></p>
<?php echo $video_description; ?>
</div>
<div style="float: left;margin: 0px 0px 0px 5px;border: 1px solid #ccc;border-top: none;background-color: #f2f2f2;padding: 5px;width: 300px;">
<strong>Video Tags:</strong>
<?php echo $video_keywords; ?>
</div>
<p/>
<div style="float: left;width: 300px;font-size: 14px;margin: 0px 0px 0px 5px;font-weight: bold;">
<?php echo $views; ?> Views
</div>      
<?php

/* Check if already liked / disliked */
$check_l = mysql_query("SELECT * FROM ratings WHERE videoid='$videoid' AND username='$user'");
if (mysql_num_rows($check_l) != 0) {
  while($rating = mysql_fetch_assoc($check_l)) {
    $videoid_l = $rating['videoid'];
    $type = $rating['type'];
    $user_l = $rating['username'];

    $d = "";
    $d2 = "";
    
    if ($type == 'like') {
      $d = 'disabled';
    }
    else {
      $d2 = 'disabled';
    }

    /* Add ratings code */

if (isset($_POST['like'])) {
  mysql_query("UPDATE ratings SET type='like' WHERE videoid='$videoid' AND username='$user'");
  header("Location: watch.php?videoid=$videoid");
}
if (isset($_POST['dislike'])) {
  mysql_query("UPDATE ratings SET type='dislike' WHERE videoid='$videoid' AND username='$user'");
  header("Location: watch.php?videoid=$videoid");
}

  }
}
else {
  $d = "";
  $d2 = "";
  /* Add ratings code */

if (isset($_POST['like'])) {
  mysql_query("INSERT INTO ratings VALUES ('','$videoid','like','$user')");
  header("Location: watch.php?videoid=$videoid");
}
if (isset($_POST['dislike'])) {
  mysql_query("INSERT INTO ratings VALUES ('','$videoid','dislike','$user')");
  header("Location: watch.php?videoid=$videoid");
}
}

/* Comment Post Code */
           if (isset($_POST['post_comment'])) {
           $comment_text = trim(htmlentities(strip_tags(mysql_real_escape_string($_POST['write_comment']))));
           $date_commented = date("d F Y");
           mysql_query("INSERT INTO comments VALUES ('','$user','$comment_text','$date_commented','$videoid')");
           }
           
           /* Calculate Likes */
           $total_width = 180;
           $green = 0;
           $red = 0;
           $get_likes = mysql_query("SELECT * FROM ratings WHERE videoid='$videoid' AND type='like'");
           $num_of_likes = mysql_num_rows($get_likes);

           $get_dislikes = mysql_query("SELECT * FROM ratings WHERE videoid='$videoid' AND type='dislike'");
           $num_of_dislikes = mysql_num_rows($get_dislikes);

           $total_num_db = $num_of_likes + $num_of_dislikes;

           if ($total_num_db == 0) {
            echo '';
           }
           else {

           $total_num = $num_of_likes + $num_of_dislikes;

           $width_of_one = $total_width / $total_num;
           $green  = $width_of_one * $num_of_likes;
           $red = $width_of_one * $num_of_dislikes;
         }

?>
<div style="float: left;width: 100%;margin: 10px 5px 0px 5px;">
           <div style="float: left; height: 25px; width: 475px;">
            <div style="float: left; width: 50%;">
              <div style="margin-top: -5px;">
              <form action='watch.php?videoid=<?php echo $videoid; ?>' method='POST'>
                <input type='submit' name='like' value='Like' <?php echo $d; ?>>
                <input type='submit' name='dislike' value='Dislike' <?php echo $d2; ?>>
              </form>
            </div>
            </div>
           <div style="float: right; width: <?php echo $total_width; ?>;">
           <div style="float: right; width: <?php echo $red; ?>px; height: 5px; background-color: red;"></div>
           <div style="float: right; width: <?php echo $green; ?>px; height: 5px; background-color: green;"></div>
           </div>
           </div>
           <div style="float: left; width: 100%;">
           <form action="watch.php?videoid=<?php echo $videoid; ?>" method="POST">
           <textarea name="write_comment" rows="7" cols="43" style="float: left;"></textarea>
           <input type="submit" name="post_comment" value="Post Comment" style="height: 120px; float: left;">
           </form>
           </div>
<?php
// This is the section of the watch page that isn't specific to the video

$select_comment = mysql_query( "SELECT * FROM comments WHERE video_id='$videoid' ORDER BY id DESC" );
if (mysql_num_rows($select_comment) != 0) {
 //The video has some comments
 while($r = mysql_fetch_assoc($select_comment)) {
           $id = $r['id'];
           $user_commented = $r['user_commented'];
           $comment = $r['comment'];
           $date_posted = $r['date_posted'];
           ?>

           <div style="float: left;">
           <form action="watch.php?videoid=<?php echo $videoid; ?>" method="POST">

           </form>
           </div><br />
           <div style="float: left; width: 150px;">
 <?php
 echo "On ".$date_posted;
 echo " <a href='#'>".$user_commented.'</a> said: <br />';
?>
</div>
<div style="float: left;">
 <?php
 echo $comment;
 ?>
 </div>
 <br /><br />
  <hr width="98%" style="height: 1px; border: none; border-top: 1px solid #CCCCCC"/>
</div>
           <?php
}

} else {
 // The video has no comments

}
?>

<?php
}
else {
 header("Location: index.php");
}
?>