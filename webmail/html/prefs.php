<!-- start of $Id: prefs.php 3016 2022-08-25 11:00:42Z oheil $ -->
<?php
  if (!isset($conf->loaded))
    die('Hacking attempt');

$big_list = $pop->getmailboxesnames();

// Build list for sent mails folder selection
$sent_folders_list = array();
$currentSentFolder = $user_prefs->getSentFolderName();
if (count($big_list) > 1) {
  for ($i = 0; $i < count($big_list); $i++) {
    if (!empty($currentSentFolder) && $_SESSION['imap_namespace'] . $currentSentFolder == $big_list[$i]) {
      array_push($sent_folders_list, "\t<option value=\"".$big_list[$i]."\" selected=\"selected\">".$big_list[$i]."</option>\n");
    } else {
      array_push($sent_folders_list, "\t<option value=\"".$big_list[$i]."\">".$big_list[$i]."</option>\n");
    }
  }
}

// Build list for deleted mails folder selection
$trash_folders_list = array();
$currentTrashFolder = $user_prefs->getTrashFolderName();
if (count($big_list) > 1) {
  for ($i = 0; $i < count($big_list); $i++) {
    if (!empty($currentTrashFolder) && $_SESSION['imap_namespace'] . $currentTrashFolder == $big_list[$i]) {
      array_push($trash_folders_list, "\t<option value=\"".$big_list[$i]."\" selected=\"selected\">".$big_list[$i]."</option>\n");
    } else {
      array_push($trash_folders_list, "\t<option value=\"".$big_list[$i]."\">".$big_list[$i]."</option>\n");
    }
  }
}

// Build list for inbox mails folder selection
$inbox_folders_list = array();
$currentInboxFolder = $user_prefs->getInboxFolderName();
if (count($big_list) > 1) {
  for ($i = 0; $i < count($big_list); $i++) {
    if (!empty($currentInboxFolder) && $_SESSION['imap_namespace'] . $currentInboxFolder == $big_list[$i]) {
      array_push($inbox_folders_list, "\t<option value=\"".$big_list[$i]."\" selected=\"selected\">".$big_list[$i]."</option>\n");
    } else {
      array_push($inbox_folders_list, "\t<option value=\"".$big_list[$i]."\">".$big_list[$i]."</option>\n");
    }
  }
}
?>

<div class="prefs">
  <h3><?php echo convertLang2Html($html_preferences) ?></h3>
  <form method="post" action="action.php?<?php echo NOCC_Session::getUrlGetSession();?>">
    <div>
      <input type="hidden" name="action" value="setprefs" />
      <input type="hidden" name="submit_prefs" value="set" />
      <table>
         <tr>
           <td class="prefsLabel"><label for="full_name"><?php echo convertLang2Html($html_full_name_label) ?></label></td>
           <td class="prefsData">
             <input class="button" type="text" name="full_name" id="full_name" value="<?php echo $user_prefs->getFullName() ?>" size="40"/>
           </td>
         </tr>
         <?php
		$allow_address_change=(
			( isset($conf->domains[$_SESSION['nocc_domainnum']]->allow_address_change) && $conf->domains[$_SESSION['nocc_domainnum']]->allow_address_change )
			|| ( ! isset($conf->domains[$_SESSION['nocc_domainnum']]->allow_address_change) && $conf->allow_address_change  )
		);
		if( $allow_address_change ) {
			//$from_email_show=($user_prefs->getEmailAddress() != '') ? $user_prefs->getEmailAddress() : get_default_from_address();
			$from_email_show=$user_prefs->getEmailAddress();
	?>
         <tr>
           <td class="prefsLabel"><label for="email_address"><?php echo convertLang2Html($html_email_address_label) ?></label></td>
           <td class="prefsData">
             <input class="button" type="text" name="email_address" id="email_address" value="<?php echo $from_email_show ?>" size="40"/>
           </td>
         </tr>
         <?php } ?>
      </table>
    <fieldset>
      <legend><?php echo convertLang2Html($html_inbox); ?></legend>
      <table>
         <tr>
           <td class="prefsLabel"><label for="msg_per_page"><?php echo convertLang2Html($html_msgperpage_label) ?></label></td>
           <td class="prefsData">
             <input class="button" type="text" name="msg_per_page" id="msg_per_page" value="<?php echo (isset($user_prefs->msg_per_page)) ? $user_prefs->msg_per_page : $conf->msg_per_page ?>" size="3" maxlength="3"/>
           </td>
         </tr>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <input type="checkbox" name="seperate_msg_win" id="seperate_msg_win" value="on" <?php if(isset($user_prefs->seperate_msg_win) && $user_prefs->seperate_msg_win) echo 'checked="checked"'; ?> /><label for="seperate_msg_win"><?php echo convertLang2Html($html_seperate_msg_win) ?></label>
           </td>
         </tr>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <input type="checkbox" name="hide_addresses" id="hide_addresses" value="on" <?php if ($user_prefs->getHideAddresses()) echo 'checked="checked"'; ?> /><label for="hide_addresses"><?php echo convertLang2Html($html_hide_addresses) ?></label>
           </td>
         </tr>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <input type="checkbox" name="show_alert" id="show_alert" value="on" <?php if ($user_prefs->getShowAlert()) echo 'checked="checked"'; ?> /><label for="show_alert"><?php echo convertLang2Html($html_inbox_show_alert) ?></label>
           </td>
         </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend><?php echo convertLang2Html($html_msg); ?></legend>
      <table>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <input type="checkbox" name="graphical_smilies" id="graphical_smilies" value="on" <?php if ($user_prefs->getUseGraphicalSmilies()) echo 'checked="checked"'; ?> /><label for="graphical_smilies"><?php echo convertLang2Html($html_use_graphical_smilies) ?></label>
           </td>
         </tr>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <input type="checkbox" name="colored_quotes" id="colored_quotes" value="on" <?php if($user_prefs->getColoredQuotes()) echo 'checked="checked"'; ?> /><label for="colored_quotes"><?php echo convertLang2Html($html_colored_quotes) ?></label>
           </td>
         </tr>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <input type="checkbox" name="display_struct" id="display_struct" value="on" <?php if($user_prefs->getDisplayStructuredText()) echo 'checked="checked"'; ?> /><label for="display_struct"><?php echo convertLang2Html($html_display_struct) ?></label>
           </td>
         </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend><?php echo convertLang2Html($html_reply); ?></legend>
      <table>
         <?php if ($conf->enable_reply_leadin) { ?>
         <tr>
           <td class="prefsLabel"><label for="reply_leadin"><?php echo convertLang2Html($html_reply_leadin_label) ?></label></td>
           <td class="prefsData">
             <input class="button" type="text" name="reply_leadin" id="reply_leadin" value="<?php echo (isset($user_prefs->reply_leadin)) ? $user_prefs->reply_leadin : "" ?>" size="40"/>
           </td>
         </tr>
         <?php } ?>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <input type="checkbox" name="outlook_quoting" id="outlook_quoting" value="on" <?php if($user_prefs->getOutlookQuoting()) echo 'checked="checked"'; ?> /><label for="outlook_quoting"><?php echo convertLang2Html($html_outlook_quoting) ?></label>
           </td>
         </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend><?php echo convertLang2Html($html_send); ?></legend>
      <table>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <input type="checkbox" name="cc_self" id="cc_self" value="on" <?php if($user_prefs->getBccSelf()) echo 'checked="checked"'; ?> /><label for="cc_self"><?php echo convertLang2Html($html_bccself) ?></label>
           </td>
         </tr>
         <?php if (file_exists('ckeditor.php')) { ?>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <input type="checkbox" name="html_mail_send" id="html_mail_send" value="on" <?php if($user_prefs->getSendHtmlMail()) echo 'checked="checked"'; ?> /><label for="html_mail_send"><?php echo convertLang2Html($html_send_html_mail) ?></label>
           </td>
         </tr>
         <?php } ?>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <?php echo convertLang2Html($html_wrap) ?>
             <input type="radio" name="wrap_msg" id="wrap_msg_80" value="80" <?php if($user_prefs->getWrapMessages() == 80) echo 'checked="checked"'; ?> /><label for="wrap_msg_80">80</label>
             &nbsp;&nbsp;
             <input type="radio" name="wrap_msg" id="wrap_msg_72" value="72" <?php if($user_prefs->getWrapMessages() == 72) echo 'checked="checked"'; ?> /><label for="wrap_msg_72">72</label>
             &nbsp;&nbsp;
             <input type="radio" name="wrap_msg" id="wrap_msg_0" value="0" <?php if($user_prefs->getWrapMessages() == 0) echo 'checked="checked"'; ?> /><label for="wrap_msg_0"><?php echo convertLang2Html($html_wrap_none) ?></label>
           </td>
         </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend><?php echo convertLang2Html($html_signature); ?></legend>
      <table>
         <tr>
           <td class="prefsLabel"><label for="signature"><?php echo convertLang2Html($html_signature_label) ?></label></td>
           <td class="prefsData">
             <?php if (NOCC_Session::getSendHtmlMail() && file_exists('ckeditor.php') && ! $conf->ckeditor5) {
               include 'ckeditor.php';
               $oCKEditor = new CKEditor();
               $oCKEditor->basePath = 'ckeditor/';
               $oCKEditor->config['customConfig'] = $conf->base_url . 'config/ckeditor_config.js';
               $oCKEditor->editor('signature', $user_prefs->getSignature());
		}
		else if( NOCC_Session::getSendHtmlMail() && file_exists('ckeditor5/ckeditor.js') && file_exists('ckeditor5.php') && $conf->ckeditor5 ) {
			// use ckeditor5
			print('<textarea id="mail_body" name="mail_body" cols="82" rows="20">');
			$ckeditor5_mb=$user_prefs->getSignature();
			print('</textarea>');
			include "ckeditor5.php";
             } else { ?>
             <textarea class="button" name="signature" id="signature" rows="5" cols="40"><?php echo $user_prefs->getSignature(); ?></textarea>
             <?php } ?>
           </td>
         </tr>
         <?php if (!$user_prefs->getSendHtmlMail()) { ?>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <input type="checkbox" name="sig_sep" id="sig_sep" value="on" <?php if ($user_prefs->getUseSignatureSeparator()) echo 'checked="checked"'; ?> /><label for="sig_sep"><?php echo convertLang2Html($html_usenet_separator) ?></label>
           </td>
         </tr>
         <?php } ?>
      </table>
    </fieldset>
         <?php if ($pop->is_imap()) { ?>
    <fieldset>
      <legend><?php echo convertLang2Html($html_folders); ?></legend>
      <table>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <input type="checkbox" name="sent_folder" id="sent_folder" value="on" <?php if ($user_prefs->getUseSentFolder()) echo 'checked="checked"'; ?> /><label for="sent_folder"><?php echo convertLang2Html($html_sent_folder_label) ?></label>
             <select class="button" name="sent_folder_name"><?php echo join('', $sent_folders_list) ?></select>
           </td>
         </tr>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <input type="checkbox" name="trash_folder" id="trash_folder" value="on" <?php if ($user_prefs->getUseTrashFolder()) echo 'checked="checked"'; ?> /><label for="trash_folder"><?php echo convertLang2Html($html_trash_folder_label) ?></label>
             <select class="button" name="trash_folder_name"><?php echo join('', $trash_folders_list) ?></select>
           </td>
         </tr>
         <tr>
           <td class="prefsLabel">&nbsp;</td>
           <td class="prefsData">
             <input type="checkbox" name="inbox_folder" id="inbox_folder" value="on" <?php if ($user_prefs->getUseInboxFolder()) echo 'checked="checked"'; ?> /><label for="inbox_folder"><?php echo convertLang2Html($html_inbox_folder_label) ?></label>
             <select class="button" name="inbox_folder_name"><?php echo join('', $inbox_folders_list) ?></select>
           </td>
         </tr>
      </table>
    </fieldset>
         <?php } ?>
    <fieldset>
      <legend>NOCC</legend>
      <table>
         <tr>
           <td class="prefsLabel"><label for="collect"><?php echo convertLang2Html($html_collect_label) ?></label></td>
           <td class="prefsData">
             <select class="button" name="collect" id="collect">
	     <option value="0" <?php if(0==$user_prefs->getCollect()) echo " selected"; ?>><?php echo convertLang2Html($html_collect_option0) ?></option>
	     <option value="1" <?php if(1==$user_prefs->getCollect()) echo " selected"; ?>><?php echo convertLang2Html($html_collect_option1) ?></option>
	     <option value="2" <?php if(2==$user_prefs->getCollect()) echo " selected"; ?>><?php echo convertLang2Html($html_collect_option2) ?></option>
	     <option value="3" <?php if(3==$user_prefs->getCollect()) echo " selected"; ?>><?php echo convertLang2Html($html_collect_option3) ?></option>
             </select>
           </td>
         </tr>
         <tr>
           <td class="prefsLabel"><label for="lang"><?php echo convertLang2Html($html_lang_label) ?></label></td>
           <td class="prefsData">
             <select class="button" name="lang" id="lang">
               <?php
                 for ($i = 0; $i < sizeof($lang_array); $i++) {
	                 if( $lang_array[$i]->filename == 'default' || file_exists('lang/'.$lang_array[$i]->filename.'.php') ) {
	                   echo '<option value="'.$lang_array[$i]->filename.'"';
			   if( isset($user_prefs->lang) ) {
				if( $user_prefs->lang == $lang_array[$i]->filename ) {
					echo ' selected="selected"';
				}
			   }
			   else if( $_SESSION['nocc_lang'] == $lang_array[$i]->filename ) {
				echo ' selected="selected"';
			   }
	                   echo '>'.$lang_array[$i]->label.'</option>';
	                 }
		 }
               ?>
             </select>
           </td>
         </tr>
         <?php if ($conf->use_theme == true) { ?>
         <tr>
           <td class="prefsLabel"><label for="theme"><?php echo convertLang2Html($html_theme_label) ?></label></td>
           <td class="prefsData">
             <select class="button" name="theme" id="theme">
             <?php
               echo '<option value="default"';
		if( ! isset($user_prefs->theme) || $user_prefs->theme=="default" ) {
                     echo ' selected="selected"';
		}
               echo '>'.convertLang2Html($html_default).'</option>';
               $handle = opendir('./themes');
               while (($file = readdir($handle)) != false) {
                 if (($file != '.') && ($file != '..') && ($file != '.svn')) {
                   echo '<option value="'.$file.'"';
			if( isset($user_prefs->theme) && $user_prefs->theme==$file ) {
				echo ' selected="selected"';
		     	}
                   echo '>'.$file.'</option>';
                 }
               }
               closedir($handle);
             ?>
             </select>
           </td>
         </tr>
         <?php } ?>
      </table>
    </fieldset>
           <?php
             if (NoccException::isException($ev)) {
           ?>
             <div class="error">
               <table class="errorTable">
                 <tr class="errorTitle">
                   <td><?php echo convertLang2Html($html_error_occurred) ?></td>
                 </tr>
                 <tr class="errorText">
                   <td>
                     <p><?php echo convertLang2Html($ev->getMessage()); ?></p>
                   </td>
                 </tr>
               </table>
             </div>
           <?php
             } else {
               if (isset($_REQUEST['submit_prefs']))
                 echo '<p>' . convertLang2Html($html_prefs_updated) . '</p>';
             }
           ?>
      <p class="sendButtons">
        <input type="submit" class="button" value="<?php echo convertLang2Html($html_submit) ?>" />
        &nbsp;&nbsp;
	<a href="action.php?<?php echo NOCC_Session::getUrlGetSession(); ?>"><?php echo convertLang2Html($html_cancel) ?></a>
      </p>
     </div>
   </form>
 </div>
<!-- end of $Id: prefs.php 3016 2022-08-25 11:00:42Z oheil $ -->
