<?php
include ('./includes/header.php');

if (isset($_FILES['video'])) {
  $title = $_POST['video_title'];
  $desc = $_POST['video_description'];
  $keywords = $_POST['video_keywords'];
  $privacy = @$_POST['privacy'];
  if (!empty($title) || ($desc) || ($keywords) || ($privacy)) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   $video_id = substr(str_shuffle($chars), 0, 15);
   $video_id = md5($video_id);
  }
  else
  {
   die('empty fields');
  }
   if (($_FILES['video']['type']=='video/mp4')) {
   $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   $random_directory = substr(str_shuffle($chars), 0, 15);

   if (file_exists('data/users/videos/' . $random_directory . ''.$_FILES['video']['name'])) {
     echo 'video exists';
   }
   else
   {

   move_uploaded_file($_FILES['video']['tmp_name'],'data/users/videos/' . $random_directory . ''.$_FILES['video']['name']);
   $img_name = $_FILES['video']['name'];
   $filename = "data/users/videos/".$random_directory.$_FILES['video']['name'];
   $md5_file = md5_file($filename);
   $check_md5 = mysql_query("SELECT file_md5 FROM videos WHERE file_md5='$md5_file'");
   if (mysql_num_rows($check_md5) != 0) {
     unlink($filename);
    die("This is a duplicate upload");
   }else {
     $date = date("F j, Y");
   $insert = mysql_query("INSERT INTO videos VALUES ('','$title','$desc','$keywords','$user','$privacy','$date','0','$video_id','','$filename','images/thumbnail.png','no')");
   mysql_query("UPDATE videos SET file_md5='$md5_file' WHERE video_id='$video_id'");
   die('The video was uploaded successfully');
   }
   }
   }

}
?>
<h2>Upload a Video</h2>
<form action='upload.php' method='POST' enctype='multipart/form-data'>
Video Title: <input type='text' name='video_title' value='' /><br />
Video Description:<br />
<textarea rows='10' cols='40' name='video_description'></textarea><br />
Video Keywords: <input type='text' name='video_keywords' value='' /><br />
Privacy: <input type='radio' name='privacy' value='Public' /> Public &nbsp;&nbsp; <input type='radio' name='privacy' value='Private' /> Private<br />
<input type='file' name='video' value='Upload Your Video'>
<input type='submit' name='submit' value='Upload'>
</form>