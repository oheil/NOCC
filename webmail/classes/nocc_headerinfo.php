<?php
/**
 * Class for wrapping a imap_headerinfo() object
 *
 * Copyright 2009-2011 Tim Gerundt <tim@gerundt.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: nocc_headerinfo.php 2875 2020-04-21 18:20:08Z oheil $
 */

/**
 * Wrapping a imap_headerinfo() object
 * 
 * @package    NOCC
 */
class NOCC_HeaderInfo {
    /**
     * imap_headerinfo() object
     * @var object
     * @access private
     */
    private $_headerinfo;
    
    /**
     * Default charset
     * @var string
     * @access private
     */
    private $_defaultcharset;

    /**
     * we are using Horde/Imap library
     * @var bool
     * @access private
     */
    private $_ishorde;

    /**
     * hold some more headerinfo from horde call
     * @var object
     * @access private
     */
    private $_horde_flags;

    
    /**
     * Initialize the wrapper
     * @param object $headerinfo imap_headerinfo() object
     * @param string $defaultcharset Default charset
     * @todo Throw exception, if no vaild header info? 
     */
    public function __construct($headerinfo, $defaultcharset = 'ISO-8859-1', $horde_flags = null, $is_horde = false) {
	$this->_ishorde = $is_horde;
	$this->_horde_flags = $horde_flags;
        $this->_headerinfo = $headerinfo;
        $this->_defaultcharset = $defaultcharset;
    }
    
    /**
     * Get the default charset
     * @return string Default charset
     */
    public function getDefaultCharset() {
        return $this->_defaultcharset;
    }
    
    /**
     * Set the default charset
     * @param string $defaultcharset Default charset
     */
    public function setDefaultCharset($defaultcharset) {
        $this->_defaultcharset = $defaultcharset;
    }
    
    /**
     * Get the message id from the header info
     * @return string Message id
     */
    public function getMessageId() {
        if( ! $this->_ishorde ) {
	        if (isset($this->_headerinfo->message_id)) {
	            return $this->_headerinfo->message_id;
	        }
	}
	else {
		if( $this->_headerinfo->count() >= 1 ) {
			return $this->_headerinfo->first()->getEnvelope()->message_id;
                }
        }
        return '';
    }
    
    /**
     * Get the subject from the header info8
     * @return string Subject
     */
    public function getSubject() {
        if( ! $this->_ishorde ) {
	        if (isset($this->_headerinfo->subject)) {
	            return $this->_decodeMimeHeader($this->_headerinfo->subject, $this->_defaultcharset);
	        }
	}
	else {
		if( $this->_headerinfo->count() >= 1 ) {
			return $this->_decodeMimeHeader($this->_headerinfo->first()->getEnvelope()->subject, $this->_defaultcharset, false);
                }
        }
        return '';
    }
    
    /**
     * Get the "From" address from the header info
     * @return string "From" address
     */
    public function getFromAddress() {
	$ret='';
	if( ! $this->_ishorde ) {
	        if (isset($this->_headerinfo->fromaddress)) {
			$ret=$this->_decodeMimeHeader($this->_headerinfo->fromaddress, $this->_defaultcharset);
			$ret=reformat_address_list($ret);
	        }
	}
	else {
		if( $this->_headerinfo->count() >= 1 ) {
			$ret=$this->_decodeMimeHeader($this->_headerinfo->first()->getEnvelope()->from, $this->_defaultcharset, false);
			//$ret=$this->_headerinfo->first()->getEnvelope()->from;
			$ret=reformat_address_list($ret);
		}
	}
        return $ret;
    }
    
    /**
     * Get the "To" address from the header info
     * @return string "To" address
     */
    public function getToAddress() {
	$ret='';
	if( ! $this->_ishorde ) {
	        if (isset($this->_headerinfo->toaddress)) {
			$ret=$this->_decodeMimeHeader($this->_headerinfo->toaddress, $this->_defaultcharset);
			$ret=reformat_address_list($ret);
	        }
	}
	else {
		if( $this->_headerinfo->count() >= 1 ) {
			$ret=$this->_decodeMimeHeader($this->_headerinfo->first()->getEnvelope()->to, $this->_defaultcharset, false);
			$ret=reformat_address_list($ret);
		}
	}
        return $ret;
    }
    
    /**
     * Get the "Cc" address from the header info
     * @return string "Cc" address
     */
    public function getCcAddress() {
	$ret='';
	if( ! $this->_ishorde ) {
	        if (isset($this->_headerinfo->ccaddress)) {
			$ret=$this->_decodeMimeHeader($this->_headerinfo->ccaddress, $this->_defaultcharset);
			$ret=reformat_address_list($ret);
	        }
	}
	else {
		if( $this->_headerinfo->count() >= 1 ) {
			$ret=$this->_decodeMimeHeader($this->_headerinfo->first()->getEnvelope()->cc, $this->_defaultcharset, false);
			$ret=reformat_address_list($ret);
		}
	}
        return $ret;
    }
    
    /**
     * Get the "Reply-To" address from the header info
     * @return string "Reply-To" address
     */
    public function getReplyToAddress() {
	$ret='';
	if( ! $this->_ishorde ) {
	        if (isset($this->_headerinfo->reply_toaddress)) {
			$ret=$this->_decodeMimeHeader($this->_headerinfo->reply_toaddress, $this->_defaultcharset);
			$ret=reformat_address_list($ret);
	        }
	}
	else {
		if( $this->_headerinfo->count() >= 1 ) {
			$ret=$this->_decodeMimeHeader($this->_headerinfo->first()->getEnvelope()->reply_to, $this->_defaultcharset, false);
			$ret=reformat_address_list($ret);
		}
	}
        return $ret;
    }
    
    /**
     * Get the date (in Unix time) from the header info
     * @return int Date in Unix time
     */
    public function getTimestamp() {
	if( ! $this->_ishorde ) {
	        if (isset($this->_headerinfo->udate)) {
	            return $this->_headerinfo->udate;
	        }
	}
	else {
		if( $this->_headerinfo->count() >= 1 ) {
			return $this->_headerinfo->first()->getEnvelope()->date->__toString();
		}
	}
        return 0;
    }
    
    /**
     * Is the mail unread?
     * @return boolean Is unread?
     */
    public function isUnread() {
	if( ! $this->_ishorde ) {
	        if (($this->_headerinfo->Unseen == 'U') || ($this->_headerinfo->Recent == 'N')) {
	            return true;
	        }
	}
	else {
		if( $this->_horde_flags != null && $this->_horde_flags->count() >= 1 ) {
			$flags = $this->_horde_flags->first()->getFlags();
			$unseen = true;
			foreach( $flags as $flag ) {
				if( strtolower($flag) == '\seen' || strtolower($flag) == '\recent' ) {
					$unseen = false;
				}
			}
			return $unseen;
		}
	}
        return false;
    }
    
    /**
     * Is the mail flagged?
     * @return boolean Is flagged?
     */
    public function isFlagged() {
	if( ! $this->_ishorde ) {
	        if ($this->_headerinfo->Flagged == 'F') {
	            return true;
	        }
	}
	else {
		if( $this->_horde_flags != null && $this->_horde_flags->count() >= 1 ) {
			$flags = $this->_horde_flags->first()->getFlags();
			$flagged = false;
			foreach( $flags as $flag ) {
				if( strtolower($flag) == '\flagged' ) {
					$flagged = true;
				}
			}
			return $flagged;
		}
	}
        return false;
    }
    
    /**
     * Decode MIME header
     * @param string $mimeheader Encoded MIME header
     * @param string $defaultcharset Default charset
     * @return string Decoded MIME header
     * @access private
     */
    private function _decodeMimeHeader($mimeheader, $defaultcharset, $decode=true) {
	$decodedheader = '';
	if (isset($mimeheader)) {
		$mimeheader = str_replace('x-unknown', $defaultcharset, $mimeheader);
		$decodedheader = nocc_imap::mime_header_decode($mimeheader,$decode,$this->_ishorde);
	}
        return $decodedheader;
    }
}
?>
