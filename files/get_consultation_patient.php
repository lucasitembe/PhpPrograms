<?php 
include("./includes/connection.php");

if(isset($_GET['Start_Date'])) {
    $Start_Date = $_GET['Start_Date'];
} else {
    $Start_Date = $_GET['Start_Date'];
}

if (isset($_GET['End_Date'])) {
    $End_Date = $_GET['End_Date'];
} else {
    $End_Date = $_GET['End_Date'];   
}


echo '
<center>
    <table width=100%>
        <tr><td style=\'text-align: right;\' width=7%><input name=\'Filter2\' type=\'button\' value=\'PREVIEW\' class=\'art-button\' onclick=\'Get_Consulted_Patient()\'></td></tr>
    </table>
</center>
<center>


        <table id="myList"  width=100%>
            <thead>
                <tr>
                    <td width="5%"><b>SN</b></td>
                    <td><b>PATIENT NAME</b></td>
                    <td><b>SPONSOR NAME</b></td>
                    <td><b>CONSULTING DR / CLINIC</b></td>
                    <td><b>IN CLINIC CONSULTING DR</b></td>
                    <td><b>CONSULTATION DATE</b></td>
                </tr>
            </thead>
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
				    Receipt_Date between '$Start_Date' and '$End_Date' and Check_In_Type='Doctor Room' GROUP BY pr.Registration_ID") or die(mysqli_error($conn));
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
        echo '                    <tr>
                        <td>';
        echo ++$temp;;
        echo '</td>
                        <td>';
        echo $data['Patient_Name'];;
        echo '</td>
                        <td>';
        echo $data['Guarantor_Name'];;
        echo '</td>

                        ';
        if ($Patient_Direction == 'Direct To Doctor' || $Patient_Direction == 'Direct To Doctor Via Nurse Station') {
            $queryforconsultant = mysqli_query($conn, "SELECT
			Employee_Name,Employee_ID 
			FROM tbl_employee where 
			Employee_ID='$consultingdoctor' ") or die(mysqli_error($conn));
            $consult = mysqli_fetch_array($queryforconsultant);;
            echo '                            <td>Dr. ';
            echo $consult['Employee_Name'];;
            echo '</td><td>' . $Clinic_Name . '</td>
                            ';
        } else {
            echo '   <td>' . $Clinic_Name . '</td>                         <td>';
            echo $Clinic_Name;
            echo '</td>
                        ';
        };
        echo '<td>
                        ';
        // $querytocheck = mysqli_query($conn, "SELECT ppl.Patient_Payment_Item_List_ID,ppl.Patient_Direction,
        // c.Patient_Payment_Item_List_ID,c.employee_ID,Employee_Name,ppl.Consultant,ppl.Clinic_ID
        // FROM tbl_consultation c ,tbl_patient_payment_item_list ppl,tbl_employee e
        // WHERE c.Patient_Payment_Item_List_ID=ppl.Patient_Payment_Item_List_ID AND
        // c.Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID' AND
        // e.Employee_ID=c.employee_ID AND 
        // ppl.Patient_Direction='Direct To Clinic'") or die(mysqli_error($conn));
        // $rowconsult = mysqli_fetch_array($querytocheck);;
        echo $data['Check_In_Date_And_Time'];;
        echo '</td>
                    </tr>   
        ';
    }
};
echo '	
        </table>
</center>
<!--END HERE-->';

?>