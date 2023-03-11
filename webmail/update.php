<?php
/**
 * File for automatic system update
 *
 * Copyright 2016 Oliver Heil <oheil@heilbit.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: update.php 2610 2016-08-30 08:48:56Z oheil $
 */

require_once './common.php';

$currentdir=getcwd();

try {
	$pop = new nocc_imap();
	$pop->close();
}
catch (Exception $ex) {
	require_once './utils/proxy.php';
	Header('Location: ' . $conf->base_url . 'index.php?'.NOCC_Session::getUrlGetSession());
	return;
}

require './html/header.php';
require './html/menu_inbox.php';

$state_ok=true;

$start_time=time();

echo '<div class="messageList">';
echo '<p class="new-version-update-info">This section is available only in english.</p>';

if( $state_ok && ( !isset($_SESSION['auto_update']) || !$_SESSION['auto_update'] ) ) {
	echo '<p class="new-version-update-missing">Missing proper rights, contact your system administrator.</p>';
	$state_ok=false;
}

if( $state_ok && ini_get("allow_url_fopen")!=1 ) {
	echo '<p class="new-version-update-missing">php.ini "allow_url_fopen" is not set, contact your system administrator.</p>';
	unset($_SESSION['auto_update_new']);
	$state_ok=false;
}

if( $state_ok && ( !isset($_SESSION['auto_update_new']) || $_SESSION['auto_update_new']===2 ) ) {
	echo '<p class="new-version-update-missing">New NOCC version is undetermined, please try again.</p>';
	unset($_SESSION['auto_update_new']);
	$state_ok=false;
}

if( $state_ok && ( !isset($_SESSION['auto_update_new']) || $_SESSION['auto_update_new']===1 ) ) {
	echo '<p class="new-version-update-missing">No new NOCC version.</p>';
	$state_ok=false;
}

$nocc_update_running=false;
if( $state_ok && is_file("NOCC_UPDATE_RUNNING") ) {
	$nocc_update_running=true;
	echo '<p class="new-version-update-missing">Another update process is running!</p>';
	echo '<p class="new-version-update-missing">Please wait some minutes to make sure that a running process can finish.</p>';
	echo '<p class="new-version-update-missing">Then, if possible, delete the file NOCC_UPDATE_RUNNING in your NOCC root directory if it still exists or contact your system administrator.</p>';
	echo '<p class="new-version-update-missing">Go back into NOCC and check if an update is still needed.</p>';
	$state_ok=false;
}

$new_version="undefined";
if( $state_ok ) {
	$new_version=$_SESSION['auto_update_new'];
	$new_version=str_ireplace("-dev","",$new_version);
	$new_v=explode('.',$new_version);
	$old_v=explode('.',str_ireplace("-dev","",$conf->nocc_version));
	$old_dev_version=false;
	if( preg_match("/-dev/",$conf->nocc_version) ) {
		$old_dev_version=true;
	}
	if(
		($old_dev_version && $old_v[0]==$new_v[0] && $old_v[1]==$new_v[1] && $old_v[2]<=$new_v[2]) ||
		($old_v[0]==$new_v[0] && $old_v[1]==$new_v[1] && $old_v[2]<$new_v[2]) ||
		($old_v[0]==$new_v[0] && $old_v[1]<$new_v[1]) ||
		($old_v[0]<$new_v[0]) ||
		0
	) {
		echo '<p class="new-version-update-info">Udate from '.$conf->nocc_version.' to '.$new_version.' possible.</p>';
	}
	else {
		echo '<p class="new-version-update-missing">No update possible. Current version: '.$conf->nocc_version.', new version: '.$new_version.'</p>';
		unset($_SESSION['auto_update_new']);
		$state_ok=false;
	}
}

if( $state_ok ) {
	echo '<h2 class="new-version-update-info">Your version is: '.$conf->nocc_version.', Updating to version: '.$new_version.'</h2>';
	echo '<h2 class="new-version-update-info">Checking environment</h2>';

	$nocc_root=dirname(__FILE__);
	chdir($nocc_root);

	if( is_writable(".") ) {
		echo '<p class="new-version-update-ok">NOCC root directory is writable.</p>';
	}
	else {
		echo '<p class="new-version-update-missing">NOCC root directory is not writable.</p>';
		$state_ok=false;
	}

	$target_extract_dir="nocc-".$new_version;
	if( ! file_exists($target_extract_dir) ) {
		echo '<p class="new-version-update-ok">Directory '.$target_extract_dir.' does not exist. Good.</p>';
	}
	else {
		echo '<p class="new-version-update-missing">Directory '.$target_extract_dir.' should not exist.</p>';
		$state_ok=false;
	}

	if( strncasecmp(PHP_OS,"win",3)==0 ) {
		if( defined("ZIPARCHIVE::CREATE") ) {
			echo '<p class="new-version-update-ok">ZipArchive extension exists.</p>';
		}
		else {
			echo '<p class="new-version-update-missing">ZipArchive extension missing.</p>';
			$state_ok=false;
		}
	}
	if( defined("Phar::TAR") && Phar::TAR==2 && defined("Phar::ZIP") && Phar::ZIP==3 ) {
		echo '<p class="new-version-update-ok">Phar extension exists.</p>';
	}
	else {
		echo '<p class="new-version-update-missing">Phar extension missing.</p>';
		$state_ok=false;
	}
	try {
		$file_list=array(
			'action.php',
			'ckeditor.php',
			'ckeditor5.php',
			//'ckeditor_php4.php',
			//'ckeditor_php5.php',
			'addcgipath.sh',
			'common.php',
			'contacts_manager.php',
			'contacts.php',
			'delete.php',
			'download.php',
			'down_mail.php',
			'favicon.ico',
			'get_img.php',
			'help.php',
			'index.php',
			'logout.php',
			'robots.txt',
			'rss.php',
			'send.php',
			'update.php'
			);
		$file_list=array_merge($file_list,recursive_directory("htmlpurifier","/^.*(?<!\.$)(?<!\.\.$)(?<!~)(?<!\.htaccess)$/i"));
		$file_list=array_merge($file_list,["htmlpurifier"]);
		$file_list=array_merge($file_list,recursive_directory("ckeditor5","/^.*(?<!\.$)(?<!\.\.$)(?<!~)(?<!\.htaccess)$/i"));
		$file_list=array_merge($file_list,["ckeditor5"]);
		$file_list=array_merge($file_list,recursive_directory("ckeditor","/^.*(?<!\.$)(?<!\.\.$)(?<!~)(?<!\.htaccess)$/i"));
		$file_list=array_merge($file_list,["ckeditor"]);
		$file_list=array_merge($file_list,recursive_directory("classes","/^.*(?<!\.$)(?<!\.\.$)(?<!~)(?<!\.htaccess)$/i"));
		$file_list=array_merge($file_list,["classes"]);
		$file_list=array_merge($file_list,recursive_directory("config","/^.*(?<!\.$)(?<!\.\.$)(?<!~)(?<!\.htaccess)(?<!conf.php)$/i"));
		$file_list=array_merge($file_list,["config"]);
		$file_list=array_merge($file_list,recursive_directory("docs","/^.*(?<!\.$)(?<!\.\.$)(?<!~)(?<!\.htaccess)$/i"));
		$file_list=array_merge($file_list,["docs"]);
		$file_list=array_merge($file_list,recursive_directory("html","/^.*(?<!\.$)(?<!\.\.$)(?<!~)(?<!\.htaccess)$/i"));
		$file_list=array_merge($file_list,["html"]);
		$file_list=array_merge($file_list,recursive_directory("js","/^.*(?<!\.$)(?<!\.\.$)(?<!~)(?<!\.htaccess)$/i"));
		$file_list=array_merge($file_list,["js"]);
		$file_list=array_merge($file_list,recursive_directory("lang","/^.*(?<!\.$)(?<!\.\.$)(?<!~)(?<!\.htaccess)$/i"));
		$file_list=array_merge($file_list,["lang"]);
		$file_list=array_merge($file_list,recursive_directory("themes","/^.*(?<!\.$)(?<!\.\.$)(?<!~)(?<!\.htaccess)$/i"));
		$file_list=array_merge($file_list,["themes"]);
		$file_list=array_merge($file_list,recursive_directory("utils","/^.*(?<!\.$)(?<!\.\.$)(?<!~)(?<!\.htaccess)$/i"));
		$file_list=array_merge($file_list,["utils"]);
	}
	catch (Exception $ex) {
		$ev = new NoccException($ex->getMessage());
		require './html/error.php';
		$state_ok=false;
	}
	foreach($file_list as $file) {
		if( file_exists($file) && ! is_writable($file) ) {
			if( is_file($file) ) {
				echo '<p class="new-version-update-missing">File '.$file.' is not writable!</p>';
			}
			else if( is_dir($file) ) {
				echo '<p class="new-version-update-missing">Folder '.$file.' is not writable!</p>';
			}
			else {
				echo '<p class="new-version-update-missing">File '.$file.' can not be checked for being writable!</p>';
			}
			$state_ok=false;
		}
	}
	$number_of_files=count($file_list);
	if( $state_ok ) {
		echo '<p class="new-version-update-ok">All '.$number_of_files.' files are writable.</p>';
	}
	$max_execution_time=ini_get("max_execution_time");

	$end_time=time();
	$diff_time=$end_time-$start_time;
	if( $diff_time >= 1 ) {
		echo '<p class="new-version-update-info">Environment check took '.$diff_time.' seconds.</p>';
	}
	else {
		$diff_time=1;
		echo '<p class="new-version-update-info">Environment check took less than 1 second.</p>';
	}
	$min_execution_time=30*$diff_time+60;
	if( $max_execution_time>$min_execution_time ) {
		echo '<p class="new-version-update-ok">Your php max_execution_time of '.$max_execution_time.' seconds seems to be sufficient.</p>';
	}
	else {
		echo '<p class="new-version-update-missing">Your php max_execution_time of '.$max_execution_time.' could be to small. Please consider setting the php max_execution_time to at least '.($min_execution_time+20).' seconds.';
		echo '</p>';
		$state_ok=false;
	}
}

if( ! isset($_GET['doUpdate']) || $_GET['doUpdate']!=1 ) {
	if( $state_ok ) {
		//creating zip files with Phar has performance issues under windows
		if( strncasecmp(PHP_OS,"win",3)==0 ) {
			$archive_name="nocc-".$conf->nocc_version."_backup_".date("YmdHis").".zip";
		}
		else {
			$archive_name="nocc-".$conf->nocc_version."_backup_".date("YmdHis").".tar.gz";
		}
		//unpacking zip file with Phar does not work on windows
		if( strncasecmp(PHP_OS,"win",3)==0 ) {
			$download_link='http://downloads.sourceforge.net/project/nocc/NOCC/'.$new_version.'/nocc-'.$new_version.'.zip';
		}
		else {
			$download_link='http://downloads.sourceforge.net/project/nocc/NOCC/'.$new_version.'/nocc-'.$new_version.'.tar.gz';
		}
		echo '<h2 class="new-version-update-info"><a href="update.php?'.NOCC_Session::getUrlGetSession().'&doUpdate=1">Click here to automatically backup and update your existing NOCC installation. ONLY CLICK ONCE AND WAIT!</a></h2>';
		echo '<h3 class="new-version-update-info">Change Log:</h3>';
		$news=file_get_contents('http://nocc.sourceforge.net/docs/NEWS?v='.$conf->nocc_version);
		echo '<textarea cols="100" rows="20" readonly>'.$news.'</textarea>';
		echo '<h3 class="new-version-update-info">What will happen:</h3>';
		echo '<h4 class="new-version-update-info">Backup:</h3>';
		echo '<ul class="new-version-update-info">';
		echo '<li class="new-version-update-info">Your old NOCC files will be saved as archive in your NOCC root directory.</li>';
		echo '<li class="new-version-update-info">The archive file name will have the current date and time, e.g.: '.$archive_name.'</li>';
		echo '<li class="new-version-update-info">The file config/conf.php and .htaccess files will NOT be part of this archive.</li>';
		echo '<li class="new-version-update-info">You can download the archive or move it on the server to some place you wish.</li>';
		echo '<li class="new-version-update-info">If you need to go back just unpack the archive in your NOCC root directory overwriting the existing files.</li>';
		echo '</ul>';
		echo '<h4 class="new-version-update-info">Update:</h3>';
		echo '<ul class="new-version-update-info">';
		echo '<li class="new-version-update-info">The latest NOCC version will be downloaded automatically from the projects web site: <a href="'.$download_link.'">'.$download_link.'</a></li>';
		echo '<li class="new-version-update-info">The MD5 checksum will be checked.</li>';
		echo '<li class="new-version-update-info">The downloaded distribution archive will be unpacked overwriting your existing files.</li>';
		echo '</ul>';
	}
	else {
		echo '<h2 class="new-version-update-missing">Automatic update not possible.</h2>';
	}
}
else {
	error_log('NOCC: user <'.$_SESSION['nocc_user'].'@'.$_SESSION['nocc_domain'].'> initiated system update');
	if( $state_ok ) {
		if( $nocc_update_running ) {
			$state_ok=false;
		}
		else {
			$step=0;
			file_put_contents("NOCC_UPDATE_RUNNING",$step);
		}
	}
	if( $state_ok ) {
		echo '<h2 class="new-version-update-info">Creating backup</h2>';
		try {
			//creating zip files with Phar has performance issues under windows
			if( strncasecmp(PHP_OS,"win",3)==0 ) {
				$archive_name="nocc-".$conf->nocc_version."_backup_".date("YmdHis").".zip";
				echo '<p class="new-version-update-info">Creating backup file: '.$archive_name.'</p>';
				//$archive=new PharData($archive_name,FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO,"",Phar::ZIP);
				$archive=new ZipArchive();
				$archive->open($archive_name,ZIPARCHIVE::CREATE);
				foreach($file_list as $file) {
					if( is_dir($file) ) {
						//$number_of_files--;
						$archive->addEmptyDir($file);
					}
					else {
						$archive->addFile($file);
					}
				}
				//unset($archive);
				$archive->close();
				$archive=new PharData($archive_name);
				$number_of_files_check=$archive->count();
				unset($archive);
				if( $number_of_files==$number_of_files_check ) {
					echo '<p class="new-version-update-ok">Created archive of '.$number_of_files_check.' files successfully: <a href="'.$archive_name.'">'.$archive_name.'</a></p>';
				}
				else {
					echo '<p class="new-version-update-missing">Number of files in '.$archive_name.' is '.$number_of_files_check.', should be '.$number_of_files.'.</p>';
					unlink($archive_name);
					$state_ok=false;
				}
			}
			else {
				$archive_name="nocc-".$conf->nocc_version."_backup_".date("YmdHis").".tar";
				echo '<p class="new-version-update-info">Creating backup file: '.$archive_name.'</p>';
				$archive=new PharData($archive_name,FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO,"",Phar::TAR);
				foreach($file_list as $file) {
					//can not add directory name to archive under windows
					if( strncasecmp(PHP_OS,"win",3)==0 && is_dir($file) ) {
						$number_of_files--;
					}
					else {
						$archive->addFile($file);
					}
				}
				echo '<p class="new-version-update-info">Compressing archive: '.$archive_name.'.gz</p>';
				//the following failes in php 8.2 with Allowed memory size exhausted
				//$archive->compress(Phar::GZ);
				$gzfile=gzcompressfile($archive_name);
				unset($archive);
				echo '<p class="new-version-update-info">Removing file: '.$archive_name.'</p>';
				Phar::unlinkArchive($archive_name);

				$archive_name=gzdecompress($gzfile);
				$archive=new PharData($archive_name);

				$number_of_files_check=$archive->count();
				unset($archive);
				unlink($archive_name);

				if( $number_of_files==$number_of_files_check ) {
					echo '<p class="new-version-update-ok">Created archive of '.$number_of_files_check.' files successfully: <a href="'.$archive_name.'.gz">'.$archive_name.'.gz</a></p>';
				}
				else {
					echo '<p class="new-version-update-missing">Number of files in '.$archive_name.'.gz is '.$number_of_files_check.', should be '.$number_of_files.'.</p>';
					unlink($archive_name.'.gz');
					$state_ok=false;
				}
			}
		}
		catch (Exception $ex) {
			$ev = new NoccException($ex->getMessage());
			require './html/error.php';
			$state_ok=false;
		}
	}

	if( $state_ok ) {
		echo '<h2 class="new-version-update-info">Download new version</h2>';
		//creating zip files with Phar has performance issues under windows
		//unpacking zip file with Phar does not work on windows
		if( strncasecmp(PHP_OS,"win",3)==0 ) {
			$archive_name='nocc-'.$new_version.'.zip';
			$download_link='http://downloads.sourceforge.net/project/nocc/NOCC/'.$new_version.'/nocc-'.$new_version.'.zip';
		}
		else {
			$archive_name='nocc-'.$new_version.'.tar.gz';
			$download_link='http://downloads.sourceforge.net/project/nocc/NOCC/'.$new_version.'/nocc-'.$new_version.'.tar.gz';
		}
		if( ! is_file($archive_name) ) {
			$fpc=file_put_contents($archive_name,fopen($download_link,'r'));
			if( $fpc!=false ) {
				echo '<p class="new-version-update-ok">Downloaded new version archive successful.</p>';
			}
			else {
				echo '<p class="new-version-update-missing">Downloaded new version archive failed. You can savely try again.</p>';
				$state_ok=false;
			}
		}
		else {
			echo '<p class="new-version-update-ok">New version archive already exists, proceeding to checksum.</p>';
		}
	}

	if( $state_ok ) {
		echo '<h2 class="new-version-update-info">Checking MD5 checksum</h2>';
		$ckecksums=file_get_contents('http://nocc.sourceforge.net/checksums.txt');
		$matches=array();
		if( preg_match("/".$archive_name."\t(\S+)\R/",$ckecksums,$matches) ) {
			$md5sum=$matches[1];
			$md5sum_check=md5_file($archive_name);
			if( $md5sum==$md5sum_check) {
				echo '<p class="new-version-update-ok">MD5 checksums match: '.$md5sum.'. See also <a href="http://nocc.sourceforge.net/download/" target="_blank">http://nocc.sourceforge.net/download/</a></p>';
			}
			else {
				echo '<p class="new-version-update-missing">Failed md5 checksum match: '.$md5sum_check.' should be '.$md5sum.'.</p>';
				echo '<p class="new-version-update-missing">See also <a href="http://nocc.sourceforge.net/download/" target="_blank">http://nocc.sourceforge.net/download/</a></p>';
				$state_ok=false;
			}
		}
		else {
			echo '<p class="new-version-update-missing">Could not find md5 checksum. See also <a href="http://nocc.sourceforge.net/download/" target="_blank">http://nocc.sourceforge.net/download/</a></p>';
			$state_ok=false;
		}
	}

	if( $state_ok ) {
		echo '<h2 class="new-version-update-info">Unpacking new version</h2>';
		if( strncasecmp(PHP_OS,"win",3)==0 ) {
			$archive=new ZipArchive();
			$archive->open($archive_name);
			$archive->extractTo('.');
			$archive->close();
		}
		else {
			//the following failes in php 8.2 with Allowed memory size exhausted
			//$archive=new PharData($archive_name);

			$tar_archive_name=gzdecompress($archive_name);
			$archive=new PharData($tar_archive_name);
			
			$archive->extractTo('.');
			unset($archive);
			unlink($tar_archive_name);
		}
		chdir($target_extract_dir);
		$file_list=recursive_directory(".","/^.*(?<!\.$)(?<!\.\.$)$/");
		chdir($nocc_root);
		foreach($file_list as $file) {
			$file=preg_replace("/^(\.\/|\.\\\)/","",$file);
			if( preg_match("/\.htaccess/",$file) ) {
				if( is_file($file) ) {
					$new_htaccess=file_get_contents($target_extract_dir.DIRECTORY_SEPARATOR.$file);
					$old_htaccess=file_get_contents($file);
					if( $new_htaccess != $old_htaccess ) {
						if( ! is_dir(dirname($file.".nocc-".$new_version)) ) {
							mkdir(dirname($file.".nocc-".$new_version),0777,true);
						}
						if( ! copy($target_extract_dir.DIRECTORY_SEPARATOR.$file,$file.".nocc-".$new_version) ) {
							echo '<p class="new-version-update-missing">Failed creating file: '.$file.'.nocc-'.$new_version.'. Try manually.</p>';
						}
						echo '<p class="new-version-update-missing">Important:<br />';
						echo '&nbsp;&nbsp;Integrate content of new file '.$file.".nocc-".$new_version.' into '.$file.'</p>';
					}
					else {
						echo '<p class="new-version-update-ok">New file '.$target_extract_dir.DIRECTORY_SEPARATOR.$file.' and '.$file.' match, nothing to do.</p>';
					}
					unlink($target_extract_dir.DIRECTORY_SEPARATOR.$file);
				}
				else {
					if( ! is_dir(dirname($file)) ) {
						mkdir(dirname($file),0777,true);
					}
			 		if( ! copy($target_extract_dir.DIRECTORY_SEPARATOR.$file,$file) ) {
						echo '<p class="new-version-update-missing">Failed to copy file: '.$file.'. Try manually.</p>';
					}
					unlink($target_extract_dir.DIRECTORY_SEPARATOR.$file);
				}
			}
			else {
				if( is_file($target_extract_dir.DIRECTORY_SEPARATOR.$file) ) {
					if( ! is_dir(dirname($file)) ) {
						mkdir(dirname($file),0777,true);
					}
					if( ! copy($target_extract_dir.DIRECTORY_SEPARATOR.$file,$file) ) {
						echo '<p class="new-version-update-missing">Failed to copy file: '.$file.'. Try manually.</p>';
					}
					unlink($target_extract_dir.DIRECTORY_SEPARATOR.$file);
				}
				else if( is_dir($target_extract_dir.DIRECTORY_SEPARATOR.$file) ) {
					rmdir($target_extract_dir.DIRECTORY_SEPARATOR.$file);
				}
			}
		}
		rmdir($target_extract_dir);
	}

	if( $state_ok ) {
		echo '<h2 class="new-version-update-info">Removing deprecated files</h2>';
		$file_list=array(
			'ckeditor_php4.php',
			'ckeditor_php5.php',
		);
		foreach($file_list as $file) {
			if( file_exists($file) ) {
				if( ! unlink($file) ) {
					echo '<p class="new-version-update-missing">Failed to remove deprecated file: '.$file.'. Try manually.</p>';
				}			
				else {
					echo '<p class="new-version-update-ok">File '.$file.' removed.</p>';
				}
			}
		}
	}

	$end_time=time();
	$diff_time=$end_time-$start_time;

	if( ! $state_ok ) {
		echo '<h2 class="new-version-update-missing">Update failed.</h2>';
	}
	else {
		echo '<h2 class="new-version-update-info">Update took '.$diff_time.' seconds to finish successfully.</p>';
		unset($_SESSION['auto_update_new']);
	}

	if( ! $nocc_update_running && is_file("NOCC_UPDATE_RUNNING") ) {
		unlink("NOCC_UPDATE_RUNNING");
	}
}
echo '</div>';
chdir($currentdir);

require './html/menu_inbox.php';
require './html/footer.php';

// https://stackoverflow.com/questions/6073397/how-do-you-create-a-gz-file-using-php/56140427#56140427
function gzcompressfile(string $inFilename, int $level = 9): string
{
    // Is the file gzipped already?
    $extension = pathinfo($inFilename, PATHINFO_EXTENSION);
    if ($extension == "gz") {
        return $inFilename;
    }

    // Open input file
    $inFile = fopen($inFilename, "rb");
    if ($inFile === false) {
        throw new \Exception("Unable to open input file: $inFilename");
    }

    // Open output file
    $gzFilename = $inFilename.".gz";
    $mode = "wb".$level;
    $gzFile = gzopen($gzFilename, $mode);
    if ($gzFile === false) {
        fclose($inFile);
        throw new \Exception("Unable to open output file: $gzFilename");
    }

    // Stream copy
    $length = 512 * 1024; // 512 kB
    while (!feof($inFile)) {
        gzwrite($gzFile, fread($inFile, $length));
    }

    // Close files
    fclose($inFile);
    gzclose($gzFile);

    // Return the new filename
    return $gzFilename;
}
// https://stackoverflow.com/questions/3293121/how-can-i-unzip-a-gz-file-with-php
function gzdecompress(string $file_name): string
{
	// Raising this value may increase performance
	$buffer_size = 4096; // read 4kb at a time
	$out_file_name = str_replace('.gz', '', $file_name);

	// Open our files (in binary mode)
	$file = gzopen($file_name, 'rb');
	$out_file = fopen($out_file_name, 'wb');

	// Keep repeating until the end of the input file
	while(!gzeof($file)) {
	    // Read buffer-size bytes
	    // Both fwrite and gzread and binary-safe
	    fwrite($out_file, gzread($file, $buffer_size));
	}

	// Files are done, close files
	fclose($out_file);
	gzclose($file);
	return $out_file_name;
}

