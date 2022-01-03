<?php
/**
 * Class for wrapping a imap_fetchstructure() object
 *
 * Copyright 2009-2011 Tim Gerundt <tim@gerundt.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: nocc_mailstructure.php 2875 2020-04-21 18:20:08Z oheil $
 */

require_once 'nocc_internetmediatype.php';
require_once 'nocc_encoding.php';

/**
 * Wrapping a imap_fetchstructure() object
 *
 * @package    NOCC
 */
class NOCC_MailStructure {
    /**
     * imap_fetchstructure() object
     * @var object
     * @access private
     */
    private $_structure;

    /**
     * Internet media type
     * @var NOCC_InternetMediaType
     * @access private
     */
    private $_internetMediaType;

    /**
     * Encoding
     * @var NOCC_Encoding
     * @access private
     */
    private $_encoding;

    /**
     * we are using Horde/Imap library
     * @var bool
     * @access private
     */
    private $_ishorde;
    
    /**
     * contains mimeIDs and content_transfer_encoding for each part if Horde/Imap is used
     * @var array
     * @access private
     */
    private $_parts_info;
    
    /**
     * Initialize the wrapper
     * @param object $structure imap_fetchstructure() object
     * @param boolean $is_horde true if Horde/Imap is used
     * @param array $parts_info contains mimeIDs and content_transfer_encoding for each part
     * @todo Throw exception, if no vaild structure? 
     */
    public function __construct($structure,$is_horde=false, $parts_info=array() ) {
	$this->_parts_info = $parts_info;
	$this->_ishorde = $is_horde;
	$this->_structure = $structure;  //in case of Horde this must be a Horde_Mime_Part
	$this->_internetMediaType = NOCC_MailStructure::getInternetMediaTypeFromStructure($structure,$is_horde);
	$this->_encoding = NOCC_MailStructure::getEncodingFromStructure($structure,$is_horde, $parts_info);
    }
    
    /**
     * Check if this structure was obtained using Horde/Imap library
     * @return boolean
     */
    public function isHorde() {
        return $this->_ishorde;
    }

    /**
     * return the parts info if we use Horde/Imap library, empty if not Horde/Imap
     * @return array
     */
    public function getPartsInfo() {
        return $this->_parts_info;
    }

    /**
     * Get the complete imap_fetchstructure() object
     * @return object
     */
    public function getStructure() {
        return $this->_structure;
    }

    /**
     * Get the transfer encoding from the structure
     * @return NOCC_Encoding Transfer encoding
     */
    public function getEncoding() {
	return $this->_encoding;
    }

    /**
     * Get the content description from the structure
     * @return string Content description
     */
    public function getDescription() {
	if( ! $this->_ishorde ) {
		if ($this->_structure->ifdescription) {
			return $this->_structure->description;
		}
	}
	else {
		if( $this->_structure != null ) {
			$description=$this->_structure->getDescription();
			return ( $description == null ? '' : $description );
		}
	}
        return '';
    }
    
    /**
     * Get the identification from the structure
     * @return string Identification
     */
    public function getId($contentID=false) {
	$result="";
	if( ! $this->_ishorde ) {
		if ($this->_structure->ifid) {
			$result=$this->_structure->id;
		}
	}
	else {
		if( $this->_structure != null ) {
			$id=$this->_structure->getMimeId();
			if( $id != null ) {
				if( ! $contentID ) {
					$result=$id;
				}
				else {
					$parts_info=$this->_parts_info;
					if( isset($parts_info[$id]) && isset($parts_info[$id]['contentId']) ) {
						$result=$parts_info[$id]['contentId'];
					}
				}
			}
		}
	}
        return $result;
    }
    
    /**
     * Has the structure a identification?
     * @return bool Has identification?
     */
    public function hasId() {
        $id = $this->getId();
        return !empty($id);
    }

    //removed, because not used anywhere
    ///**
    // * Get the number of lines from the structure
    // * @return integer Number of lines
    // */
    //public function getLines() {
    //    if (isset($this->_structure->lines)) {
    //        return $this->_structure->lines;
    //    }
    //    return 0;
    //}
    
    /**
     * Get the number of bytes from the structure
     * @return integer Number of bytes
     */
    public function getBytes() {
	if( ! $this->_ishorde ) {
		if (isset($this->_structure->bytes)) {
			return $this->_structure->bytes;
		}
	}
	else {
		if( $this->_structure != null ) {
			return $this->_structure->getBytes();
		}
	}
        return 0;
    }
    
    /**
     * Get the total number of bytes from the structure
     * @return integer Total number of bytes
     */
    public function getTotalBytes() {
	$totalbytes = $this->getBytes();
	if ($totalbytes == 0) { //if a mail has ANY attachements, $structure->bytes is ALWAYS empty...
		if( ! $this->_ishorde ) {
			if (isset($this->_structure->parts)) {
				for ($i = 0; $i < count($this->_structure->parts); $i++) { //for all parts...
					if (isset($this->_structure->parts[$i]->bytes)) {
						$totalbytes += $this->_structure->parts[$i]->bytes;
					}
				}
			}
		}
		else {
			if( $this->_structure != null ) {
				$all_parts=$this->_structure->getParts();
				foreach($all_parts as $part) {
					$totalbytes += $part->getBytes();
				}
			}
		}
	}
        return $totalbytes;
    }
    
    /**
     * Get the size from the structure in kilobyte
     * @return integer Size in kilobyte
     */
    public function getSize() {
        $totalBytes = $this->getTotalBytes();

        if ($totalBytes > 1024) { //if more then 1024 bytes...
            return ceil($totalBytes / 1024);
        }
        return 1;
    }
    
    /**
     * Get the disposition from the structure
     * @return string Disposition
     */
    public function getDisposition() {
	if( ! $this->_ishorde ) {
		if ($this->_structure->ifdisposition) {
			return $this->_structure->disposition;
		}
	}
	else {
		if( $this->_structure != null ) {
			return $this->_structure->getDisposition();
		}
	}
        return '';
    }
    
    // removed because never called
    ///**
    // * Get the Content-disposition MIME header parameters from the structure
    // * @return array Content-disposition MIME header parameters
    // */
    //public function getDparameters() {
    //	if ($this->_structure->ifdparameters) {
    //		return $this->_structure->dparameters;
    //	}
    //        return array();
    //    }
    
    /**
     * Get a value from the Content-disposition MIME header parameters
     * @param string $attribute Attribute
     * @param string $defaultvalue Default value
     * @return string Value
     */
    public function getValueFromDparameters($attribute, $defaultvalue = '') {
        $attribute = strtolower($attribute);
	if( ! $this->_ishorde ) {
		if ($this->_structure->ifdparameters) {
			foreach ($this->_structure->dparameters as $parameter) { //for all parameters...
				if (strtolower($parameter->attribute) == $attribute) {
					return $parameter->value;
				}
			}
		}
	}
	else {
		if( $this->_structure != null ) {
			$value=$this->_structure->getDispositionParameter($attribute);
			return ( $value == null ? '' : $value );
		}
	}
        return $defaultvalue;
    }
    
    // removed because never called
    ///**
    // * Get the parameters from the structure
    // * @return array Parameters
    // */
    //public function getParameters() {
    //    if ($this->_structure->ifparameters) {
    //      return $this->_structure->parameters;
    //    }
    //    return array();
    //}
    
    /**
     * Get a value from the parameters
     * @param string $attribute Attribute
     * @param string $defaultvalue Default value
     * @return string Value
     */
    public function getValueFromParameters($attribute, $defaultvalue = '') {
        $attribute = strtolower($attribute);
	if( ! $this->_ishorde ) {
	        if ($this->_structure->ifparameters) {
	            foreach ($this->_structure->parameters as $parameter) { //for all parameters...
	                if (strtolower($parameter->attribute) == $attribute) {
	                    return $parameter->value;
	                }
	            }
	        }
	}
	else {
		if( $this->_structure != null ) {
			$value=$this->_structure->getContentTypeParameter($attribute);
			return ( $value == null ? '' : $value );
		}
	}
        return $defaultvalue;
    }

    /**
     * Has the structure parts?
     * @return bool Has parts?
     */
    public function hasParts() {
	if( ! $this->_ishorde ) {
	        if (isset($this->_structure->parts)) {
	            if (count($this->_structure->parts) > 0) {
	                return true;
	            }
	        }
	}
	else {
		if( $this->_structure != null ) {
			$all_parts=$this->_structure->getParts();
			if( count($all_parts) > 0 ) {
				return true;
			}
		}
	}
        return false;
    }

    /**
     * Get the parts from the structure
     * @return array Parts
     */
    public function getParts() {
        if ($this->hasParts()) {
		if( ! $this->_ishorde ) {
			return $this->_structure->parts;
		}
		else {
			if( $this->_structure != null ) {
				return $this->_structure->getParts();
			}
		}
        }
        return array();
    }

    /**
     * Get the (file) name from the structure
     * @param string $defaultname Default (file) name
     * @return string (File) name
     * @todo I got a mail which use "name*" as parameter: string(52) "UTF-8''Bestellliste%20f%C3%BCr%20das%20Fotoalbum.doc"
     */
    public function getName($defaultname = '') {
        $name = $this->getValueFromParameters('name');
        if (!empty($name)) { //if "name" parameter exists...
            return $name;
        }
        else { //if "name" parameter NOT exists...
            $filename = $this->getValueFromDparameters('filename');
            if (!empty($filename)) { //if "filename" parameter exists...
                return $filename;
            }
        }
        return $defaultname;
    }

    /**
     * Get the charset from the structure
     * @param string $defaultcharset Default charset
     * @return string Charset
     */
    public function getCharset($defaultcharset = '') {
        return $this->getValueFromParameters('Charset', $defaultcharset);
    }

    /**
     * Get the internet media type (MIME type) from the structure
     * @return NOCC_InternetMediaType Internet media type
     */
    public function getInternetMediaType() {
        return $this->_internetMediaType;
    }

    /**
     * Is attachment?
     * @return bool Is attachment?
     */
    public function isAttachment() {
        if (strtolower($this->getDisposition()) == 'attachment') { //if attachment...
            return true;
        }
        return false;
    }

    /**
     * Is inline?
     * @return bool Is inline?
     */
    public function isInline() {
        if (strtolower($this->getDisposition()) == 'inline') { //if inline...
            return true;
        }
        return false;
    }

    /**
     * ...
     * @param object $structure imap_fetchstructure() object
     * @param bool $ishorde true if we are using Horde/Imap library
     * @return NOCC_InternetMediaType ...
     */
    public static function getInternetMediaTypeFromStructure($structure,$ishorde=false) {
	if( ! $ishorde ) {
	        if (isset($structure->type) && isset($structure->subtype)) {
	            return new NOCC_InternetMediaType($structure->type, $structure->subtype);
	        }
	}
	else {
		if( $structure != null ) {
			$type=$structure->getPrimaryType();
			$type_code=-1;
			switch($type) {
				case(1==preg_match("/^text/i",$type)): $type_code=0; break;
				case(1==preg_match("/^multipart/i",$type)): $type_code=1; break;
				case(1==preg_match("/^message/i",$type)): $type_code=2; break;
				case(1==preg_match("/^application/i",$type)): $type_code=3; break;
				case(1==preg_match("/^audio/i",$type)): $type_code=4; break;
				case(1==preg_match("/^image/i",$type)): $type_code=5; break;
				case(1==preg_match("/^video/i",$type)): $type_code=6; break;
				case(1==preg_match("/^model/i",$type)): $type_code=7; break;
				case(1==preg_match("/^other/i",$type)): $type_code=8; break;
				default: break;
			}
			if( $type_code >= 0 ) {
				$subtype=$structure->getSubType();
				return new NOCC_InternetMediaType($type_code,$subtype);
			}
		}
	}
        return new NOCC_InternetMediaType();
    }

    /**
     * ...
     * @param object $structure imap_fetchstructure() object
     * @param bool $ishorde true if we are using Horde/Imap library
     * @return NOCC_Encoding ...
     */
    public static function getEncodingFromStructure($structure,$ishorde=false,$parts_info=array()) {
	if( ! $ishorde ) {
	        if (isset($structure->encoding)) {
	            return new NOCC_Encoding($structure->encoding);
	        }
	}
	else {
		$ne=0;
		$encoding="";
		$mimeID=$structure->getMimeId();
		if( isset($parts_info[$mimeID]) && isset($parts_info[$mimeID]['encoding']) ) {
			$encoding=$parts_info[$mimeID]['encoding'];
		}
		switch($encoding) {
			case("7bit"): $ne=0; break;
			case("8bit"): $ne=1; break;
			case("binary"): $ne=2; break;
			case("base64"): $ne=3; break;
			case("quoted-printable"): $ne=4; break;
			default: return new NOCC_Encoding(); break;
		}
		return new NOCC_Encoding($ne);
		
//		//does not exist in Horde/Imap
//		if( $structure->isAttachment() && $structure->getType() != "message/rfc822" ) {
//			return new NOCC_Encoding(3);  //base64
//		}
//		else if( $structure->getType() == "text/html" ) {
//			return new NOCC_Encoding(4);  //quoted-printable
//		}
//		else {
//			return new NOCC_Encoding(0);  //7bit default
//		}
	}
        return new NOCC_Encoding();
    }
}
?>
