<?php
define('IN_CB', true);
include('include/header.php');

$default_value['checksum'] = '';
$checksum = isset($_POST['checksum']) ? $_POST['checksum'] : $default_value['checksum'];
registerImageKey('checksum', $checksum);
registerImageKey('code', 'BCGcode39');

$characters = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '-', '.', '&nbsp;', '$', '/', '+', '%');


			$finalRequest = '';
			foreach (getImageKeys() as $key => $value) {
				$finalRequest .= '&' . $key . '=' . urlencode($value);
			}
			if (strlen($finalRequest) > 0) {
				$finalRequest[0] = '?';
			}
	
	$htm="<style>
			@page{
			margin-top:10px;
			margin-left:10px;
			}	
			</style>";
	$htm.="<table>";
	$htm.="<tr><td><span style='font-size:6;'><center>Powered By GPITG</cente></span></td></tr>";
	$htm.="<tr>";
			if ($imageKeys['text'] !== '') { 
    $htm.="<td><img src='image.php".$finalRequest."' alt='Barcode Image' /></td>";
			}
			else { 
	$htm.="<td>Fill the form to generate a barcode.</td>";
		}
	$htm.="</tr>";
	$htm.="<tr><td><span style='font-size:8;'><center>GPITG HEALTH CARE</center></span></td></tr>";
	$htm.="</table>";
	
include("../MPDF/mpdf.php");
   $mpdf=new mPDF('','Letter',0,12.7,12.7,14,12.7,8,8); 
   $mpdf->WriteHTML($htm);
   $mpdf->Output();
  exit;



