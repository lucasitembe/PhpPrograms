

<?php
/*+++++++++++++++++++ Designed and implimented  +++++++++++++++++++++++++++++
    ++++++++++++++++++++ by Eng. Msk moscow Since 2020-07-13  ++++++++++++++*/
    @session_start();
    include("./includes/connection.php");

    $fromDate =$_POST['fromDate'];
    $toDate=$_POST['toDate'];
    $Registration_ID=$_POST['Registration_ID'];
    $filter ='';
    if($Registration_ID != ""){
        $filter = " AND pd.Registration_ID='$Registration_ID'";
	}else{
        $filter="";
    }
    $bill_staus = $_POST['bill_staus'];
    if($bill_staus != 'All' ){
        $filter .= "AND Debt_status='$bill_staus'";
    }
    

    $sn = 1;
		echo "<div style='background-color:white;'>";
			echo "<table class='table table-hover table-responsive table-condensed' width='100%'>";
			echo "<thead>";
            echo "<tr>
                    <th style='width:3%' >NO.</th>
                    <th style='width:15%;'>Patient Name</th>
                    <th style='width:7%' >Registration #:</th>                    
                    <th style='width:5%;'>Age (Yrs)</th>
                    <th style='width:10%;'>Sponsor Name</th>                    
                    <th style='width:10%;'>Debit Status</th>
                    <th style='width:10%;'>Created By</th>
                    <th style='width:10%;'>Date</th>
                    <th style='width:5%;'>Total Amount</th>
                    <th style='width:5%;'>Amount Paid</th>
                    <th style='width:5%;'>Amount Remained</th>
                    <th style='width:15%;'>Action</th>
                    </tr>";
            echo "</thead>
            <tbody>";

            $Total_amount = 0;
            $Amount_remained =0;
            $Total_grand_debit =0;
                        $Total_amount_paid =0;
                        $Total_amount_remained =0;
		$querySubcategory = mysqli_query($conn,"SELECT cd.Check_In_ID, Guarantor_Name,Grand_Total_Direct_Cash, Patient_Name,Employee_Name, pd.Registration_ID,pd.Patient_Bill_ID, Grand_Total, pd.created_at,Debt_status,Debt_ID, Debt_creared_by, Clearance_date, TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS Age from tbl_admission ad, tbl_check_in_details cd, tbl_patient_debt pd, tbl_employee e, tbl_patient_registration pr, tbl_sponsor s  WHERE cd.Admission_ID = ad.Admision_ID and ad.Admision_ID=pd.Admision_ID AND (Grand_Total- Grand_Total_Direct_Cash) != 0 AND s.Sponsor_ID=pr.Sponsor_ID AND  pd.Registration_ID=pr.Registration_ID AND e.Employee_ID = pd.Employee_ID AND  pd.created_at BETWEEN '$fromDate' AND '$toDate' $filter") or die(mysqli_error($conn));
            if(mysqli_num_rows($querySubcategory)>0){
            while($row1 = mysqli_fetch_assoc($querySubcategory)) {
                $Registration_ID = $row1['Registration_ID'];
                $Patient_Name = $row1['Patient_Name'];
                $Grand_Total = $row1['Grand_Total'];
                $created_at = $row1['created_at'];
                $Debt_status = $row1['Debt_status'];
                $Grand_Total_Direct_Cash = $row1['Grand_Total_Direct_Cash'];
                $Debt_creared_by = $row1['Debt_creared_by'];
                $Clearance_date = $row1['Clearance_date'];
                $Patient_Bill_ID = $row1['Patient_Bill_ID'];
                $Guarantor_Name =$row1['Guarantor_Name'];
                $Employee_Name = $row1['Employee_Name'];
                $Debt_ID = $row1['Debt_ID'];
                $Age = $row1['Age'];
                $Check_In_ID = $row1['Check_In_ID'];
                $Total_amount = $Grand_Total -$Grand_Total_Direct_Cash;
                $Amount_paid =0;
                $Amount_paid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(Patient_debt_amount) as Amount_paid, debit_status, created_at, Paid_at FROM tbl_debt_cash_deposit WHERE Debt_ID='$Debt_ID' AND debit_status='Paid'"))['Amount_paid'];
                // while($dtrw = mysqli_fetch_assoc($selectAmount_paid)){
                //         $Amount_paid = $dtrw['Amount_paid'];
                //         $debit_status =$dtrw['debit_status'];
                //         $created_at = $dtrw['created_at'];
                //         $Paid_at = $dtrw['Paid_at'];
                        
                     //   if($debit_status=='Active'){
                          //  $Amount_paid =0;
                            $Amount_remained = $Total_amount - $Amount_paid;
                      //  }

                      //  if($debit_status=='Paid'){
                          //  $Amount_remained = $Total_amount - $Amount_paid;
                        //}
                echo"<tr>
                        <td style='text-align:right'> ".$sn."</td>
                        <td style='text-align:left'>".$Patient_Name."</td>
                        <td style='text-align:right'> ".$Registration_ID."</td>
                        
                        <td style='text-align:right'>$Age </td>
                        <td style='text-align:left'>".$Guarantor_Name."</td>
                        <td style='text-align:left'>$Debt_status</td>
                        <td style='text-align:left'>$Employee_Name</td>
                        <td style='text-align:center'>$created_at</td>
                        <td style='text-align:right'>".number_format($Total_amount).".00/=</td>
                        <td style='text-align: right;'> ".number_format($Amount_paid).".00/=</td>
                        <td style='text-align: right;'>
                        <input type='text' style='display:none;' value='$Registration_ID' id='Registration_ID$Debt_ID'>
                        <input type='text' style='display:none;' value='$Amount_remained' id='Patient_debt_$Debt_ID'>
                        <input type='text' style='display:none;' value='$Patient_Bill_ID' id='Patient_Bill_ID$Debt_ID'>
                        ".number_format( $Amount_remained).".00/=</td><td>";
                        if($Amount_remained >0){
                            echo "<input type='button' stye='width:40%;' class='art-button-green' onclick='open_dialogpaydebit($Debt_ID,\"$Registration_ID\", \"$Patient_Name\", \"$Amount_remained\")' value='PAY DEBIT'>";
                        }
                        echo "<a href='previewpatientbill.php?Registration_ID=$Registration_ID&Patient_Bill_ID=$Patient_Bill_ID&Check_In_ID=$Check_In_ID&Sponsor_ID=$Sponsor_ID&Transaction_Type=Cash_Bill_Details&Status=cld' class='art-button-green' stye='width:40%;' target='blank'>VIEW BILL</a>";
                echo "</td></tr>";
                        $Total_grand_debit +=$Grand_Total;
                        $Total_amount_paid +=$Amount_paid;
                        $Total_amount_remained += $Amount_remained; 
                $sn++;
                   // }
                }
            }else{
                echo "<tr><td colspan='8'><b style='color:red;'>No data Found </b></td></tr>";
            }
	
           echo "<tr>
                    <th colspan='7'><b>Total</b></th>
                    <th colspan='' style='text-align:right'>".number_format($Total_grand_debit).".00/=</th>
                    <th colspan='' style='text-align:right'>".number_format($Total_amount_paid).".00/=</th>
                    <th colspan='' style='text-align:right'>".number_format($Total_amount_remained).".00/=</th>
           </tr>";
		echo "</tbody></table>";
        echo "</div>";
        
	
?> 

