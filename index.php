<?php
function _str2barcodeBase64($str, $type = 'code128') {
  include_once "Image/Barcode2.php";

  $str = empty($str) && $str != 0 ? 'test' : $str;
  
  $imgtype = 'png';
  $bSendToBrowser = false;
  $height = 200;
  $width = 2;
$showtext = true;
  $rotation = 0;
  $img = Image_Barcode2::draw($str, $type, $imgtype, $bSendToBrowser, $height, $width, $showtext, $rotation);

  ob_start();
    imagepng($img);
    $imgBase64 = base64_encode(ob_get_contents());
  ob_end_clean();

  imagedestroy($img);

  return '<img style="clear:left;" src="data:image/' . $imgtype . ';base64,' . $imgBase64 . '">';
}

$images = array();
for ($x = 0; $x < 20; $x++) {
	 array_push($images, _str2barcodeBase64("${x}${x}${x}${x}${x}", ($x % 2 == 0 ) ? 'code39' : 'code128'));
}
$images_out = print_r($images, true);
$out = <<<ABC

<!DOCTYPE html>
	<head></head>
	<body>
		<h1>Barcodes</h1>
		<pre>
		$images_out
		</pre>
	</body>
</html>

ABC;
echo $out;
?>