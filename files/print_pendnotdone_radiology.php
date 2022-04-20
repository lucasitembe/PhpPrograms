
<?php

include("./includes/connection.php");
session_start();
$Sponsor='';
$filter = '';
$procedureStatus='';
$filterPatient = '';
if (isset($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $procedureStatus=$_GET['procedureStatus'];
    $Sponsor = $_GET['sponsorID'];
    $filter = " WHERE ilc.Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND ilc.Check_In_Type='Radiology'";
    $filterPatient=" AND ilc.Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND ilc.Check_In_Type='Radiology'";
  
}

$Guarantor_Name="All";
if ($Sponsor != 'All') {
     $filter .=" AND sp.Sponsor_ID='$Sponsor'";
    
    $Guarantor_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'"))['Guarantor_Name'] ;
}

if ($procedureStatus == 'All') {
    $filter .="  AND ilc.Status IN ('active','pending','not done')";
    $filterPatient .="  AND ilc.Status IN ('active','pending','not done')";
}  else {
    $filter .="  AND ilc.Status = '$procedureStatus'";
    $filterPatient .="  AND ilc.Status = '$procedureStatus'";
}

?>

<?php

$htm = "<table width ='100%'  class='nobordertable'>
		    <tr><td style='text-align:center'>
			<img src='./branchBanner/branchBanner.png' width='100%'>
		    </td></tr>
		    <tr><td style='text-align: center;'><span><b>PATIENTS WITH RADIOLOGY ACTIVE / PENDING / NOT DONE</b></span></td></tr>
                    <tr><td style='text-align: center;'><span><b>FROM</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . date('j F, Y H:i:s', strtotime($fromDate)) . "</b><b>&nbsp;&nbsp;TO</b>&nbsp;&nbsp; <b style='color: #002166;'>" . date('j F, Y H:i:s', strtotime($toDate)) . "</b></td></tr>
                    <tr><td style='text-align: center;'><span><b>SPONSOR</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . $Guarantor_Name . "</b></td></tr>
                        <tr><td style='text-align: center;'><span><b>PROCESS STATUS </b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . $procedureStatus . "</b></td></tr>
        </table><br/>";

 $htm.= '<center>'
         . '<table width ="100%" border="0" id="patientspecimenCollected" class="display">';
     $htm.= "<thead>
	            <tr>
			    <td style='widtd:1%'>SN</td>
			    <td style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</td>
			    <td style='text-align: left;' >REG #</td>
			    <td style='text-align: left;' >GENDER</td>
			    <td style='text-align: left;' >AGE</td>
				<td style='text-align: left;'>STATUS</td>
				<td style='text-align: left;' >TEST NAME</td>
			    <td style='text-align: left;' >TRANS DATE</td>
		     </tr>
           </thead>";
      
       
          $select_patient = "SELECT pr.Registration_ID,pr.Patient_Name,pr.Region,pr.District,pr.Gender,
                           pr.Date_Of_Birth,pr.Phone_Number,sp.Guarantor_Name,ilc.Transaction_Date_And_Time,ilc.Consultant
                    FROM tbl_item_list_cache ilc 
                    INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID 
                    JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID 
                    JOIN tbl_sponsor sp ON sp.Sponsor_ID =pr.Sponsor_ID $filter GROUP BY pr.Registration_ID";
        $select_data_patient_result = mysqli_query($conn,$select_patient);

        $count=1;
        while ($row = mysqli_fetch_array($select_data_patient_result)) {
            $registration_ID = $row['Registration_ID'];
            $patientName = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Gender = $row['Gender'];
            $dob = $row['Date_Of_Birth'];
            $Registration_Date_And_Time = $row['Transaction_Date_And_Time'];

            //these codes are here to determine the age of the patient
            $date1 = new DateTime(date('Y-m-d'));
            $date2 = new DateTime($dob);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";


            //Select Items.
            $select_items = "SELECT Product_Name,Payment_Item_Cache_List_ID FROM tbl_item_list_cache ilc 
                    INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID 
                    JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID  
                    JOIN tbl_items i ON i.Item_ID=ilc.Item_ID WHERE pr.Registration_ID='" . $row['Registration_ID'] . "' $filterPatient GROUP BY ilc.Payment_Item_Cache_List_ID ORDER BY ilc.Transaction_Date_And_Time"; // AND TimeCollected BETWEEN 

      
           //die($select_items);
            $selected_items = mysqli_query($conn,$select_items) or die(mysqli_error($conn));
            
             $products = '';
            $date_items = '';
            $numberOfItem = mysqli_num_rows($selected_items);
            $track = 1;
            while ($rowdata = mysqli_fetch_array($selected_items)) {
                $Product_Name = $rowdata['Product_Name'];
                $ppil=$rowdata['Payment_Item_Cache_List_ID'];
                 $transDate = $row['Transaction_Date_And_Time'];
                
                if ($numberOfItem == 1) {
                    $products = $Product_Name;
                    $date_items = $transDate ;
                } else {
                    if ($track < $numberOfItem) {
                        $products .=$Product_Name.',  ';
                        $date_items .= $transDate.',  ' ;
                    } else {
                        $products .=$Product_Name .'.';
                        $date_items .= $transDate.'.' ;
                    }
                }

                $track++;
            }

            //End of Items
            $htm.= "<tr><td>" . $count++ . "</td>";
            $htm.= "<td style='text-align:left; width:18%'>" . $patientName . "</td>";
            $htm.= "<td style='text-align:left; width:10%'>" . $row['Registration_ID'] . "</td>";
            $htm.= "<td style='text-align:left; width:10%'>" . $Gender . "</td>";
            $htm.= "<td style='text-align:left; width:15%'>" . $age . "</td>";
            $htm.= "<td style='text-align:left; width:10%'>" . ucfirst($procedureStatus) . "</td>";
            $htm.= "<td style='text-align:left; width:26%'>" . $products . "</td>";
            $htm.= "<td style='text-align:left; width:22%'>" . $date_items . "</td>
                </tr>";
        }
        
          $htm.= "</table></center>";

include("MPDF/mpdf.php");
$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($htm, 2);
$mpdf->Output();
        exit; 


?>



