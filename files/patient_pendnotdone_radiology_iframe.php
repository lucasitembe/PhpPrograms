<center>
    <?php
    include("./includes/connection.php");
     echo '<center><table width ="100%" border="0" id="patientdoneradiology" class="display">';
     echo "<thead>
	            <tr>
			    <th style='width:1%'>SN</th>
			    <th style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</th>
			    <th style='text-align: left;' width=18%>PATIENT NUMBER</th>
			    <th style='text-align: left;' width=6%>GENDER</th>
			    <th style='text-align: left;' width=10%>AGE</th>
                            <th style='text-align: left;' width=10%>STATUS</th>
                            <th style='text-align: left;' width=26%>TEST NAME</th>
			    <th style='text-align: left;' width=22%>TRANS DATE</th>
		     </tr>
           </thead>";
     
 $Sponsor='';
 $procedureStatus='';
$filter = '';
$filterPatient = '';
if (isset($_GET['action'])) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $procedureStatus=$_GET['procedureStatus'];
    $Sponsor = $_GET['sponsorID'];
    $filter = " WHERE ilc.Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND ilc.Check_In_Type='Radiology'";
   $filterPatient=" AND ilc.Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND ilc.Check_In_Type='Radiology'";
}

if ($Sponsor != 'All') {
    $filter .=" AND sp.Sponsor_ID='$Sponsor'";
    $filterPatient .=" AND pr.Sponsor_ID='$Sponsor'";
}

if ($procedureStatus == 'All') {
    $filter .="  AND ilc.Status IN ('active','pending','not done')";
    $filterPatient .="  AND ilc.Status IN ('active','pending','not done')";
}  else {
    $filter .="  AND ilc.Status = '$procedureStatus'";
    $filterPatient .="  AND ilc.Status = '$procedureStatus'";
}


    if (isset($_GET['action'])) {
       
        $count = 1;
       
        $select_patient = "SELECT pr.Registration_ID,pr.Patient_Name,pr.Region,pr.District,pr.Gender,
                           pr.Date_Of_Birth,pr.Phone_Number,sp.Guarantor_Name,ilc.Transaction_Date_And_Time,ilc.Consultant
                    FROM tbl_item_list_cache ilc 
                    INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID 
                    JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID 
                    JOIN tbl_sponsor sp ON sp.Sponsor_ID =pr.Sponsor_ID $filter GROUP BY pr.Registration_ID";
        
       // echo $select_patient;exit;
        
        $select_data_patient_result = mysqli_query($conn,$select_patient) or die(mysqli_error($conn));

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
            echo "<tr><td>" . $count++ . "</td>";
            echo "<td style='text-align:left; width:18%'>" . $patientName . "</td>";
            echo "<td style='text-align:left; width:10%'>" . $row['Registration_ID'] . "</td>";
            echo "<td style='text-align:left; width:10%'>" . $Gender . "</td>";
            echo "<td style='text-align:left; width:15%'>" . $age . "</td>";
            echo "<td style='text-align:left; width:10%'>" . ucfirst($procedureStatus) . "</td>";
            echo "<td style='text-align:left; width:26%'>" . $products . "</td>";
            echo "<td style='text-align:left; width:22%'>" . $date_items . "</td>
                </tr>";
        }
       
    } else {
        // echo "<tr><td colspan='10' style='text-align:center'>Choose your date range to show results</td>";
       // echo 'Choose your date range to show results';
    }
     echo "</table></center>";
    ?>

    <script>
        $('#patientdoneradiology').dataTable({
            "bJQueryUI": true,
        });

    </script>