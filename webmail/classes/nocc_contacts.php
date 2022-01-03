<?php
/**
 * Class for wrapping the contacts
 *
 * Copyright 2009-2011 Tim Gerundt <tim@gerundt.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: nocc_contacts.php 2629 2014-11-19 15:23:53Z oheil $
 */


/**
 * Wrapping the contacts
 *
 * @package    NOCC
 */
class NOCC_Contacts {

	/**
	 * add contact if email not already exist
	 * @param string all_to
	 * @return array
	 */
	public function add_contact($path='',$all_to='') {
		$contact_emails = array();
		$contacts=array();
		$all_to=trim($all_to);
		if( strlen($path)>0 && strlen($all_to)>0 ) {
			$contacts=$this->loadList($path);
			foreach( $contacts as $buffer ) {
				//$contact=explode("\t",$buffer);
				$contact=$buffer;
				$email=trim(strtolower($contact[3]));
				if( strlen($email) > 0 && ! array_key_exists($email,$contact_emails) ) {
					$contact_emails[$email]=1;
				}
			}

			$remove=array();
			$all=reformat_address_list($all_to,$remove,";");

			$all_emails=array();
			$all_firstnames=array();
			$all_lastnames=array();
			split_address_list($all,$all_emails,$all_firstnames,$all_lastnames,";");
			for($i=0;$i<count($all_emails);$i++) {
				$email=trim($all_emails[$i]);
				$check_email=strtolower($email);
				if( ! array_key_exists($check_email,$contact_emails) ) {
					//$line=trim($all_firstnames[$i])."\t".trim($all_lastnames[$i])."\t\t".$email;
					$line=array(trim($all_firstnames[$i]),trim($all_lastnames[$i]),"",$email,'',0);
					array_push($contacts,$line);
					$contact_emails[$email]=1;
				}
			}
		}
		usort($contacts,"NOCC_Contacts::cmp_sort_contacts_list");
		return($contacts);
	}

    /**
     * ...
     * @param string $path
     * @return array
     * @static
     * @todo Rewrite!
     */
    public static function loadList($path,&$lists=0) {
       $fp = @fopen($path, 'r');
       if (!$fp)
           return array();

       $contacts=array();

       while (!feof($fp)) {
           $buffer=trim(fgets($fp)," \n\r\0\x0B");
           if ($buffer != '') {
		$contact=explode("\t",$buffer);
		if( count($contact)<4 ) {
			$contact=array_pad($contact,-4,"");
		}
		if( count($contact)==4 ) {
			array_push($contact,''); //empty string
			array_push($contact,0); //0=not an email list,1=list of emails
		}
		if( is_array($lists) && $contact[5]==1 ) {
			array_push($lists,$contact);
		}
		array_push($contacts,$contact);
               //array_push($contacts, $buffer);
	   }
       }

	usort($contacts,"NOCC_Contacts::cmp_sort_contacts_list");

       fclose($fp);
       return $contacts;
    }

	/**
	 * create set of rulers for initials
	 * @param array $contacts list
	 * @param string $ruler_top start string of each ruler
	 * @param string $ruler_listonly end string of each ruler
	 * @return array $all_rulers all rulers as html code
	 * @return array $count2list start index of next initial
	 * @param bool $lists_only true if only show lists
	 */
	public static function create_rulers($contacts,$ruler_top,$ruler_listonly,&$all_rulers,&$count2list,$lists_only=false) {
		$initials=NOCC_Contacts::count_initials($contacts,$lists_only);
		$sum=0;
		$all_sum=0;
		$link_pos=$all_sum;
		$link="";
		//$count2list=array();
		$sub_rulers=array();
		foreach( $initials as $initial => $count ) {
			if( $sum>5 ) {
				$sum=$count;
				$sub_rulers[]=$link;
				$count2list[strval($link_pos)]=$link;
				$link_pos=$all_sum;
				$link="";
			}
			$sum+=$count;
			$all_sum+=$count;
			$link=$link.$initial;
		}
		$sub_rulers[]=$link;
		$count2list[strval($link_pos)]=$link;
		//$all_rulers=array();
		for($i=0;$i<count($sub_rulers);$i++) {
			//$sub_ruler='<a href="#top">'.convertLang2Html($html_contact_ruler_top).'</a>&nbsp;&nbsp;-&nbsp;&nbsp;';
			$sub_ruler=$ruler_top;
			$first=true;
			for($j=0;$j<count($sub_rulers);$j++) {
				$high_on='';
				$high_off='';
				if( $i == $j ) {
					$high_on='<span class="contactsListRulerHigh">';
					$high_off='</span>';
				}
				if( $first ) {
					$sub_ruler=$sub_ruler.$high_on.'<a href="#'.$sub_rulers[$j].'">&nbsp;&nbsp;'.$sub_rulers[$j].'&nbsp;&nbsp;</a>'.$high_off;
					$first=false;
				}
				else {
					$sub_ruler=$sub_ruler.'-'.$high_on.'<a href="#'.$sub_rulers[$j].'">&nbsp;&nbsp;'.$sub_rulers[$j].'&nbsp;&nbsp;</a>'.$high_off;
				}
			}
			$sub_ruler=$sub_ruler.$ruler_listonly;
			$all_rulers[]='<tr class="contactsListRuler"><td colspan="7"><a name="'.$sub_rulers[$i].'"></a>'.$sub_ruler.'</td></tr>';
		}
		return;
	}

	/**
	 * count the initials used in all contacts
	 * @param array contact list
	 * @param bool $lists_only true if only show lists
	 * @return array count of all found initials
	 */
	public static function count_initials($contacts,$lists_only=false) {
		$contacts_initials=array();
		for ($i = 0; $i < count($contacts); ++$i) {
			if( ! $lists_only || $contacts[$i][5]==1 ) {
				if( strlen($contacts[$i][2])>0 ) {
					$used=$contacts[$i][2];
				}
				else if( strlen($contacts[$i][1])>0 ) {
					$used=$contacts[$i][1];
				}
				else {
					$used=$contacts[$i][3];
				}
				$initial=mb_convert_case($used[0],MB_CASE_LOWER);
				if( isset($contacts_initials[$initial]) ) {
					$contacts_initials[$initial]=$contacts_initials[$initial]+1;
				}
				else {
					$contacts_initials[$initial]=1;
				}
			}
		}
		return $contacts_initials;
	}

	public static function cmp_sort_contacts_list($a,$b) {
		$r=0;
		$cmp_a="";
		//nick name highest sort priority
		if( strlen($a[2])>0 ) {
			$cmp_a=$a[2];
		}
		//last name next sort priority
		else if( strlen($a[1])>0 ) {
			$cmp_a=$a[1];
		}
		//last email last sort priority
		else if( strlen($a[3])>0 ) {
			$cmp_a=$a[3];
		}
		$cmp_b="";
		if( strlen($b[2])>0 ) {
			$cmp_b=$b[2];
		}
		else if( strlen($b[1])>0 ) {
			$cmp_b=$b[1];
		}
		else if( strlen($b[3])>0 ) {
			$cmp_b=$b[3];
		}
		$r=strcasecmp($cmp_a,$cmp_b);
		return $r;
	}

    /**
     * ...
     * @param string $path
     * @param array $contacts
     * @param object $conf
     * @param object $ev
     * @return bool
     * @static
     * @todo Rewrite!
     */
    public static function saveList($path, $contacts, $conf, &$ev) {
        include 'lang/' . $_SESSION['nocc_lang'] . '.php';
        if (file_exists($path) && !is_writable($path)) {
            $ev = new NoccException($html_err_file_contacts);
            return false;
        }
        if (!is_writeable($conf->prefs_dir)) {
            $ev = new NoccException($html_err_file_contacts);
            return false;
        }
        $fp = fopen($path, 'w');

        for ($i = 0; $i < count($contacts); ++$i) {
		$contact=$contacts[$i];
		$line='';
		for( $j=0;$j<count($contact)-1;$j++ ) {
			$line=$line.trim($contact[$j])."\t";
		}
		$line=$line.trim($contact[count($contact)-1]);
		if( trim($line)!='' ) {
			//fwrite($fp, $contacts[$i] . "\n");
			fwrite($fp, $line . "\n");
		}
        }

        fclose($fp);
        return true;
    }
}
?>
