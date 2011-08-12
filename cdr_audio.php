<?php
/**
 * @file
 * plays recording file
 */
if (isset($_REQUEST['cdr_file'])) {
	include_once("crypt.php");

	$REC_CRYPT_PASSWORD = (isset($amp_conf['AMPPLAYKEY']) && trim($amp_conf['AMPPLAYKEY']) != "")?trim($amp_conf['AMPPLAYKEY']):'TheWindCriesMary';
	$crypt = new Crypt();
	$opath = $_REQUEST['cdr_file'];
	$path = $crypt->decrypt($opath,$REC_CRYPT_PASSWORD);

	// Gather relevent info about file
	$size = filesize($path);
	$name = basename($path);
	$extension = strtolower(substr(strrchr($name,"."),1));
	// This will set the Content-Type to the appropriate setting for the file
	$ctype ='';
	switch( $extension ) {
		case "WAV":
			$ctype="audio/x-wav";
			break;
		case "wav":
			$ctype="audio/x-wav";
			break;
		case "ulaw":
			$ctype="audio/basic";
			break;
		case "alaw":
			$ctype="audio/x-alaw-basic";
			break;
		case "sln":
			$ctype="audio/x-wav";
			break;
		case "gsm":
			$ctype="audio/x-gsm";
			break;
		case "g729":
			$ctype="audio/x-g729";
			break;
	// not downloadable
		default: 
//		echo ("<b>404 File not found! foo</b>"); 
// TODO: what to do if none of the above work?
		break ;
	}

  $fp=fopen($path, "rb");
  if ($size && $ctype && $fp) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: wav file");
    header("Content-Type: " . $ctype);
    header("Content-Disposition: attachment; filename=" . $name);
    header("Content-Transfer-Encoding: binary");
    header("Content-length: " . $size);
    fpassthru($fp);
  } 
}

?>
