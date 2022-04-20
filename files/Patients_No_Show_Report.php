<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Date_From'])){
        $Date_From = $_GET['Date_From'];
    }else{
        $Date_To = '';
    }
    
    if(isset($_GET['Date_To'])){
        $Date_To = $_GET['Date_To'];
    }else{
        $Date_To = '';
    }
    
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    
     if(isset($_GET['Patient_Name_No'])){
        $Patient_Name_No = $_GET['Patient_Name_No'];  
        $patient_no="AND pr.Registration_ID LIKE '%$Patient_Name_No%'";
    }else{
        $Patient_Name_No = '';
        $patient_no='';
    }
	
?>


<legend style="background-color:#006400;color:white;padding:5px;" align="right"><b>LIST OF CHECKED IN PATIENTS</b></legend>
    <table width=100%>
        <tr><td colspan="10"><hr></td></tr>
        <tr>
            <td width=2%><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width=6%><b>PATIENT#</b></td>
            <td width=7%><b>SPONSOR</b></td>
            <td width=10%><b>PHONE#</b></td>
            <td width=8%><b>CHECK-IN</b></td>
            <td width=12%><b>DATE</b></td>
            <td width=12%><b>E NAME</b></td>
            <td width=18%><b>PATIENT DIRECTION</b></td>
            <td width=11%><b>DESTINATION</b></td>
        </tr>
        <tr><td colspan="10"><hr></td></tr>
        <?php

								
            $temp = 0; $Destination = '';
            
            if(isset($_GET['Patient_Name']) || ($_GET['Patient_Name_No'])){    
                $select = mysqli_query($conn,"select sp.Guarantor_Name, pr.Registration_ID, pr.Patient_Name, pr.Phone_Number, ci.Type_Of_Check_In, ci.Check_In_Date_And_Time, emp.Employee_Name, ci.Check_In_ID
					from tbl_check_in ci, tbl_employee emp, tbl_patient_registration pr, tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_payment_item_list ppl
                                        where pr.Registration_ID = ci.Registration_ID and
					ci.Employee_ID = emp.Employee_ID and
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        ppl.Process_Status = 'no show' and
                                        pp.Check_In_ID = ci.Check_In_ID and
                                        pr.Patient_Name like '%$Patient_Name%' and
										sp.Sponsor_ID = pr.Sponsor_ID $patient_no
                                        group by pp.Patient_Payment_ID order by ci.Check_In_Date_And_Time desc LIMIT 20") or die(mysqli_error($conn));
            }else{
                $select = mysqli_query($conn,"select sp.Guarantor_Name, pr.Registration_ID, pr.Patient_Name, pr.Phone_Number, ci.Type_Of_Check_In, ci.Check_In_Date_And_Time, emp.Employee_Name, ci.Check_In_ID
					from tbl_check_in ci, tbl_employee emp, tbl_patient_registration pr, tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_payment_item_list ppl
                                        where pr.Registration_ID = ci.Registration_ID and
					ci.Employee_ID = emp.Employee_ID and
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        ppl.Process_Status = 'no show' and
                                        pp.Check_In_ID = ci.Check_In_ID and
                                        ci.Check_In_Date between '$Date_From' and '$Date_To' and
					sp.Sponsor_ID = pr.Sponsor_ID
                                        group by pp.Patient_Payment_ID order by ci.Check_In_Date_And_Time desc LIMIT 50") or die(mysqli_error($conn));
            }
            
            
            $num = mysqli_num_rows($select);
            if($num > 0){
                while($row = mysqli_fetch_array($select)){
                    //get destination
                    //$select_location  = mysqli_query($conn,"select ") or die(mysqli_error($conn));
                    $Check_In_ID = $row['Check_In_ID'];
                    $get = mysqli_query($conn,"select ppl.Patient_Direction, ppl.Consultant_ID, ppl.Consultant, ppl.Check_In_Type from
                                            tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
                                                pp.Check_In_ID = '$Check_In_ID' and
                                                    pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                                                        group by pp.Check_In_ID order by Patient_Payment_Item_List_ID limit 1") or die(mysqli_error($conn));
                    $num_rows = mysqli_num_rows($get);
                    if($num_rows > 0){
                        while($data = mysqli_fetch_array($get)){
                            $Patient_Direction = $data['Patient_Direction'];
                            $Consultant_ID = $data['Consultant_ID'];
                            $Check_In_Type = $data['Check_In_Type'];
                            $Consultant = $data['Consultant'];
                            
                            if(strtolower($Patient_Direction) == 'others'){
                                $Patient_Direction = $Check_In_Type;
                            }
                            
                            if(strtolower($Check_In_Type) == 'doctor room'){
                                if(strtolower($Patient_Direction) == 'direct to doctor' || strtolower($Patient_Direction) == 'direct to doctor via nurse station'){
                                    //get the doctor name
                                    $select_doctor = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Consultant_ID'") or die(mysqli_error($conn));
                                    $no_of_rows = mysqli_num_rows($select_doctor);
                                    if($no_of_rows > 0){
                                        while($detail = mysqli_fetch_array($select_doctor)){
                                            $Destination= 'Dr. '.$detail['Employee_Name'];
                                        }
                                    }else{
                                        $Destination = $Consultant;
                                    }
                                }else{
                                    $Destination = $Consultant;
                                }
                            }else{
                                $Destination = $Check_In_Type;
                            }
                        }
                    }else{
                        $Destination = '';
                        $Patient_Direction = '';
                    }
                    
                    
                    
                    echo "<tr>
                        <td>".++$temp."</td>
                        <td>".$row['Patient_Name']."</td>
                        <td>".$row['Registration_ID']."</td>
                        <td>".$row['Guarantor_Name']."</td>
                        <td>".$row['Phone_Number']."</td>
                        <td>".$row['Type_Of_Check_In']."</td>
                        <td>".$row['Check_In_Date_And_Time']."</td>
                        <td>".$row['Employee_Name']."</td>
                        <td>".$Patient_Direction."</td>
                        <td>".$Destination."</td>
                    </tr>";
                }
            }
        ?>
    </table>