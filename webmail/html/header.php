<?php
  if (!isset($conf->loaded))
    die('Hacking attempt');

if (file_exists('./utils/proxy.php'))
    require_once './utils/proxy.php';
else
    die('./utils/proxy.php is missing');

header("Content-type: text/html; Charset=UTF-8");

// Don't call getPref unless session has been initialised enough for
// prefs.php to find it's prefs file.
$header_display_address = get_default_from_address();

$theme = new NOCC_Theme($_SESSION['nocc_theme']);

$custom_header = $theme->getCustomHeader();
if(file_exists($custom_header)) {
    include $custom_header;
}
else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang ?>" lang="<?php echo $lang ?>">
    <head>
        <title>NOCC - Webmail</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="<?php echo $theme->getStylesheet(); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo $theme->getPrintStylesheet(); ?>" rel="stylesheet" media="print" type="text/css" />
        <link href="<?php echo $theme->getFavicon(); ?>" rel="shortcut icon" type="image/x-icon" />
        <script src="js/nocc.js" type="text/javascript"></script>
<?php
  // if message is opened in another window, we reload the opener window
  // (message list), in order to refresh the mail list after a successful 
  // deletion. It does not works with Safari
  if (isset($_SESSION['message_deleted']) && $_SESSION['message_deleted'] == "true") {
    echo ("        <script type=\"text/javascript\">\n");
    echo ("          if (window.opener != null)\n");
    echo ("            window.opener.location.href = window.opener.location;\n");
    echo ("        </script>\n");
    $_SESSION['message_deleted'] = "false";
  }

  //TODO: Why check for $_SESSION['quota_type'])?
	if( isRssAllowed() ) {
		if (isset($_SESSION['quota_type'])) {
			//$sname='NOCC_'.md5(uniqid(rand(),true));
			$rss_url = "rss.php";
			//$rss_url .= '?sname='.$sname.'&';
			$rss_url .= '?sname=RSS&';
			$rss_url .= 'nocc_lang=' . base64_encode($_SESSION['nocc_lang']);
			$rss_url .= '&amp;nocc_smtp_server=' . base64_encode($_SESSION['nocc_smtp_server']);
			$rss_url .= '&amp;nocc_smtp_port=' . base64_encode($_SESSION['nocc_smtp_port']);
			$rss_url .= '&amp;nocc_theme=' . base64_encode($_SESSION['nocc_theme']);
			$rss_url .= '&amp;nocc_domain=' . base64_encode($_SESSION['nocc_domain']);
			$rss_url .= '&amp;nocc_domainnum=' . base64_encode($_SESSION['nocc_domainnum']);
			$rss_url .= '&amp;imap_namespace=' . base64_encode($_SESSION['imap_namespace']);
			$rss_url .= '&amp;nocc_servr=' . base64_encode($_SESSION['nocc_servr']);
			$rss_url .= '&amp;nocc_folder=' . base64_encode($_SESSION['nocc_folder']);
			$rss_url .= '&amp;smtp_auth=' . base64_encode($_SESSION['smtp_auth']);
			$rss_url .= '&amp;nocc_user=' . base64_encode($_SESSION['nocc_user']);
			$rss_url .= '&amp;nocc_passwd=' . base64_encode($_SESSION['nocc_passwd']);
			$rss_url .= '&amp;nocc_login=' . base64_encode($_SESSION['nocc_login']);
			$rss_url .= '&amp;ucb_pop_server=' . base64_encode($_SESSION['ucb_pop_server']);
			$rss_url .= '&amp;quota_enable=' . base64_encode($_SESSION['quota_enable']);
			$rss_url .= '&amp;quota_type=' . base64_encode($_SESSION['quota_type']);
			$rss_url .= '&amp;is_horde=' . base64_encode($_SESSION['is_horde']);
		}
	}
  if (isset($rss_url)) {
?>
        <link rel="alternate" type="application/rss+xml" title="RSS - NOCC" href="<?php echo $rss_url ?>" />
<?php } ?>
    </head>
    <body dir="<?php echo convertLang2Html($lang_dir); ?>">
        <div id="header">
            <h1>NOCC</h1>
<?php
  if (isset($_SESSION['nocc_loggedin'])) {
    if ($header_display_address != '') {
      echo "<h2>" . htmlspecialchars($header_display_address,ENT_COMPAT | ENT_SUBSTITUTE) . "</h2>\n";
    }
    echo "<ul>\n";
	echo "<li><a href=\"index.php?".NOCC_Session::get_next_session_name()."\" target=\"_blank\">".$html_new_session."</a></li>";

    echo "  <li><a href=\"action.php?".NOCC_Session::getUrlGetSession()."&action=setprefs\">" . convertLang2Html($html_preferences) . "</a></li>\n";
    if ($conf->enable_logout) {
	$title="";
	if( isset($_SESSION['creation_time']) ) {
		$max_session_age=60*60*6;  //6 hours
		if( isset($conf->min_session_lifetime) ) {
			$max_session_age=$conf->min_session_lifetime;
		}
		if( isset($_SESSION['persistent']) && $_SESSION['persistent']==1 ) {
			$max_session_age=4*7*24*60*60;  //4 weeks
			if( isset($conf->max_session_lifetime) ) {
				$max_session_age=$conf->max_session_lifetime;
			}
		}
		$expire_time=$_SESSION['creation_time']+$max_session_age;
		$title=convertLang2Html($html_session_expire_time).' '.format_date($expire_time,$lang).' '.format_time($expire_time,$lang);
	}
        echo '  <li><span title="'.$title.'"><a href="logout.php?'.NOCC_Session::getUrlGetSession().'">' . convertLang2Html($html_logout) . '</a></span></li>';
    }

	echo "<li>";
	$v=version();
	if( $v===0 ) {
		echo 'NOCC v'.$conf->nocc_version;
	}
	else if( $v===1 ) {
		echo '<span title="'.convertLang2Html($html_version_message1).'">NOCC v'.$conf->nocc_version.'</span>';
	} 
	else if( $v===2 ) {
		echo '<span title="'.convertLang2Html($html_version_message2).'"><a href="update.php?'.NOCC_Session::getUrlGetSession().'">NOCC v'.$conf->nocc_version.'</a></span>';
	} 
	else {
		echo '<span class="new-version" title="'.convertLang2Html($html_version_message3).': '.$v.'"><a href="update.php?'.NOCC_Session::getUrlGetSession().'">NOCC v'.$conf->nocc_version.'</a></span>';
	}
	echo "</li>\n";

    echo "</ul>\n";

  }
?>
        </div>
        <div id="main">
<?php
}
?>
<!-- end of $Id: header.php 2981 2021-12-22 08:53:00Z oheil $ -->
