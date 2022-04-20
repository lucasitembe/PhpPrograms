<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Date_From'])){
        $Date_From = $_GET['Date_From'];
    }else{
        $Date_From = '';
    }
    
    if(isset($_GET['Date_To'])){
        $Date_To = $_GET['Date_To'];
    }else{
        $Date_To = '';
    }
    
    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = '';
    }

    $selected = '';

    //echo $Date_From.' , '.$Date_To.' , '.$Employee_ID.' , '.$Sponsor_ID; exit();

    $Grand_Total_Paid = 0;
    $Grand_Total_Check_In = 0;
    $Grand_Total_Not_Check_In = 0;
    $Grand_Registred = 0;
    $Grand_Total_My_Check_In = 0;
    $num_rows = 0;
    $My_Check_In = 0;
    $Grand_Total_Paid = 0;
    $Grand_Total_Not_Paid = 0;
    $Grand_Total_Paid_All = 0;
    $Grand_Total_My_Check_In_Others = 0;
?>

<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>PATIENTS REGISTRATION PERFORMANCE</b></legend>
<center>
    <table width=100% border=1>
        <?php
            $temp = 0;
            echo "<tr id='thead'>
                <td width=5%><b>SN</b></td>
                <td><b>EMPLOYEE NAME</b></td>
                <td style='text-align: center;'><b>REGISTERED</b></td>
                <td style='text-align: center;'><b>CHECKED-IN</b></td>
                <td style='text-align: center;'><b>NOT CHECKED-IN</b></td>
                <td style='text-align: center;'><b>CHECKED-IN OTHERS</b></td>
                <td style='text-align: center;'><b>TOTAL CHECKED-IN</b></td>
                <td style='text-align: center;'><b>CHECKED-IN & PAID</b></td>
                <td style='text-align: center;'><b>NOT PAID</b>&nbsp;&nbsp;&nbsp;</td>
            </tr>";
            echo '<tr><td colspan="9"><hr></td></tr>';                         

    //get employee details (Cashiers)
    if($Sponsor_ID == 0){
        if($Employee_ID == 0){
            $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_registration pr, tbl_employee emp where
                                            pr.Employee_ID = emp.Employee_ID and 
                                            pr.Registration_Date_And_Time between '$Date_From' and '$Date_To' 
                                            group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
        }else{
            $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_registration pr, tbl_employee emp where
                                            pr.Employee_ID = emp.Employee_ID and 
                                            pr.Registration_Date_And_Time between '$Date_From' and '$Date_To' and
                                            emp.Employee_ID = '$Employee_ID'
                                            group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
        }
    }else{
        if($Employee_ID == 0){
            $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_registration pr, tbl_employee emp where
                                            pr.Employee_ID = emp.Employee_ID and 
                                            pr.Registration_Date_And_Time between '$Date_From' and '$Date_To' and
                                            pr.Sponsor_ID = '$Sponsor_ID' 
                                            group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
        }else{
            $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_registration pr, tbl_employee emp where
                                            pr.Employee_ID = emp.Employee_ID and 
                                            pr.Registration_Date_And_Time between '$Date_From' and '$Date_To' and
                                            emp.Employee_ID = '$Employee_ID' and
                                            pr.Sponsor_ID = '$Sponsor_ID'
                                            group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
        }
    }
            $num = mysqli_num_rows($select_details);

            if($num > 0){
                while($row = mysqli_fetch_array($select_details)){
                	$selected .= ','.$row['Employee_ID']; //selected employees
                    $Total_Paid = 0;
                    $Amount_Paid = 0;
                    $Total_Check_In = 0;
                    $Total_Not_Check_In = 0;
                    $My_Check_In = 0;
                    
                    $Employee_ID = $row['Employee_ID']; //cashier id
                    $Employee_Name = ucwords(strtolower($row['Employee_Name'])); //cashier name
                    
                    //filter all transactions based on selected cashier and insurance
                    if($Sponsor_ID == 0){
                        $select = mysqli_query($conn,"select Registration_ID, Registration_Date from tbl_patient_registration where 
                                                Employee_ID = '$Employee_ID' and
                                                Registration_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"select Registration_ID, Registration_Date from tbl_patient_registration where 
                                                Employee_ID = '$Employee_ID' and
                                                Registration_Date_And_Time between '$Date_From' and '$Date_To' and
                                                Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
                    }
                    $num_rows = mysqli_num_rows($select);

                    if($num_rows > 0){
                        $Grand_Registred += $num_rows;
                        //$Grand_Registred = $num_rows;
                        while($data = mysqli_fetch_array($select)){
                            $Registration_ID = $data['Registration_ID'];
                            $Registration_Date = $data['Registration_Date'];

                            //check check in
                            $find_checkin = mysqli_query($conn,"select Registration_ID, Check_In_ID from tbl_check_in where Visit_Date = '$Registration_Date' and Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                            $num_checkin = mysqli_num_rows($find_checkin);
                            if($num_checkin > 0){
                                $Total_Check_In += 1;
                                $Grand_Total_Check_In += 1;
                                while ($data1 = mysqli_fetch_array($find_checkin)) {
                                    $Check_In_ID = $data1['Check_In_ID'];
                                }
                            }else{
                                $Grand_Total_Not_Check_In += 1;
                                $Total_Not_Check_In += 1;
                            }
                        }

                    }

                    //get checked in but not in my registration list based on dates & insurance selected
                    if($Sponsor_ID == 0){
                        $get_others = mysqli_query($conn,"select count(Check_In_ID) as Amount from tbl_check_in ci, tbl_patient_registration pr where 
                                                    pr.Registration_ID = ci.Registration_ID and
                                                    ci.Employee_ID = '$Employee_ID' and 
                                                    Check_In_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
                    }else{
                        $get_others = mysqli_query($conn,"select count(Check_In_ID) as Amount from tbl_check_in ci, tbl_patient_registration pr where 
                                                    pr.Registration_ID = ci.Registration_ID and
                                                    ci.Employee_ID = '$Employee_ID' and
                                                    pr.Sponsor_ID = '$Sponsor_ID' and 
                                                    Check_In_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
                    }
                    $nms = mysqli_num_rows($get_others);
                    if($nms > 0){
                        while ($ddd = mysqli_fetch_array($get_others)) {
                            $My_Check_In = $ddd['Amount'];
                        }
                    }

                    //get total paid based on dates & insurance selected
                    if($Sponsor_ID == 0){
                        $get_others = mysqli_query($conn,"select ci.Check_In_ID from tbl_patient_payments pp, tbl_check_in ci where
                                                ci.Employee_ID = '$Employee_ID' and
                                                pp.Check_In_ID = ci.Check_In_ID and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' group by ci.Check_In_ID") or die(mysqli_error($conn));
                    }else{
                        $get_others = mysqli_query($conn,"select ci.Check_In_ID from tbl_patient_payments pp, tbl_check_in ci where
                                                ci.Employee_ID = '$Employee_ID' and
                                                pp.Check_In_ID = ci.Check_In_ID and
                                                pp.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' group by ci.Check_In_ID") or die(mysqli_error($conn));
                    }
                    $nums = mysqli_num_rows($get_others);
                    $Total_Paid = $nums;                        
        ?>
                <tr>
                    <td>
                        <?php echo ++$temp; ?>.
                    </td>
                    <td>
                        <?php echo $Employee_Name; ?>
                    </td>
                    <td style='text-align: center;'>
                        <?php echo $num_rows; ?>
                    </td>
                    <td style='text-align: center;'>
                        <?php echo $Total_Check_In; ?>
                    </td>
                    <td style='text-align: center;'>
                        <?php echo $Total_Not_Check_In; ?>
                    </td>
                    <td style='text-align: center;'>
                        <?php
                            echo ($My_Check_In - $Total_Check_In);
                            $Grand_Total_My_Check_In_Others += ($My_Check_In - $Total_Check_In);
                            $Grand_Total_My_Check_In += ($My_Check_In);
                        ?>
                    </td>
                    <td style='text-align: center;'>
                            <?php echo ($My_Check_In); ?>
                    </td>
                    <td style='text-align: center;'>
                        <?php 
                            echo ($Total_Paid);
                            $Grand_Total_Paid += $Total_Paid;
                        ?>
                            
                    </td>
                    <td style='text-align: center;'>
                        <?php 
                            echo ($My_Check_In-$Total_Paid); 
                            $Grand_Total_Not_Paid += ($My_Check_In-$Total_Paid);
                        ?>
                    </td> 
                </tr>
            <?php
                }
                if($selected != ''){ $selected = ' and emp.Employee_ID NOT IN('.substr($selected, 1).')'; }
            }

                if(isset($_GET['Employee_ID'])){
			        $Employee_ID = $_GET['Employee_ID'];
			    }else{
			        $Employee_ID = 0;
			    }
            	
            	//get remainders employees
                //remainders who perform check in but not register
            	if($Employee_ID == 0){
            	    $select_r = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_check_in ci, tbl_employee emp where
	                                    ci.Employee_ID = emp.Employee_ID and 
	                                    ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'
	                                    $selected
	                                    group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            	}else{
            		$select_r = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_check_in ci, tbl_employee emp where
	                                    ci.Employee_ID = emp.Employee_ID and
	                                    emp.Employee_ID = '$Employee_ID' and
	                                    ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'
	                                    $selected
	                                    group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            	}

                $num2 = mysqli_num_rows($select_r);
                if($num2 > 0){

                	while($row = mysqli_fetch_array($select_r)){
                	$selected .= ','.$row['Employee_ID']; //selected employees
                    $Total_Paid = 0;
                    $Amount_Paid = 0;
                    $Total_Check_In = 0;
                    $Total_Not_Check_In = 0;
                    $My_Check_In = 0;
                    
                    $Employee_ID = $row['Employee_ID']; //cashier id
                    $Employee_Name = ucwords(strtolower($row['Employee_Name'])); //cashier name
                    
                    //filter all transactions based on selected cashier and insurance
                    if($Sponsor_ID == 0){
                        $select = mysqli_query($conn,"select Registration_ID, Registration_Date from tbl_patient_registration where 
                                                Employee_ID = '$Employee_ID' and
                                                Registration_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"select Registration_ID, Registration_Date from tbl_patient_registration where 
                                                Employee_ID = '$Employee_ID' and
                                                Registration_Date_And_Time between '$Date_From' and '$Date_To' and
                                                Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
                    }
                    $num_rows = mysqli_num_rows($select);

                    if($num_rows > 0){
                        $Grand_Registred += $num_rows;
                        //$Grand_Registred = $num_rows;
                        while($data = mysqli_fetch_array($select)){
                            $Registration_ID = $data['Registration_ID'];
                            $Registration_Date = $data['Registration_Date'];

                            //check check in
                            $find_checkin = mysqli_query($conn,"select Registration_ID, Check_In_ID from tbl_check_in where Visit_Date = '$Registration_Date' and Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                            $num_checkin = mysqli_num_rows($find_checkin);
                            if($num_checkin > 0){
                                $Total_Check_In += 1;
                                $Grand_Total_Check_In += 1;
                                while ($data1 = mysqli_fetch_array($find_checkin)) {
                                    $Check_In_ID = $data1['Check_In_ID'];

                                    //check payments
                                    /*$payment_check = mysqli_query($conn,"select  Patient_Payment_ID from tbl_patient_payments where
                                                                    Check_In_ID = '$Check_In_ID' group by Check_In_ID") or die(mysqli_error($conn));
                                    $payment_num = mysqli_num_rows($payment_check);
                                    if($payment_num > 0){
                                        $Total_Paid += 1;
                                    }*/
                                }
                            }else{
                                $Grand_Total_Not_Check_In += 1;
                                $Total_Not_Check_In += 1;
                            }
                        }

                    }

                    //get checked in but not in my registration list based on dates & insurance selected
                    if($Sponsor_ID == 0){
                        $get_others = mysqli_query($conn,"select count(Check_In_ID) as Amount from tbl_check_in ci, tbl_patient_registration pr where 
                                                    pr.Registration_ID = ci.Registration_ID and
                                                    ci.Employee_ID = '$Employee_ID' and 
                                                    Check_In_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
                    }else{
                        $get_others = mysqli_query($conn,"select count(Check_In_ID) as Amount from tbl_check_in ci, tbl_patient_registration pr where 
                                                    pr.Registration_ID = ci.Registration_ID and
                                                    ci.Employee_ID = '$Employee_ID' and
                                                    pr.Sponsor_ID = '$Sponsor_ID' and 
                                                    Check_In_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
                    }
                    $nms = mysqli_num_rows($get_others);
                    if($nms > 0){
                        while ($ddd = mysqli_fetch_array($get_others)) {
                            $My_Check_In = $ddd['Amount'];
                        }
                    }

                    //get total paid based on dates & insurance selected
                    if($Sponsor_ID == 0){
                        $get_others = mysqli_query($conn,"select ci.Check_In_ID from tbl_patient_payments pp, tbl_check_in ci where
                                                ci.Employee_ID = '$Employee_ID' and
                                                pp.Check_In_ID = ci.Check_In_ID and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' group by ci.Check_In_ID") or die(mysqli_error($conn));
                    }else{
                        $get_others = mysqli_query($conn,"select ci.Check_In_ID from tbl_patient_payments pp, tbl_check_in ci where
                                                ci.Employee_ID = '$Employee_ID' and
                                                pp.Check_In_ID = ci.Check_In_ID and
                                                pp.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' group by ci.Check_In_ID") or die(mysqli_error($conn));
                    }
                    $nums = mysqli_num_rows($get_others);
                    $Total_Paid = $nums;                        
        ?>
                <tr>
                    <td>
                        <?php echo ++$temp; ?>.
                    </td>
                    <td>
                        <?php echo $Employee_Name; ?>
                    </td>
                    <td style='text-align: center;'>
                        <?php echo $num_rows; ?>
                    </td>
                    <td style='text-align: center;'>
                        <?php echo $Total_Check_In; ?>
                    </td>
                    <td style='text-align: center;'>
                        <?php echo $Total_Not_Check_In; ?>
                    </td>
                    <td style='text-align: center;'>
                        <?php 
                            echo ($My_Check_In - $Total_Check_In);
                            $Grand_Total_My_Check_In_Others += ($My_Check_In - $Total_Check_In);
                            $Grand_Total_My_Check_In += ($My_Check_In);
                        ?>
                    </td>
                    <td style='text-align: center;'>
                            <?php echo ($My_Check_In); ?>
                    </td>
                    <td style='text-align: center;'>
                        <?php 
                            echo ($Total_Paid);
                            $Grand_Total_Paid += $Total_Paid;
                        ?>
                            
                    </td>
                    <td style='text-align: center;'>
                        <?php 
                            echo ($My_Check_In-$Total_Paid); 
                            $Grand_Total_Not_Paid += ($My_Check_In-$Total_Paid);
                        ?>
                    </td> 
                </tr>
            <?php
                }

			}



            echo "<tr><td colspan='9'><hr></td></tr>";
            echo "<tr><td colspan='2' style='text-align: left;'>
                        <b>GRAND TOTAL</b>
                    </td>
                    <td style='text-align: center;'><b>".$Grand_Registred."</b></td>
                    <td style='text-align: center;'><b>".$Grand_Total_Check_In."</b></td>
                    <td style='text-align: center;'><b>".$Grand_Total_Not_Check_In."</b></td>
                    <td style='text-align: center;'><b>".$Grand_Total_My_Check_In_Others."</b></td>
                    <td style='text-align: center;'><b>".$Grand_Total_My_Check_In."</b></td>
                    <td style='text-align: center;'><b>".$Grand_Total_Paid."</b></td>
                    <td style='text-align: center;'><b>".$Grand_Total_Not_Paid."</b>&nbsp;&nbsp;&nbsp;</td>
                </tr>";
            echo '</table>';
        ?>
    </td>
</tr>
    </table>
</center>