<!-- start of $Id: menu_mail.php 3187 2025-12-02 16:27:49Z oheil $ -->
<?php
  if (!isset($conf->loaded))
    die('Hacking attempt');
?>
<div class="mainmenu">
  <ul>



	<?php if( $user_prefs->getUseInboxFolder() ) { ?>
	<li><a href="action.php?<?php echo NOCC_Session::getUrlGetSession(); ?>"><?php echo convertLang2Html($html_back); ?></a></li>
	<?php } ?>
    <li>

	<?php
		$jumpInbox="";
		if(
			$user_prefs->getUseInboxFolder()
			&& strlen($user_prefs->getInboxFolderName())>0
		) {
			$jumpInbox="&folder=".$user_prefs->getInboxFolderName();
		}
	?>

    <li>
	<a href="action.php?<?php echo NOCC_Session::getUrlGetSession().$jumpInbox;?>"><?php echo convertLang2Html($html_inbox); ?><span class="inbox_changed" style="display:none;color:darkred;">!</span></a>
    </li>
    <li class="selected">
      <span><?php echo convertLang2Html($html_msg) ?></span>
    </li>
    <?php if ($_SESSION['is_imap']) { ?>
    <li>
      <a href="action.php?<?php echo NOCC_Session::getUrlGetSession();?>&action=managefolders" title="<?php echo convertLang2Html($html_manage_folders_link); ?>"><?php echo convertLang2Html($html_folders); ?></a>
    </li>
    <?php } ?>
    <?php if ($conf->prefs_dir && isset($conf->contact_number_max) && $conf->contact_number_max != 0 ) { ?>
    <li>
      <a href="javascript:void(0);" onclick="window.open('contacts_manager.php?<?php echo NOCC_Session::getUrlGetSession();?>&<?php echo NOCC_Session::getUrlQuery(); ?>','','scrollbars=yes,resizable=yes,width=<?php echo (isset($user_prefs->new_window_width)) ? $user_prefs->new_window_width : 900; ?>,height=<?php echo (isset($user_prefs->new_window_height)) ? $user_prefs->new_window_height : 400; ?>')"><?php echo i18n_message($html_contacts, ''); ?></a>
    </li>
    <?php } ?>
  </ul>
</div>
<!-- end of $Id: menu_mail.php 3187 2025-12-02 16:27:49Z oheil $ -->
