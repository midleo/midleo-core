<?php
$modulelist["image"]["name"]="Image manipulation module";
$modulelist["image"]["system"]=true;
class imageClass{
  public static function adjustBrightness($hex, $steps) {
    $steps = max(-255, min(255, $steps));
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
      $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }
    $color_parts = str_split($hex, 2);
   // $return = '#';
    foreach ($color_parts as $color) {
      $color   = hexdec($color);
      $color   = max(0,min(255,$color + $steps));
      $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
    }
    return $return;
  }
  public static function cropCentered($img, $w, $h){
    $cx = $img->getWidth() / 2;
    $cy = $img->getHeight() / 2;
    $x = $cx - $w / 2;
    $y = $cy - $h / 2;
    if ($x < 0) $x = 0;
    if ($y < 0) $y = 0;
    return $img->crop($x, $y, $w, $h);
  }
  public static function get_brightness($hex) {
    $hex = str_replace('#', '', $hex);
    $c_r = hexdec(substr($hex, 0, 2));
    $c_g = hexdec(substr($hex, 2, 2));
    $c_b = hexdec(substr($hex, 4, 2));
    return (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;
  }
  public static function getBytesFromHexString($hexdata)
  {
    for($count = 0; $count < strlen($hexdata); $count+=2)
        $bytes[] = chr(hexdec(substr($hexdata, $count, 2)));
    return implode($bytes);
  }
  public static function getImageMimeType($imagedata)
  {
    $imagemimetypes = array(
      "jpeg" => "FFD8",
      "png" => "89504E470D0A1A0A",
      "gif" => "474946",
      "bmp" => "424D",
      "tiff" => "4949",
      "tiff" => "4D4D"
    );
    foreach ($imagemimetypes as $mime => $hexbytes)
             {
               $bytes = imageClass::getBytesFromHexString($hexbytes);
               if (substr($imagedata, 0, strlen($bytes)) == $bytes)
                 return $mime;
             }
    return NULL;
  }
  public static function resizeavatar($theimputname){
    global $maindir;
    $uploadOk = 1;
    $randomid=md5(uniqid(microtime()).$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
    $target_dir = $maindir."/assets/images/users/";
    $base64img = str_replace('data:image/jpeg;base64,', '', $_POST[$theimputname]);
    $data = base64_decode($base64img);
    $target_file = $target_dir.$randomid.'.jpg';
    $imageFileType = imageClass::getImageMimeType($data);
    if (file_exists($target_file)) {
      $msg='File exists.';
      $uploadOk = 0;
    }
    if($imageFileType == "exe" || $imageFileType == "zip" || $imageFileType == "sh" || $imageFileType == "rar" || $imageFileType == "php" ) {
      $msg='You cannot upload exe, sh or archives.</div>';
      $uploadOk = 0;
    }
    if ($uploadOk == 0) { $msg='File was not added'; } else {
      if (!empty($base64img)) {
        file_put_contents($target_file, $data);
        $msg='Avatar was uploaded.';
        $pdo = pdodb::connect();
        $sql = "update users set avatar=? where mainuser=?";  
        $q = $pdo->prepare($sql);
        $q->execute(array(str_replace($maindir,"",$target_file),$_SESSION['user']));
        pdodb::disconnect();
      } else {
        $msg='Error.Try again!';
      }
    };
    return $msg;
  }
  public static function resizeimg($theimputname,$number,$purpose,$max_width=640, $max_height=485,$watermark=false){
    $quality = 100;
    $randomid=md5(uniqid(microtime()).$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
    $putq = "../assets/images/${purpose}/bigimg/${number}${randomid}.jpg";
    $path_small="../assets/images/${purpose}/${number}${randomid}.jpg";
    if($watermark){ $stampimg="../assets/images/watermark.png";}
    $theimagefile=$_FILES[$theimputname]['tmp_name']; 
    $theimagefiletype=$_FILES[$theimputname]['type'];
    if (@copy($theimagefile, $putq)) { 
      list($width, $height, $type) = getimagesize($putq);

    switch($theimagefiletype){
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            break;
 
        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $quality = 7;
            break;
 
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 80;
            break;

        case 'jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 80;
            break;
 
        default:
            return false;
            break;
    }
     
    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = $image_create($theimagefile);
     
    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    if($width_new > $width){
        $h_point = (($height - $height_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    }else{
        $w_point = (($width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }
   if ($watermark) {
    //add stamp to small picture
    $stamp = imagecreatefrompng($stampimg);
    $marge_right = 10;
    $marge_bottom = 10;
    $sx = imagesx($stamp);
    $sy = imagesy($stamp);
    imagecopy($dst_img, $stamp, imagesx($dst_img) - $sx - $marge_right, imagesy($dst_img) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
    //end stamp
    //add stamp to big picture
    imagecopy($src_img, $stamp, imagesx($src_img) - $sx - $marge_right, imagesy($src_img) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
    //end stamp
    $image($src_img, $putq, $quality);
    }
    $image($dst_img, $path_small, $quality);
    if($dst_img)imagedestroy($dst_img);
    if($src_img)imagedestroy($src_img);
    $newarray=array("image"=>$putq,"image_small"=>$path_small,"imgname"=>$number.$randomid.'.jpg');
    return $newarray;
    } else { exit; }
  }
}