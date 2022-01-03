<?php
/**
 * Class for wrapping the $_SESSION array
 *
 * Copyright 2009-2011 Tim Gerundt <tim@gerundt.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: nocc_session.php 2985 2021-12-27 10:05:54Z oheil $
 */
require_once 'horde_autoloader.php';
require_once 'user_prefs.php';

/**
 * Wrapping the $_SESSION array
 *
 * @package    NOCC
 */
class NOCC_Session {

    /**
     * Start the session
     * @static
     */
    public static function start($persistent=0) {
	global $conf;

	$cookie_lifetime=0;
	if( $persistent==1 ) {
		$cookie_lifetime=60*60*24*7*4; //4weeks
		if( isset($conf->max_session_lifetime) ) {
			$cookie_lifetime=$conf->max_session_lifetime;
		}
	}
	$session_has_expired=0;
	NOCC_Session::remove_old_sessions();
	if( ! isset($_REQUEST['sname']) || ( strlen($_REQUEST['sname'])>0 && preg_match("/^NOCC_/",$_REQUEST['sname']) ) ) {
		foreach( $_COOKIE as $cookie_key => $cookie_value ) {
			if( preg_match("/^NOCC_/",$cookie_key) ) {
				$sname=$cookie_key;
				session_name($sname);
				session_set_cookie_params($cookie_lifetime,'/','',false);
				session_start();

				$_SESSION['sname']=$sname;
				//
				// The currently provided RSS URL (right next to INBOX) allows to view list of emails without authentification.
				// With the following if/then/else switch one can read/answer/... the email
				//   without authentication and with the result of a complete logged in NOCC session.
				// This mechanism would allow complete sessions only using the RSS URL and without athentication.
				// Unclear what the RSS URL should be used for and what should be possible with it.
				// This is here and elsewhere tagged with
				//   RSS-QUESTION
				//
				//if( isset($_SESSION['rss']) && $_SESSION['rss'] ) {
				//}
				//else {
					if( isset($_SESSION['send_backup']) && ! isset($_GET['discard']) ) {
						$send_backup=$_SESSION['send_backup'];
						session_write_close();
						NOCC_Session::new_session($persistent);
					}
		       			NOCC_Session::destroy();
				//}
			}
		}
	}
	$found_session=false;
	//
	//   RSS-QUESTION
	//
	//if( isset($_SESSION['rss']) && $_SESSION['rss'] ) {
	//	$found_session=true;
	//}
	//else {
		if( isset($_REQUEST['sname']) && strlen($_REQUEST['sname'])>0 ) {
			$sname=$_REQUEST['sname'];
			session_name($sname);
			session_set_cookie_params($cookie_lifetime,'/','',false);
			session_start();

			if( isset($_SESSION['send_backup']) && ! isset($_GET['discard']) ) {
				$send_backup=$_SESSION['send_backup'];
			}
			$svalue=session_id();
			$_SESSION['sname']=$sname;
			$_SESSION['svalue']=$svalue;
	
			if( $_SESSION['sname'] == "RSS" ) {
				$found_session=true;
			}
			else if(isset($_SESSION['nocc_loggedin']) && $_SESSION['nocc_loggedin']) {
				$_SESSION['restart_session']=true;
				$found_session=true;
			}
			else if( NOCC_Session::load_session() ) {
				$_SESSION['restart_session']=true;
				$found_session=true;
			}
			else {
		       		NOCC_Session::destroy();
				if( preg_match("/^NOCCLI_/",$sname) ) {
					$session_has_expired=1;
				}
			}
			if( isset($_SESSION['sname']) && $_SESSION['sname'] == "RSS" ) {
			}
			else {
				if( $found_session && NOCC_Session::check_session_age() ) {
			       		NOCC_Session::destroy();
					$session_has_expired=1;
					$found_session=false;
				}
				if( $found_session && isset($conf->check_client_ip) && $conf->check_client_ip ) {
					if( $_SESSION['remote_addr'] != $_SERVER['REMOTE_ADDR'] ) {
			       			NOCC_Session::destroy();
						$found_session=false;
						$session_has_expired=2;
					}
				}
			}
		}
		else {
			foreach( $_COOKIE as $cookie_key => $cookie_value ) {
				if( preg_match("/^NOCCLI_/",$cookie_key) ) {
					$sname=$cookie_key;
					session_name($sname);
					session_set_cookie_params($cookie_lifetime,'/','',false);
					session_start();
					if( isset($_SESSION['send_backup']) ) {
						$send_backup=$_SESSION['send_backup'];
					}
					$svalue=session_id();
					$_SESSION['sname']=$sname;
					$_SESSION['svalue']=$svalue;
					$_SESSION['restart_session']=true;
	
					if( isset($_SESSION['nocc_loggedin']) && $_SESSION['nocc_loggedin'] ) {
						$found_session=true;
						break;
					}
					else if( NOCC_Session::load_session() ) {
						$found_session=true;
						break;
					}
					else {
		       				NOCC_Session::destroy();
					}
				}
			}
			if( $found_session && NOCC_Session::check_session_age() ) {
		       		NOCC_Session::destroy();
				$session_has_expired=1;
				$found_session=false;
			}
			if( $found_session && isset($conf->check_client_ip) && $conf->check_client_ip ) {
				if( $_SESSION['remote_addr'] != $_SERVER['REMOTE_ADDR'] ) {
		       			NOCC_Session::destroy();
					$found_session=false;
					$session_has_expired=2;
				}
			}
		}
	//
	//   RSS-QUESTION
	//
	//}
	if( ! $found_session ) {
		NOCC_Session::new_session($persistent);
		if( isset($send_backup) ) {
			$_SESSION['send_backup']=$send_backup;
		}
	}
	if( ! isset($_SESSION['persistent']) ) {
		$_SESSION['persistent']=-1;
	}
	if( $persistent==1 ) {
		$_SESSION['persistent']=1;
	}
	NOCC_Session::remove_old_session_tmp_file();

	$_SESSION['remote_addr']=$_SERVER['REMOTE_ADDR'];

	return $session_has_expired;
    }

	/**
	 * Manage session time outs, don't rely on servers session gc or on user cookies
	 * @static
	 */
	public static function check_session_age() {
		global $conf;
		$session_expired=true;
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
		if( isset($_SESSION['creation_time']) ) {
			if( time()-$_SESSION['creation_time']<=$max_session_age ) {
				$session_expired=false;
			}
			if( ! $session_expired && ( ! isset($_SESSION['persistent']) || $_SESSION['persistent']!=1 ) ) {
				$_SESSION['creation_time']=time();
			}
		}
		return $session_expired;
	}

	/**
	 * Remove old saved sessions
	 * @static
	 */
	public static function remove_old_sessions() {
		global $conf;
		$max_age=60*60*24*7*4;  //4 weeks
		if( isset($conf->max_session_lifetime) ) {
			$max_age=$conf->max_session_lifetime;
		}
		if( ! isset($conf->prune_sessions) || ! $conf->prune_sessions==0 ) {
			if (!empty($conf->prefs_dir)) {
				$old_session_files=glob($conf->prefs_dir.'/'."NOCCLI_*");
				if( is_array($old_session_files) && count($old_session_files)>0 ) {
					foreach( $old_session_files as $filename) {
						$last_mod=filemtime($filename);
						$age=time()-$last_mod;
						if( $age>$max_age ) {
							unlink($filename);
						}
					}
				}
			}
		}
	}

	/**
	 * Remove old session tmp files
	 * @static
	 */
	public static function remove_old_session_tmp_file() {
		global $conf;
		if( !empty($conf->tmpdir) && isset($_SESSION['sname']) && strlen($_SESSION['sname'])>0 ) {
			$available_session_files=glob($conf->tmpdir.'/'.$_SESSION['sname']."_*");
			if( is_array($available_session_files) && count($available_session_files)>0 ) {
				foreach( $available_session_files as $filename) {
					$sname=preg_replace("/\.session$/","",$filename);
					if( isset($_SESSION[$sname]) && $_SESSION[$sname]>0 ) {
						$_SESSION[$sname]=$_SESSION[$sname]-1;
					}
					else {
						unset($_SESSION[$sname]);
						unlink($filename);
					}
				}
			}
		}
		if( !empty($conf->tmpdir) ) {
			$old_session_files=glob($conf->tmpdir.'/'."NOCCLI_*");
			if( is_array($old_session_files) && count($old_session_files)>0 ) {
				foreach( $old_session_files as $filename) {
					$last_mod=filemtime($filename);
					$age=time()-$last_mod;
					$max_age=60*60*1;  //1 hour
					if( $age>$max_age ) {
						unlink($filename);
					}
				}
			}
			$old_session_files=glob($conf->tmpdir.'/'."php*.att");
			if( is_array($old_session_files) && count($old_session_files)>0 ) {
				foreach( $old_session_files as $filename) {
					$last_mod=filemtime($filename);
					$age=time()-$last_mod;
					$max_age=60*60*24*1;  //1 day
					if( $age>$max_age ) {
						unlink($filename);
					}
				}
			}
		}
	}

	/**
	 * Get next session name
	 * @static
	 */
	public static function get_next_session_name() {
		$current_name=session_name();
		$set_next=false;
		$next_name="";
		foreach( $_COOKIE as $cookie_key => $cookie_value ) {
			if( preg_match("/^NOCCLI_/",$cookie_key) ) {
				if( $set_next ) {
					$next_name=$cookie_key;
					break;
				}
				if( $current_name==$cookie_key ) {
					$set_next=true;
				}
			}
		}
		if( strlen($next_name)==0 ) {
			$next_name='NOCC_'.md5(uniqid(rand(),true));
		}
		$next_name="sname=".$next_name;
		return $next_name;
	}

	/**
	 * Rename current session
	 * @static
	 */
	public static function rename_session() {
		$old_sname=session_name();
		if( preg_match("/^NOCC_/",$old_sname) ) {
			$sname='NOCCLI_'.md5(uniqid(rand(),true));
			//session_name($sname);
			session_regenerate_id(true);
			$svalue=session_id();
			$_SESSION['sname']=$sname;
			$_SESSION['svalue']=$svalue;

			setcookie($old_sname, '', time() - 3600, '/', '', false);
			//return true;
			return $sname;
		}
		else {
			//return false;
			return "";
		}
	}

	/**
	 * Start a new  session
	 * @static
	 */
	public static function new_session($persistent=0) {
		global $conf;
		$cookie_lifetime=0;
		if( $persistent==1 ) {
			$cookie_lifetime=60*60*24*7*4; //4weeks
			if( isset($conf->max_session_lifetime) ) {
				$cookie_lifetime=$conf->max_session_lifetime;
			}
		}
		$sname='NOCC_'.md5(uniqid(rand(),true));
		session_name($sname);
		session_set_cookie_params($cookie_lifetime,'/','',false);
		session_start();
		$svalue=session_id();
		$_SESSION['sname']=$sname;
		$_SESSION['svalue']=$svalue;
		$_SESSION['creation_time']=time();
	}

	/**
	 * Save a session
	 * @static
	 */
	public static function save_session() {
		global $conf;
		if (!empty($conf->prefs_dir)) {

			 // generate string with session information
			$save_string = session_id();
			$save_string .= " " . $_SESSION['nocc_user'];
			$save_string .= " " . $_SESSION['nocc_passwd'];
			$save_string .= " " . $_SESSION['nocc_login'];
			$save_string .= " " . $_SESSION['nocc_lang'];
			$save_string .= " " . $_SESSION['nocc_smtp_server'];
			$save_string .= " " . $_SESSION['nocc_smtp_port'];
			$save_string .= " " . $_SESSION['nocc_theme'];
			$save_string .= " " . $_SESSION['nocc_domain'];
			$save_string .= " " . $_SESSION['nocc_domainnum'];
			$save_string .= " " . $_SESSION['imap_namespace'];
			$save_string .= " " . $_SESSION['nocc_servr'];
			$save_string .= " " . $_SESSION['nocc_folder'];
			$save_string .= " " . $_SESSION['smtp_auth'];
			$save_string .= " " . $_SESSION['ucb_pop_server'];
			$save_string .= " " . $_SESSION['quota_enable'];
			$save_string .= " " . $_SESSION['quota_type'];
			$save_string .= " " . $_SESSION['creation_time'];
			$save_string .= " " . $_SESSION['persistent'];
			$save_string .= " " . $_SESSION['remote_addr'];
			$save_string .= " " . $_SESSION['is_horde'];

			// encode string to base64
			$save_string = base64_encode($save_string);

			// save string to file
			$filename = $conf->prefs_dir . '/' . $_SESSION['sname'].'.session';

			if (file_exists($filename) && !is_writable($filename)) {
				$ev = new NoccException($html_session_file_error);
				return false;
			}
			if (!is_writable($conf->prefs_dir)) {
				$ev = new NoccException($html_session_file_error);
				return false;
			}
			$file = fopen($filename, 'w');
			if (!$file) {
				$ev = new NoccException($html_session_file_error);
				return false;
			}
			fwrite($file, $save_string . "\n");
			fclose($file);
			return true;
		}
		return false;
	}

	/**
	 * Load a saved session file
	 * @static
	 */
	public static function load_session_file($sname) {
		global $conf;
		if (empty($conf->prefs_dir)) {
			return false;
		}

		$filename=$conf->prefs_dir.'/'.$sname.'.session';
		if (!file_exists($filename)) {
			return false;
		}
	
		$file=fopen($filename, 'r');
		if (!$file) {
			return false;
		}
	
		$line = trim(fgets($file, 1024));
		fclose($file);

		return $line;
	}

	/**
	 * Load a saved session into SESSION
	 * @static
	 */
	public static function load_session() {
		global $conf;
		if (empty($conf->prefs_dir)) {
			return false;
		}
	
		$sname=session_name();
		$line=NOCC_Session::load_session_file($sname);
		if( ! $line ) {
			return false;
		}
		list(
			$session_id,
			$_SESSION['nocc_user'],
			$_SESSION['nocc_passwd'],
			$_SESSION['nocc_login'],
			$_SESSION['nocc_lang'],
			$_SESSION['nocc_smtp_server'],
			$_SESSION['nocc_smtp_port'],
			$_SESSION['nocc_theme'],
			$_SESSION['nocc_domain'],
			$_SESSION['nocc_domainnum'],
			$_SESSION['imap_namespace'],
			$_SESSION['nocc_servr'],
			$_SESSION['nocc_folder'],
			$_SESSION['smtp_auth'],
			$_SESSION['ucb_pop_server'],
			$_SESSION['quota_enable'],
			$_SESSION['quota_type'],
			$_SESSION['creation_time'],
			$_SESSION['persistent'],
			$_SESSION['remote_addr'],
			$_SESSION['is_horde']
			) = explode(" ", base64_decode($line));
		$_SESSION['nocc_folder'] = isset($_REQUEST['nocc_folder']) ? $_REQUEST['nocc_folder'] : 'INBOX';

		if( session_id()==$session_id ) {
			return true;	
		}
		else {
			return false;
		}
	}

	/**
	 * Remove a saved session file
	 * @static
	 */
	public static function remove_session_file() {
		global $conf;
		if( isset($conf->prefs_dir) ) {
			$sname=session_name();
			$filename=$conf->prefs_dir.'/'.$sname.'.session';
			if( file_exists($filename) ) {
				unlink($filename);
			}
		}
	}
    
    /**
     * Destroy the session
     * @param bool $forceSessionStart Force session start?
     * @static
     */
    public static function destroy($forceSessionStart = false) {
	$sname='NOCCSESSID';
	if( isset($_SESSION['sname']) && strlen($_SESSION['sname'])>0 ) {
		$sname=$_SESSION['sname'];
	}
        //session_name($sname);
	NOCC_Session::remove_session_file();
        if ($forceSessionStart) {
		session_set_cookie_params(0,'/','',false);
            session_start();
        }
	setcookie($sname, '', time() - 3600, '/', '', false);

        $_SESSION = array();
        session_destroy();
    }
    
    /**
     * Create session cookie
     * @static
     */
    public static function createCookie($persistent=0) {
	global $conf;
	$cookie_lifetime=0;
	if( $persistent==1 ) {
		$cookie_lifetime=time()+60*60*24*7*4; //4weeks
		if( isset($conf->max_session_lifetime) ) {
			$cookie_lifetime=time()+$conf->max_session_lifetime;
		}
	}
	$sname='NOCCSESSID';
	if( isset($_SESSION['sname']) && strlen($_SESSION['sname'])>0 ) {
		$sname=$_SESSION['sname'];
	}
	$svalue=session_id();
	setcookie($sname,$svalue,$cookie_lifetime, '/', '', false);
    }
    
    /**
     * Delete session cookie
     * @static
     */
    public static function deleteCookie() {
	$sname='NOCCSESSID';
	if( isset($_SESSION['sname']) && strlen($_SESSION['sname'])>0 ) {
		$sname=$_SESSION['sname'];
	}
        setcookie($sname, '', time() - 3600, '/', '', false);
    }
    
    /**
     * Get the URL query from the session
     * @return string URL query
     * @static
     */
    public static function getUrlQuery() {
        #return session_name() . '=' . session_id();
	return "";
    }
    
    /**
     * Get the URL session GET part
     * @return string URL GET part
     * @static
     */
    public static function getUrlGetSession() {
	return "sname=".session_name();
    }
    
    /**
     * Get the user key from the session
     * @return string User key
     * @static
     */
    public static function getUserKey() {
        return $_SESSION['nocc_user'] . '@' . $_SESSION['nocc_domain'];
    }
    
    /**
     * Get the SMTP server from the session
     * @return string SMTP server
     * @static
     */
    public static function getSmtpServer() {
        if (isset($_SESSION['nocc_smtp_server'])) {
            return $_SESSION['nocc_smtp_server'];
        }
        return '';
    }
    
    /**
     * Set the SMTP server from the session
     * @param string $value SMTP server
     * @static
     */
    public static function setSmtpServer($value) {
        $_SESSION['nocc_smtp_server'] = $value;
    }
    
    /**
     * Get quota enabling from the session
     * @return bool Quota enabled?
     * @static
     */
    public static function getQuotaEnable() {
        if (isset($_SESSION['quota_enable']) && $_SESSION['quota_enable']) {
            return true;
        }
        return false;
    }
    
    /**
     * Set quota enabling from the session
     * @param bool $value Quota enabled?
     * @static
     */
    public static function setQuotaEnable($value) {
        $_SESSION['quota_enable'] = $value;
    }
    
    /**
     * Get quota type (STORAGE or MESSAGE) from the session
     * @return string Quota type
     * @static
     * @todo Check for STORAGE or MESSAGE?
     */
    public static function getQuotaType() {
        if (isset($_SESSION['quota_type'])) {
            return $_SESSION['quota_type'];
        }
        return 'STORAGE';
    }
    
    /**
     * Set quota type (STORAGE or MESSAGE) from the session
     * @param string $value Quota type
     * @static
     * @todo Check for STORAGE or MESSAGE?
     */
    public static function setQuotaType($value) {
        $_SESSION['quota_type'] = $value;
    }

    /**
     * Exists user preferences in the session?
     * @return boolean Exists user preferences?
     * @static
     */
    public static function existsUserPrefs() {
        if (isset($_SESSION['nocc_user_prefs'])) {
            if ($_SESSION['nocc_user_prefs'] instanceof NOCCUserPrefs) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get user preferences from the session
     * @return NOCCUserPrefs User preferences
     * @static
     */
    public static function getUserPrefs() {
        if (NOCC_Session::existsUserPrefs()) {
            return $_SESSION['nocc_user_prefs'];
        }
        return new NOCCUserPrefs('');
    }

    /**
     * Set user preferences from the session
     * @param NOCCUserPrefs $value User preferences
     * @static
     * @todo Check for NOCCUserPrefs?
     */
    public static function setUserPrefs($value) {
        $_SESSION['nocc_user_prefs'] = $value;
    }

    /**
     * Get HTML mail sending from the session
     * @return bool User preferences
     * @static
     */
    public static function getSendHtmlMail() {
        if (isset($_SESSION['html_mail_send']) && $_SESSION['html_mail_send']) {
            return true;
        }
        return false;
    }

    /**
     * Set HTML mail sending from the session
     * @param bool $value User preferences
     * @static
     */
    public static function setSendHtmlMail($value) {
        $_SESSION['html_mail_send'] = $value;
    }
}
