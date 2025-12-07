<?php
/**
 * Mail search results
 *
 * Copyright 2025 Oliver Heil <oheil@heilbit.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: search.php 2681 2016-09-14 14:48:40Z oheil $
 */

require_once './common.php';
require_once './utils/proxy.php';

$json = trim(file_get_contents("php://input"));
$json_data = json_decode($json, true);
$ajx_search = 0;
if( isset($json_data['ajx_search']) ) {
	$ajx_search = $json_data['ajx_search'];
}

$theme = new NOCC_Theme($_SESSION['nocc_theme']);

$arrow = ($_SESSION['nocc_sortdir'] == 0) ? 'up' : 'down';
$new_sortdir = ($_SESSION['nocc_sortdir'] == 0) ? 1 : 0;

$msq = isset($_REQUEST['mail_search_query']) ? $_REQUEST['mail_search_query'] : "";

if( isset($_POST['mail_search_query']) && ! isset($_REQUEST['subject_only']) ) {
	$_SESSION['subject_only'] = 0;
}
if( isset($_POST['mail_search_query']) && isset($_REQUEST['subject_only']) ) {
	$_SESSION['subject_only'] = 1;
}
$subject_only = "";
if( isset($_SESSION['subject_only']) && $_SESSION['subject_only'] == 1 ) {
	$subject_only = " checked";
}

if( isset($_POST['mail_search_query']) && ! isset($_REQUEST['all_folders']) ) {
	$_SESSION['all_folders'] = 0;
}
if( isset($_POST['mail_search_query']) && isset($_REQUEST['all_folders']) ) {
	$_SESSION['all_folders'] = 1;
}
$all_folders = "";
$max_folders_disabled = " disabled";
if( isset($_SESSION['all_folders']) && $_SESSION['all_folders'] == 1 ) {
	$all_folders = " checked";
	$max_folders_disabled = "";
}

$search_folder=$_SESSION['nocc_folder'];
if( isset($_REQUEST['search_folder']) ) {
	$search_folder=$_REQUEST['search_folder'];
}

$start_folder = 1;
if( isset($_REQUEST['start_folder']) ) {
	$start_folder = $_REQUEST['start_folder'];
}
$max_search_folders_per_page = 5;
if( isset($_SESSION['nocc_domainnum']) && isset($conf->domains[$_SESSION['nocc_domainnum']]->max_search_folders_per_page) ) {
	$max_search_folders_per_page = $conf->domains[$_SESSION['nocc_domainnum']]->max_search_folders_per_page;
}

$max_folders = $max_search_folders_per_page;
if( isset($_POST['mail_search_query']) && isset($_REQUEST['max_folders']) ) {
	$max_folders = $_REQUEST['max_folders'];
}

$max_search_folders_per_page = $max_folders;

$_SESSION['nocc_loggedin'] = 1;

$is_imap = false;
$pop = null;

$use_ajax_js=true;
if( isset($conf->allow_js_folder_search) && ! $conf->allow_js_folder_search ) {
	$use_ajax_js=false;
}

if( $ajx_search == 0 ) {
	try {
		$pop = new nocc_imap();
	}
	catch (Exception $ex) {
		$ev = new NoccException($ex->getMessage());
		require './html/header.php'; 
		require './html/error.php';
		require './html/footer.php';
		exit;
	}   
	$is_imap = $pop->is_imap();
	$pop->logout();

	header("Content-type: text/html; Charset=UTF-8");
?>

<!DOCTYPE html>
<html lang="<?php echo $lang ?>">
<head>
  <title>NOCC - Webmail - <?php echo i18n_message($html_search,''); ?></title>
  <link href="<?php echo $theme->getStylesheet(); ?>" rel="stylesheet" type="text/css" />
  <link href="<?php echo $theme->getFavicon(); ?>" rel="shortcut icon" type="image/x-icon" />
  <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<style>
.loader {
    width: 48px;
    height: 48px;
    border: 5px solid #000;
    border-bottom-color: transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
    }

    @keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}   
.paused{
    -webkit-animation-play-state:paused;
    -moz-animation-play-state:paused;
    -o-animation-play-state:paused; 
    animation-play-state:paused;
}
:root {
	--display-empty:inline;
}
.folder_empty {
	display:var(--display-empty);
}
</style>
<script type="text/javascript">
	function toggle_checkbox(id) {
		el=document.getElementById(id);
		if(el){
			if(el.checked){
				el.checked=false;
			}
			else{
				el.checked=true;
			}
		}
		return el.checked;
	}
	function set_empty_style(id){
		el=document.getElementById(id);
		if(el){
			if(el.checked){
				document.documentElement.style.setProperty('--display-empty','none');
			}
			else{
				document.documentElement.style.setProperty('--display-empty','inline');
			}
		}
		return;
	}
</script>

</head>

<body id="popup" dir="<?php echo $lang_dir; ?>">
<a name="top"></a>

<div class="search">

<div style="position:fixed;top:0;left:0;right:0;height:180px;z-index:998;background-color:#ffffff;">
<h1 style="text-align:center;"><?php echo i18n_message($html_search,''); ?></h1>

<form name="mail_search" id="mail_search" action="javascript:;" method="post" onsubmit="document.getElementById('messageList').innerHTML='';document.body.style.cursor = 'wait';form=document.getElementById('mail_search');sq=document.getElementById('mail_search_query').value;form.action='search.php?<?php echo NOCC_Session::getUrlGetSession();?>&<?php echo NOCC_Session::getUrlQuery(); ?>&mail_search_query='+sq;form.submit();return false;" target="_self">
<input id="mail_search_query" name="mail_search_query" style="margin-left:5px;" value="<?php echo $msq; ?>" />
<input type="submit" class="button" value="<?php echo i18n_message($html_search, ''); ?>" style="margin-right:5px;" />
<label for="subject_only"><input type="checkbox" name="subject_only" id="subject_only" value="subject_only" <?php echo $subject_only; ?>/><?php echo $html_subject_only; ?></label>
<?php if( $is_imap ) { ?>
<label for="all_folders"><input type="checkbox" name="all_folders" id="all_folders" value="all_folders" <?php if(0){ ?>onclick="el=document.getElementById('folder');if(el){if(this.checked){el.disabled=1;}else{el.disabled=0;}};el2=document.getElementById('max_folders');if(el2){if(this.checked){el2.disabled=0;}else{el2.disabled=1;}};<?php } ?>" <?php echo $all_folders; ?>/><?php echo $html_all_folders; ?></label>
<?php echo $pop->html_folder_select('folder',$search_folder); ?>
<input type="hidden" name="start_folder" id="start_folder" value="<?php echo $start_folder; ?>" />
<?php
if( ! $use_ajax_js ) {
	$inf = '<input name="max_folders" id="max_folders" value="'.$max_folders.'" size="5" '.$max_folders_disabled.' />';
	echo sprintf($html_max_folders,$inf);
}
?>
<?php } ?>

<?php if(0){ ?>
<script type="text/javascript">
	elso=document.getElementById('all_folders');
	el=document.getElementById('folder');
	if(elso&&el){if(elso.checked){el.disabled=1;}else{el.disabled=0;}};
</script>
<?php } ?>

<div style="text-align:center;">
<h2 style="text-align:center;"><?php echo i18n_message($html_search_result,''); ?></h2>
<h3>
<input type="checkbox" name="hide_empty" id="hide_empty" value="hide_empty" onclick="set_empty_style('hide_empty');" />
<a href="" onclick="toggle_checkbox('hide_empty');set_empty_style('hide_empty');return false;"><?php echo convertLang2Html($html_hide_empty); ?></a>
</h3>
</div>

</div>

<div style="padding-top:180px;" class="messageList" id="messageList">

<div id="progress" style="text-align:center;position:fixed;top:0;right:0;width:200px;height:180px;z-index:998;background-color:#ffffff;">
<div id="search_progress"></div>
<div id="spinner" style="display:none;"><span id="loader" class="loader"></span></div>
<div id="stop" style="display:none;padding-top:5px;">
<h3>
<input type="checkbox" name="stop_search" id="stop_search" value="stop_search" /><a href="" onclick="el=document.getElementById('stop_search');if(el){if(el.checked){el.checked=false;}else{el.checked=true;}};return false;"><?php echo convertLang2Html($html_stop); ?></a>
<input type="checkbox" name="pause_search" id="pause_search" value="pause_search" /><a href="" onclick="el=document.getElementById('pause_search');if(el){if(el.checked){el.checked=false;}else{el.checked=true;}};return false;"><?php echo convertLang2Html($html_pause); ?></a>
</h3>
</div>
</div>

<?php
//if( isset($_SERVER["NOCC_ALPHA"]) ) {

	$conf_msg_per_page = -1;
	if( isset($conf->msg_per_page) ) {
		$conf_msg_per_page = $conf->msg_per_page;
	}
	$user_msg_per_page = -1;
	if( isset($user_prefs->msg_per_page) ) {
		$user_msg_per_page = $user_prefs->msg_per_page;
	}

	$jslist=array();
	$folders=array();
	$folder_count = 1;
	if(
		! $use_ajax_js &&
		isset($_SESSION['all_folders']) && $_SESSION['all_folders'] == 1
	) {
		$list = $pop->getsubscribednames();
		$folder_count = count($list);
		$index = $start_folder-1;
		while( $index >= $start_folder-1 && $index < $start_folder-1+$max_search_folders_per_page && $index <= $folder_count-1 ) {
			array_push($folders,$list[$index]);
			$index++;
		}
	}
	else {
		if( isset($_SESSION['all_folders']) && $_SESSION['all_folders'] == 1 ) {
			$jslist = $pop->getsubscribednames();
			$jslist = array_filter($jslist, fn($el) => $el != $search_folder);
		}
		array_push($folders,$search_folder);
	}

	$result_mail = array();
	$tmp_folder = $_SESSION['nocc_folder'];
	foreach( $folders as $folder ) {
		$error = false;
		$errorout="";
		if( ob_start() ) {
			try {
				$result_mail_folder = search_folder( $folder, $msq );
			}
			catch (Exception $ex) {
				$errorout = $errorout . ($ex->getMessage()) . "\n";
			}
			$errorout = $errorout . ob_get_contents();
			if( strlen($errorout) > 0 ) {
				$errorout = $errorout . "\n";
				$error = true;
			}
			ob_end_clean();
		} else {
			error_log("NOCC: search.php: output buffering failed.");
			$errorout = $errorout . "\n" . "output buffering failed";
			$error = true;
		}
		if( ! $error ) {
			$result_mail[$folder] = $result_mail_folder;
		}
		else {
			$content = '<div>'."\n";;
			$content = $content . '<h3 style="text-align:center;">'.$folder.'</h3>'."\n";
			$content = $content . '<div style="text-align:center;">'.convertLang2Html($html_error_occurred).':</div>'."\n";
			$content = $content . '<div style="text-align:center;"><pre>'.$errorout.'</pre></div>'."\n";
			$content = $content . '<br />'."\n";
			$content = $content . '<div style="text-align:right;">'."\n";
			$content = $content . '<a href="#top">'.convertLang2Html($html_contact_ruler_top).'</a>'."\n";
			$content = $content . '</div>'."\n";
			$content = $content . '<div style="text-align:center;"></div>'."\n";
			$content = $content . '</div>'."\n";
			echo $content;
		}
	}
	$_SESSION['nocc_folder'] = $tmp_folder;
	foreach( $result_mail as $folder => $result_mail_folder ) {
		$content = show_search_folder( $folder, $result_mail_folder, $msq, $folder_count );
		echo $content;
	}

	echo '<div id="next_folder"></div>'."\n";

	if( $conf_msg_per_page >= 0 ) {
		$conf->msg_per_page = $conf_msg_per_page;
	}
	if( $user_msg_per_page >= 0 ) {
		$user_prefs->msg_per_page = $user_msg_per_page;
	}
	else {
		$user_prefs->msg_per_page = 25;
	}
// } // if( isset($_SERVER["NOCC_ALPHA"]) )
?>

</div>
</form>

<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

</div>
<script type="text/javascript">
<?php
if( $use_ajax_js && isset($_SESSION['all_folders']) && $_SESSION['all_folders'] == 1 ) {
	echo 'var folder_list = ['."\n";
	foreach( $jslist as $folder ) {
		echo '\''.$folder.'\','."\n";
	}
	echo '];'."\n";
	echo 'var session = \''.preg_replace("/sname=/","",NOCC_Session::getUrlGetSession()).'\';'."\n";
	echo 'var msq = \''.$msq.'\';'."\n";
	echo 'var subject_only = '.$_SESSION['subject_only'].';'."\n";
?>
	function update_spinner(count,folder_count,i) {
		let elprog = document.getElementById('search_progress');
		if( count == folder_count ) {
			elprog.innerHTML = '<h2><?php echo convertLang2Html($html_search_finished); ?></h2>';
			document.body.style.cursor = 'auto';
			elspin = document.getElementById('spinner');
			if(elspin) {
				elspin.style.display='none';
			}
			elstop = document.getElementById('stop');
			if(elstop) {
				elstop.style.display='none';
			}
		} else {
			elprog.innerHTML = '<h2><?php echo convertLang2Html($html_folders); ?> '+(i+2)+'/'+(folder_count+1)+'</h2>';
		}
	}
	function sleep(ms) {
		return new Promise(resolve => setTimeout(resolve, ms));
	}
	async function out(folder_list) {
		var count = 0;
		var folder_count = folder_list.length;
		document.body.style.cursor = 'wait';
		let elprog = document.getElementById('search_progress');
		if(elprog) {
			elprog.innerHTML = '<h2><?php echo convertLang2Html($html_folders); ?> 1/'+(folder_count+1)+'</h2>';
		}
		let elspin = document.getElementById('spinner');
		if(elspin) {
			elspin.style.display='inline';
		}
		let elstop = document.getElementById('stop');
		if(elstop) {
			elstop.style.display='inline';
		}
		let elstopsearch = document.getElementById('stop_search');
		let elpausesearch = document.getElementById('pause_search');
		let elloader = document.getElementById('loader');
		for( let i = 0; i < folder_count; i++ ) {
			if(elstopsearch && elpausesearch && elloader) {
				while( elpausesearch.checked && ! elstopsearch.checked ) {
					document.body.style.cursor = 'auto';
					elloader.classList.add('paused');
					await sleep(500);
				}
				elloader.classList.remove("paused");
				document.body.style.cursor = 'wait';
				if(elstopsearch.checked) {
					i = folder_count;
				}
				else {
					var url = 'search.php?<?php echo NOCC_Session::getUrlGetSession(); ?>';
					var data = {sname: session, mail_search_query: msq, ajx_search: 1, search_folder: folder_list[i], subject_only: subject_only };
					await fetch(url, {
						method: 'POST',
						body: JSON.stringify(data),
						headers:{
							'Content-Type': 'application/json'
						}
					}).then(res => res.json())
					.then( response => {
						el = document.getElementById('next_folder');
						if(el) {
							el.outerHTML = response['result']+'<div id="next_folder"></div>';
						}
						elprog = document.getElementById('search_progress');
						count++;
						if(elprog) {
							update_spinner(count,folder_count,i);
						}
					})
					.catch(error => {
						count++;
						//console.error('Error:', error);
						//console.error('error while searching in folder '+folder_list[i]);
						update_spinner(count,folder_count,i);
					})
					//console.log(i);
					//give some process cycles if user clicks on a search result
					await sleep(500);
				}
			}
		}
		count = folder_count;
		update_spinner(count,folder_count,count);
		return;
	}
	setTimeout(function(){out(folder_list)},1000);
<?php
} else { ?>
	document.body.style.cursor = 'auto';
<?php } ?>
</script>

</body>
</html>

<?php
} // if( $ajx_search == 0 ) {
else {
	$msq = "";
	if( isset($json_data['mail_search_query']) ) {
		$msq = $json_data['mail_search_query'];
	}
	else {
		echo json_encode(['success'=>false]);
		exit();
	}
	$folder = "";
	if( isset($json_data['search_folder']) ) {
		$folder = $json_data['search_folder'];
	}
	else {
		echo json_encode(['success'=>false]);
		exit();
	}
	$subject_only = "";
	if( isset($json_data['subject_only']) ) {
		$subject_only = $json_data['subject_only'];
	}
	else {
		echo json_encode(['success'=>false]);
		exit();
	}
	$tmp_folder = $_SESSION['nocc_folder'];
	$error = false;
	$errorout="";
	if( ob_start() ) {
		try {
			$result_mail_folder = search_folder( $folder, $msq );
		}
		catch (Exception $ex) {
			$errorout = $errorout . ($ex->getMessage()) . "\n";
		}
		$errorout = $errorout . ob_get_contents();
		if( strlen($errorout) > 0 ) {
			$errorout = $errorout . "\n";
			$error = true;
		}
		ob_end_clean();
	} else {
		error_log("NOCC: search.php: output buffering failed.");
		$errorout = $errorout . "\n" . "output buffering failed";
		$error = true;
	}
	$_SESSION['nocc_folder'] = $tmp_folder;
	$folder_count = 1;
	if( ! $error ) {
		$content = show_search_folder( $folder, $result_mail_folder, $msq, $folder_count );
		echo json_encode(['success'=>true,'result'=>$content]);
	}
	else {
		$content = "";
		$content = $content . '<h3 style="text-align:center;">'.$folder.'</h3>'."\n";
		$content = $content . '<div style="text-align:center;">'.convertLang2Html($html_error_occurred).':</div>'."\n";
		$content = $content . '<div style="text-align:center;"><pre>'.$errorout.'</pre></div>'."\n";
		$content = $content . '<br />'."\n";
		$content = $content . '<div style="text-align:right;">'."\n";
		$content = $content . '<a href="#top">'.convertLang2Html($html_contact_ruler_top).'</a>'."\n";
		$content = $content . '</div>'."\n";
		$content = $content . '<div style="text-align:center;"></div>'."\n";
		echo json_encode(['success'=>false,'result'=>$content]);
	}
} // if( $ajx_search == 0 ) {} else {

function search_folder($folder,$msq) {
		global $pop, $user_prefs;

		$result_mail_folder = array();
		$mails = array();

		$_SESSION['nocc_folder'] = $folder;

		//$pop->logout();
		try {
			  $pop = new nocc_imap();
		}
		catch (Exception $ex) {
			throw new Exception($ex->getMessage());
		}
		$is_imap = $pop->is_imap();
		$pop->logout();

		$num_msg = $pop->num_msg();
		$user_prefs->msg_per_page = $num_msg;
		$tab_mail = inbox($pop, 0);
		
		$count = 0;
		while( ( $tmp = array_shift($tab_mail) ) && ($count <= 11 || $_SESSION['all_folders'] == 0) ) {
			$match = 0;
	
			$query = '/'.$msq.'/i';

			$haystack = convertMailData2Html(display_address($tmp['from']),0);
			$match = $match || preg_match($query,$haystack);
			$haystack = convertMailData2Html(display_address($tmp['to']),0);
			$match = $match || preg_match($query,$haystack);
			$haystack = convertMailData2Html($tmp['subject'],0);
			$match = $match || preg_match($query,$haystack);
			$haystack = $tmp['date'];
			$match = $match || preg_match($query,$haystack);
			$haystack = $tmp['time'];
			$match = $match || preg_match($query,$haystack);
			if( $_SESSION['subject_only'] == 0 ) {
				$content = "";
				try {
					$attachmentParts = null;
					$content = aff_mail($pop,$tmp['number'],NOCC_Request::getBoolValue('verbose'),$attachmentParts,false);
				}
				catch (Exception $ex) {
					//ignore this and go on
					//throw new Exception($ex->getMessage());
				}
				$match = $match || preg_match($query,$content['body']);
				$content = "";
			}
			if( $match == 1 ) {
				$count++;
				//show only first 10 hits, show only all hits if not searching in all folders
				if( $count <= 10 || $_SESSION['all_folders'] == 0 ) {
					array_push($mails,$tmp);
				}
			}
		}
		$result_mail_folder['mails'] = $mails;
		$result_mail_folder['found'] = count($mails);
		$result_mail_folder['num_msg'] = $num_msg;
		$result_mail_folder['more'] = false;
		if( $count >= 11 && $_SESSION['all_folders'] == 1 ) {
			$result_mail_folder['more'] = true;
			$result_mail_folder['found'] = $result_mail_folder['found'].'+';
		}
		return $result_mail_folder;
} // function search_folder($folder,$msq) {

function show_search_folder( $folder, $result_mail_folder, $msq, $folder_count ) {
		global $pop, $user_prefs, $conf, $use_ajax_js;
		global $is_imap, $html_from, $html_to, $html_subject, $html_nosubject, $html_date, $html_size, $html_kb, $html_contact_ruler_top, $new_sortdir, $arrow, $html_sort, $html_sort_by, $html_fd_mailcount, $html_search_more;
		global $start_folder, $max_search_folders_per_page;

		$mails = $result_mail_folder['mails'];

		$class_empty="";
		if( count($mails) < 1 ) {
			$class_empty="folder_empty";
		}

		$content = '<div class="'.$class_empty.'">'."\n";
		$content = $content.'<h3 style="text-align:center;">'.$folder.' ('.$result_mail_folder['found'].'/'.$result_mail_folder['num_msg'].')</h3>';
		if (count($mails) < 1) {
			// nothing found
			$content = $content.'<div style="text-align:center;">'.i18n_message($html_fd_mailcount,0).'</div>';
		} else {

			$content = $content.'<table id="inboxTable">'."\n";
			$content = $content.'<tr>'."\n";
			$content = $content.'<th class="column0"></th>'."\n";

			foreach ($conf->column_order as $column) { //For all columns...
				switch ($column) {
					case '1': $column_title = $html_from; break;
					case '2': $column_title = $html_to; break;
					case '3': $column_title = $html_subject; break;
					case '4': $column_title = $html_date; break;
					case '5': $column_title = $html_size; break;
					case '6': $column_title = ''; break;
					case '7': $column_title = ''; break;
					case '8': $column_title = ''; break;
					case '9': $column_title = ''; break;
					case '10': $column_title = ''; break;
					case '11': $column_title = ''; break;
				}
				$content = $content.'<th class="column'.$column;
				if ($_SESSION['nocc_sort'] == $column) {
					$content = $content.' sorted';
				}
				$content = $content.'">';
				if ($column_title != '') { //If we have a column title...
					$content = $content.'<a href="?'.NOCC_Session::getUrlGetSession().'&mail_search_query='.$msq.'&sort='.$column.'&amp;sortdir='.$new_sortdir.'">'.$column_title.'</a>';
					if ($_SESSION['nocc_sort'] == $column) {
						$content = $content.'&nbsp;';
						$content = $content.'<a href="action.php?'.NOCC_Session::getUrlGetSession().'&mail_search_query='.$msq.'&sort='.$column.'&amp;sortdir='.$new_sortdir.'">';
						$content = $content.'  <img src="themes/'.$_SESSION['nocc_theme'].'/img/'.$arrow.'.png" class="sort" alt="'.$html_sort.'" title="'.$html_sort_by.' '.$column_title.'" />';
						$content = $content.'</a>';
					}
				}
				else { //If we NOT have a column title...
					if ($column == '8') { //If "Priority Text" column...
						$content = $content.$html_priority;
					}
					elseif ($column == '9') { //If "Priority Number" column...
						$content = $content.'<span title="'.$html_priority.'">!</span>';
					}
					elseif ($column == '10') { //If "Flagged" column...
						$content = $content.'<span title="'.$html_flagged.'">*</span>';
					}
					elseif ($column == '11') { //If "SPAM" column...
						$content = $content.$html_spam;
					}
				}
				$content = $content.'</th>';
			}

			$content = $content.'</tr>'."\n";

			// there are messages, we display
			while ($tmp = array_shift($mails)) {
				//require './html/html_inbox.php';
				$even_odd_class = ($tmp['index'] % 2) ? 'even' : 'odd';
				$new_class = '';
				if ($_SESSION['ucb_pop_server'] || $is_imap ) {
					if ($tmp['new'] == true) {
						$new_class = ' new';
					}
				}
				$spam_class = '';
				if ($tmp['spam'] == true) { 
					$spam_class = ' spam'.ucfirst($even_odd_class);
				}
				$content = $content.'<tr class="'.$even_odd_class.$new_class.$spam_class.'">';
				$content = $content.'<td class="column0">';
				$content = $content.'</td>';
				foreach ($conf->column_order as $column) {
					$content = $content . '<td class="column'.$column;
					if ($_SESSION['nocc_sort'] == $column) {
						$content = $content.' sorted';
					}
					$content = $content.'">';
					$content = $content.'<a href="" ';
					$content = $content.' onclick="window.opener.location.href=\'action.php?'.NOCC_Session::getUrlGetSession().'&action=aff_mail&amp;mail='.$tmp['number'].'&amp;verbose=0&amp;mail_search_query='.$msq.'&folder='.$folder.'\';return false;"';
					$content = $content.'">';
					    switch ($column) {
						case '1': //From...
							$content = $content.convertMailData2Html(display_address($tmp['from']), 55).'&nbsp;';
							break;
						case '2': //To...
							$content = $content.convertMailData2Html(display_address($tmp['to']), 55);
							break;
						case '3': //Subject...
							if( $tmp['subject'] ) {
								$content = $content.convertMailData2Html($tmp['subject'], 55);
							}
							else {
								$content = $content.$html_nosubject;
							}
							break;
						case '4': //Date...
							$content = $content.$tmp['date'].'&nbsp;'.$tmp['time'];
							break;
						case '5': //Size...
							$content = $content.$tmp['size'].$html_kb;
							break;
						case '6': //Read/Unread...
							if ($tmp['new'] == true) { //if unread...
								$content = $content.'<img src="themes/'.$_SESSION['nocc_theme'].'/img/new.png" alt="" />';
							}
							else { //if read...
								$content = $content.'&nbsp;';
							}
							break;
						case '7': //Attachment...
							if ($tmp['attach'] == true) { //if has attachments...
								$content = $content.'<img src="themes/'.$_SESSION['nocc_theme'].'/img/attach.png" alt="" />';
							}
							else { //if NOT has attachments...
								$content = $content.'&nbsp;';
							}
							break;
						case '8': //Priority Text...
							$content = $content.$tmp['priority_text'];
							break;
						case '9': //Priority Number...
							$content = $content.'<span title="'.$html_priority_label.' '.$tmp['priority_text'].'">'.$tmp['priority'].'</span>';
							break;
						case '10': //Flagged...
							if ($tmp['flagged']) {
								$content = $content.'<span title="'.$html_flagged.'">*</span>';
							}
							break;
						case '11': //SPAM...
							if ($tmp['spam']) {
								$content = $content.$html_spam;
							}
							break;
					}
					$content = $content.'</a>';
					$content = $content.'</td>';
				}
				$content = $content.'</tr>';
			}
		}

		$content = $content . '</table>'."\n";

		if( $result_mail_folder['more'] ) {
			$content = $content.'<br />';
			$content = $content.'<div style="text-align:center;">'."\n";
			$content = $content.'<a href="" onclick="document.getElementById(\'messageList\').innerHTML=\'\';document.body.style.cursor=\'wait\';form=document.getElementById(\'mail_search\');sq=document.getElementById(\'mail_search_query\').value;all_folders=document.getElementById(\'all_folders\');all_folders.checked=false;form.action=\'search.php?'.NOCC_Session::getUrlGetSession().'&'.NOCC_Session::getUrlQuery().'&search_folder='.$folder.'&mail_search_query=\'+sq;form.submit();return false;" target="_self" >'."\n";
			$content = $content.'<h3>'.convertLang2Html($html_search_more).'</h3>'."\n";
			$content = $content.'</a>'."\n";
			$content = $content.'</div>'."\n";
		}

		$content = $content.'<br />';
		$content = $content.'<div style="text-align:right;">'."\n";
		$content = $content.'<a href="#top">'.convertLang2Html($html_contact_ruler_top).'</a>'."\n";
		$content = $content.'</div>'."\n";
		$content = $content.'<div style="text-align:center;">'."\n";
		if( ! $use_ajax_js && isset($_SESSION['all_folders']) && $_SESSION['all_folders'] == 1 ) {
			$index = 0;
			while( $index < $folder_count ) {
				$to_folder = $index+$max_search_folders_per_page;
				if( $to_folder >= $folder_count ) {
					$to_folder = $folder_count;
				}
				if( $index+1 < $start_folder || $index+1 >= $start_folder+$max_search_folders_per_page ) {
					$content = $content.'<a href="" onclick="document.getElementById(\'messageList\').innerHTML=\'\';document.body.style.cursor = \'wait\';start_folder=document.getElementById(\'start_folder\');start_folder.value='.($index+1).';form=document.getElementById(\'mail_search\');sq=document.getElementById(\'mail_search_query\').value;form.action=\'search.php?'.NOCC_Session::getUrlGetSession().'&'.NOCC_Session::getUrlQuery().'&mail_search_query=\'+sq;form.submit();return false;" target="_self">'."\n";
				}
				$content = $content.''.($index+1).'-'.$to_folder.''."\n";
				if( $index+1 < $start_folder || $index+1 >= $start_folder+$max_search_folders_per_page ) {
					$content = $content.'</a>'."\n";
				}
				$index+=$max_search_folders_per_page;
			}
		}
		$content = $content.'</div>'."\n";

		$content = $content."</div>"."\n";
		return $content;
} // function show_search_folder( $folder, $msq ) {

?>


