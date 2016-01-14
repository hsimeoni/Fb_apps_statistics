<?php
// set dimensions
     $w = 202;
     $h = 20;
// create image
     $im = imagecreate($w, $h);
// set colours to be used
     $bg = imagecolorallocate($im, 0xE0, 0xE0, 0xE0);
     $black = imagecolorallocate($im, 0x00, 0x00, 0x00);
     $red  = imagecolorallocate($im, 0xFF, 0x00, 0x00);
     $green  = imagecolorallocate($im, 0x50, 0xB6, 0x30);  
// draw border
     imagerectangle($im, 0,0,$w-1,$h-1,$bg);                      // border uses background colur also
     imagecolortransparent($im, $bg);                             // now make bg colour transparent
// get value and max value from query string
     $val = isset($_GET['val']) ? $_GET['val'] : 0;
     $max = isset($_GET['max']) ? $_GET['max'] : 100; 
// calculate dimensions of inner bar
     $barw = $max ? floor(($w-2) * $val / $max) : 0;
     $barh = $h - 2;
// draw inner bar
	 if ($barw)
     {
        $barcolor = $red;
     	imagefilledrectangle($im, 1, 1, $barw, $barh, $barcolor);
     }
// send image header
     header("content-type: image/png");
// send png image
     imagepng($im);
     imagedestroy($im);
?>