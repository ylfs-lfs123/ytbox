
<?php
include ( './includes/header.php' );
$channel = $_GET['c'];
$check_subscribe = mysql_query("SELECT * FROM subscriptions WHERE user_who_subscribed='$user'");
$count_s  = mysql_num_rows($check_subscribe);
if ($count_s > 0) {
$subbutton = 'Unsubscribe';
}
else
{
$subbutton = 'Subscribe';
}
$check_channel = mysql_query("SELECT * FROM channels WHERE channel_name='$channel'");
$count  = mysql_num_rows($check_channel);
if ($count == 1) {
while ($row = mysql_fetch_assoc($check_channel)) {
      $id = $row['id'];
      $channel_name = $row['channel_name'];
      $created_by = $row['created_by'];
      $date_created = $row['date_created'];
      $channel_icon = $row['channel_icon'];
if (isset($_POST['subscribe'])) {
$check_subscribe = mysql_query("SELECT * FROM subscriptions WHERE user_who_subscribed='$user'");
$count_s  = mysql_num_rows($check_subscribe);
if ($count_s > 0) {
  $subscribe_query = mysql_query("DELETE FROM subscriptions WHERE user_who_subscribed='$user'");
  header("Location: channel.php?c=$channel_name");
}
else {
  $subscribe_query = mysql_query("INSERT INTO subscriptions VALUES ('','$user','$channel_name','no')");
  header("Location: channel.php?c=$channel_name");
}
}

?>
<div class='channeloptions'>
<h2><?php echo $channel_name; ?></h2>
<center>
<img src='data/channels/images/icons/<?php echo $channel_icon; ?>' height='140' width='140' />
<br /><br />
<form action='channel.php?c=<?php echo $channel_name; ?>' method='POST'>
<input type='submit' name='subscribe' value='<?php echo $subbutton; ?>' />
</form>
</center>
</div>
<div class='channelvideocontainer'>
	<?php
$check_channel = mysql_query("SELECT * FROM videos WHERE channel_name='$channel'");
$count  = mysql_num_rows($check_channel);
if ($count == 1) {
while ($row = mysql_fetch_assoc($check_channel)) {
      $id = $row['id'];
      $channel_name = $row['channel_name'];
      $created_by = $row['created_by'];
      $date_created = $row['date_created'];
      $channel_icon = $row['channel_icon'];
  }
}
	?>
<img src='#' height='100' width='180' />
</div>
<?php
}
}
else
{
 header("Location: index.php");
}
?>
