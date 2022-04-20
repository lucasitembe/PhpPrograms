<?php
include("./includes/connection.php");

$Today_Date = mysqli_query($conn, "select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
};
$htm .= '<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style> 

<br/><br/>
<!-- get employee id-->
';
if (isset($_GET['Date_To'])) {
    $Date_To = $_GET['Date_To'];
} else {
    $Date_To = $_GET['Date_To'];
}

if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
} else {
    $Date_From = $_GET['Date_From'];
}

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
};
$htm .= '


<!--Getting employee name -->
';
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
};

$htm = '<table width =100% border="0" style="background-color: white;">
          <tr>
             <td style="text-align:center"><img src="./branchBanner/branchBanner.png" width="100%" /></td>
          </tr>
          <tr>
             <td style="text-align:center"><b>LIST OF CONSULTED PATIENTS</b><br/> </td>             
          </tr>
          <tr>
            <td style="text-align:center"><b>FROM: </b>' . $Date_From . '<b> TO: </b>' . $Date_To . '</td>
          </tr>
          ';

$htm .= "</table>";
$htm .= '
<script type=\'text/javascript\'>       
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<center>



        <table width=100%>
            <tr>
                <td width="5%"><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td><b>SPONSOR NAME</b></td>
                <td><b>CONSULTING DR / CLINIC</b></td>
                <td><b>IN CLINIC CONSULTING DR</b></td>
                <td><b>CONSULTATION DATE</b></td>
            </tr>
            ';
$temp = 0;
$select_details = mysqli_query($conn, "SELECT sp.Guarantor_Name, pr.Patient_Name, 
	ci.Check_In_Date_And_Time, ppl.Check_In_Type,ppl.Patient_Payment_Item_List_ID,ppl.Clinic_ID,
				    ppl.Patient_Direction, ppl.Consultant, Consultant_ID
				    FROM
					tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
				    tbl_patient_registration pr, tbl_check_in ci, tbl_sponsor sp where
				    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
				    pp.Registration_ID = pr.Registration_ID and
				    ci.Check_In_ID = pp.Check_In_ID and
				    sp.Sponsor_ID = pr.Sponsor_ID and
				    Receipt_Date between '$Date_From' and '$Date_To' and Check_In_Type='Doctor Room' GROUP BY pr.Registration_ID") or die(mysqli_error($conn));
$num = mysqli_num_rows($select_details);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select_details)) {
        $consultingdoctor = $data['Consultant_ID'];
        $Patient_Direction = $data['Patient_Direction'];
        $consultingdoctor = $data['Consultant_ID'];
        $Clinic_ID = $data['Clinic_ID'];
        $select_clinic_name = mysqli_query($conn, "SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID = '$Clinic_ID'");
        $Clinic_Name = mysqli_fetch_assoc($select_clinic_name)['Clinic_Name'];
        $Patient_Payment_Item_List_ID = $data['Patient_Payment_Item_List_ID'];
        $queryforconsultant = mysqli_query($conn, "SELECT
		Employee_Name,Employee_ID 
		FROM tbl_employee where 
		Employee_ID='$consultingdoctor' ") or die(mysqli_error($conn));
        $consult = mysqli_fetch_array($queryforconsultant);;
        $htm .= '                    <tr>
                        <td>';
        $htm .= ++$temp;;
        $htm .= '</td>
                        <td>';
        $htm .= $data['Patient_Name'];;
        $htm .= '</td>
                        <td>';
        $htm .= $data['Guarantor_Name'];;
        $htm .= '</td>

                        ';

        if ($Patient_Direction == 'Direct To Doctor' || $Patient_Direction == 'Direct To Doctor Via Nurse Station') {
            $queryforconsultant = mysqli_query($conn, "SELECT
			Employee_Name,Employee_ID 
			FROM tbl_employee where 
			Employee_ID='$consultingdoctor' ") or die(mysqli_error($conn));
            $consult = mysqli_fetch_array($queryforconsultant);;
            $htm .= '                            <td>';
            $htm .= $consult['Employee_Name'];;
            $htm .= '</td>' . $Clinic_Name . '<td></td>
                            ';
        } else {
            $htm .= '   <td>' . $Clinic_Name . '</td>                         <td>';
            $htm .= $Clinic_Name;
            $htm .= '</td>
                        ';
        };

        // $querytocheck = mysqli_query($conn, "SELECT ppl.Patient_Payment_Item_List_ID,ppl.Patient_Direction,
		// c.Patient_Payment_Item_List_ID,c.employee_ID,Employee_Name,ppl.Consultant,ppl.Clinic_ID
		// FROM tbl_consultation c ,tbl_patient_payment_item_list ppl,tbl_employee e
		// WHERE c.Patient_Payment_Item_List_ID=ppl.Patient_Payment_Item_List_ID AND
		// c.Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID' AND
		// e.Employee_ID=c.employee_ID AND 
		// ppl.Patient_Direction='Direct To Clinic'") or die(mysqli_error($conn));
        // $rowconsult = mysqli_fetch_array($querytocheck);;
        $htm .= '
                        <td>';
        $htm .= $data['Check_In_Date_And_Time'];;
        $htm .= '</td>
                    </tr>
        ';
    }
};
$htm .= '	
        </table>
</center>
<!--END HERE-->

<script>
    function Get_Previous_Requisition() {
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;

        if (Start_Date != null && Start_Date != \'\' && End_Date != null && End_Date != \'\') {
            if (window.XMLHttpRequest) {
                myObjectGetPrevious = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetPrevious = new ActiveXObject(\'Micrsoft.XMLHTTP\');
                myObjectGetPrevious.overrideMimeType(\'text/xml\');
            }

            myObjectGetPrevious.onreadystatechange = function () {
                data80 = myObjectGetPrevious.responseText;
                if (myObjectGetPrevious.readyState == 4) {
                    document.getElementById(\'Patient_List_Area\').innerHTML = data80;
                }
            }; //specify name of function that will handle server response........

            myObjectGetPrevious.open(\'GET\', \'Get_Previous_Requisition.php?Start_Date=\' + Start_Date + \'&End_Date=\' + End_Date, true);
            myObjectGetPrevious.send();
        } else {

            if (Start_Date == null || Start_Date == \'\') {
                document.getElementById("date").style = \'border: 3px solid red; text-align: center;\';
                document.getElementById("date").focus();
            } else {
                document.getElementById("date").style = \'border: 3px; text-align: center;\';
            }

            if (End_Date == null || End_Date == \'\') {
                document.getElementById("date2").style = \'border: 3px solid red; text-align: center;\';
                document.getElementById("date2").focus();
            } else {
                document.getElementById("date2").style = \'border: 3px; text-align: center;\';
            }
        }
    }

    function Get_Consulted_Patient() {

       alert("In now");
    }
</script>
';

include("./MPDF/mpdf.php");

$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
// $stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($htm, 2);
$mpdf->Output();
exit;
?>