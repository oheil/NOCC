<?php
/**
 * Class for IMAP/POP3 functions
 *
 * Moved all imap_* PHP calls into one, which should make it easier to write
 * our own IMAP/POP3 classes in the future.
 *
 * Copyright 2001 Nicolas Chalanset <nicocha@free.fr>
 * Copyright 2001 Olivier Cahagne <cahagn_o@epita.fr>
 * Copyright 2002 Mike Rylander <mrylander@mail.com>
 * Copyright 2008-2011 Tim Gerundt <tim@gerundt.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: class_local.php 3050 2023-02-23 13:18:20Z oheil $
 */


require_once 'nocc_mailstructure.php';
require_once 'nocc_headerinfo.php';
require_once 'nocc_header.php';
require_once 'exception.php';
require_once './utils/detect_cyr_charset.php';
require_once './utils/crypt.php';

require_once 'horde_autoloader.php';

class result
{
  public $text = '';
  public $charset = '';
}

//TODO: Use mail or message as name?
class nocc_imap
{
    private $server;
    private $login;
    private $passwd;
    private $conn;
    private $folder;
    private $namespace;
    private $_isImap;

    /**
     * ...
     * @global object $conf
     * @global string $lang_could_not_connect
     * @return nocc_imap Me!
     */
    public function __construct() {
        global $conf;
        global $lang_could_not_connect;
	global $err_user_empty;
	global $err_passwd_empty;

        if (!isset($_SESSION['nocc_servr']) || !isset($_SESSION['nocc_folder']) || !isset($_SESSION['nocc_login']) || !isset($_SESSION['nocc_passwd'])) {
            throw new Exception($lang_could_not_connect."(0)");
        }

        $this->server = $_SESSION['nocc_servr'];
	if( isset($_SESSION['ajxfolder']) ) {
		$this->folder = $_SESSION['ajxfolder'];
	}
	else {
        	$this->folder = $_SESSION['nocc_folder'];
	}
        $this->login = $_SESSION['nocc_login'];
        /* decrypt password */
        $this->passwd = decpass($_SESSION['nocc_passwd'], $conf->master_key);

        $this->namespace = $_SESSION['imap_namespace'];

	if( ! isset($_SESSION['is_horde']) ) {
		$_SESSION['is_horde']=$this->is_horde();
	}

	$memcache = null;
	$login_attempts=-1;
	$remote_ip="";
	if( extension_loaded("memcache") ) {
		if( isset($_SERVER['REMOTE_ADDR']) ) {
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$memcache = new Memcache;
			if( $memcache->connect('localhost', 11211) ) {
				$count=-1;
				if( $count = $memcache->get($remote_ip) ) {
					$count++;
					$memcache->set($remote_ip,$count,false,30);
				}
				else {
					$count=1;
					$memcache->set($remote_ip,$count,false,30);
				}
				$login_attempts=$count;
			}
		}
	}
	if( $login_attempts > 5 ) {
		sleep(10);
		throw new Exception($lang_could_not_connect."(4)");
	}

        // $ev is set if there is a problem with the connection
	if( ! $this->is_horde() ) {
	        $conn = @imap_open('{'.$this->server.'}'.mb_convert_encoding($this->folder, 'UTF7-IMAP', 'UTF-8'), $this->login, $this->passwd, 0);
	}
	else {
		$spec=explode("/",$this->server);
		$host_port=explode(":",$spec[0]);
		$host=$host_port[0];
		$port=$host_port[1];
		$imap=false;
		$pop3=false;
		$secure="false";
		foreach($spec as $index => $param) {
			if( $param=="service=imap" || preg_match("/^imap/",$param) ) {
				$imap=true;
			}
			if( $param=="service=pop3" || $param=="pop3" ) {
				$pop3=true;
			}
			if( preg_match("/^ssl/",$param) ) {
				$secure=$param;
			}
			if( preg_match("/^tls/",$param) ) {
				$secure=$param;
			}
			if( $param=="true" ) {
				$secure="true";
			}
		}
		
		if( $pop3 ) {
			try {
				$conn = new Horde_Imap_Client_Socket_Pop3(array(
						'username' => $this->login,
						'password' => $this->passwd,
						'hostspec' => $host,
						'port' => $port,
						'secure' => $secure
				));
				if( $conn != null ) {
					$conn->openMailbox($this->folder);
	        			$this->_isImap = false;
	        			$_SESSION['is_imap'] = $this->_isImap;
				}
			} catch(Horde_Imap_Client_Exception $e) {
				throw new Exception($lang_could_not_connect."(1)".":".$e->$raw_msg);
			}
		}
		else if( $imap ) {
			try {
				$conn = new Horde_Imap_Client_Socket(array(
						'username' => $this->login,
						'password' => $this->passwd,
						'hostspec' => $host,
						'port' => $port,
						'secure' => $secure
				));
				if( $conn != null ) {
					$conn->openMailbox($this->folder);
	        			$this->_isImap = true;
       		 			$_SESSION['is_imap'] = $this->_isImap;
				}
			} catch(Horde_Imap_Client_Exception $e) {
				throw new Exception($lang_could_not_connect."(2)".":".$e->$raw_msg);
			}
		}
		else {
			$success=false;
			try {
				$conn = new Horde_Imap_Client_Socket(array(
						'username' => $this->login,
						'password' => $this->passwd,
						'hostspec' => $host,
						'port' => $port,
						'secure' => $secure
				));
				if( $conn != null ) {
					$conn->openMailbox($this->folder);
					$success=true;
	        			$this->_isImap = true;
	        			$_SESSION['is_imap'] = $this->_isImap;
				}
			} catch(Horde_Imap_Client_Exception $e) {
				$log_string='NOCC: open imap connection to '.$host.' failed, trying pop3';
				error_log($log_string);
				if( isset($conf->syslog) && $conf->syslog ) {
					syslog(LOG_INFO,$log_string);
				}
			}
			if( ! $success ) {
				try {
					$conn = new Horde_Imap_Client_Socket_Pop3(array(
							'username' => $this->login,
							'password' => $this->passwd,
							'hostspec' => $host,
							'port' => $port,
							'secure' => $secure
					));
					if( $conn != null ) {
						$conn->openMailbox($this->folder);
						$success=true;
	        				$this->_isImap = false;
	        				$_SESSION['is_imap'] = $this->_isImap;
					}
				} catch(Horde_Imap_Client_Exception $e) {
					$error="";
					if( strlen($this->login)==0 ) {
						$error=$error.$err_user_empty.".\n";
					}
					if( strlen($this->passwd)==0 ) {
						$error=$error.$err_passwd_empty.".\n";
					}
					throw new Exception($error.$lang_could_not_connect."(3)");
				}
			}
		}
	}
        if (!$conn) {
		//php.log,syslog message to be used against brute force attempts e.g. with fail2ban
		//don't change text or rules may fail
		if( isset($_REQUEST['enter']) ) {
			$log_string='NOCC: failed login from rhost='.$_SERVER['REMOTE_ADDR'].' to server='.$this->server.' as user='.$_SESSION['nocc_login'].'';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
		$error="";
		if( strlen($this->login)==0 ) {
			$error=$error.$err_user_empty.".\n";
		}
		if( strlen($this->passwd)==0 ) {
			$error=$error.$err_passwd_empty.".\n";
		}
		if( ! $this->is_horde() ) {
			throw new Exception($error.$lang_could_not_connect.":\n".$this->last_error());
		}
		else {
			throw new Exception($error.$lang_could_not_connect);
		}
        }

	if( isset($_REQUEST['enter']) ) {
		$log_string='NOCC: successful login from rhost='.$_SERVER['REMOTE_ADDR'].' to server='.$_SESSION['nocc_servr'].' as user='.$_SESSION['nocc_login'].'';
		error_log($log_string);
		if( isset($conf->syslog) && $conf->syslog ) {
			syslog(LOG_INFO,$log_string);
		}
	}

        $this->conn = $conn;
	//$_SESSION['conn']=$conn;

	if( ! $this->is_horde() ) {
	        $this->_isImap = $this->isImapCheck();
		$_SESSION['is_imap'] = $this->_isImap;
	}

	if( $memcache != null && $login_attempts > 0 ) {
		$count=0;
		$memcache->set($remote_ip,$count,false,30);
	}

        return $this;
    }

    /**
     * Wrap imap_rfc822_write_address
     * @param string $mailbox The mailbox name
     * @param string $host The email host part
     * @param string $personal The name of the account owner
     * @return Returns a string properly formatted email address as defined in RFC2822
     * @todo
     */
    public function write_address($mailbox, $host, $personal) {
	if( ! $this->is_horde() ) {
		return imap_rfc822_write_address($mailbox, $host, $personal);
	}
	else {
		$r=new Horde_Mail_Rfc822_Address();
		$r->mailbox = $mailbox;
		$r->host = $host;
		$r->personal = $personal;
		return $r->__toString();
	}
    }

    /**
     * Wrap imap_rfc822_parse_headers
     * @param string $headers The parsed headers data
     * @param string $defaulthost The default host name
     * @return object
     * @todo
     */
    public function parse_headers($headers, $defaulthost = "UNKNOWN" ) {
	if( ! $this->is_horde() ) {
		$result=imap_rfc822_parse_headers( $headers, $defaulthost);
	}
	else {
		//this is called only for rfc822 header
		$result=new stdClass();
		$result->subject="";
		$result->date="";
		$result->from=array();
		$result->from[0] = new stdClass();
		$result->from[0]->mailbox="";
		$result->from[0]->host="";
		$result->from[0]->personal="";
		$result->to=array();
		$result->to[0] = new stdClass();
		$result->to[0]->mailbox="";
		$result->to[0]->host="";
		$result->to[0]->personal="";
		$matches=array();
		if( preg_match("/^\s*Subject:\s+(.*)$/im",$headers,$matches) ) {
			$result->subject=$matches[1];
		}
		$matches=array();
		if( preg_match("/^\s*From:(.*)<(.*)@(.*)>\s+/im",$headers,$matches) ) {
			$result->from[0]->personal=trim($matches[1]);
			$result->from[0]->mailbox=trim($matches[2]);
			$result->from[0]->host=trim($matches[3]);
		}
		else if( preg_match("/^\s*From:\s*?<*(.*)@(.*)>*\s+/im",$headers,$matches) ) {
			$result->from[0]->personal="";
			$result->from[0]->mailbox=trim($matches[1]);
			$result->from[0]->host=trim($matches[2]);
		}
		$matches=array();
		if( preg_match("/^\s*To:(.*)<(.*)@(.*)>\s+/im",$headers,$matches) ) {
			$result->to[0]->personal=trim($matches[1]);
			$result->to[0]->mailbox=trim($matches[2]);
			$result->to[0]->host=trim($matches[3]);
		}
		else if( preg_match("/^\s*To:\s*?<*(.*)@(.*)>*\s+/im",$headers,$matches) ) {
			$result->to[0]->personal="";
			$result->to[0]->mailbox=trim($matches[1]);
			$result->to[0]->host=trim($matches[2]);
		}
		$matches=array();
		if( preg_match("/^\s*Date:\s+(.*)$/im",$headers,$matches) ) {
			$result->date=$matches[1];
		}
	}
	return $result;
    }

    /**
     * Get the last IMAP error that occurred during this page request
     * @return string Last IMAP error
     * @todo Rename to getLastError()?
     */
    public function last_error() {
	if( ! $this->is_horde() ) {
	        return imap_last_error();
	}
	else {
		return "";
	}
    }

    /**
     * Search messages matching the given search criteria
     * @param string $criteria Search criteria
     * @return array Messages
     */
    public function search($criteria) {
	$messages = array();
	if( ! $this->is_horde() ) {
	        $search_result = @imap_search($this->conn, $criteria);
	        if (is_array($search_result)) {
	            return $search_result;
	        }
	}
	else {
		$elements = explode(" ",$criteria);
		$query = new Horde_Imap_Client_Search_Query();
		for( $i=0; $i<count($elements); $i++ ) {
			switch(strtolower($elements[$i])) {
				case "unseen":
					//$query->newMsgs(true);
					$query->flag("\\Seen",false);
					break;
				case "subject":
				case "to":
				case "from":
				case "cc":
					if( $i+1 < count($elements) ) {
						$par=$elements[$i+1];
						$par=preg_replace("/^\"/","",$par);
						$par=preg_replace("/\"$/","",$par);
						$query->headerText($elements[$i],$par);
						$i++;
					}
					break;
				case "body":
					if( $i+1 < count($elements) ) {
						$par=$elements[$i+1];
						$par=preg_replace("/^\"/","",$par);
						$par=preg_replace("/\"$/","",$par);
						$query->text($par);
						$i++;
					}
					break;
			}
		}
		$options=array(
			"sequence" => true,
		);
		try {
			$horde_search=$this->conn->search($this->folder,$query,$options);
			if( $horde_search['count'] > 0 ) {
				$messages = $horde_search['match']->ids;
			}
		}
		catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: search failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
	}
        return $messages;
    }

    /**
     * Fetch mail structure
     * @param integer $msgnum Message number
     * @return NOCC_MailStructure Mail structure
     */
    public function fetchstructure($msgnum) {
	$parts_info=array();
	if( ! $this->is_horde() ) {
		$structure = @imap_fetchstructure($this->conn, $msgnum);
	}
	else {
		try {
			$query=new Horde_Imap_Client_Fetch_Query();
			$query->structure();
			$ids=new Horde_Imap_Client_Ids(array($msgnum),true);
			$options=array(
				"ids" => $ids
			);
			$fetch_result=$this->conn->fetch($this->folder,$query,$options);
			if( $fetch_result->count() == 1 ) {
				$structure=$fetch_result->first()->getStructure();
			}
		}
		catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: fetching structure failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}

		$rec = function($part) use (&$rec, &$parts_info, $msgnum) {
			$mimeID=$part->getMimeId();
			$query=new Horde_Imap_Client_Fetch_Query();
			$opts=array(
				"peek" => true,
			);
			$query->mimeHeader($mimeID,$opts);
			$ids=new Horde_Imap_Client_Ids(array($msgnum),true);
			$options=array(
				"ids" => $ids,
			);
			$fetch_result=$this->conn->fetch($this->folder,$query,$options);
			if( $fetch_result != null && $fetch_result->count() > 0 && $fetch_result->first() != null ) {
				$mimeHeader=$fetch_result->first()->getMimeHeader($mimeID,Horde_Imap_Client_Data_Fetch::HEADER_PARSE);
				$tmp_encoding="";
				$tmp_contentid="";
				if( $mimeHeader != null ) {
					if( $mimeHeader->getHeader("Content-Transfer-Encoding") != null ) {
						$tmp_encoding=strtolower($mimeHeader->getHeader("Content-Transfer-Encoding")->value);
					}
					if( $mimeHeader->getHeader("Content-ID") != null ) {
						$tmp_contentid=$mimeHeader->getHeader("Content-ID")->value;
					}
				}
				$parts_info[$mimeID]=array(
							'encoding' => $tmp_encoding,
							'contentId' => $tmp_contentid
							);
			}
			$subparts=$part->getParts();
			if( count($subparts)>0 ) {
				foreach( $subparts as $part ) {
					$rec($part);
				}
			}
		};
		$rec($structure);
	}
        if (!is_object($structure)) {
            throw new Exception('imap_fetchstructure() did not return an object.');
        }
        return new NOCC_MailStructure($structure,$this->is_horde(), $parts_info);
    }

    /**
     * Fetch header
     * @param integer $msgnum Message number
     * @return NOCC_Header Header
     * @todo Throw exceptions?
     */
    public function fetchheader($msgnum) {
	if( ! $this->is_horde() ) {
        	$header = imap_fetchheader($this->conn, $msgnum);
	}
	else {
		try {
			$query=new Horde_Imap_Client_Fetch_Query();
			$query_options=array(
				"peek" => true,
			);
			$query->headerText($query_options);
			$ids=new Horde_Imap_Client_Ids(array($msgnum),true);
			$options=array(
				"ids" => $ids,
			);
			$header_fetch=$this->conn->fetch($this->folder,$query,$options);
			if( $header_fetch->count() >= 1 && $header_fetch->first() != null ) {
				$header=$header_fetch->first()->getHeaderText();
			}
		}
		catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: fetching header failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
	}
        return new NOCC_Header($header, $this->is_horde());
    }

    /**
     * Fetch body
     * @param integer $msgnum Message number
     * @param string $partnum Part number
     * @return string Body
     * @todo Throw exceptions?
     */
    public function fetchbody($msgnum, $partnum, $mimeid="", $decode=true, $rfc822=false) {
	$bodyText="";
	if( ! $this->is_horde() ) {
	        $bodyText=@imap_fetchbody($this->conn, $msgnum, $partnum);
	}
	else {
		try {
			if( $rfc822 ) {
				$headerText="";
				$body_only=false;
				$header_only=false;
				$matches=array();
				if( preg_match("/(\d*).*?\.(\d*)/",$partnum, $matches) ) {
					if( $matches[2] == "0" ) {
						$header_only=true;
					}
					if( $matches[2] == "1" ) {
						$body_only=true;
					}
					//$partnum=$matches[1];
					//$mimeid=$partnum;
				}
				if( ! $body_only ) {
					$query=new Horde_Imap_Client_Fetch_Query();
					$opts=array(
						"id" => $mimeid,
						"peek" => true,
					);
					$query->headerText($opts);
					$ids=new Horde_Imap_Client_Ids(array($msgnum),true);
					$options=array(
						"ids" => $ids,
					);
					$fetch_result=$this->conn->fetch($this->folder,$query,$options);
					if( $fetch_result->count() >= 1 ) {
						$headerText=$fetch_result->first()->getHeaderText($mimeid);
					}
				}
				if( $header_only ) {
					$bodyText=$headerText;
				}
				else {
					$query=new Horde_Imap_Client_Fetch_Query();
					$opts=array(
						"id" => $mimeid,
					);
					$query->bodyText($opts);
					$ids=new Horde_Imap_Client_Ids(array($msgnum),true);
					$options=array(
						"ids" => $ids,
					);
					$fetch_result=$this->conn->fetch($this->folder,$query,$options);
					if( $fetch_result->count() >= 1 ) {
						$bodyText=$fetch_result->first()->getBodyText($mimeid);
					}
					if( strlen($bodyText) == 0 ) {
						$query=new Horde_Imap_Client_Fetch_Query();
						$opts=array(
							"decode" => $decode,
						);
						$query->bodyPart($mimeid,$opts);
						$ids=new Horde_Imap_Client_Ids(array($msgnum),true);
						$options=array(
							"ids" => $ids,
						);
						$fetch_result=$this->conn->fetch($this->folder,$query,$options);
						if( $fetch_result->count() >= 1 ) {
							$bodyText=$fetch_result->first()->getBodyPart($mimeid);
						}
					}
					$bodyText=$headerText.$bodyText;
				}
			}
			else {
				$query=new Horde_Imap_Client_Fetch_Query();
				$opts=array(
					"decode" => $decode,
				);
				$query->bodyPart($mimeid,$opts);
				$ids=new Horde_Imap_Client_Ids(array($msgnum),true);
				$options=array(
					"ids" => $ids,
				);
				$fetch_result=$this->conn->fetch($this->folder,$query,$options);
				if( $fetch_result->count() >= 1 ) {
					$bodyText=$fetch_result->first()->getBodyPart($mimeid);
				}
			}
		}
		catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: fetching body text failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
		
	}
	return $bodyText;
    }

    /**
     * Fetch the size of message
     * @param integer $msgnum Message number
     * @return int size in bytes
     */
    public function get_size($msgnum) {
	$size=0;
	if( ! $this->is_horde() ) {
		$overview=imap_fetch_overview($this->conn,$msgnum);
		if( isset($overview[0]->size) ) {
			$size=$overview[0]->size;
		}
	}
	else {
		try {
			$query=new Horde_Imap_Client_Fetch_Query();
			$query->size();
			$ids=new Horde_Imap_Client_Ids(array($msgnum),true);
			$options=array(
				"ids" => $ids,
			);
			$fetch_result=$this->conn->fetch($this->folder,$query,$options);
			if( $fetch_result->count() >= 1 ) {
				$size=$fetch_result->first()->getSize();
			}	
		}
		catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: fetching message size failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
	}
	return $size;
    }

    /**
     * Fetch the entire message
     * @param integer $msgnum Message number
     * @return string Message
     * @todo Throw exceptions?
     */
    public function fetchmessage($msgnum) {
	$fullText="";
	if( ! $this->is_horde() ) {
	        $fullText=@imap_fetchbody($this->conn, $msgnum, '');
	}
	else {
		try {
			$query=new Horde_Imap_Client_Fetch_Query();
			$query->fullText();
			$ids=new Horde_Imap_Client_Ids(array($msgnum),true);
			$options=array(
				"ids" => $ids,
			);
			$fetch_result=$this->conn->fetch($this->folder,$query,$options);
			if( $fetch_result->count() >= 1 ) {
				$fullText=$fetch_result->first()->getFullMsg();
			}	
		}
		catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: fetching message size failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
	}
	return $fullText;
    }

    /**
     * Get the number of messages in the current mailbox
     * @return integer Number of messages
     * @todo Rename to GetMessageCount()?
     */
    public function num_msg() {
	if( ! $this->is_horde() ) {
        	return imap_num_msg($this->conn);
	}
	else {
		$count=0;
		$status=array();
		try {
			$status=$this->conn->status($this->folder,Horde_Imap_Client::STATUS_MESSAGES);
		} catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: getting number of messages from folder '.$this->folder.' failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
		$count=$status["messages"];
		return $count;
	}
    }

    /**
     * ...
     * @param string $sort Sort criteria
     * @param integer $sortdir Sort direction
     * @return array Sorted message list
     */
    public function sort($sort, $sortdir) {
	if( ! $this->is_horde() ) {
	        switch($sort) {
	            case '1': $imapsort = SORTFROM; break;
	            case '2': $imapsort = SORTTO; break;
	            case '3': $imapsort = SORTSUBJECT; break;
	            case '4': $imapsort = SORTDATE; break;
	            case '5': $imapsort = SORTSIZE; break;
	        }
	        $sorted = imap_sort($this->conn, $imapsort, $sortdir, SE_NOPREFETCH);
	        if (!is_array($sorted)) {
	            throw new Exception('imap_sort() did not return an array.');
	        }
	}
	else {
	        switch($sort) {
	            case '1': $imapsort = Horde_Imap_Client::SORT_FROM; break;
	            case '2': $imapsort = Horde_Imap_Client::SORT_TO; break;
	            case '3': $imapsort = Horde_Imap_Client::SORT_SUBJECT; break;
	            case '4': $imapsort = Horde_Imap_Client::SORT_DATE; break;
	            case '5': $imapsort = Horde_Imap_Client::SORT_SIZE; break;
	        }
		$sort_array=array();
		if( $sortdir ) {
			$sort_array[]=Horde_Imap_Client::SORT_REVERSE;
		}
		$sort_array[]=$imapsort;
		try {
			$options=array(
				"sort" => $sort_array,
				"sequence" => true,
			);
			$result = $this->conn->search($this->folder, null, $options);
			$sorted = $result["match"]->ids;
			$options=array(
				"sort" => $sort_array,
				"sequence" => false,
			);
			$result = $this->conn->search($this->folder, null, $options);
			$sorted_uids = $result["match"]->ids;
			$_SESSION['horde_sequence2uid']=array();
			for($i=0;$i<count($sorted);$i++) {
				$_SESSION['horde_sequence2uid'][$sorted[$i]]=-1;
				if( isset($sorted_uids[$i]) ) {
					$_SESSION['horde_sequence2uid'][$sorted[$i]]=$sorted_uids[$i];
				}
			}
		} catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: getting sequence numbers of messages from folder '.$this->folder.' failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
	}
        return $sorted;
    }

    /**
     * Get header info
     * @param integer $msgnum Message number
     * @param string $defaultcharset Default charset
     * @return NOCC_HeaderInfo Header info
     */
    public function headerinfo($msgnum, $defaultcharset = 'ISO-8859-1') {
	$horde_flags=null;
	if( ! $this->is_horde() ) {
		$headerinfo = @imap_headerinfo($this->conn, $msgnum);
	}
	else {
		try {
			$query=new Horde_Imap_Client_Fetch_Query();
			$query->envelope();
			$ids=new Horde_Imap_Client_Ids(array($msgnum),true);
			$options=array(
				"ids" => $ids
			);
			$headerinfo=$this->conn->fetch($this->folder,$query,$options);
			$queryflags=new Horde_Imap_Client_Fetch_Query();
			$queryflags->flags();
			$horde_flags=$this->conn->fetch($this->folder,$queryflags,$options);
		}
		catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: fetching headerinfo failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
	}
		
        if (!is_object($headerinfo)) {
            throw new Exception('imap_headerinfo() did not return an object.');
        }
        return new NOCC_HeaderInfo($headerinfo, $defaultcharset, $horde_flags, $this->is_horde());
    }

    /**
     * Delete a mailbox
     * @param string $mailbox Mailbox
     * @return boolean Successful?
     * @todo Rename to deleteMailbox()?
     */
    public function deletemailbox($mailbox) {
	if( ! $this->is_horde() ) {
        	return imap_deletemailbox($this->conn, '{' . $this->server . '}' . mb_convert_encoding($mailbox, 'UTF7-IMAP', 'UTF-8'));
	}
	else {
		try {
			$this->conn->deleteMailbox($mailbox);
			return true;
		}
		catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: deleting mailbox '.$mailbox.' failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
	}
	return false;
    }

	/**
	 * find specific email header from complete header
	 * @param string $head_search header to find
	 * @param string $temp_header find in headers
	 * @return string header content 
	 *
	 * credits go to rklrkl, https://sourceforge.net/p/nocc/patches/149/, 2009-08-16
	 */
	function find_email_header($head_search,&$temp_header) {
		// Look for "\n<header>: " in the header
		$hpos = strpos($temp_header,"\n".$head_search.": ");
		if ($hpos!=FALSE) {
			// Now extract out the rest of the header line
			$hpos += strlen("\n".$head_search.": ");
			$hlen = strpos(substr($temp_header,$hpos),"\n");
			if ($hlen>0) {
				$hstr = substr($temp_header,$hpos,$hlen);
				if ($head_search=="Delivery-date" || $head_search=="Date") {
					// Looking for e-mail date...
					// Convert "normal" date format into bizarro mbox date format
					// (which is expressed in local time, not GMT)
					//return(date("D M d H:i:s Y",strtotime($hstr)));
					return(strtotime($hstr));
				}
				else {
					// Looking for "from" e-mail address...
					// Find out the first word which has an @ in it and return it
					$harr = explode(" ",$hstr);
					reset($harr);
					foreach($harr as $eachword) {
						// If we got an e-mail address, return it, but stripped of
						// double quotes, <, >, ( and )
						if (strpos($eachword,"@")) {
							return(trim($eachword,'"()<>'));
						}
					}
				}
			}
		}
		return "";
	}

	/**
	 * Create a tmp file for downloading a complete mailbox folder
	 * @param string $download_box name of the folder to download
	 *
	 *
	 * credits go to rklrkl, https://sourceforge.net/p/nocc/patches/149/, 2009-08-16
	 */
	function downloadmailbox(&$download_box,&$ev) {

		$_SESSION['fd_message']=array();

        	global $conf;

		// Create a sanitised mbox filename based on the folder name
		$filename = preg_replace('/[\\/:\*\?"<>\|;]/','_',str_replace('&nbsp;',' ',$download_box)).".mbox";
		$_SESSION['fd_message'][]=$filename;

		$remember_folder=$_SESSION['nocc_folder'];
		$_SESSION['nocc_folder'] = $download_box;

		$ev = '';
		$pop = new nocc_imap($ev);
		if (NoccException::isException($ev)) {
			$_SESSION['nocc_folder']=$remember_folder;
			unset($_SESSION['fd_message']);
			require ('./html/header.php');
			require ('./html/error.php');
			require ('./html/footer.php');
			return;
		}

		$memory_limit=ini_get('memory_limit');
		if( preg_match("/M$/i",$memory_limit) ) {
			$memory_limit=intval($memory_limit)*1024*1024;
		}
		else if( preg_match("/K$/i",$memory_limit) ) {
			$memory_limit=intval($memory_limit)*1024;
		}
		else if( preg_match("/G$/i",$memory_limit) ) {
			$memory_limit=intval($memory_limit)*1024*1024*1024;
		}

		if( strlen($conf->tmpdir)==0 ) {
			$_SESSION['nocc_folder']=$remember_folder;
			unset($_SESSION['fd_message']);
			$ev = new NoccException("tmp folder tmpdir is not set in config/php.conf.");
			return;
		}
		else if( ! is_writable($conf->tmpdir) ) {
			$_SESSION['nocc_folder']=$remember_folder;
			unset($_SESSION['fd_message']);
			$ev = new NoccException("tmp folder ".$conf->tmpdir." is not writeable.");
			return;
		}

		$tmpFile=$_SESSION['sname']."_".md5(uniqid(rand(),true)).'.tmp';
		$_SESSION['fd_message'][]=$tmpFile;
		$tmpFile=$conf->tmpdir.'/'.$tmpFile;
		$_SESSION[$tmpFile]=1;

		$mail_skipped=0;
		if( $mbox=fopen($tmpFile,'w') ) {
			// Find out how many messages are in the folder and loop for each one
			$tot_msgs = $pop->num_msg();
			$_SESSION['fd_message'][]=$tot_msgs;
			for ($mail = 1; $mail <= $tot_msgs; $mail++) {
				// Prefix a line feed to the header so that later searches will
				// find strings if they're right at the start of the header (first
				// char in first line). Also strip any carriage returns that the
				// IMAP server spits out.

       		 		$header_obj = $pop->fetchheader($mail);
				$header=$header_obj->getHeader();
				$header = "\n" . str_replace("\r","",$header);

				$headerinfo_obj=$pop->headerinfo($mail);
				$subject=$headerinfo_obj->getSubject();

				// Find a "from" e-mail address in the headers
				$from=$pop->find_email_header("From",$header);
				if ($from=="") $from=$pop->find_email_header("Reply-To",$header);
				if ($from=="") $from=$pop->find_email_header("X-From-Line",$header);
				if ($from=="") $from="MAILER-DAEMON"; // Fallback if no From addr

				// Find the date header and convert the date into mbox format
				// Yes, Delivery-date: takes priority over Date:, which many
				// mbox creation programs forget to take into account!
				$date=$pop->find_email_header("Delivery-date",$header);
				if ($date=="") $date=$pop->find_email_header("Date",$header);
				if ($date=="") $date=0; // Time zero fallback
				$showdate = format_date($date, $lang);
				$date=date("D M d H:i:s Y",$date);

				// Add the new "From " line, the rest of the header
				// ...and the "raw" [but CR-stripped] body, but replace
				// "\nFrom " with "\n>From " as well.(yes, 2 of the 4 crazily
				// different mbox formats need this). Also append a blank line between
				// message to separate them.

				$mail_size=$pop->get_size($mail);
				$memory_usage=memory_get_usage();

				if( 2*$mail_size+$memory_usage>$memory_limit ) {
					$mail_skipped++;
					$_SESSION['fd_message'][]='<tr><td style="text-align:left;">'.$showdate.'</td><td style="text-align:left;">'.$subject.'</td><td style="text-align:left;">'.$mail_size.'</td></tr>';
				}
				else {
					$body="\n".substr(str_replace("\n\nFrom ","\n\n>From ","\n\n".str_replace("\r","",$pop->fetchmessage($mail))."\n"),2);
					fwrite($mbox,"From ".$from." ".$date.$body);
				}

			}
			fwrite($mbox,"\n");
			fclose($mbox);
		}
		$pop->close();
		$_SESSION['nocc_folder']=$remember_folder;

		if( is_file($tmpFile) ) {
			$file_size=filesize($tmpFile);
			$_SESSION['fd_message'][]=$file_size;
			$_SESSION['fd_message'][]=$mail_skipped;
		}
		else {
			unset($_SESSION['fd_message']);
			$ev = new NoccException("folder download failed.");
			return;
		}
	}
	
	/**
	 * Download the tmp file for a complete mailbox folder
	 *
	 * credits go to rklrkl, https://sourceforge.net/p/nocc/patches/149/, 2009-08-16
	 */
	function downloadtmpfile(&$ev) {
        	global $conf;
		if( isset($_SESSION['fd_tmpfile']) && is_array($_SESSION['fd_tmpfile']) &&
			isset($_SESSION['fd_tmpfile'][0]) && strlen($_SESSION['fd_tmpfile'][0])>0 &&
			isset($_SESSION['fd_tmpfile'][1]) && strlen($_SESSION['fd_tmpfile'][1])>0 )
		{
			$tmpFile=$conf->tmpdir.'/'.basename($_SESSION['fd_tmpfile'][0]);
			$filename=$_SESSION['fd_tmpfile'][1];
			if( is_file($tmpFile) ) {
				$file_size=filesize($tmpFile);

				// If no messages were found in the folder, don't offer the download
				// and simply fall into displaying the Folder page again. Maybe a warning
				// message should go here (JavaScripted "<foldername> folder contains
				// no messages")?
				//if ($file != "") {
				// This is a repeat of a large chunk of code fromm down_mail.php -
				// perhaps that should be put in a function somewhere and shared here
				// too? Would need to take $filename and $file as parameters.
				$isIE = $isIE6 = 0;

				if (!isset($HTTP_USER_AGENT)) {
					$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
				}

				// Set correct http headers.
				// Thanks to Squirrelmail folks :-)
				if (strstr($HTTP_USER_AGENT, 'compatible; MSIE ') !== false && strstr($HTTP_USER_AGENT, 'Opera') === false) {
					$isIE = 1;
				}
	
				if (strstr($HTTP_USER_AGENT, 'compatible; MSIE 6') !== false && strstr($HTTP_USER_AGENT, 'Opera') === false) {
					$isIE6 = 1;
				}

				if ($isIE) {
					$filename=rawurlencode($filename);
					header ("Pragma: public");
					header ("Cache-Control: no-store, max-age=0, no-cache, must-revalidate"); // HTTP/1.1
					header ("Cache-Control: post-check=0, pre-check=0", false);
					header ("Cache-Control: private");
	
					//set the inline header for IE, we'll add the attachment header later if we need it
					header ("Content-Disposition: inline; filename=$filename");
				}
	
				header ("Content-Type: application/octet-stream; name=\"$filename\"");
				header ("Content-Disposition: attachment; filename=\"$filename\"");

				if ($isIE && !$isIE6) {
					header ("Content-Type: application/download; name=\"$filename\"");
				}
				else {
					header ("Content-Type: application/octet-stream; name=\"$filename\"");
				}
				header('Content-Length: '.$file_size);

				$_SESSION[$tmpFile]=$_SESSION[$tmpFile]+1;

				$chunksize = 1 * (1024 * 1024); // how many bytes per chunk
				if( $file_size > $chunksize ) {
					$handle = fopen($tmpFile, 'rb');
					$buffer = '';
					while (!feof($handle)) {
						$buffer = fread($handle, $chunksize);
						echo $buffer;
						ob_flush();
						flush();
					}
					fclose($handle);
				} else {
					readfile($tmpFile);
				}

				exit; // Don't fall into HTML page - we're downloading and need to exit
			}
			else {
				unset($_SESSION['fd_tmpfile']);
				$ev = new NoccException("download file does not exits.");
				return;
			}
		}
	}


    /**
     * Rename a mailbox
     * @param string $oldMailbox Old mailbox
     * @param string $newMailbox New mailbox
     * @return boolean Successful?
     * @todo Rename to renameMailbox()?
     */
    public function renamemailbox($oldMailbox, $newMailbox) {
	if( ! $this->is_horde() ) {
	        return imap_renamemailbox($this->conn, '{' . $this->server . '}' . $oldMailbox, '{' . $this->server . '}' . $this->namespace . mb_convert_encoding($newMailbox, 'UTF7-IMAP', 'UTF-8'));
	}
	else {
		try {
			//$this->conn->renameMailbox(imap_mutf7_to_utf8($oldMailbox),$newMailbox);
			$this->conn->renameMailbox($oldMailbox,$newMailbox);
			return true;
		}
		catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: renaming mailbox '.$oldMailbox.' to '.$newMailbox. 'failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
	}
    }

    /**
     * Create a mailbox
     * @param srtring $mailbox Mailbox
     * @return boolean Successful?
     * @todo Rename to createMailbox()?
     */
    public function createmailbox($mailbox) {
	if( ! $this->is_horde() ) {
	        return imap_createmailbox($this->conn, '{' . $this->server . '}' . $this->namespace . mb_convert_encoding($mailbox, 'UTF7-IMAP', 'UTF-8'));
	}
	else {
		try {
			$this->conn->createMailbox($mailbox);
			return true;
		}
		catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: creating mailbox failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
			return false;
		}
	}
    }

	/**
	 * Check if target mailbox is local
         * @param array $TMP_SESSION session data
	 * @param string $mailbox target mailbox
	 * @return boolean isLocal
	 */
	public function check_mb_local(&$TMP_SESSION, $mailbox) {
		global $conf;

		$is_mb_local=true;
		if( preg_match("/:/",$mailbox) ) {
			$remote=explode(":",$mailbox);
			foreach( $_COOKIE as $cookie_key => $cookie_value ) {
				if( preg_match("/^NOCCLI_/",$cookie_key) ) {
					$sname=$cookie_key;
					if( $line=NOCC_Session::load_session_file($sname) ) {
						list(
							$session_id,
							$TMP_SESSION['nocc_user'],
							$TMP_SESSION['nocc_passwd'],
							$TMP_SESSION['nocc_login'],
							$TMP_SESSION['nocc_lang'],
							$TMP_SESSION['nocc_smtp_server'],
							$TMP_SESSION['nocc_smtp_port'],
							$TMP_SESSION['nocc_theme'],
							$TMP_SESSION['nocc_domain'],
							$TMP_SESSION['nocc_domainnum'],
							$TMP_SESSION['imap_namespace'],
							$TMP_SESSION['nocc_servr'],
							$TMP_SESSION['nocc_folder'],
							$TMP_SESSION['smtp_auth'],
							$TMP_SESSION['ucb_pop_server'],
							$TMP_SESSION['quota_enable'],
							$TMP_SESSION['quota_type'],
							$TMP_SESSION['creation_time'],
							$TMP_SESSION['persistent'],
							$TMP_SESSION['remote_addr'],
							$TMP_SESSION['is_horde']
						) = explode(" ", base64_decode($line));
						if( $session_id == $cookie_value ) {
							foreach( $conf->domains as $index => $domain ) {
								if( 
									isset($conf->domains[$index]->show_as) && 
									strlen($conf->domains[$index]->show_as) > 0 && 
									$conf->domains[$index]->show_as == $remote[0] &&
									isset($conf->domains[$index]->in) &&
									$TMP_SESSION['nocc_servr'] == $conf->domains[$index]->in &&
									true
								) {
									$is_mb_local=false;
									break;
								}
								elseif( 
									isset($conf->domains[$index]->in) && 
									preg_match("/".$remote[0]."/",$conf->domains[$index]->in) &&
									$TMP_SESSION['nocc_servr'] == $conf->domains[$index]->in &&
									true
								) {
									$is_mb_local=false;
									break;
								}
							}
						}
					}
				}
				if( ! $is_mb_local ) {
					break;
				}
			}
		}
		return $is_mb_local;
	}

	/**
	 * put email on remote server
         * @param array $TMP_SESSION remote server session data
	 * @param string $msg Message
	 * @param integer $msgnum Message
	 * @return boolean successful
	 */
	public function put_remote(&$TMP_SESSION, $msg) {
        	global $conf;
		$success=false;
		if( ! $TMP_SESSION['is_horde'] ) {
	        	$conn = @imap_open(
					'{'.$TMP_SESSION['nocc_servr'].'}'.mb_convert_encoding($TMP_SESSION['nocc_folder'], 'UTF7-IMAP', 'UTF-8'),
					$TMP_SESSION['nocc_login'],
					decpass($TMP_SESSION['nocc_passwd'], $conf->master_key),
					0
				);
        		if( $conn ) {
				$success = imap_append(
						$conn,
						'{'.$TMP_SESSION['nocc_servr'].'}'.mb_convert_encoding($TMP_SESSION['nocc_folder'], 'UTF7-IMAP', 'UTF-8'),
						$msg,
						'\Seen'
						);
				imap_close($conn);
			}
		}
		else {
			$conn=null;

			$spec=explode("/",$TMP_SESSION['nocc_servr']);
			$host_port=explode(":",$spec[0]);
			$host=$host_port[0];
			$port=$host_port[1];
			$imap=false;
			$pop3=false;
			$secure="false";
			foreach($spec as $index => $param) {
				if( $param=="service=imap" || preg_match("/^imap/",$param) ) {
					$imap=true;
				}
				if( $param=="service=pop3" || $param=="pop3" ) {
					$pop3=true;
				}
				if( preg_match("/^ssl/",$param) ) {
					$secure=$param;
				}
				if( preg_match("/^tls/",$param) ) {
					$secure=$param;
				}
				if( $param=="true" ) {
					$secure="true";
				}
			}
			if( $pop3 ) {
				$conn = new Horde_Imap_Client_Socket_Pop3(array(
						'username' => $TMP_SESSION['nocc_login'],
						'password' => decpass($TMP_SESSION['nocc_passwd'], $conf->master_key),
						'hostspec' => $host,
						'port' => $port,
						'secure' => $secure
				));
				if( $conn != null ) {
					$conn->openMailbox($TMP_SESSION['nocc_folder']);
				}
			}
			else {
				$conn = new Horde_Imap_Client_Socket(array(
						'username' => $TMP_SESSION['nocc_login'],
						'password' => decpass($TMP_SESSION['nocc_passwd'], $conf->master_key),
						'hostspec' => $host,
						'port' => $port,
						'secure' => $secure
				));
				if( $conn != null ) {
					$conn->openMailbox($TMP_SESSION['nocc_folder']);
				}
			}
			if( $conn != null ) {
				try {
					$data=array(array('data' => $msg, 'flags' => ['\Seen']));
					$ids = $conn->append($TMP_SESSION['nocc_folder'],$data);
					if( ! $ids->isEmpty() ) {
						$success=true;
					}
				}
				catch(Horde_Imap_Client_Exception $e) {
				}
				$conn->close();
			}
		}
		return $success;
	}

    /**
     * Copy a mail to a mailbox
     * @param integer $msgnum Message number
     * @param string $mailbox Destination mailbox
     * @return boolean Successful?
     * @todo Rename to copyMail()?
     */
    public function mail_copy($msgnum, $mailbox) {
	$TMP_SESSION=array();
	$is_mb_local = $this->check_mb_local($TMP_SESSION, $mailbox);
	if( $is_mb_local ) {
		if( ! $this->is_horde() ) {
		        return imap_mail_copy($this->conn, $msgnum, mb_convert_encoding($mailbox, 'UTF7-IMAP', 'UTF-8'), 0);
		}
		else {
			try {
				$ids=new Horde_Imap_Client_Ids(array($msgnum),true);
				$options=array(
					"ids" => $ids
				);
				$this->conn->copy($this->folder,$mailbox,$options);
				return true;
			}
			catch(Horde_Imap_Client_Exception $e) {
				$log_string='NOCC: copying mail failed';
				error_log($log_string);
				if( isset($conf->syslog) && $conf->syslog ) {
					syslog(LOG_INFO,$log_string);
				}
				return false;
			}
		}
	}
	else {
		$msg = $this->fetchmessage($msgnum);
		return $this->put_remote($TMP_SESSION, $msg);
	}
    }

    /**
     * Subscribe to a mailbox
     * @param string $mailbox Mailbox
     * @param bool $isNewMailbox Is new mailbox?
     * @return bool Successful?
     * @todo Is $isNewMailbox really nedded?
     */
    public function subscribe($mailbox, $isNewMailbox) {
	if( ! $this->is_horde() ) {
	        if ($isNewMailbox) {
	            return @imap_subscribe($this->conn, '{' . $this->server . '}' . $this->namespace . mb_convert_encoding($mailbox, 'UTF7-IMAP', 'UTF-8'));
	        } else {
	            return @imap_subscribe($this->conn, '{' . $this->server . '}' . mb_convert_encoding($mailbox, 'UTF7-IMAP', 'UTF-8'));
	        }
	}
	else {
		try {
			$this->conn->subscribeMailbox($mailbox,true);
			return true;
		}
		catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: subscribing to mailbox failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
			return false;
		}
	}
    }

    /**
     * Unsubscribe from a mailbox
     * @param string $mailbox Mailbox
     * @return bool Successful?
     */
    public function unsubscribe($mailbox) {
	if( ! $this->is_horde() ) {
	        return @imap_unsubscribe($this->conn, '{' . $this->server . '}' . mb_convert_encoding($mailbox, 'UTF7-IMAP', 'UTF-8'));
	}
	else {
		try {
			$this->conn->subscribeMailbox($mailbox,false);
			return true;
		}
		catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: unsubscribing from mailbox failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
			return false;
		}
	}
    }

    /**
     * Move a mail to a mailbox
     * @param integer $msgnum Message number
     * @param string $mailbox Destination mailbox
     * @return boolean Successful?
     * @todo Rename to moveMail()?
     */
    public function mail_move($msgnum, $mailbox) {
	$TMP_SESSION=array();
	$is_mb_local = $this->check_mb_local($TMP_SESSION, $mailbox);
	if( $is_mb_local ) {
		if( ! $this->is_horde() ) {
		        return imap_mail_move($this->conn, $msgnum, mb_convert_encoding($mailbox, 'UTF7-IMAP', 'UTF-8'), 0);
		}
		else {
			try {
				$ids=new Horde_Imap_Client_Ids(array($msgnum),true);
				$options=array(
					"ids" => $ids,
					"move" => true,
				);
				$this->conn->copy($this->folder,$mailbox,$options);
			} catch(Horde_Imap_Client_Exception $e) {
				$log_string='NOCC: move mail to folder '.$mailbox.' failed';
				error_log($log_string);
				if( isset($conf->syslog) && $conf->syslog ) {
					syslog(LOG_INFO,$log_string);
				}
				return false;
			}
			return true;
		}
	}
	else {
		$msg = $this->fetchmessage($msgnum);
		$ret = $this->put_remote($TMP_SESSION, $msg);
		if( $ret ) {
			$ret = $this->delete($msgnum);
		}
		return $ret;
	}
    }

    /**
     * Delete all messages marked for deletion
     * @return boolean Successful?
     */
    public function expunge() {
	if( ! $this->is_horde() ) {
	        return imap_expunge($this->conn);
	}
	else {
		try {
			$this->conn->expunge($this->folder);
			return true;
		} catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: expunge of folder '.$this->folder.' failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
	}
    }

    /**
     * Delete a mail
     * @param integer $msgnum Message number
     * @return boolean Successful?
     * @todo Rename to deleteMail()?
     */
    public function delete($msgnum) {
	if( ! $this->is_horde() ) {
	        return imap_delete($this->conn, $msgnum, 0);
	}
	else {
		//only works with uids
		if( isset($_SESSION['horde_sequence2uid'][$msgnum]) && $_SESSION['horde_sequence2uid'][$msgnum]>=0 ) {
			try {
				$uid=$_SESSION['horde_sequence2uid'][$msgnum];
				$ids=new Horde_Imap_Client_Ids(array($uid),false);
				$options=array(
					'ids' => $ids,
					'add' => array(Horde_Imap_Client::FLAG_DELETED),
				);
				$this->conn->store($this->folder,$options);
			} catch(Horde_Imap_Client_Exception $e) {
				$log_string='NOCC: deleting message failed';
				error_log($log_string);
				if( isset($conf->syslog) && $conf->syslog ) {
					syslog(LOG_INFO,$log_string);
				}
			}
		}
		return false;
	}
	return true;
    }

    public function close() {
	if( ! $this->is_horde() ) {
	        return imap_close($this->conn, CL_EXPUNGE);
	}
	else {
		try {
			$options = array(
				"expunge" => true,
			);
			$this->conn->close($options);
			return;
		} catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: close failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
	}
    }

    /**
     * ...
     * @return bool Is IMAP?
     * @todo Rename to isImap()?
     */
    public function is_imap() {
        return $this->_isImap;
    }

    /**
     * ...
     * @return bool Is IMAP?
     */
    private function isImapCheck() {
	if( ! $this->is_horde() ) {
	        //--------------------------------------------------------------------------------
	        // Check IMAP keywords...
	        //--------------------------------------------------------------------------------
	        $keywords = array('/imap', '/service=imap', ':143');
	        foreach ($keywords as $keyword) { //for each IMAP keyword...
	            if (stripos($this->server, $keyword) !== false) {
	                return true;
	            }
	        }

	        //--------------------------------------------------------------------------------
	        // Check POP3 keywords...
	        //--------------------------------------------------------------------------------
	        $keywords = array('/pop3', '/service=pop3', ':110');
	        foreach ($keywords as $keyword) { //for each POP3 keyword...
	            if (stripos($this->server, $keyword) !== false) {
	                return false;
	            }
	        }
	        //--------------------------------------------------------------------------------
	
	        //--------------------------------------------------------------------------------
	        // Check driver...
	        //--------------------------------------------------------------------------------
	        $check = imap_check($this->conn);
	        if ($check) {
	          return ($check->{'Driver'} == 'imap');
	        }
	        //--------------------------------------------------------------------------------
	}
	else {
		return $this->_isImap;
	}

        return false;
    }

//    public static function utf8($mime_encoded_text) {
//        //TODO: Fixed in PHP 5.3.2!
//        //Since PHP 5.2.5 returns imap_utf8() only capital letters!
//        //See bug #44098 for details: http://bugs.php.net/44098
//        if (version_compare(PHP_VERSION, '5.2.5', '>=')) { //if PHP 5.2.5 or newer...
//            return nocc_imap::decode_mime_string($mime_encoded_text);
//        }
//        else { //if PHP 5.2.4 or older...
//            return imap_utf8($mime_encoded_text);
//        }
//   }
//
//    /**
//     * Decode MIME string
//     * @param string $string MIME encoded string
//     * @param string $charset Charset
//     * @return string Decoded string
//     * @static
//     */
//    public static function decode_mime_string($string, $charset = 'UTF-8') {
//        $decodedString = '';
//	$elements = imap_mime_header_decode($string);
//        foreach ($elements as $element) { //for all elements...
//            if ($element->charset == 'default') { //if 'default' charset...
//                $element->charset = mb_detect_encoding($element->text);
//            }
//            $decodedString .= mb_convert_encoding($element->text, $charset, $element->charset);
//        }
//        return $decodedString;
//    }

    /**
     * ...
     * @return array Mailboxes
     */
    public function getmailboxes() {
	if( ! $this->is_horde() ) {
	        $mailboxes = @imap_getmailboxes($this->conn, '{' . $this->server . '}', '*');
	        if (!is_array($mailboxes)) {
	            throw new Exception('imap_getmailboxes() did not return an array.');
	        } else {
	            sort($mailboxes);
	        }
	        return $mailboxes;
	}
	else {
		try {
			$mode = Horde_Imap_Client::MBOX_ALL;
			$options = array(
				"flat" => true,
				"sort" => true,
			);
			$horde_mboxes=$this->conn->listMailboxes("*",$mode,$options);
			$allmailboxes=array();
			foreach( $horde_mboxes as $mbox ) {
				$obj=new stdClass();
				$obj->name="{".$this->server."}".$mbox->utf8;
				$allmailboxes[]=$obj;
			}
			return $allmailboxes;
		} catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: failed to get mailbox names';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
			throw new Exception('imap_getmailboxes() did not return an array.');
		}
	}
    }

    /**
     * ...
     * @return array Mailboxes names
     * @todo Return UTF-8 names?
     */
    public function getmailboxesnames() {
        try {
            $mailboxes = $this->getmailboxes();
            $names = array();
            foreach ($mailboxes as $mailbox) { //for all mailboxes...
		if( ! $this->is_horde() ) {
                	$name = str_replace('{' . $this->server . '}', '', mb_convert_encoding($mailbox->name, 'UTF-8', 'UTF7-IMAP'));
		}
		else {
	                $name = str_replace('{' . $this->server . '}', '', $mailbox->name);
		}
                //TODO: Why not add names with more the 32 chars?
                //if (strlen($name) <= 32) {
                    array_push($names, $name);
                //}
            }
            return $names;
        }
        catch (Exception $ex) {
            return array();
        }
    }

    /**
     * ...
     * @return array Subscribed mailboxes
     * @todo Really throw an exception?
     */
    public function getsubscribed() {
	if( ! $this->is_horde() ) {
	        $subscribed = @imap_getsubscribed($this->conn, '{' . $this->server . '}', '*');
	        if (!is_array($subscribed)) {
	            throw new Exception('imap_getsubscribed() did not return an array.');
	        } else {
	            sort($subscribed);
	        }
	}
	else {
		try {
			$mode = Horde_Imap_Client::MBOX_SUBSCRIBED;
			$options = array(
				"flat" => true,
				"sort" => true,
			);
			$horde_mboxes=$this->conn->listMailboxes("*",$mode,$options);
			$subscribed=array();
			foreach( $horde_mboxes as $mbox ) {
				$obj=new stdClass();
				$obj->name="{".$this->server."}".$mbox->utf8;
				$subscribed[]=$obj;
			}
		} catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: list subscribed mailboxes failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
	}
        return $subscribed;
    }

    /**
     * ...
     * @return array Subscribed mailboxes names
     * @todo Return UTF-8 names?
     */
    public function getsubscribednames() {
        try {
            $subscribed = $this->getsubscribed();

            $names = array();
            foreach ($subscribed as $mailbox) { //for all mailboxes...
		if( ! $this->is_horde() ) {
                	$name = str_replace('{' . $this->server . '}', '', mb_convert_encoding($mailbox->name, 'UTF-8', 'UTF7-IMAP'));
		}
		else {
                	$name = str_replace('{' . $this->server . '}', '', $mailbox->name);
		}
                if (!in_array($name, $names)) {
                    array_push($names, $name);
                }
            }
            return $names;
        }
        catch (Exception $ex) {
            return array();
        }
    }

    /**
     * Mark mail as read
     * @param integer $msgnum Message number
     * @return boolean Successful?
     * @todo Rename to markMailRead()?
     */
    public function mail_mark_read($msgnum) {
	if( ! $this->is_horde() ) {
	        return imap_setflag_full($this->conn, $msgnum, '\\Seen');
	}
	else {
		//only works with uids
		if( isset($_SESSION['horde_sequence2uid'][$msgnum]) && $_SESSION['horde_sequence2uid'][$msgnum]>=0 ) {
			try {
				$uid=$_SESSION['horde_sequence2uid'][$msgnum];
				$ids=new Horde_Imap_Client_Ids(array($uid),false);
				$options=array(
					"ids" => $ids,
					"add" => array(Horde_Imap_Client::FLAG_SEEN),
				);
				$this->conn->store($this->folder,$options);
			} catch(Horde_Imap_Client_Exception $e) {
				$log_string='NOCC: setting mail as read failed';
				error_log($log_string);
				if( isset($conf->syslog) && $conf->syslog ) {
					syslog(LOG_INFO,$log_string);
				}
			}
		}
	}
    }

    /**
     * Mark mail as unread
     * @param integer $msgnum Message number
     * @return boolean Successful?
     * @todo Rename to markMailUnread()?
     */
    public function mail_mark_unread($msgnum) {
	if( ! $this->is_horde() ) {
	        return imap_clearflag_full($this->conn, $msgnum, '\\Seen');
	}
	else {
		//only works with uids
		if( isset($_SESSION['horde_sequence2uid'][$msgnum]) && $_SESSION['horde_sequence2uid'][$msgnum]>=0 ) {
			try {
				$uid=$_SESSION['horde_sequence2uid'][$msgnum];
				$ids=new Horde_Imap_Client_Ids(array($uid),false);
				$options=array(
					"ids" => $ids,
					"remove" => array(Horde_Imap_Client::FLAG_SEEN),
				);
				$this->conn->store($this->folder,$options);
			} catch(Horde_Imap_Client_Exception $e) {
				$log_string='NOCC: setting mail as read failed';
				error_log($log_string);
				if( isset($conf->syslog) && $conf->syslog ) {
					syslog(LOG_INFO,$log_string);
				}
			}
		}
	}
    }

    public function copytosentfolder($maildata, &$ev, $sent_folder_name) {
	if( ! $this->is_horde() ) {
	        if (!(imap_append($this->conn, '{'.$this->server.'}'.$this->namespace.mb_convert_encoding($sent_folder_name, 'UTF7-IMAP', 'UTF-8'), $maildata, "\\Seen"))) {
	            $ev = new NoccException("could not copy mail into $sent_folder_name folder: ".$this->last_error());
	            return false;
	        }
	}
	else {
		try {
			$data=array(
				array(
					"data" => $maildata,
					"flags" => array(Horde_Imap_Client::FLAG_SEEN),
				),
			);
			$ids=$this->conn->append($sent_folder_name,$data);
			if( $ids->isEmpty() ) {
				$ev = new NoccException("could not copy mail into $sent_folder_name folder, ids empty");
				return false;
			}
		} catch(Horde_Imap_Client_Exception $e) {
			$ev = new NoccException("could not copy mail into $sent_folder_name folder");
			return false;
		}
	}
	return true;
    }

//    /*
//     * These functions are static, but if we could re-implement them without
//     * requiring PHP IMAP support, more people can use NOCC.
//     */
//    public static function base64($file) {
//        return imap_base64($file);
//    }
//
//    public static function i8bit($file) {
//        return imap_8bit($file);
//    }
//
//    public static function qprint($file) {
//        return imap_qprint($file);
//    }

    /**
     * Decode  BASE64 or QUOTED-PRINTABLE data
     * @param string $data Encoded data
     * @param string $transfer BASE64 or QUOTED-PRINTABLE?
     * @return string Decoded data
     * TODO: Better name?
     */
    public static function decode($data, $transfer) {
        if ($transfer == 'BASE64') { //if BASE64...
		$data=mb_convert_encoding($data,"UTF-8","BASE64");
		return $data;
        }
        elseif ($transfer == 'QUOTED-PRINTABLE') { //if QUOTED-PRINTABLE...
		return quoted_printable_decode($data);
        }
	
        return $data;
    }

	public static function mime_header_decode($header,$decode=true,$ishorde=false) {
		global $conf;
		$decodedheader="";
		if( ! $ishorde ) {

			//special utf-16 handling:
			$do_pre_decoding=false;
			$source=imap_mime_header_decode($header);
			for ($j = 0; $j < count($source); $j++ ) {
				if( $source[$j]->charset == 'utf-16' ) {
					$do_pre_decoding=true;
				}
			}
			if( $do_pre_decoding ) {
				if (isset($conf->default_charset) && $conf->default_charset != '') {
					$header=mb_convert_encoding(mb_decode_mimeheader($header),$conf->default_charset,'UTF-8');
				}
				else {
					$header=mb_convert_encoding(mb_decode_mimeheader($header),'ISO-8859-1','UTF-8');
				}
			}

			$source=imap_mime_header_decode($header);
			for ($j = 0; $j < count($source); $j++ ) {
				$element_charset=($source[$j]->charset == 'default') ? detect_charset($source[$j]->text) : $source[$j]->charset;
				if ($element_charset == '' || $element_charset == null) {
					if (isset($conf->default_charset) && $conf->default_charset != '') {
						$element_charset = $conf->default_charset;
					}
					else {
						$element_charset = 'ISO-8859-1';
					}
				}
				if( $decode ) {
					//$element_converted = os_iconv($element_charset, 'UTF-8', $source[$j]->text);
					$element_converted = mb_convert_encoding($source[$j]->text, 'UTF-8', $element_charset);
				}
				else {
					$element_converted = $source[$j]->text;
				}
				$decodedheader=$decodedheader.$element_converted;
			}
		}
		else {
			$charset_all="";
			$tail=$header;
			$matches=array();
			while( preg_match("/^(.*)(=\?.*\?.*\?=)(.*)$/U",$tail,$matches) ) {
				$decodedheader=$decodedheader.trim($matches[1]);
				$mime_encoded=$matches[2];
				$tail=trim($matches[3]);
				$encoding="";
				$charset="";
				$matches=array();
				if( preg_match("/^=\?(.*)\?.*\?=/U",$mime_encoded,$matches) ) {
					$charset=mb_strtoupper($matches[1]);
				}
				$matches=array();
				if( preg_match("/^=\?.*\?([pq])\?.*\?=/iU",$mime_encoded,$matches) ) {
					$encoding=mb_strtoupper($matches[1]);
				}
				if( $charset == '' || $charset == null) {
					$charset='ISO-8859-1';
				}
				$charset_all=$charset;
				$mime_decoded=mb_convert_encoding(mb_decode_mimeheader($mime_encoded),$charset);
				if( $encoding == "Q" ) {
					$mime_decoded=preg_replace("/_/"," ",$mime_decoded);
				}
				$decodedheader=$decodedheader.$mime_decoded;
				$matches=array();
			}
			$decodedheader=$decodedheader.$tail;

			if( $decode ) {
				if( $charset_all == '' || $charset_all == null) {
					$charset_all='ISO-8859-1';
				}
				$decodedheader=mb_convert_encoding($decodedheader,"UTF-8",$charset_all);
			}
		}
		return $decodedheader;
	}


//    public static function mime_header_decode($header, $decode=true) {
//	$source = imap_mime_header_decode($header);
//        $result[] = new result;
//        $result[0]->text='';
//        $result[0]->charset='ISO-8859-1';
//        for ($j = 0; $j < count($source); $j++ ) {
//            $element_charset =  ($source[$j]->charset == 'default') ? detect_charset($source[$j]->text) : $source[$j]->charset;
//		if ($element_charset == '' || $element_charset == null) {
//			if (isset($conf->default_charset) && $conf->default_charset != '') {
//				$element_charset = $conf->default_charset;
//			}
//			else {
//				$element_charset = 'ISO-8859-1';
//			}
//		}
//		if( $decode ) {
//	            $element_converted = os_iconv($element_charset, 'UTF-8', $source[$j]->text);
//		}
//		else {
//	            $element_converted = $source[$j]->text;
//		}
//            $result[$j] = new stdClass();
//            $result[$j]->text = $element_converted;
//            $result[$j]->charset = 'UTF-8';
//        }
//        return $result;
//    }

    /*
     * These are general utility functions that extend the imap interface.
     */
    public function html_folder_select($value, $selected = '') {
        global $conf;
        $folders = $this->getsubscribednames();
        if (!is_array($folders) || count($folders) < 1) {
            return "<p class=\"error\">Not currently subscribed to any mailboxes</p>";
        }
        reset($folders);

        $html_select = "<select class=\"button\" id=\"$value\" name=\"$value\">\n";

        foreach ($folders as $folder) {
            $html_select .= "\t<option ".($folder == $selected ? "selected=\"selected\"" : "")." value=\"$folder\">".$folder."</option>\n";
        }

	foreach( $_COOKIE as $cookie_key => $cookie_value ) {
		if( preg_match("/^NOCCLI_/",$cookie_key) ) {
			$sname=$cookie_key;
			if( $line=NOCC_Session::load_session_file($sname) ) {
				list(
					$session_id,
					$TMP_SESSION['nocc_user'],
					$TMP_SESSION['nocc_passwd'],
					$TMP_SESSION['nocc_login'],
					$TMP_SESSION['nocc_lang'],
					$TMP_SESSION['nocc_smtp_server'],
					$TMP_SESSION['nocc_smtp_port'],
					$TMP_SESSION['nocc_theme'],
					$TMP_SESSION['nocc_domain'],
					$TMP_SESSION['nocc_domainnum'],
					$TMP_SESSION['imap_namespace'],
					$TMP_SESSION['nocc_servr'],
					$TMP_SESSION['nocc_folder'],
					$TMP_SESSION['smtp_auth'],
					$TMP_SESSION['ucb_pop_server'],
					$TMP_SESSION['quota_enable'],
					$TMP_SESSION['quota_type'],
					$TMP_SESSION['creation_time'],
					$TMP_SESSION['persistent'],
					$TMP_SESSION['remote_addr'],
					$TMP_SESSION['is_horde']
				) = explode(" ", base64_decode($line));

				//unclear if INBOX is always the best default, but we don't have available folders of another server session
				$TMP_SESSION['nocc_folder'] = 'INBOX';

				if( $session_id == $cookie_value && $TMP_SESSION['nocc_servr'] != $_SESSION['nocc_servr'] ) {
					foreach( $conf->domains as $index => $domain ) {
						if( isset($conf->domains[$index]->in) && $conf->domains[$index]->in == $TMP_SESSION['nocc_servr'] ) {
							if( isset($conf->domains[$index]->show_as) && strlen($conf->domains[$index]->show_as)>0 ) {
								$folder=$conf->domains[$index]->show_as.":".$TMP_SESSION['nocc_folder'];
								$html_select .= "\t<option ".($folder == $selected ? "selected=\"selected\"" : "")." value=\"$folder\">".$folder."</option>\n";
							}
							else {						
								$folder=explode(":",$TMP_SESSION['nocc_servr'])[0].":".$TMP_SESSION['nocc_folder'];
								$html_select .= "\t<option ".($folder == $selected ? "selected=\"selected\"" : "")." value=\"$folder\">".$folder."</option>\n";
							}
							break;
						}
					}
				}
			}
		}
	}

        $html_select .= "</select>\n";
        return $html_select;
    }

    public function get_folder_count() {
        try {
            return count($this->getsubscribed());
        }
        catch (Exception $ex) {
            return 0;
        }
    }

    /**
     * ...
     * @param int $num_messages Number of messages
     * @return int Page count
     */
    public function get_page_count($num_messages) {
        if (!is_int($num_messages)) { //if NO integer...
            return 0;
        }
        if ($num_messages == 0) { //if 0 messages...
            return 0;
        }
        return ceil($num_messages / get_per_page());
    }

    /**
     * Retrieve the quota settings
     * @param string $quotaRoot Quota root (mailbox)
     * @return array Quota settings
     */
    public function get_quota_usage($quotaRoot) {
	if( ! $this->is_horde() ) {
	        return @imap_get_quotaroot($this->conn, mb_convert_encoding($quotaRoot, 'UTF7-IMAP', 'UTF-8'));
	}
	else {
		try {
			$quota=$this->conn->getQuotaRoot($quotaRoot);
			return $quota;
		} catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: getting quotaroot failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
	}
	return false;
    }

    /**
     * Return status information from a mailbox
     * @param string $mailbox Mailbox
     * @return object Status information
     */
    public function status($mailbox) {
	if( ! $this->is_horde() ) {
	        $status_obj=imap_status($this->conn, mb_convert_encoding($mailbox, 'UTF7-IMAP', 'UTF-8'), SA_ALL);
		$status=array();
		if( isset($status_obj->unseen) ) {
			$status['unseen']=$status_obj->unseen;
		}
		else {
			$status['unseen']=0;
		}
		return $status;
	}
	else {
		$status=array();
		try {
	                $mailbox=str_replace('{' . $this->server . '}', '',$mailbox);
			$status=$this->conn->status($mailbox,Horde_Imap_Client::STATUS_ALL);
		} catch(Horde_Imap_Client_Exception $e) {
			$log_string='NOCC: getting status failed';
			error_log($log_string);
			if( isset($conf->syslog) && $conf->syslog ) {
				syslog(LOG_INFO,$log_string);
			}
		}
		return $status;
	}
    }

    /**
     * Check if we should use Horde/Imap_Client
     * @param
     * @return bool true if Horde/Imap_Client should be used, default is false
     */
    public function is_horde() {
        global $conf;
	$r=false;
	if( isset($conf->horde_imap_client) && $conf->horde_imap_client ) {
		$r=true;
	}
	if( isset($conf->domains[$_SESSION['nocc_domainnum']]->horde_imap_client) ) {
		if( $conf->domains[$_SESSION['nocc_domainnum']]->horde_imap_client ) {
			$r=true;
		}
		else {
			$r=false;
		}
	}
	return $r;
    }

}



