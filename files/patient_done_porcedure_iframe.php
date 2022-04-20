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
        $filter = " AND ilc.Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND ilc.Check_In_Type='Procedure' AND ilc.Status = 'served'";
    $filterPatient=" AND ilc.Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND ilc.Check_In_Type='Procedure' AND ilc.Status = 'served'";
    }

    if ($Sponsor != 'All') {
        $filter .=" AND stp.Sponsor_ID='$Sponsor'";
    }
    if (isset($_POST['action'])) {
       
        $count = 1;
       $select_patient = "SELECT pr.Registration_ID,pc.Payment_Cache_ID, pr.Patient_Name,pr.Region,pr.District,pr.Gender,  pr.Date_Of_Birth,pr.Phone_Number,stp.Guarantor_Name,ilc.Transaction_Date_And_Time,ilc.Consultant  FROM tbl_item_list_cache ilc, tbl_payment_cache pc, tbl_patient_registration pr, tbl_sponsor stp WHERE  pc.Payment_Cache_ID=ilc.Payment_Cache_ID AND pr.Registration_ID=pc.Registration_ID AND stp.Sponsor_ID =pr.Sponsor_ID $filter GROUP BY pr.Registration_ID";
       //die($select_patient);
         $select_data_patient_result = mysqli_query($conn,$select_patient);

        while ($row = mysqli_fetch_array($select_data_patient_result)) {
            $registration_ID = $row['Registration_ID'];
            $patientName = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Payment_Cache_ID = $row['Payment_Cache_ID'];
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
            $select_items = "SELECT Product_Name,Payment_Item_Cache_List_ID FROM tbl_item_list_cache ilc, tbl_items i WHERE ilc.Payment_Cache_ID='$Payment_Cache_ID' AND i.Item_ID=ilc.Item_ID $filterPatient GROUP BY ilc.Payment_Item_Cache_List_ID ORDER BY ilc.Transaction_Date_And_Time ASC";
            
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