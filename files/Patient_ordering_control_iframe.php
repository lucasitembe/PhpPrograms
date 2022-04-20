<?php
include("./includes/connection.php");
$filter="";
$filterItems = '';
// $filter2="";
//today function
$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $Start = $Today." 00:00";
    $End = $Today." 23:59";
    // $age ='';
}
//end

    $Patient_Name=$_POST['Patient_Name'];
    $Patient_Number=$_POST['Patient_Number'];
    $Date_From=$_POST['Date_From'];
    $Date_To=$_POST['Date_To'];
    $Employee_ID=$_POST['Employee_ID'];
    $Sub_Department_ID=$_POST['Sub_Department_ID'];
    $Sponsor_ID=$_POST['Sponsor_ID'];
    $Check_In_Type =$_POST['Service_type'];
    $Item_ID = $_POST['Item_ID'];

    if(!empty($Patient_Name)){
       $filter.=" AND pr.Patient_Name LIKE '%$Patient_Name%'";
    }
    if(!empty($Patient_Number)){
       $filter .="AND pc.Registration_ID = '$Patient_Number'"; 
    }
    if(!empty($Date_From) && !empty($Date_To)){
        $filter .=" AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'"; 
     }else{
         $filter .= " AND DATE(pc.Payment_Date_And_Time) = CURDATE()";
     }
     if($Check_In_Type != 'All'){
         $filterItems .= " AND ilc.Check_In_Type = '$Check_In_Type'";
     }else{
         $filterItems .= " AND ilc.Check_In_Type NOT IN('Others', 'Doctor Room')";
     }
     if($Sponsor_ID != 'All'){
         $filter .= " AND pc.Sponsor_ID = '$Sponsor_ID'";
     }
     if($Employee_ID != 'All'){
         $filterItems .= " AND ilc.Consultant_ID = '$Employee_ID'";
     }
     if($Sub_Department_ID != 'All'){
         $filterItems .= " AND ilc.finance_department_id = '$Sub_Department_ID'";
     }
     if($Item_ID != 'All'){
        $filterItems .= " AND ilc.Item_ID = '$Item_ID'";
    }

?>
<center>
    <a href="Patient_ordering_control_Excell_report.php?Patient_Name=<?=$Patient_Name?>&Patient_Number=<?=$Patient_Number?>&Date_From=<?=$Date_From?>&Date_To=<?=$Date_To?>&Employee_ID=<?=$Employee_ID?>&Sub_Department_ID=<?=$Sub_Department_ID?>&Sponsor_ID=<?=$Sponsor_ID?>&Service_type=<?=$Check_In_Type?>&Item_ID=<?=$Item_ID?>" class='art-button-green' target='_blank'>PRINT IN EXCELL</a>
</center>
<table id="list_of_checked_in_n_discharged_tbl" class="table table-bordered">
    <thead>
        <tr>
            <th style="width:50px">S/No.</th>
            <th style='text-align: left;'>PATIENT NAME</th>
            <th style='text-align: left;'>PATIENT NUMBER</th>
            <th style='text-align: left;'>GENDER</th>
            <th style='text-align: left;'>AGE</th>
            <th style='text-align: left;'>SPONSOR</th>
            <th style='text-align: left;'>ORDERED DOCTOR</th>
            <th style='text-align: left; width: 20% !important'>SERVICE</th>
            <th style='text-align: left; width: 7% !important'>LOCATION</th>
            <th style='text-align: left; width: 10% !important'>ORDERED DATE</th>
            <th style='text-align: left;'>PAYMENT STATUS</th>
            <th style='text-align: left; width: 7% !important'>ORDER STATUS</th>
        </tr>
    </thead>
<tbody>
                       <?php 
                            $temp=1;


                            $Select_Patients = mysqli_query($conn, "SELECT pr.Patient_Name, pc.Registration_ID, pr.Gender, pr.Date_Of_Birth, pc.Payment_Cache_ID, pc.Billing_Type, Guarantor_Name FROM tbl_payment_cache pc, tbl_patient_registration pr, tbl_sponsor sp WHERE sp.Sponsor_ID = pc.Sponsor_ID AND pr.Registration_ID = pc.Registration_ID $filter GROUP BY pc.Payment_Cache_ID") or die(mysqli_error($conn));
                            if(mysqli_num_rows($Select_Patients)>0){
                                while($list = mysqli_fetch_assoc($Select_Patients)){
                                    $Patient_Name = $list['Patient_Name'];
                                    $Registration_ID = $list['Registration_ID'];
                                    $Gender = $list['Gender'];
                                    $Date_Of_Birth = $list['Date_Of_Birth'];
                                    $Payment_Cache_ID = $list['Payment_Cache_ID'];
                                    $Billing_Type = $list['Billing_Type'];
                                    $Guarantor_Name = $list['Guarantor_Name'];
                                    // $Guarantor_Name = $list['Guarantor_Name'];


                                        $date1 = new DateTime($Today);
                                        $date2 = new DateTime($Date_Of_Birth);
                                        $diff = $date1->diff($date2);
                                        $age = $diff->y . " Years, ";
                                        $age .= $diff->m . " Months, ";
                                        $age .= $diff->d . " Days";

                                    $select_items = mysqli_query($conn, "SELECT Product_Name, Price, Quantity, Employee_Name as Consultant, Sub_Department_Name, Check_In_Type, ilc.Status, Transaction_Type, Transaction_Date_And_Time, Consultant_ID FROM tbl_employee em, tbl_sub_department sd, tbl_item_list_cache ilc, tbl_items i WHERE Payment_Cache_ID='$Payment_Cache_ID' AND ilc.removing_status = 'No' AND ilc.Item_ID= i.Item_ID AND em.Employee_ID = ilc.Consultant_ID AND sd.Sub_Department_ID = ilc.Sub_Department_ID $filterItems") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($select_items)>0){
                                            $products = '';		
                                            $Total_amount = 0;
                                            $num=1;
                                            $Sn=1;
                                            $Type='';
                                            $Types='';
                                            $Type='';
                                            $Date='';
                                            $malipo='';
                                            $Billing = '';
                                            $ConsultantS ='';

                                            while($rowD = mysqli_fetch_assoc($select_items)){
                                                $Product_Name = $rowD['Product_Name'];
                                                // $Price = $rowD['Price'];
                                                $Transaction_Date_And_Time = $rowD['Transaction_Date_And_Time'];
                                                $Check_In_Type = $rowD['Check_In_Type'];
                                                $Transaction_Type = $rowD['Transaction_Type'];
                                                $Status = $rowD['Status'];
                                                $Consultant = $rowD['Consultant'];
                                                // $Amount = ($Price * $Quantity);
                                        
                                        
                                                // if($Transaction_Type == 'Cash'){
                                                //     if($Status == 'paid'){
                                                //         $malipo = 'Cash Paid';
                                                //     }else{
                                                //         $malipo = 'Not Paid';
                                                //     }
                                                // }else{
                                                //     if($Status == 'paid'){
                                                //         $malipo = 'Billed';
                                                //     }else{
                                                //         $malipo = 'Not Billed';
                                                //     }
                                                // }
                                            if($Transaction_Type == 'Cash'){
                                                if($Status == 'paid'){
                                                    $Show = 'Paid';
                                                    $malipo = 'Cash Paid';
                                                }elseif($Status == 'Served'){
                                                    $Show = 'Done';
                                                    $malipo = 'Cash Paid';
                                                }elseif($Status == 'dispensed'){
                                                    $Show = 'Dispensed';
                                                    $malipo = 'Cash Paid';
                                                }elseif($Status == 'partial dispense'){
                                                    $Show = 'Partial Dispensed';
                                                    $malipo = 'Cash Paid';
                                                }elseif($Status == 'Sample Collected'){
                                                    $Show = 'Sample Collected';
                                                    $malipo = 'Cash Paid';
                                                }else{
                                                    $Show = 'Pending';
                                                    $malipo = 'Not Paid';
                                                }
                                            }else{
                                                if($Status == 'paid'){
                                                    $Show = 'Billed';
                                                    $malipo = 'Billed';
                                                }elseif($Status == 'Served'){
                                                    $Show = 'Done';
                                                    $malipo = 'Billed';
                                                }elseif($Status == 'dispensed'){
                                                    $Show = 'Dispensed';
                                                    $malipo = 'Billed';
                                                }elseif($Status == 'partial dispense'){
                                                    $Show = 'Partial Dispensed';
                                                    $malipo = 'Billed';
                                                }elseif($Status == 'Sample Collected'){
                                                    $Show = 'Sample Collected';
                                                    $malipo = 'Billed';
                                                }else{
                                                    $Show = 'Pending';
                                                    $malipo = 'Not Billed';
                                                }
                                            }

                                                $j++;
                                                if ($numberOfItem == 1) {
                                                    $products ='<b>'. $num.'. </b> '.$Product_Name;
                                                } else {
                                                    if ($track < $numberOfItem) {
                                                        $products .= '<b>'.$num.'.</b>  '.$Product_Name.'<br/> ';
                                                    } else {
                                                        $products .='<b>'. $num.'. </b> '.$Product_Name .'<br/> ';				
                                                    }
                                                }
                                        
                                                if ($numberOfcheck == 1) {
                                                    $Type ='<b>'. $Sn.'. </b> '.$Check_In_Type;
                                                } else {
                                                    if ($trackss < $numberOfcheck) {
                                                        $Type = '<b>'.$Sn.'.</b>  '.$Check_In_Type.'<br/> ';
                                                    } else {
                                                        $Type .='<b>'. $Sn.'. </b> '.$Check_In_Type .'<br/> ';				
                                                    }
                                                }
                                        
                                                if ($numberOfWard == 1) {
                                                    $Date ='<b>  '.$malipo.'</b>';
                                                } else {
                                                    if ($trackss < $numberOfWard) {
                                                        $Date .= '<b> '.$malipo.'</b><br/> ';
                                                    } else {
                                                        $Date .='<b>'.$malipo .'</b><br/> ';				
                                                    }
                                                }

                                                if ($numberOfType == 1) {
                                                    $Types ='<b>  '.$Show.'</b>';
                                                } else {
                                                    if ($trackss < $numberOfType) {
                                                        $Types .= '<b> '.$Show.'</b><br/> ';
                                                    } else {
                                                        $Types .='<b>'.$Show .'</b><br/> ';				
                                                    }
                                                }
                                        
                                                if ($numberOfBill == 1) {
                                                    $Billing =' '.$Transaction_Date_And_Time;
                                                } else {
                                                    if ($trackss < $numberOfBill) {
                                                        $Billing .=$Transaction_Date_And_Time.'<br/> ';
                                                    } else {
                                                        $Billing .=$Transaction_Date_And_Time .'<br/> ';				
                                                    }
                                                }
                                                                                        
                                                if ($numberOfdRS == 1) {
                                                    $ConsultantS =' '.$Consultant;
                                                } else {
                                                    if ($trackss < $numberOfdRS) {
                                                        $ConsultantS .=$Consultant.'<br/> ';
                                                    } else {
                                                        $ConsultantS .=$Consultant .'<br/> ';				
                                                    }
                                                }

                                                
                                                $num++;
                                                $Sn++;
                                                // $Total_amount +=$Amount;
                                            }
                                    
                                            echo "<tr class='idadi'><td>" . $temp . "</td>";
                                            echo "<td>" . ucwords(strtolower($Patient_Name)) . "</td>";
                                            echo "<td>" . $Registration_ID . "</td>";
                                            echo "<td>" . $Gender. "</td>";
                                            echo "<td>" . $age. "</td>";
                                            echo "<td>" . $Guarantor_Name. "</td>";
                                            echo "<td>" . $ConsultantS. "</td>";
                                            echo "<td>" . $products. "</td>";
                                            echo "<td>" . $Type . "</td>";
                                            echo "<td>" . $Billing."</td>";
                                            echo "<td>" . $Date."</td>";
                                            echo "<td><b>" . $Types."</b></td></tr>";
                                    
                                            $temp++;
                                    
                                        }
                                }
                            }
            ?>
    </tbody>
</table>