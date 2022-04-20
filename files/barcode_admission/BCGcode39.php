<?php
define('IN_CB', true);
@session_start();
include('include/header.php');
include("../includes/connection.php");
if($_GET['Registration_ID'] != ''){
	$Registration_ID=$_GET['Registration_ID'];
}else{
	$Registration_ID='';
}

if($_GET['Hospital_Ward'] != ''){
	$Hospital_Ward=$_GET['Hospital_Ward'];
}else{
	$Hospital_Ward='';
}


if($_GET['Bed_ID'] != ''){
	$Bed_ID=$_GET['Bed_ID'];
}else{
	$Bed_ID='';
}

//Get ward
$get_ward=mysql_query("SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$Hospital_Ward'");
$ward=mysql_fetch_assoc($get_ward);
$ward_Name=$ward['Hospital_Ward_Name'];

//Get Bed No
$get_bed=mysql_query("SELECT Bed_Name FROM tbl_beds WHERE Bed_ID='$Bed_ID'");
$bed=mysql_fetch_assoc($get_bed);
$bed_Name=$ward['Bed_Name'];

$Date_And_Time=date('Y-m-d H:i:s');
//$Print_Date_And_Time=date('j F,Y H:i"s',date(strtotime($Date_And_Time)));
//run the query to get patient information for this patient
$query=mysql_query("SELECT Patient_Name,Gender,TIMESTAMPDIFF(YEAR,Date_Of_Birth,NOW()) AS Age FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID' ") or die(mysql_error());
$row=mysql_fetch_assoc($query);
$Patient_Name=$row['Patient_Name'];
$Gender=($row['Gender']=='Female'?'F':'M');
$Age=$row['Age'];

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
                        $retrieve = mysql_query("SELECT Hospital_Name,Box_Address,Telephone,Cell_Phone,Fax,Tin FROM tbl_system_configuration WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'");
         $data=  mysql_fetch_assoc($retrieve);
         
    $hospital_Name = strtoupper($data['Hospital_Name']);
    $box_Address = $data['Box_Address'];
    $tel_phone = $data['Telephone'];
    $cell_phone =$data['Cell_Phone'];
    $fax = $data['Fax'];
    $tin = $data['Tin'];

	$htm="<style>
			@page{
			margin-top:64px;
			margin-left:5px;
                        margin-right:5px;
                        font-weight:bolder;
                        font-size:20px;
			}
                        
.textdata{
  text-align:center;
  font-family:widen latin;
  font-size:20px;
}
			</style>";
	$htm.="<center><table border='0' cellspacing='0' cellpadding='0'>";
       
        $htm.="<tr>";
			if ($imageKeys['text'] !== '') {
        $htm.="<td style='text-align:center'><img src='image.php".$finalRequest."' alt='Barcode Image'/></td>";
			}
			else {
	$htm.="<td>Fill the form to generate a barcode.</td>";
                      }
	$htm.="</tr>";
        $htm.= "<tr><td class='textdata' ><strong>".$hospital_Name."</strong></td></tr>";

	$htm.= "<tr><td class='textdata'><strong>".substr($Registration_ID.' - '.$Patient_Name.', '.$Gender.', '.$Age,0,38)."</strong></td></tr>";
	$htm.= "<tr><td class='textdata' style='padding-bottom:5px'><strong> WARD: ".$ward_Name.", BED :".$bed_Name."</strong></td></tr>";
	//$htm.= "<tr><td style=''>SPECIMEN NO: ";
	?>
  
<?php $htm."</td></tr>";
	//$htm.="<tr><td><span style='font-size:8;'><center>GPITG HEALTH CARE</cente></span></td></tr>";
	$htm.="</table></center>";

//include("../MPDF/mpdf.php");
//   $mpdf=new mPDF('','Letter',0,12.7,12.7,14,12.7,8,8);
//   $mpdf->WriteHTML($htm);
//   $mpdf->Output();
//  exit;

      echo $htm;
?>
<script>
 window.print(false);
 CheckWindowState();
 // window.onafterprint = function(){
   // window.close();
// }

 function PrintWindow() {                    
       window.print();            
       CheckWindowState();
    }

    function CheckWindowState()    {           
        if(document.readyState=="complete") {
            window.close(); 
        } else {           
            setTimeout("CheckWindowState()", 2000);
        }
    }

 </script>


