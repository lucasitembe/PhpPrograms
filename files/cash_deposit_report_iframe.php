

<?php
    @session_start();
    include("./includes/connection.php");
    
        $fromDate =$_POST['fromDate'];
        $toDate=$_POST['toDate'];
        $patient_number=$_POST['patient_number'];

        $filter ='';
        if($patient_number != ""){
            $filter = " AND pp.Registration_ID='$patient_number'";
        }else{
            $filter="";
        }   
        $sn = 1;
		echo "<div style='background-color:white;'>";
			echo "<table class='table table-hover table-responsive table-condensed' width='100%'>";
			echo "<thead>";
            echo "<tr>
                    <th style='width:3%' >NO.</th>
                    <th style='width:15%;'>Patient Name</th>
                    <th style='width:5%' >Registration #:</th>                    
                    <th style='width:5%;'>Age</th>
                    <th style='width:10%;'>Sponsor Name</th>                    
                    <th style='width:17%;'>Description</th>
                    <th style='width:10%;'> Employee Name</th>
                    <th style='width:10%;'>Department</th>
                    <th style='width:10%;'>Transaction Date</th>
                    <th style='width:7%;'>Total Amount</th>
                    </tr>";
            echo "</thead>
            <tbody>";

        $Total_amount = 0;
        $Total_deposit = 0;
		$querySubcategory = mysqli_query($conn,"SELECT Guarantor_Name,Item_Name,Consultant_ID, Patient_Name, pp.Registration_ID,  pil.Patient_Payment_ID,Price,Quantity, Transaction_Date_And_Time,Sub_Department_ID,  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS Age from tbl_patient_payment_item_list pil, tbl_patient_registration pr,tbl_patient_payments pp,tbl_items i, tbl_sponsor s  WHERE pp.Patient_Payment_ID=pil.Patient_Payment_ID AND s.Sponsor_ID=pr.Sponsor_ID AND  pp.Registration_ID=pr.Registration_ID AND pil.Item_ID=i.Item_ID AND   Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND Visible_Status='Others' $filter") or die(mysqli_error($conn));
            if(mysqli_num_rows($querySubcategory)>0){
            while($row1 = mysqli_fetch_assoc($querySubcategory)) {
                $Quantity = $row1['Quantity'];
                $Price = $row1['Price'];
                $Consultant_ID = $row1['Consultant_ID'];
                $Registration_ID = $row1['Registration_ID'];
                $Patient_Name = $row1['Patient_Name'];
                $Item_Name = $row1['Item_Name'];
                $Transaction_Date_And_Time = $row1['Transaction_Date_And_Time'];
                $Cash_Bill_Status = $row1['Cash_Bill_Status'];
                $Guarantor_Name =$row1['Guarantor_Name'];
                $Age = $row1['Age'];
                $Sub_Department_ID = $row1['Sub_Department_ID'];
               $Total_amount = $Quantity * $Price;
               $Employee_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROm tbl_employee WHERE Employee_ID='$Consultant_ID'"))['Employee_Name'];
               
               $Sub_Department_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_Name FROm tbl_sub_department WHERE Sub_Department_ID='$Sub_Department_ID'"))['Sub_Department_Name'];
                echo"<tr>
                        <td style='text-align:right'> ".$sn."</td>
                        <td style='text-align:left'>".$Patient_Name."</td>
                        <td style='text-align:right'> ".$Registration_ID."</td>                        
                        <td style='text-align:right'>$Age</td>
                        <td style='text-align:center'>".$Guarantor_Name."</td>
                        <td style='text-align:left'>$Item_Name</td>
                        <td style='text-align:left'>$Employee_name</td>
                        <td>$Sub_Department_Name</td>
                        <td style='text-align:center'>$Transaction_Date_And_Time</td>
                        
                        <td style='text-align:right'>".number_format($Total_amount)."/=</td>";
                        
                echo "</tr>";
                $sn++;
                $Total_deposit +=$Total_amount;

                }
            }else{
                echo "<tr><td colspan='8'><b style='color:red;'>No data Found </b></td></tr>";
            }
            
           echo "<tr>
                    <th colspan='9'><b>Total Amount Deposited $patient_number From $fromDate To $toDate </b></th>
                    <td colspan='' style='text-align:right'><b>".number_format($Total_deposit)."/=</b></td>
           </tr>";
		echo "</tbody></table>";
        echo "</div>";
   
?> 

