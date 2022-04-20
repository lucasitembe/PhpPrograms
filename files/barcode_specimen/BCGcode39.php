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

if($_GET['payment_Cache_Id']){
    $payment_Cache_Id=$_GET['payment_Cache_Id'];
}  else {
    $payment_Cache_Id='';
}

if(isset($_GET['Item_ID'])){
  $Item_ID=$_GET['Item_ID'];  
    
}  else {
  $Item_ID='';  
}


if($_GET['Patient_Cache_Test_Specimen_ID'] !=''){
	$Patient_Cache_Test_Specimen_ID=$_GET['Patient_Cache_Test_Specimen_ID'];
}else{
	$Patient_Cache_Test_Specimen_ID='';
}

if($_GET['Patient_Payment_Test_Specimen_ID'] !=''){
	$Patient_Payment_Test_Specimen_ID=$_GET['Patient_Payment_Test_Specimen_ID'];
}else{
	$Patient_Payment_Test_Specimen_ID='';
}
  
$getItem=mysql_query("SELECT * FROM tbl_items WHERE Item_ID='$Item_ID'");
$myqueryItem=  mysql_fetch_assoc($getItem);

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
        $htm.= "<tr><td class='textdata'><strong>".$payment_Cache_Id."</strong></td></tr>";
        
        $htm.= "<tr><td class='textdata'><strong>".  substr($myqueryItem['Product_Name'],0,38)."</strong></td></tr>";
	
        
	$htm.= "<tr><td class='textdata'><strong>".substr($Registration_ID.' - '.$Patient_Name.', '.$Gender.', '.$Age,0,38)."</strong></td></tr>";
	$htm.= "<tr><td class='textdata' style='padding-bottom:5px'><strong>".substr($Date_And_Time,0,38)."</strong></td></tr>";
	//$htm.= "<tr><td style=''>SPECIMEN NO: ";
	?>
<?php
		if(isset($Patient_Payment_Test_Specimen_ID)){
			$htm.="$Patient_Payment_Test_Specimen_ID";
		}elseif(isset($Patient_Cache_Test_Specimen_ID)){
			$htm.="$Patient_Payment_Test_Specimen_ID";
		}else{
			$htm.=".No details.";
		}
                
                
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
//  //window.onfocus=function(){ window.close();}
//// (function() {
////
////    var beforePrint = function() {
////        console.log('Functionality to run before printing.');
////    };
////
//    var afterPrint = function() {
//        console.log('Functionality to run after printing');
//    };
//
//    if (window.matchMedia) {
//        var mediaQueryList = window.matchMedia('print');
//        mediaQueryList.addListener(function(mql) {
//            if (mql.matches) {
//                beforePrint();
//            } else {
//                afterPrint();
//            }
//        });
//    }
//
//    window.onbeforeprint = beforePrint;
//    window.onafterprint = afterPrint;
//
//}());
 </script>


