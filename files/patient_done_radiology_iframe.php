<center>
    <?php
    include("./includes/connection.php");
     echo '<center><table width ="100%" border="0" id="patientdoneprocedure" class="display">';
     echo "<thead>
	            <tr>
			    <th style='width:1%'>SN</th>
			    <th style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</th>
			    <th style='text-align: left;' width=10%>PATIENT NUMBER</th>
			    <th style='text-align: left;' width=10%>REGION</th>
			    <th style='text-align: left;' width=10%>DISTRICT</th>
			    <th style='text-align: left;' width=6%>GENDER</th>
			    <th style='text-align: left;' width=10%>AGE</th>
                            <th style='text-align: left;' width=18%>PROCEDURE</th>
			    <th style='text-align: left;' width=15%>PROCEDURE DATE</th>
		     </tr>
           </thead>";
     
 $Sponsor='';
$filter = '';
$filterPatient = '';
if (isset($_POST['action'])) {
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $Sponsor = $_POST['sponsorID'];
    $filter = " WHERE ilc.Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND ilc.Check_In_Type='Radiology' AND ilc.Status = 'served'";
   $filterPatient=" AND ilc.Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND ilc.Check_In_Type='Radiology' AND ilc.Status = 'served'";
}

if ($Sponsor != 'All') {
    $filter .=" AND sp.Sponsor_ID='$Sponsor'";
    $filterPatient .=" AND sp.Sponsor_ID='$Sponsor'";
}
    if (isset($_POST['action'])) {
       
        $count = 1;
       
        $select_patient = "SELECT pr.Registration_ID,pr.Patient_Name,pr.Region,pr.District,pr.Gender,
                           pr.Date_Of_Birth,pr.Phone_Number,sp.Guarantor_Name,ilc.Transaction_Date_And_Time,ilc.Consultant
                    FROM tbl_item_list_cache ilc 
                    INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID 
                    JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID 
                    JOIN tbl_sponsor sp ON sp.Sponsor_ID =pr.Sponsor_ID $filter GROUP BY pr.Registration_ID";
        $select_data_patient_result = mysqli_query($conn,$select_patient);

//         $select_data="SELECT * FROM tbl_specimen_results INNER JOIN tbl_item_list_cache  ON payment_item_ID=Payment_Item_Cache_List_ID JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID
//                      JOIN tbl_patient_registration ON tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID JOIN 
//                      tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID WHERE TimeCollected BETWEEN '$fromDate' AND '$toDate' GROUP BY Payment_Item_Cache_List_ID ORDER BY TimeCollected";// AND TimeCollected BETWEEN 
//           
//        $select_data_result=  mysqli_query($conn,$select_data_patient_result);
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

          // die($select_items);
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
            echo "<tr><td>" . $count++ . "</td>";
            echo "<td style='text-align:left; width:10%'>" . $patientName . "</td>";
            echo "<td style='text-align:left; width:10%'>" . $row['Registration_ID'] . "</td>";
            echo "<td style='text-align:left; width:10%'>" . $Region . "</td>";
            echo "<td style='text-align:left; width:10%'>" . $District . "</td>";
            echo "<td style='text-align:left; width:10%'>" . $Gender . "</td>";
            echo "<td style='text-align:left; width:15%'>" . $age . "</td>";
            echo "<td style='text-align:left; width:15%'>" . $products . "</td>";
            echo "<td style='text-align:left; width:10%'>" . $Registration_Date_And_Time . "</td>
                </tr>";
        }
       
    } else {
        // echo "<tr><td colspan='10' style='text-align:center'>Choose your date range to show results</td>";
       // echo 'Choose your date range to show results';
    }
     echo "</table></center>";
    ?>

    <script>
        $('#patientdoneprocedure').dataTable({
            "bJQueryUI": true,
        });

    </script>