<?php
include("includes/connection.php");
$Date_From = $_POST['Date_From'];
$Date_To = $_POST['Date_To'];
$Registration_ID = $_POST['Registration_ID'];
$subcategory_ID = $_POST['subcategory_ID'];
$Patient_Name = $_POST['Patient_Name'];
$Type = $_POST['Type'];

$filter = '';
$filter_cat = '';
if(!empty($Date_From) && !empty($Date_To)){
    $filter .= " AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'";
}else{
    $filter .= " AND DATE(pc.Payment_Date_And_Time) = CURDATE()";
}

if(!empty($Registration_ID)){
    $filter .= " AND pc.Registration_ID = '$Registration_ID'";
}

if(!empty($Patient_Name)){
    $filter .= " AND pr.Patient_Name LIKE '%$Patient_Name%'";
}

if($subcategory_ID != 'All'){
    $filter_cat .= " AND it.Item_Subcategory_ID = '$subcategory_ID'";
}else{
    $filter_cat .= '';
}

if($Type != 'All'){
    if($Type == 'Done'){
        $filter_cat .= " AND ilc.Status = 'Sample Collected'";
    }else{
        $filter_cat .= " AND ilc.Status IN ('Active','paid')";
    }
}else{
    $filter_cat .= '';
}

$Sn = 1;
$display = '';
// // die("SELECT pc.Payment_Cache_ID FROM tbl_payment_cache pc, tbl_patient_registration pr WHERE pr.Registration_ID = pc.Registration_ID AND pc.Payment_Cache_ID IN(SELECT Payment_Cache_ID AND ilc.Check_In_Type = 'Laboratory' $filter GROUP BY pc.Payment_Cache_ID");
// die("SELECT pc.Payment_Cache_ID, pr.Patient_Name, pc.Registration_ID, sp.Guarantor_Name FROM tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_sponsor sp, tbl_patient_registration pr WHERE pr.Registration_ID = pc.Registration_ID AND sp.Sponsor_ID = pc.Sponsor_ID AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Laboratory' $filter GROUP BY pc.Payment_Cache_ID");

        $Select_Patients = mysqli_query($conn, "SELECT pr.Date_Of_Birth, pc.Payment_Cache_ID, pr.Gender, pr.Patient_Name, pc.Registration_ID, sp.Guarantor_Name FROM tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_sponsor sp, tbl_patient_registration pr WHERE pr.Registration_ID = pc.Registration_ID AND sp.Sponsor_ID = pc.Sponsor_ID AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Laboratory' $filter GROUP BY pc.Payment_Cache_ID") or die(mysqli_error($conn));
            if(mysqli_num_rows($Select_Patients) > 0){
                while($dts = mysqli_fetch_assoc($Select_Patients)){
                    $Payment_Cache_ID = $dts['Payment_Cache_ID'];
                    $Patient_Name = $dts['Patient_Name'];
                    $Registration_ID = $dts['Registration_ID'];
                    $Guarantor_Name = $dts['Guarantor_Name'];
                    $Gender = $dts['Gender'];
                    $dob = $dts['Date_Of_Birth'];
                    
                    $date1 = new DateTime(date('Y-m-d'));
                    $date2 = new DateTime($dob);
                    $diff = $date1->diff($date2);
                    $age = $diff->y . " Years, ";
                    $age .= $diff->m . " Months, ";
                    $age .= $diff->d . " Days";
// die("SELECT ilc.Payment_Item_Cache_List_ID, its.Item_Subcategory_Name, em.Employee_Name AS Consultant, it.Product_Name, ilc.Status FROM tbl_item_list_cache ilc, tbl_employee em, tbl_items it, tbl_item_subcategory its WHERE ilc.Check_In_Type = 'Laboratory' AND it.Item_ID = ilc.Item_ID AND ilc.Payment_Cache_ID = '$Payment_Cache_ID' AND em.Employee_ID = ilc.Consultant_ID AND its.Item_Subcategory_ID = it.Item_Subcategory_ID AND ilc.Status <> 'removed' $filter_cat");
                    $select_items = mysqli_query($conn, "SELECT ilc.Payment_Item_Cache_List_ID, its.Item_Subcategory_Name, ilc.Transaction_Date_And_Time, em.Employee_Name AS Consultant, it.Product_Name, ilc.Status FROM tbl_item_list_cache ilc, tbl_employee em, tbl_items it, tbl_item_subcategory its WHERE ilc.Check_In_Type = 'Laboratory' AND it.Item_ID = ilc.Item_ID AND ilc.Payment_Cache_ID = '$Payment_Cache_ID' AND em.Employee_ID = ilc.Consultant_ID AND its.Item_Subcategory_ID = it.Item_Subcategory_ID AND ilc.Status <> 'removed' $filter_cat") or die(mysqli_error($conn));
                        if(mysqli_num_rows($select_items) > 0){
                            while($data = mysqli_fetch_assoc($select_items)){
                                $Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
                                $Item_Subcategory_Name = $data['Item_Subcategory_Name'];
                                $Consultant = $data['Consultant'];
                                $Product_Name = $data['Product_Name'];
                                $Status = $data['Status'];
                                $Transaction_Date_And_Time = $data['Transaction_Date_And_Time'];

                                
                                    if($Status == 'Sample Collected'){
                                        $Status_given = "Done";
                                        $style="style='color: white; background: green;'";
                                    }else{
                                        $Status_given = "Not Done";
                                        $style="style='color: white; background: #bd0d0d;'";
                                    }


                                    $display .="<tr>
                                                    <td>$Sn</td>
                                                    <td>$Patient_Name</td>
                                                    <td>$Registration_ID</td>
                                                    <td>$Guarantor_Name</td>
                                                    <td>$Gender</td>
                                                    <td>$age</td>
                                                    <td>".ucwords($Consultant)."</td>
                                                    <td>$Transaction_Date_And_Time</td>
                                                    <td>$Product_Name</td>
                                                    <td>$Item_Subcategory_Name</td>
                                                    <td $style>$Status_given</td></tr>";

                                                    $Sn++;
                            }
                        }
                }
            }else{
                $display .="<tr class='rows_list'>
                                <td colspan='11' style='font-size: 17px; color: #bd0d0d; font-weight: bold; text-align: center;'>NO TEST ORDERED IN A GIVEN PERIOD</td>
                            </tr>";
            }

            echo $display;


mysqli_close($conn);
?>