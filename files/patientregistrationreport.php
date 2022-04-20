<?php
    session_start();
    include("./includes/connection.php");

    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Emp_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Emp_Name = '';
    }
    
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

    //get sponsor name
    if($Sponsor_ID == '0'){
        $Guarantor_Name = 'All';
    }else{        
        $get_sponsor = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($get_sponsor);
        if($num > 0){
            while ($data = mysqli_fetch_array($get_sponsor)) {
                $Guarantor_Name = $data['Guarantor_Name'];
            }
        }
    }

    $selected = '';
    $Grand_Total_Paid = 0;
    $Grand_Total_Check_In = 0;
    $Grand_Total_Not_Check_In = 0;
    $Grand_Registred = 0;
    $Grand_Total_My_Check_In = 0;
    $Grand_Total_Not_Paid = 0;
    $Grand_Total_My_Check_In_Others = 0;
    $num_rows = 0;
    $My_Check_In = 0;
    $temp = 0;
    $htm = "<center><table width ='100%' height = '30px'>
                <tr><td style='text-align: center;'>
                <img src='./branchBanner/branchBanner.png'>
                </td></tr>
            </table></center><br/>";
    $htm .= "<span style='text-align: left; text-size: x-small;'>PATIENTS REGISTRATION PERFORMANCE</span><br/>";
    $htm .= "<span style='text-align: left; text-size: x-small;'>SPONSOR ~ ".$Guarantor_Name."</span><br/>";
    $htm .= "<span style='text-align: left; text-size: x-small;'>START DATE & TIME ~ ".$Date_From."</span><br/>";
    $htm .= "<span style='text-align: left; text-size: x-small;'>END DATE & TIME ~ ".$Date_To."</span><br/>";


$htm .= "<table width=100% border=0>
        <thead><tr id='thead'>
                <td width=5%><span style=' text-size: xx-small;'>SN</span></td>
                <td><span style=' text-size: xx-small;'>EMPLOYEE NAME</span></td>
                <td width='11%' style='text-align: center;'><span style=' text-size: xx-small;'>REGISTERED</span></td>
                <td width='11%' style='text-align: center; text-size: x-small;'><span style=' text-size: xx-small;'>CHECKED-IN</span></td>
                <td width='11%' style='text-align: center; text-size: x-small;'><span style=' text-size: xx-small;'>NOT CHECKED-IN</span></td>
                <td width='11%' style='text-align: center; text-size: x-small;'><span style=' text-size: xx-small;'>CHECKED-IN FROM VISITORS PAGE</span></td>
                <td width='11%' style='text-align: center; text-size: x-small;'><span style=' text-size: xx-small;'>CHECKED-IN & PAID</span></td>
                <td width='11%' style='text-align: center; text-size: x-small;'><span style=' text-size: xx-small;'>CHECKED-IN & NOT PAID</span></td>
                <td width='11%' style='text-align: center; text-size: x-small;'><span style=' text-size: xx-small;'>TOTAL CHECKED-IN</span></td>
            </tr></thead><tr><td colspan='9'><hr></td></tr>";
            
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
                    
                    //filter all transactions based on selected cashier
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
                                                    ci.Employee_ID = '$Employee_ID' and
                                                    pr.Registration_ID = ci.Registration_ID and
                                                    Check_In_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
                    }else{
                        $get_others = mysqli_query($conn,"select count(Check_In_ID) as Amount from tbl_check_in ci, tbl_patient_registration pr where 
                                                    ci.Employee_ID = '$Employee_ID' and
                                                    pr.Registration_ID = ci.Registration_ID and
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
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' group by Ci.Check_In_ID") or die(mysqli_error($conn));
                    }else{
                        $get_others = mysqli_query($conn,"select ci.Check_In_ID from tbl_patient_payments pp, tbl_check_in ci where
                                                ci.Employee_ID = '$Employee_ID' and
                                                pp.Check_In_ID = ci.Check_In_ID and
                                                pp.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' group by Ci.Check_In_ID") or die(mysqli_error($conn));
                    }

                    $nums = mysqli_num_rows($get_others);
                    $Total_Paid = $nums; 


                    $htm .= "<tr>
                            <td>".++$temp.".</td>
                            <td>".$Employee_Name."</td>
                            <td style='text-align: center;'>
                            ".$num_rows."                        
                            </td><td style='text-align: center;'>
                            ".$Total_Check_In."
                            </td><td style='text-align: center;'>
                            ".$Total_Not_Check_In."
                            </td><td style='text-align: center;'>".($My_Check_In - $Total_Check_In)."
                            </td><td style='text-align: center;'>".($Total_Paid)."</td>
                            </td><td style='text-align: center;'>".($My_Check_In-$Total_Paid)."</td>
                            </td><td style='text-align: center;'>".($My_Check_In)."</td>
                            </tr>".
                            $Grand_Total_My_Check_In += ($My_Check_In);
                            $Grand_Total_Paid += $Total_Paid;
                            $Grand_Total_Not_Paid += ($My_Check_In-$Total_Paid);
                            $Grand_Total_My_Check_In_Others += ($My_Check_In - $Total_Check_In);
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
                                                    ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' group by Ci.Check_In_ID") or die(mysqli_error($conn));
                        }else{
                            $get_others = mysqli_query($conn,"select ci.Check_In_ID from tbl_patient_payments pp, tbl_check_in ci where
                                                    ci.Employee_ID = '$Employee_ID' and
                                                    pp.Check_In_ID = ci.Check_In_ID and
                                                    pp.Sponsor_ID = '$Sponsor_ID' and
                                                    ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' group by Ci.Check_In_ID") or die(mysqli_error($conn));
                        }
                        $nums = mysqli_num_rows($get_others);
                        $Total_Paid = $nums;                        

                    $htm .="<tr>
                            <td>".++$temp."</td>
                            <td>".$Employee_Name."</td>
                            <td style='text-align: center;'>".$num_rows."</td>
                            <td style='text-align: center;'>".$Total_Check_In."</td>
                            <td style='text-align: center;'>".$Total_Not_Check_In."</td>
                            <td style='text-align: center;'>".($My_Check_In - $Total_Check_In);

                    $Grand_Total_My_Check_In_Others += ($My_Check_In - $Total_Check_In);
                    $Grand_Total_My_Check_In += ($My_Check_In);
                                
                    $htm .= "</td>
                            <td style='text-align: center;'>".($Total_Paid);
                    $Grand_Total_Paid += $Total_Paid;
                                    
                    $htm .= "</td><td style='text-align: center;'>".($My_Check_In-$Total_Paid); 
                    $Grand_Total_Not_Paid += ($My_Check_In-$Total_Paid);
                    $htm .=  "</td><td style='text-align: center;'>".($My_Check_In)."</td></tr>";
                }
            }










            $htm .= "<tr><td colspan='9'><hr></td></tr>";
            $htm .= "<tr><td colspan='2' style='text-align: left;'>
                        GRAND TOTAL
                    </td>
                    <td style='text-align: center; text-size: x-small;'>".$Grand_Registred."</td>
                    <td style='text-align: center; text-size: x-small;'>".$Grand_Total_Check_In."</td>
                    <td style='text-align: center; text-size: x-small;'>".$Grand_Total_Not_Check_In."</td>
                    <td style='text-align: center; text-size: x-small;'>".$Grand_Total_My_Check_In_Others."</td>
                    <td style='text-align: center; text-size: x-small;'>".$Grand_Total_Paid."</td>
                    <td style='text-align: center; text-size: x-small;'>".$Grand_Total_Not_Paid."</td>
                    <td style='text-align: center; text-size: x-small;'>".$Grand_Total_My_Check_In."</td>
                </tr>";
            $htm .= "</table>";
            $htm .= "</td></tr></table></center>";
            //$htm .= "<tr><td colspan='9'><hr></td></tr>";
?>
<?php
    //echo $htm;
    $htm .= "</table>";

    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('utf-8','A3', 0, '', 15,15,20,35,15,30, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($Emp_Name).'|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>