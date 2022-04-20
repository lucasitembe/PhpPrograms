
<?php

include("./includes/connection.php");
session_start();
$Sponsor='';
$filter = '';
$filterPatient = '';
if (isset($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $Sponsor = $_GET['sponsorID'];
     $filter = " WHERE ilc.Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND ilc.Check_In_Type='Radiology' AND ilc.Status = 'served'";
     $filterPatient=" AND ilc.Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND ilc.Check_In_Type='Radiology' AND ilc.Status = 'served'";
  
}

$Guarantor_Name="All";
if ($Sponsor != 'All') {
     $filter .=" AND sp.Sponsor_ID='$Sponsor'";
    
    $Guarantor_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'"))['Guarantor_Name'] ;
}

?>

<?php

$htm = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
$htm.="<p align='center'><b>PATIENTS WITH RADIOLOGY DONE REPORT <br/>FROM</b> <b style=''>" . $fromDate . "</b> <b>TO</b> <b style=''>" . $toDate . "</b>"
        . "<br/>"
         . "<span><b>SPONSOR $Guarantor_Name</b></span>"
        . "</p>";

 $htm.= '<center>'
         . '<table width ="100%" border="0" id="patientspecimenCollected" class="display">';
     $htm.= "<thead>
	            <tr>
			    <th style='width:1%'>SN</th>
			    <th style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</th>
			    <th style='text-align: left;' >PATIENT #</th>
			    <th style='text-align: left;' >REGION</th>
			    <th style='text-align: left;' >DISTRICT</th>
			    <th style='text-align: left;' >GENDER</th>
			    <th style='text-align: left;' >AGE</th>
                            <th style='text-align: left;' >PROCEDURE</th>
			    <th style='text-align: left;' >PROCEDURE DATE</th>
                            <!--<th style='text-align: left;' >Procedure FROM</th>-->
		     </tr>
                     <tr>
                       <td colspan='10'><hr width='100%'/></td>
                     <tr/>
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
            $numberOfItem = mysqli_num_rows($selected_items);
            $track = 1;
            while ($rowdata = mysqli_fetch_array($selected_items)) {
                $Product_Name = $rowdata['Product_Name'];
                $ppil=$rowdata['Payment_Item_Cache_List_ID'];
                
                if ($numberOfItem == 1) {
                    $products = $Product_Name;
                } else {
                    if ($track < $numberOfItem) {
                        $products .=$Product_Name.',  ';
                    } else {
                        $products .=$Product_Name .'.';
                     
                    }
                }

                $track++;
            }

            //End of Items
            $htm.= "<tr>"
                    . "<td>" . $count++ . "</td>";
            $htm.= "<td style='text-align:left; width:10%'>" . $patientName . "</td>";
            $htm.= "<td style='text-align:left; width:10%'>" . $row['Registration_ID'] . "</td>";
            $htm.= "<td style='text-align:left; width:10%'>" . $Region . "</td>";
            $htm.= "<td style='text-align:left; width:10%'>" . $District . "</td>";
            $htm.= "<td style='text-align:left; width:5%'>" . $Gender . "</td>";
            $htm.= "<td style='text-align:left; width:15%'>" . $age . "</td>";
            $htm.= "<td style='text-align:left; width:24%'>" . $products. "</td>";
            $htm.= "<td style='text-align:left; width:8%'>" . $Registration_Date_And_Time . "</td>";
            $htm.= "<!--<td style='text-align:left; width:10%'>" . $row['Consultant'] . "</td>-->
                  </tr>
                  <tr>
                    <td colspan='10'><hr width='100%'/></td>
                  <tr/>
                  ";
        }
        
          $htm.= "</table></center>";

        include("MPDF/mpdf.php");
       
        $mpdf=new mPDF(); 

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($htm);
        $mpdf->Output();
        exit; 


?>



