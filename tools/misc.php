<?php
function login($email, $password) {
    return MySqlSelectSingle("users", "*", "email='$email' AND password='$password'");
}

/* Files */
function cleanFileName($filename) {
	return preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
}
function getImage($tableName, $itemId, $size = "main") {
	$html = "";
	$result = MySqlSelectSingle("_images", "*", "tableName='".$tableName."' AND itemId=".$itemId);
	if ($result) {
		$html = "<img src='".HOST."/images/$size/".$result['name']."' class='img-responsive'>";
	}
	return $html;
}
function getImages($tableName, $itemId, $size = "crop") {
	$html = array();
	$result = MySqlSelect("_images", "*", "tableName='".$tableName."' AND itemId=".$itemId, false, "user-defined-order");
	if ($result) foreach ($result as $r) {
		$html[] = "<img src='".HOST."/images/$size/".$r['name']."' class='img-responsive'>";
	}
	return $html;
}
function ftpSendImageFile($dst, $src) {
	$result = false;
	$connection = ftp_connect(FTPHOST);
	$login = ftp_login($connection, FTPUSER, FTPPASS);
	if ($connection && $login) {
		if (FTPHOST == "localhost"){
			$src = "../".$src;
		}
		$upload = ftp_put($connection, FTPROOT.$dst, $src, FTP_BINARY);
		if ($upload) {$result = true;}
		ftp_close($connection);
	}
	return $result;
}

function ftpGetImageFile($dst, $src) {
	$result = false;
	$connection = ftp_connect(FTPHOST);
	$login = ftp_login($connection, FTPUSER, FTPPASS);
	if ($connection && $login) {
		if (FTPHOST == "localhost"){
			$dst = "../".$dst;
		}
		$download = ftp_get($connection, $dst, FTPROOT.$src, FTP_BINARY);
		if ($download) {$result = true;}
		ftp_close($connection);
	}
	return $result;
}

function ftpDeleteFile($src) {
	$result = false;
	$connection = ftp_connect(FTPHOST);
	$login = ftp_login($connection, FTPUSER, FTPPASS);
	if ($connection && $login) {
		$delete = ftp_delete($connection, FTPROOT.$src);
		if ($delete) {$result = true;}
		ftp_close($connection);
	}
	return $result;
}

// -------------- RESIZE FUNCTION -------------
// Function for resizing any jpg, gif, or png image files
function imgResize($target, $newcopy, $w, $h, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);
    $scale_ratio = $w_orig / $h_orig;
    if (($w / $h) > $scale_ratio) {
           $w = $h * $scale_ratio;
    } else {
           $h = $w / $scale_ratio;
    }
    $img = "";
    $ext = strtolower($ext);
    if ($ext == "gif"){ 
    $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
    $img = imagecreatefrompng($target);
    } else { 
    $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
    if ($ext == "gif"){ 
        imagegif($tci, $newcopy);
    } else if($ext =="png"){ 
        imagepng($tci, $newcopy);
    } else { 
        imagejpeg($tci, $newcopy, 84);
    }
}
// -------------- CROP FUNCTION --------------
// Function for creating a true thumbnail cropping from any jpg, gif, or png image files
function imgCrop($target, $newcopy, $cropX1, $cropY1, $cropX2, $cropY2, $w, $h, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);
    $ext = strtolower($ext);
    $img = "";
    if ($ext == "gif"){ 
    	$img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
    	$img = imagecreatefrompng($target);
    } else { 
    	$img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    //imagecopyresampled($tci, $img, 0, 0, $src_x, $src_y, $w, $h, $w, $h);
    imagecopyresampled($tci, $img, 0, 0, $cropX1, $cropY1, $w, $h, $cropX2, $cropY2);
    if ($ext == "gif"){ 
        imagegif($tci, $newcopy);
    } else if($ext =="png"){ 
        imagepng($tci, $newcopy);
    } else { 
        imagejpeg($tci, $newcopy, 84);
    }
}

function imgCropOriginal($target, $newcopy, $w, $h, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);
    $src_x = ($w_orig / 2) - ($w / 2);
    $src_y = ($h_orig / 2) - ($h / 2);
    $ext = strtolower($ext);
    $img = "";
    if ($ext == "gif"){ 
    $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
    $img = imagecreatefrompng($target);
    } else { 
    $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    imagecopyresampled($tci, $img, 0, 0, $src_x, $src_y, $w, $h, $w, $h);
    if ($ext == "gif"){ 
        imagegif($tci, $newcopy);
    } else if($ext =="png"){ 
        imagepng($tci, $newcopy);
    } else { 
        imagejpeg($tci, $newcopy, 84);
    }
}
?>