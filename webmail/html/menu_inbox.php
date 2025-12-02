<!-- start of $Id: menu_inbox.php 3187 2025-12-02 16:27:49Z oheil $ -->
<?php
  if (!isset($conf->loaded))
    die('Hacking attempt');

$max_search_folders_per_page = 5;
if( isset($_SESSION['nocc_domainnum']) && isset($conf->domains[$_SESSION['nocc_domainnum']]->max_search_folders_per_page) ) {
	$max_search_folders_per_page = $conf->domains[$_SESSION['nocc_domainnum']]->max_search_folders_per_page;
}

$action = NOCC_Request::getStringValue('action');
$selected = 0;
switch ($action) {
  case '':
  case 'login':
  case 'cookie':
    $selected = 1;
    $line = '<a href="action.php?'.NOCC_Session::getUrlGetSession().'&action=write">'.$html_new_msg.'</a>';
    break;
  case 'write':
    $selected = 2;
    $line = '<span>' . $html_new_msg . '</span>';
    break;
  case 'reply':
    $selected = 2;
    $line = '<span>' . $html_reply . '</span>';
    break;
  case 'reply_all':
    $selected = 2;
    $line = '<span>' . $html_reply_all . '</span>';
    break;
  case 'forward':
    $selected = 2;
    $line = '<span>' . $html_forward . '</span>';
    break;
  case 'managefolders':
    $selected = 3;
    $line = '<a href="action.php?'.NOCC_Session::getUrlGetSession().'&action=write">'.$html_new_msg.'</a>';
    break;
}
?>
<div class="mainmenu">
  <ul>
	<?php if( $selected != 1 && $user_prefs->getUseInboxFolder() ) { ?>
		<li><a href="action.php?<?php echo NOCC_Session::getUrlGetSession(); ?>"><?php echo convertLang2Html($html_back); ?></a></li>
	<?php } ?>
    <?php if ($selected == 1) echo '<li class="selected">'; else echo '<li>'; ?>
	<?php
		$jumpInbox="";
		if(
			$user_prefs->getUseInboxFolder()
			&& strlen($user_prefs->getInboxFolderName())>0
		) {
			$jumpInbox="&folder=".$user_prefs->getInboxFolderName();
		}
	?>
      <a href="action.php?<?php echo NOCC_Session::getUrlGetSession().$jumpInbox;?>"><?php echo convertLang2Html($html_inbox); ?><span class="inbox_changed" style="display:none;color:darkred;">!</span></a>
    </li>
    <?php if ($selected == 2) echo '<li class="selected">'; else echo '<li>'; ?>
      <?php echo $line ?>
    </li>
    <?php if ($_SESSION['is_imap']) { ?>
    <?php if ($selected == 3) echo '<li class="selected">'; else echo '<li>'; ?>
      <a href="action.php?<?php echo NOCC_Session::getUrlGetSession();?>&action=managefolders" title="<?php echo convertLang2Html($html_manage_folders_link); ?>"><?php echo convertLang2Html($html_folders); ?></a>
    </li>
    <?php } ?>
    <?php if ($conf->prefs_dir && isset($conf->contact_number_max) && $conf->contact_number_max != 0 ) { ?>
    <li>
      <a href="javascript:void(0);" onclick="window.open('contacts_manager.php?<?php echo NOCC_Session::getUrlGetSession();?>&<?php echo NOCC_Session::getUrlQuery(); ?>','','scrollbars=yes,resizable=yes,width=<?php echo (isset($user_prefs->new_window_width)) ? $user_prefs->new_window_width : 900; ?>,height=<?php echo (isset($user_prefs->new_window_height)) ? $user_prefs->new_window_height : 400; ?>')"><?php echo i18n_message($html_contacts, ''); ?></a>
    </li>
	<?php if ($selected == 1 /* && isset($_SERVER["NOCC_ALPHA"]) */ ) { ?>
    <li>
	<form name="mail_search" id="mail_search" target="mail_search_win" action="javascript:;" method="post" onsubmit="form=document.getElementById('mail_search');sq=document.getElementById('mail_search_query').value;form.action='search.php?<?php echo NOCC_Session::getUrlGetSession();?>&<?php echo NOCC_Session::getUrlQuery(); ?>&mail_search_query='+sq;window.open('search.php?<?php echo NOCC_Session::getUrlGetSession();?>&<?php echo NOCC_Session::getUrlQuery(); ?>&mail_search_query='+sq,'mail_search_win','scrollbars=yes,resizable=yes,width=<?php echo (isset($user_prefs->new_window_width)) ? $user_prefs->new_window_width : 900; ?>,height=<?php echo (isset($user_prefs->new_window_height)) ? $user_prefs->new_window_height : 400; ?>');">
	<input id="mail_search_query" name="mail_search_query" style="margin-left:5px;" />
	<input type="submit" class="button" value="<?php echo i18n_message($html_search, ''); ?>" style="margin-right:5px;" />
	<input type="hidden" name="subject_only" id="subject_only" value="subject_only" />
	<input type="hidden" name="start_folder" id="start_folder" value="1" />
	<input type="hidden" name="max_folders" id="max_folders" value="<?php echo $max_search_folders_per_page; ?>" />
	<input type="hidden" name="search_folder" id="search_folder" value="<?php echo $_SESSION['nocc_folder']; ?>" />
	</form>
    </li>
	<?php } ?>
    <?php } ?>
    <?php if (isset($_GET['successfulsend']) && $_GET['successfulsend']) { ?>
    <li>
      <?php echo convertLang2Html($html_send_confirmed); ?>
    </li>
    <?php } ?>
  </ul>
</div>
<!-- end of $Id: menu_inbox.php 3187 2025-12-02 16:27:49Z oheil $ -->
