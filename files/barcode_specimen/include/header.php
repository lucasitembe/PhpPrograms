<?php
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
$default_value['dpi'] = 27;
$default_value['scale'] = isset($defaultScale) ? $defaultScale : 4;
$default_value['rotation'] =0;
$default_value['font_family'] = 0;
$default_value['font_size'] = 10;
$default_value['text'] = '';
$default_value['a1'] = '';
$default_value['a2'] = '';
$default_value['a3'] = '';

//$default_value = array();
//$default_value['filetype'] = 'PNG';
//$default_value['dpi'] = 96;
//$default_value['scale'] = isset($defaultScale) ? $defaultScale : 1.385;
//$default_value['rotation'] = 0;
//$default_value['font_family'] = 'Arial.ttf';
//$default_value['font_size'] = 8;

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



//	// 1. Create a database connection
//	$connection = mysql_connect("localhost","root","");
//	if (!$connection) {
//		die("Database connection failed: " . mysql_error());
//	}
//	
//	// 2. Select a database to use 
//	$db_select = mysql_select_db("ehms_db",$connection);
//	if (!$db_select) {
//		die("Database selection failed: " . mysql_error());
//	}
//	
//	
//$sql =mysql_query("select Old_Registration_Number from tbl_patient_registration where Registration_ID =1 ");
//$id=mysql_fetch_array($sql);


if(isset($_GET['payment_Cache_Id'])){
   $text = $_GET['payment_Cache_Id'];
}else{
   $text ='';
}

registerImageKey('text', stripslashes($text));
// Text in form is different than text sent to the image
$text = convertText($text);

?>

        

