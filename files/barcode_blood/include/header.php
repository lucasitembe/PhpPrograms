<?php
include("../includes/connection.php");

if (!defined('IN_CB')) { die('You are not allowed to access to this page.'); }

if (version_compare(phpversion(), '5.0.0', '>=') !== true) {
    exit('Sorry, but you have to run this script with PHP5... You currently have the version <b>' . phpversion() . '</b>.');
}

if (!function_exists('imagecreate')) {
    exit('Sorry, make sure you have the GD extension installed before running this script.');
}

include_once('function.php');

// FileName & Extension
$system_temp_array = explode('/', $_SERVER['PHP_SELF']);
$filename = $system_temp_array[count($system_temp_array) - 1];
$system_temp_array2 = explode('.', $filename);
$availableBarcodes = listBarcodes();
$barcodeName = findValueFromKey($availableBarcodes, $filename);
$code = $system_temp_array2[0];

// Check if the code is valid
if (file_exists('config' . DIRECTORY_SEPARATOR . $code . '.php')) {
    include_once('config' . DIRECTORY_SEPARATOR . $code . '.php');
}

$default_value = array();
$default_value['filetype'] = 'PNG';
$default_value['dpi'] = 300;
$default_value['scale'] = isset($defaultScale) ? $defaultScale : 4;
$default_value['rotation'] =270;
$default_value['font_family'] = 0;
$default_value['font_size'] = 10;
$default_value['text'] = '';
$default_value['a1'] = '';
$default_value['a2'] = '';
$default_value['a3'] = '';

$filetype = isset($_POST['filetype']) ? $_POST['filetype'] : $default_value['filetype'];
$dpi = isset($_POST['dpi']) ? $_POST['dpi'] : $default_value['dpi'];
$scale = intval(isset($_POST['scale']) ? $_POST['scale'] : $default_value['scale']);
$rotation = intval(isset($_POST['rotation']) ? $_POST['rotation'] : $default_value['rotation']);
$font_family = isset($_POST['font_family']) ? $_POST['font_family'] : $default_value['font_family'];
$font_size = intval(isset($_POST['font_size']) ? $_POST['font_size'] : $default_value['font_size']);

registerImageKey('filetype', $filetype);
registerImageKey('dpi', $dpi);
registerImageKey('scale', $scale);
registerImageKey('rotation', $rotation);
registerImageKey('font_family', $font_family);
registerImageKey('font_size', $font_size);


//get registration id
if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
}else{
	$Registration_ID = 0;
}
 	
$sql =mysql_query("select * from 
						tbl_patient_blood_data bd, tbl_patient_registration pr
							where bd.Donor_ID = '$Registration_ID' and
								pr.registration_id = bd.donor_id") or die(mysql_error());
$no = mysql_num_rows($sql);
if($no > 0){
	while($data = mysql_fetch_array($sql)){
		$Blood_ID = $data['Blood_ID'];
		$Blood_Group = $data['Blood_Group'];
	}
}else{
	$Blood_Group = '';
}
$id=mysql_fetch_array($sql);

if(isset($_GET['Registration_ID'])){
    $text = $_GET['Registration_ID']." ".$Blood_Group." ".$Blood_ID;
}else{
    $text ='';
}


registerImageKey('text', stripslashes($text));

// Text in form is different than text sent to the image
$text = convertText($text);
?>

        

