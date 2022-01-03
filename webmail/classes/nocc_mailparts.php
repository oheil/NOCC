<?php
/**
 * Class for wrapping mail parts
 *
 * Copyright 2010-2011 Tim Gerundt <tim@gerundt.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: nocc_mailparts.php 2979 2021-12-15 10:06:49Z oheil $
 */

require_once 'nocc_mailstructure.php';
require_once 'nocc_mailpart.php';

/**
 * Wrapping mail parts
 *
 * @package    NOCC
 */
class NOCC_MailParts {
    /**
     * Body part
     * @var NOCC_MailPart
     * @access private
     */
    private $_bodyPart;

    /**
     * Attachments parts
     * @var array
     * @access private
     */
    private $_attachmentParts;
    
    /**
     * Initialize the wrapper
     * @param NOCC_MailStructure $mailstructure Mail structure
     * @todo Throw exception, if no vaild structure?
     */
    public function __construct($mailstructure) {
        $this->_bodyPart = null;
        $this->_attachmentParts = array();
        
        $parts = array();
        $this->_fillArrayWithParts($parts, $mailstructure);
	$body_index=-1;
	if( !empty($parts) ) {
		$not_attachment_parts=array();
		for( $i=0;$i<count($parts);$i++ ) {
			$bodyPart=$parts[$i];
			if( ! $bodyPart->getPartStructure()->isAttachment() ) {
				if( $bodyPart->getInternetMediaType()->isHtmlText() || $bodyPart->getInternetMediaType()->isPlainText() ) {
					$not_attachment_parts[]=$i;
				}
				if( $bodyPart->getInternetMediaType()->isHtmlText() ) {
					$body_index=$i;
				}
				if( $body_index==-1 && $bodyPart->getInternetMediaType()->isPlainText() ) {
					$body_index=$i;
				}
			}
		}
		if( $body_index>=0 ) {
			$this->_bodyPart=$parts[$body_index];
		}
		$count=0;
		foreach( $not_attachment_parts as $i ) {
			array_splice($parts,$i-$count,1);
			$count++;
		}
		$this->_attachmentParts = $parts;
	}
    }

    /**
     * Get the body part
     * @return NOCC_MailPart Body part
     */
    public function getBodyPart() {
        return $this->_bodyPart;
    }

    /**
     * Get the attachment parts
     * @return array Attachment parts
     */
    public function getAttachmentParts() {
        return $this->_attachmentParts;
    }

    /**
     * ...
     * Based on a function from matt@bonneau.net
     * @param array $parts Parts array
     * @param NOCC_MailStructure $mailstructure Mail structure
     * @param string $partNumber Part number
     * @access private
     * @todo Rewrite!
     */
    private function _fillArrayWithParts(&$parts, $mailstructure, $partNumber = '', $skip_message=false) {
        //$this_part = $mailstructure->getStructure();
	$mailstructure_parts=$mailstructure->getParts();
	$parts_info=$mailstructure->getPartsInfo();

	$isHorde = $mailstructure->isHorde();
        $internetMediaType = $mailstructure->getInternetMediaType();
        if ($internetMediaType->isMultipart()) { //if multipart...
		//$num_parts = count($this_part->parts);
		$num_parts = count($mailstructure_parts);
		$found_plain=false;
		$found_html=false;
		if( $internetMediaType->isAlternativeMultipart() ) {
			// check if alternative consists of PLAIN and HTML, if yes we skip the PLAIN
			for ($i = 0; $i < $num_parts; $i++) {
				if( ! $isHorde ) {
					//if( $this_part->parts[$i]->subtype == "PLAIN" ) {
					if( $mailstructure_parts[$i]->subtype == "PLAIN" ) {
						$found_plain=true;
					}
					//if( $this_part->parts[$i]->subtype == "HTML" ) {
					if( $mailstructure_parts[$i]->subtype == "HTML" ) {
						$found_html=true;
					}
				}
				else {
					$subtype=strtolower($mailstructure_parts[$i]->getSubType());
					if( $subtype == "plain" ) {
						$found_plain=true;
					}
					if( $subtype == "html" ) {
						$found_html=true;
					}
				}
			}
		}
		for ($i = 0; $i < $num_parts; $i++) {
			if( ! $isHorde ) {
				$subtype=strtolower($mailstructure_parts[$i]->subtype);
			}
			else {
				$subtype=strtolower($mailstructure_parts[$i]->getSubType());
			}
			if ($partNumber != '') {
				if (substr($partNumber, -1) != '.') $partNumber = $partNumber . '.';
			}
			if( $found_plain==true && $found_html==true ) {
				if( $subtype != "plain" ) {
					$this->_fillArrayWithParts($parts, new NOCC_MailStructure($mailstructure_parts[$i],$isHorde,$parts_info),$partNumber.($i+1),$skip_message);
				}
			}
			else {
				$this->_fillArrayWithParts($parts, new NOCC_MailStructure($mailstructure_parts[$i],$isHorde,$parts_info),$partNumber.($i+1),$skip_message);
			}
		}
        }
        else if( $internetMediaType->isMessage()) { //if message...
		if ($internetMediaType->isRfc822Message()) { //if RFC822 message...
			if (empty($partNumber)) {
				$partNumber = '1';
			}
			$part = new NOCC_MailPart($mailstructure, $partNumber, $isHorde);
			array_unshift($parts, $part);
			$skip_message=true;
		}
		$num_parts=-1;
		if( ! $isHorde && isset($mailstructure_parts[0]->parts) ) {
			$num_parts = count($mailstructure_parts[0]->parts);
		}
		if( $isHorde ) {
                	$num_parts = count($mailstructure_parts[0]->getParts());
		}
                for ($i = 0; $i < $num_parts; $i++) {
			if( ! $isHorde ) {
				$tmp_part=($mailstructure_parts[0]->parts)[$i];
			}
			else {
				$tmp_part=($mailstructure_parts[0]->getParts())[$i];
			}
			$this->_fillArrayWithParts($parts, new NOCC_MailStructure($tmp_part,$isHorde,$parts_info), $partNumber . '.' . ($i + 1),$skip_message);
		}
        }
        else {
		if (empty($partNumber)) {
			$partNumber = '1';
		}
		$part = new NOCC_MailPart($mailstructure, $partNumber, $isHorde);
		if( $mailstructure->isAttachment() || !$skip_message || ! $internetMediaType->isPlainOrHtmlText() ) { 
			array_unshift($parts, $part);
		}
        }
    }
}
?>
