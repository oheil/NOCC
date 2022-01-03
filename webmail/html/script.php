<!-- start of $Id: script.php 2255 2017-07-31 07:46:41Z oheil $ -->
<?php
  if (!isset($conf->loaded))
    die('Hacking attempt');

	$timer=60;
	if( isset($conf->check_inbox_timer) && is_int($conf->check_inbox_timer) ) {
		$timer=$conf->check_inbox_timer;
	}

	$message=$html_inbox_changed;

	$show_alert=false;
	if( $user_prefs->getShowAlert()==true && isset($_SESSION['inbox_alert']) && $_SESSION['inbox_alert'] ) {
		$show_alert=true;
	}

	$num_msg=0;
	if( isset($_SESSION['inbox_num_msg']) ) {
		$num_msg=$_SESSION['inbox_num_msg'];
	}

	if( $timer>0 && $user_prefs->getUseInboxFolder() ) {
?>

<script type="text/javascript">
	InitInboxChangedHandler(<?php echo $num_msg; ?>,"<?php echo NOCC_Session::getUrlGetSession(); ?>",<?php echo $timer; ?>,"<?php echo $message; ?>",<?php echo $show_alert; ?>);
</script>

<?php
	}
?>


<!-- end of $Id: script.php 2255 2017-07-31 07:46:41Z oheil $ -->
