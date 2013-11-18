<?php

ini_set('display_errors', '0');     
require_once 'HTML/Table.php';

function _str2barcodeBase64($str, $type = 'code128') {
	include_once "Image/Barcode2.php";
	
	$str = empty($str) && $str != 0 ? 'test' : $str;
	
	$imgtype = 'png';
	$bSendToBrowser = false;
	$height = 100;
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


$data = array();
for ($x = 0; $x < 3; $x++) {
	switch ($x) {
		case 0:
			$str_in = "${x}${x}${x}${x}${x}${x}${x}";
			$type_out = 'code39';
			break;
		case 1:
			$str_in = "${x}${x}${x}${x}${x}${x}${x}";
			$type_out = 'code128';
			break;
		case 2:
			$str_in = '12341234';
			$type_out = 'ean8';
			break;
		default:
			$str_in = "${x}${x}${x}${x}${x}${x}${x}";
			$type_out = ($x % 2 == 0 ) ? 'code39' : 'code128';
	}
	array_push($data, array(_str2barcodeBase64($str_in, $type_out), $type_out));
}

// BUILD TABLE
$attrs = array(
	'width' => '300',
	'class' => 'table table-bordered table-striped',
	'style' => 'background-color: rgba(255,255,255,0.7);'
);

$table = new HTML_Table($attrs);
$table->setAutoGrow(true);
$table->setAutoFill('---');
$table->setHeaderContents(0, 0, '#');
$table->setHeaderContents(0, 1, 'Image');
$table->setHeaderContents(0, 2, 'Type');

$rowAttrs = array(
	'style' => 'font-size: 18px; font-family: Arial; color: #f00; font-weight: bold;'
);
$colAttrs = array();
$table->setRowAttributes(0, $rowAttrs, true);
$table->setColAttributes(0, $rowAttrs);

for ($row = 0; $row < count($data); $row++) {
	$i = 0;
	$table->setHeaderContents($row+1, 0, (string)$row);
	$table->setCellContents($row+1, $i+1, $data[$row][0]);
	$table->setCellContents($row+1, $i+2, $data[$row][1]);
}

$table_out = $table->toHtml();

/*************************************************************************************************************************/
/*************************************************************************************************************************/
/*************************************************************************************************************************/
echo <<<HTML_OUTPUT

<!DOCTYPE html>
	<head>
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<h1>Barcodes</h1>
			</div>
			<div class="row">
				<pre class="col-lg-6" style="background-image: linear-gradient(180deg, #000 0%, #d33 50%)">
				$table_out
				</pre>
				<div class="col-lg-6">
					<p>A bunch of barcodes, <br />for barcode research!</p>
				</div>
			</div>
		</div>
		<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
	</body>
</html>

HTML_OUTPUT;

?>