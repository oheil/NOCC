<?php
/**
 * Crypt functions
 *
 * Copyright 2001 Nicolas Chalanset <nicocha@free.fr>
 * Copyright 2001 Olivier Cahagne <cahagn_o@epita.fr>
 * Copyright 2005 Arnaud Boudou <goddess_skuld@users.sourceforge.net>
 * Copyright 2008-2011 Tim Gerundt <tim@gerundt.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @subpackage Utilities
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: crypt.php 3097 2023-09-05 10:44:26Z oheil $
 */


/**
 * Returns NTLM Type 3 message
 * @param string $type2message the Type 2 message from the server as a binary string
 * @param string $realm the clients realm/domain (not empty)
 * @param string $workstation the clients host name (not empty)
 * @param string $user the SMTP user name
 * @param string $password the users password
 * @return string $message the binary string composed NTLM Type 3 message to send to the server
 */
function NTLM_type3message($type2message="", $realm="", $workstation="", $user="", $password="") {
	// https://davenport.sourceforge.net/ntlm.html
	if( strlen($realm)==0 ) {
		$realm="unknown";
	}
	if( strlen($workstation)==0 ) {
		$workstation="unknown";
	}
	if( strlen($type2message)==0 ) {
		return "";
	}
	
	$tn_sbuffer=unpack("vlength/vsize/Voffset",substr($type2message,12,8));
	$flags=substr($type2message,20,4);

	$domain=$realm;
	if( $flags && 0x0100 ) {  // Flag Target Type Domain (0x00010000) in little endian: 0x00000100
		$tn_data=substr($type2message,$tn_sbuffer['offset'],$tn_sbuffer['size']);
		$domain=mb_convert_encoding($tn_data,"ASCII","UCS-2LE");
	}

	$challenge=substr($type2message,24,8);

	$pw_uni=mb_convert_encoding($password,"UCS-2LE");

	$message="";

	if( extension_loaded("openssl") && function_exists("openssl_encrypt")
		&& extension_loaded("hash") && function_exists("hash")
		&& in_array("md4",hash_algos())
		&& count( preg_grep("/^des-ecb$/i",openssl_get_cipher_methods(true)) ) > 0
	) {
		$md4=hash("md4", $pw_uni, false);
		$md4=hex2bin($md4);
		$pad=$md4.str_repeat(chr(0),21-strlen($md4));

		$iv_size=openssl_cipher_iv_length("des-ecb");
		$iv="";
		for($i=0;$i<21;$i+=7) {
			$packed="";
			for($p=$i;$p<$i+7;$p++) {
				$packed.=str_pad(decbin(ord(substr($pad,$p,1))),8,"0",STR_PAD_LEFT);
			}
			$key="";
			for($p=0;$p<strlen($packed);$p+=7) {
				$s=substr($packed,$p,7);
				$b=$s.((substr_count($s,"1") % 2) ? "0" : "1");
				$key.=chr(bindec($b));
			}
			$message.=openssl_encrypt($challenge,"des-ecb",$key,$options=OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING,$iv);
		}
	}
	else if( extension_loaded("mcrypt") && function_exists("mcrypt_encrypt")
		&& extension_loaded("hash") && function_exists("hash")
		&& in_array("md4",hash_algos())
	) {
		$md4=hash("md4", $pw_uni, false);
		$md4=hex2bin($md4);
		$pad=$md4.str_repeat(chr(0),21-strlen($md4));

		$iv_size=mcrypt_get_iv_size(MCRYPT_DES,MCRYPT_MODE_ECB);
		$iv=mcrypt_create_iv($iv_size,MCRYPT_RAND);
		for($i=0;$i<21;$i+=7) {
			$packed="";
			for($p=$i;$p<$i+7;$p++) {
				$packed.=str_pad(decbin(ord(substr($pad,$p,1))),8,"0",STR_PAD_LEFT);
			}
			$key="";
			for($p=0;$p<strlen($packed);$p+=7) {
				$s=substr($packed,$p,7);
				$b=$s.((substr_count($s,"1") % 2) ? "0" : "1");
				$key.=chr(bindec($b));
			}
			$message.=mcrypt_encrypt(MCRYPT_DES,$key,$challenge,MCRYPT_MODE_ECB,$iv);
		}
	}
	else {
		return "";
	}

	$r_unicode=mb_convert_encoding($domain,"UCS-2LE");
	$r_length=strlen($r_unicode);
	$r_offset=64;
	$u_unicode=mb_convert_encoding($user,"UCS-2LE");
	$u_length=strlen($u_unicode);
	$u_offset=$r_offset+$r_length;
	$ws_unicode=mb_convert_encoding($workstation,"UCS-2LE");
	$ws_length=strlen($ws_unicode);
	$ws_offset=$u_offset+$u_length;
	$lm=mb_convert_encoding("","UCS-2LE");
	$lm_length=strlen($lm);
	$lm_offset=$ws_offset+$ws_length;
	$ntlm=$message;
	$ntlm_length=strlen($ntlm);
	$ntlm_offset=$lm_offset+$lm_length;
	$session="";
	$session_length=strlen($session);
	$session_offset=$ntlm_offset+$ntlm_length;

	$message="NTLMSSP\0".
	"\x03\x00\x00\x00".
	pack("v",$lm_length).
	pack("v",$lm_length).
	pack("V",$lm_offset).
	pack("v",$ntlm_length).
	pack("v",$ntlm_length).
	pack("V",$ntlm_offset).
	pack("v",$r_length).
	pack("v",$r_length).
	pack("V",$r_offset).
	pack("v",$u_length).
	pack("v",$u_length).
	pack("V",$u_offset).
	pack("v",$ws_length).
	pack("v",$ws_length).
	pack("V",$ws_offset).
	pack("v",$session_length).
	pack("v",$session_length).
	pack("V",$session_offset).
	"\x01\x02\x00\x00".
	$r_unicode.
	$u_unicode.
	$ws_unicode.
	$lm.
	$ntlm;

	return $message;
}

/**
 * Returns NTLM Type 1 message
 * @param string $realm the clients realm/domain (not empty)
 * @param string $workstation the clients host name (not empty)
 * @return string $message the binary string composed NTLM Type 1 message to send to the server
 */
function NTLM_type1message($realm="", $workstation="") {
	// https://davenport.sourceforge.net/ntlm.html
	if( strlen($realm)==0 ) {
		$realm="unknown";
	}
	if( strlen($workstation)==0 ) {
		$workstation="unknown";
	}
	$r_length=strlen($realm);
	$ws_length=strlen($workstation);
	$ws_offset=32;
	$r_offset=$ws_offset+$ws_length;
	$message="NTLMSSP\0".
		"\x01\x00\x00\x00".
		"\x07\x32\x00\x00".
		pack("v",$r_length).
		pack("v",$r_length).
		pack("V",$r_offset).
		pack("v",$ws_length).
		pack("v",$ws_length).
		pack("V",$ws_offset).
		$workstation.
		$realm;
	return $message;
}

function encrXOR($string, $key) {
    for ($i=0; $i<strlen($string); $i++) {
        for ($j=0; $j<strlen($key); $j++) {
            $string[$i] = $string[$i]^$key[$j];
        }
    }
    return $string;
}

function decrXOR($string, $key) {
    for ($i=0; $i<strlen($string); $i++) {
        for ($j=0; $j<strlen($key); $j++) {
            $string[$i] = $key[$j]^$string[$i];
        }
    }
    return $string;
}

/**
 * Returns encrypted password
 * @param string $passwd Password
 * @param string $rkey Master key
 * @return string Encrypted password
 */
function encpass($passwd, $rkey) {
	if( extension_loaded("openssl") && function_exists("openssl_encrypt") && function_exists("openssl_decrypt")
		&& extension_loaded("hash") && function_exists("hash")
		&& in_array("sha256",hash_algos())
		&& count( preg_grep("/^aes256$/i",openssl_get_cipher_methods(true)) ) > 0
	) {
		$key=hash("SHA256",$rkey,true);
		$iv_size=openssl_cipher_iv_length("AES256");
		$iv=openssl_random_pseudo_bytes($iv_size);
		$encpasswd=openssl_encrypt($passwd,"AES256",$key,$options=0,$iv);
		$encpasswd=base64_encode($iv.$encpasswd);
	}
	else if( extension_loaded("sodium") && function_exists("sodium_crypto_secretbox") && function_exists("sodium_memzero")
		&& extension_loaded("hash") && function_exists("hash")
		&& in_array("sha256",hash_algos())
	) {
		$key=hash("SHA256",$rkey,true);
		$nonce = random_bytes(
			SODIUM_CRYPTO_SECRETBOX_NONCEBYTES
		);
		$encpasswd=base64_encode($nonce.sodium_crypto_secretbox($passwd,$nonce,$key));
		sodium_memzero($passwd);
		sodium_memzero($key);
	}
	else if( extension_loaded("mcrypt") && function_exists("mcrypt_encrypt") && function_exists("mcrypt_decrypt")
		&& extension_loaded("hash") && function_exists("hash")
		&& in_array("sha256",hash_algos())
		&& in_array("rijndael-128",mcrypt_list_algorithms())
	) {
		$key=hash("SHA256",$rkey,true);
		$iv_size=mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC);
		$iv=mcrypt_create_iv($iv_size,MCRYPT_RAND);
		$encpasswd=base64_encode($iv.mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$key,$passwd,MCRYPT_MODE_CBC,$iv));
	}
	else {
		$encpasswd = false; //don't allow unsecure encyption anymore
	}
	return $encpasswd;
}

/**
 * Returns decrypted password
 * @param string $cipher Cipher
 * @param string $rkey Master key
 * @return string Decrypted password
 */
function decpass($cipher, $rkey) {
	if( extension_loaded("openssl") && function_exists("openssl_encrypt") && function_exists("openssl_decrypt")
		&& extension_loaded("hash") && function_exists("hash")
		&& in_array("sha256",hash_algos())
		&& count( preg_grep("/^aes256$/i",openssl_get_cipher_methods(true)) )>0
	) {
		$key=hash("SHA256",$rkey,true);
		$iv_size=openssl_cipher_iv_length("AES256");
		$ciphertext_dec=base64_decode($cipher);
		$iv_dec=substr($ciphertext_dec,0,$iv_size);
		$ciphertext_dec=substr($ciphertext_dec,$iv_size);
		$decpasswd=openssl_decrypt($ciphertext_dec,"AES256",$key,$options=0,$iv_dec);
	}
	else if( extension_loaded("sodium") && function_exists("sodium_crypto_secretbox") && function_exists("sodium_memzero")
		&& extension_loaded("hash") && function_exists("hash")
		&& in_array("sha256",hash_algos())
	) {
		$key=hash("SHA256",$rkey,true);
		$decoded = base64_decode($cipher);
		$nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
		$ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
		$decpasswd = sodium_crypto_secretbox_open( $ciphertext, $nonce, $key);
		sodium_memzero($ciphertext);
		sodium_memzero($key);
	}
	else if( extension_loaded("mcrypt") && function_exists("mcrypt_encrypt") && function_exists("mcrypt_decrypt")
		&& extension_loaded("hash") && function_exists("hash")
		&& in_array("sha256",hash_algos())
		&& in_array("rijndael-128",mcrypt_list_algorithms())
	) {
		$key=hash("SHA256",$rkey,true);
		$iv_size=mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC);
		$ciphertext_dec=base64_decode($cipher);
		$iv_dec=substr($ciphertext_dec,0,$iv_size);
		$ciphertext_dec=substr($ciphertext_dec,$iv_size);
		$decpasswd=mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$key,$ciphertext_dec,MCRYPT_MODE_CBC,$iv_dec);
	}
	else {
		//don't allow unsecure encyption anymore
		$decpasswd = false;
	}
	return $decpasswd;
}
