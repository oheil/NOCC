<?php
/**
 * Adds additional string formatting for better i18n support
 *
 * Copyright 2001 Nicolas Chalanset <nicocha@free.fr>
 * Copyright 2001 Olivier Cahagne <cahagn_o@epita.fr>
 * Copyright 2008-2011 Tim Gerundt <tim@gerundt.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @subpackage Utilities
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id$
 */


  function i18n_message($func_str_translation = null, $func_str_insert = null, $func_convert = 1) {
  
    if ((!is_null($func_str_translation)) && (!is_null($func_str_insert))) {

	$func_str_translation=plural($func_str_translation,$func_str_insert);

      if (is_array($func_str_insert)) {
        $func_output = vsprintf($func_str_translation, $func_str_insert);
      } else {
        $func_output = sprintf($func_str_translation, $func_str_insert);
      }
      
      if ($func_convert == 1) {
        $func_output = convertLang2Html($func_output);
      }
      
      return $func_output;
    }

    return false;
  }



function plural($format="",$params=array(),$depth=0) {
	if( ! is_array($params) ) {
		$params=array($params);
	}
	$out=$format;

	$matches=array();
	if( preg_match('/^(.*?)(\{\{PLURAL:\$\d+\|.*\}\})(.*?)$/U',$format,$matches) ) {
		$head=$matches[1];
		$mid=$matches[2];
		$tail=$matches[3];
		$format=$head."#####".$depth."#####".$tail;
		$format=plural($format,$params,$depth+1);
		$format=str_replace("#####".$depth."#####",$mid,$format);
	}

	$matches=array();
	if( preg_match('/^(.*)\{\{PLURAL:\$(\d+)\|(.*?)\}\}(.*)$/U',$format,$matches) ) {
		$param_index=intval($matches[2])-1;
		if( ! is_integer($param_index) ) {
			error_log("NOCC: error:wrong PLURAL syntax (e.g. PLURAL:$1) in <".$format.">");
			$param_index=1;
		}
		$head=$matches[1];
		$tail=$matches[4];
		$choices=preg_split('/\|/',$matches[3]);
		$values=array();
		$results=array();
		foreach($choices as $choice) {
			$value_matches=array();
			if( preg_match('/^(.*?)=(.*)$/',$choice,$value_matches) ) {
				$values[]=$value_matches[1];
				$results[]=$value_matches[2];
			}
			else {
				$values[]=null;
				$results[]=$choice;
			}
		}
		$key=$params[$param_index];
		if( in_array($key,$values) ) {
			$choice_index=array_search($key,$values);
			$out=$head.$results[$choice_index].$tail;
			$found=true;
		}
		if( ! $found ) {
			if( $key==1 || $key=="1" ) {
				if( isset($results[0]) ) {
					$out=$head.$results[0].$tail;
					$found=true;
				}
			}
		}
		if( ! $found ) {
			if( isset($results[count($results)-1]) ) {
				$out=$head.$results[count($results)-1].$tail;
				$found=true;
			}
		}
		if( ! $found ) {
			error_log("NOCC: error:wrong PLURAL syntax in <".$format.">");
		}
	}
	else {
		if( preg_match('/\{\{PLURAL:.*\}\}/',$format) ) {
			error_log("NOCC: error:wrong PLURAL syntax in <".$format.">");
		}
	}

	return $out;
}



