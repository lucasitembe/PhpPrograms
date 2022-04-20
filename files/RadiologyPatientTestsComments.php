<?php

//
session_start();
include("../includes/connection.php");
$paramdescid1 = 0;
$paramname1 = '';
$paramid1 = 0;
$paramcomment1 = '';
$Registration_ID = '';
$Patient_Payment_Item_List_ID = '';
$Patient_Payment_ID = '';
$Status_From = '';
$Item_ID = '';

//get all item details based on item id
if (isset($_GET['RI'])) {
    $Registration_ID = $_GET['RI'];
} else {
    $Registration_ID = '';
}
if (isset($_GET['PPILI'])) {
    $Patient_Payment_Item_List_ID = $_GET['PPILI'];
} else {
    $Patient_Payment_Item_List_ID = '';
}

if (isset($_GET['PPI'])) {
    $Patient_Payment_ID = $_GET['PPI'];
} else {
    $Patient_Payment_ID = '';
}
if (isset($_GET['Status_From'])) {
    $Status_From = $_GET['Status_From'];
} else {
    $Status_From = '';
}
if (isset($_GET['II'])) {
    $Item_ID = $_GET['II'];
} else {
    $Item_ID = '';
}

isset($_GET['listtype']) ? $listtype = $_GET['listtype'] : $listtype = '';
isset($_GET['PatientType']) ? $PatientType = $_GET['PatientType'] : $PatientType = '';
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];



//get all item details based on item id
if (isset($_GET['RI'])) {
    $Registration_ID = $_GET['RI'];
} else {
    $Registration_ID = '';
}

$patient_details = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_sponsor
 sp WHERE Registration_ID = '$Registration_ID' AND pr.Sponsor_ID=sp.Sponsor_ID") or die(mysqli_error($conn));
$rows_count = mysqli_num_rows($patient_details);
if ($rows_count > 0) {
    while ($row = mysqli_fetch_array($patient_details)) {
        $Patient_Name = $row['Patient_Name'];
        $Gender = $row['Gender'];
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $Guarantor_Name = $row['Guarantor_Name'];

        //calculate age
        //$Date_Of_Birth = '1984-08-04';
        $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age.= $diff->m . " Months and ";
        $age.= $diff->d . " Days ";
    }
}
?> 

<?php

//Getting the Item Name
$select_item = "SELECT Product_Name FROM tbl_items WHERE Item_ID = '$Item_ID'";
$select_item_qry = mysqli_query($conn,$select_item) or die(mysqli_error($conn));
while ($theitem = mysqli_fetch_assoc($select_item_qry)) {
    $item_name = $theitem['Product_Name'];
}
?>  
<style type="text/css"> 
    table.mycomments{
        border-collapse:collapse !important;
        border:none !important;
    }
    table.mycomments  tr ,td{
        border-collapse:collapse !important;
        border:none !important;
    }
</style>  
<?php

$select_Patient_Phone = "
				SELECT Phone_Number FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'
			";
$select_Patient_Phone_Qry = mysqli_query($conn,$select_Patient_Phone) or die(mysqli_error($conn));
if (mysqli_num_rows($select_Patient_Phone_Qry) > 0) {
    while ($PPhone = mysqli_fetch_assoc($select_Patient_Phone_Qry)) {
        $Receiver = $PPhone['Phone_Number'];
    }
} else {
    $Receiver = 'NoNumber';
}
?>
<span id="SMSRespond"></span> 
<span id="radStatus"></span>

<?php

$data = "<table width='80%' cellspacing='0' cellpadding='0' class='mycomments'>
           <tr>
             <td colspan='2' style='text-align:right;padding-left:10px;'>
                                        <button class='art-button-green' Onclick='SaveComments(" . $Patient_Payment_Item_List_ID . "," . $Item_ID . "," . $Registration_ID . ")'>
						Save Reports
				        </button>
					<button class='art-button-green' Onclick='SendSMS(\"Radiology\", \"" . $Receiver . "\")'>
						Send SMS Alert
				        </button>
						
					    <button class='art-button-green' Onclick='closeDialog()'>Close
                        </button>
                        <a href='Radiology_image_viewer.php?name=".$Patient_Name."&id=" . $Registration_ID . "' class='art-button-green' target='_blank'>
						View Images
				        </a>
						<a style='' href='RadiologyTests_Print.php?previewnly=true&RI=" . $Registration_ID . "&II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "'' class='art-button-green' target='_blank'>P R I N T</a>";

$data.='
					    <span id="status_respond"></span>
					  </td>
					</tr>
		 </table><br/>';

$data.='<fieldset><center>';

$data.='<table width="90%" id="tableComment" class="mycomments">';
//Get all Parameters
//Getting the Parameters
$select_item_parameters = "SELECT * FROM tbl_radiology_parameter WHERE Item_ID = '$Item_ID'";
//$select_default_parameters = "SELECT * FROM tbl_radiology_parameter";

$select_item_parameters_qry = mysqli_query($conn,$select_item_parameters) or die(mysqli_error($conn));
//$select_default_parameters_qry = mysqli_query($conn,$select_default_parameters) or die(mysqli_error($conn));
$parameters_qry = '';
$item_params_count = mysqli_num_rows($select_item_parameters_qry);

if ($item_params_count > 0) {
    $parameters_qry = $select_item_parameters_qry;
} else {
    $parameters_qry = null;
}
$textAreaRows='';
if($item_params_count <=4){
    $textAreaRows=' rows="5"';
}

if(!is_null($parameters_qry)){
while ($allparams = mysqli_fetch_assoc($parameters_qry)) {
    $allparamid = $allparams['Parameter_ID'];
    $allparamname = $allparams['Parameter_Name'];

    //Getting the Old Comments
    $select_old = "
				SELECT * 
					FROM 
					tbl_radiology_discription rd,
					tbl_radiology_parameter rp
						WHERE
						rd.Parameter_ID = rp.Parameter_ID AND
						rd.Registration_ID = '$Registration_ID' AND
						rd.Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' AND
						rd.Item_ID = '$Item_ID' AND
						rp.Parameter_ID = '$allparamid'
					";

    //die($select_old);
    $select_old_qry = mysqli_query($conn,$select_old) or die(mysqli_error($conn));
    while ($theold1 = mysqli_fetch_assoc($select_old_qry)) {
        $paramdescid1 = $theold1['Radiology_Description_ID'];
        $paramname1 = $theold1['Parameter_Name'];
        $paramid1 = $theold1['Parameter_ID'];
        $paramcomment1 = $theold1['comments'];
    }
    $attr = '';

    if ($allparamid != $paramid1) {

        $attr = "style='display:none;' id='row" . $allparamid . "'";
    }
    $parCom = '';
    if ($allparamid == $paramid1) {
        if (isset($paramcomment1)) {
            $parCom = $paramcomment1;
        }
    }

    $data.='<tr>
                <td width="22%" style="text-align:right;"> <strong>' . $allparamname . '</strong> </td>
                <td> 
                    <textarea style="padding-left:10px;width:100%" id="' . $allparamid . '" class="parametersValues" '.$textAreaRows.'>' . str_replace("<br/>", "\n", $parCom) . '</textarea> 
                </td>
                </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            ';
}


$data.="  </table>
         </center>
	</fieldset>";
}else{
   $data.="<p style='text-align:center;font-size:17px'>This Item has no parameter(s).Please go to radiology setup and add the parameter(s) sequentially.</p>"; 
}
echo $data;
?>

