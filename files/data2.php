<?php
include("includes/connection.php");

// $Select_Admission = mysqli_query($conn, "SELECT ilc.Payment_Item_Cache_List_ID, ilc.Item_ID, pc.Sponsor_ID, ilc.Patient_Payment_ID FROM tbl_item_list_cache ilc, tbl_payment_cache pc WHERE pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Price = 0 ORDER BY ilc.Payment_Item_Cache_List_ID DESC") or die(mysqli_error($conn));
// $nums = 1;
$display = "<table class='table'>";

//     if(mysqli_num_rows($Select_Admission) > 0){
//         while($data = mysqli_fetch_assoc($Select_Admission)){
//             $Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
//             $Item_ID = $data['Item_ID'];
//             $Sponsor_ID = $data['Sponsor_ID'];
//             $Patient_Payment_ID = $data['Patient_Payment_ID'];

//             $Items_Price = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Items_Price FROM tbl_item_price WHERE Item_ID = '$Item_ID' AND Sponsor_ID = '$Sponsor_ID'"))['Items_Price'];

//             if($Patient_Payment_ID > 0){
//                 $malipo = $Patient_Payment_ID;
//             }else{
//                 $malipo = "HAIJALIPIWA";
//             }

//             if($Items_Price > 0){
//                 $Update_Item_LIst = mysqli_query($conn, "UPDATE tbl_item_list_cache SET Price = '$Items_Price' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));

//                 if($Patient_Payment_ID > 0){
//                     $Update_Payment_Item_LIst = mysqli_query($conn, "UPDATE tbl_patient_payment_item_list SET Price = '$Items_Price' WHERE Patient_Payment_ID = '$Patient_Payment_ID' AND Item_ID = '$Item_ID' AND Price = 0") or die(mysqli_error($conn));
//                 }

//                 $display .= "<tr><td>".$nums."</td><td>".$Payment_Item_Cache_List_ID."</td><td>".$malipo."</td></tr>";
//             }

//             $nums++;
//         }
//     }

//     $display .= "</table>";

    
//     header("Content-Type:application/xls");
//     header("content-Disposition: attachement; filename=download.xls");
//     echo $display;




// $select_Surgery = mysqli_query($conn, "SELECT * FROM `tbl_item_list_cache` WHERE `Payment_Item_Cache_List_ID` IN(SELECT Payment_Item_Cache_List_ID from tbl_surgery_appointment) AND Status = 'served' ORDER BY `tbl_item_list_cache`.`Payment_Item_Cache_List_ID` DESC") or die(mysqli_error($conn));

// while($data = mysqli_fetch_assoc($select_Surgery)){
//     $Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];

//     $UPDATE = mysqli_query($conn, "UPDATE `tbl_surgery_appointment` SET `Surgery_Status` = 'completed' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");

//     echo $Payment_Item_Cache_List_ID. "======> WAS UPDATED <br>";
// }
// $Snumber = 1;
// // die("SELECT Patient_Payment_ID, Item_ID, Check_In_Type FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID > 0 AND Item_ID > 0 AND (Sub_Department_ID = 0 OR Sub_Department_ID IS NULL)  ORDER BY Patient_Payment_Item_List_ID DESC LIMIT 20");
// // $onesha ='';
// echo "<table>";
// die("SELECT Patient_Payment_ID, Item_ID, Check_In_Type FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID > 0 AND Item_ID > 0 AND (Sub_Department_ID = 0 OR Sub_Department_ID IS NULL)  ORDER BY Patient_Payment_Item_List_ID ASC");
//     $Select_Lists = mysqli_query($conn, "SELECT Patient_Payment_ID, Item_ID, Check_In_Type FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID > 0 AND Item_ID > 0 AND (Sub_Department_ID = 0 OR Sub_Department_ID IS NULL)  ORDER BY Patient_Payment_Item_List_ID ASC") or die(mysqli_error($conn));
//         if(mysqli_num_rows($Select_Lists)>0){


//             while($dts = mysqli_fetch_assoc($Select_Lists)){
//                 $Patient_Payment_ID = $dts['Patient_Payment_ID'];
//                 $Item_ID = $dts['Item_ID'];
//                 $Check_In_Type = $dts['Check_In_Type'];
// // die("SELECT Sub_Department_ID FROM tbl_item_list_cache WHERE Patient_Payment_ID = '$Patient_Payment_ID' AND Item_ID = '$Item_ID' AND Check_In_Type = '$Check_In_Type' AND (Sub_Department_ID > 0 OR Sub_Department_ID IS NULL)");
//                 $Select_Depts = mysqli_query($conn, "SELECT Sub_Department_ID FROM tbl_item_list_cache WHERE Patient_Payment_ID = '$Patient_Payment_ID' AND Item_ID = '$Item_ID' AND Check_In_Type = '$Check_In_Type' AND (Sub_Department_ID > 0 OR Sub_Department_ID IS NULL)") or die(mysqli_error($conn));
//                     if(mysqli_num_rows($Select_Depts) > 0){
//                         while($data = mysqli_fetch_assoc($Select_Depts)){
//                             $Sub_Department_ID = $data['Sub_Department_ID'];

//                             $Update_receipt = mysqli_query($conn, "UPDATE tbl_patient_payment_item_list SET Sub_Department_ID = '$Sub_Department_ID' WHERE Patient_Payment_ID = '$Patient_Payment_ID' AND Item_ID = '$Item_ID' AND Check_In_Type = '$Check_In_Type'") or die(mysqli_error($conn));

//                                 // if($Update_receipt){
//                                     echo "<tr>
//                                                 <td>".$Snumber."</td>
//                                                 <td>".$Patient_Payment_ID."</td>
//                                                 <td>".$Check_In_Type."</td>
//                                                 <td>".$Item_ID."</td>
//                                                 <td>".$Sub_Department_ID."</td></tr>";
//                                 // }
//                                 $Snumber++;
//                         }
//                     }else{
//             echo "HAIPOOOOOOOOO77777777777777";

//                     }
//             }
//         }else{
//             echo "HAIPOOOOOOOOO";
//         }

//         echo "</table>"

$numssss = 1;
$Select_Mortuary_Patient = mysqli_query($conn, "SELECT pr.Patient_Name, ad.Registration_ID, ad.Admision_ID FROM tbl_admission ad, tbl_patient_registration pr WHERE pr.Registration_ID = ad.Registration_ID AND ad.Hospital_Ward_ID = 1 AND ad.Admission_Status = 'Admitted'") or die(mysqli_error($conn));
    if(mysqli_num_rows($Select_Mortuary_Patient)>0){
        while($nums = mysqli_fetch_assoc($Select_Mortuary_Patient)){
            $Patient_Name = $nums['Patient_Name'];
            $Registration_ID = $nums['Registration_ID'];
            $Admision_ID = $nums['Admision_ID'];

            $Update = mysqli_query($conn, "UPDATE tbl_patient_registration SET Diseased = 'yes' WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                $display .="<tr>";
                $display .="<td>".$numssss."</td>
                            <td>".$Registration_ID."</td>
                            <td>".$Patient_Name."</td>
                            <td>".$Admision_ID."</td>
                            </tr>";

        }
    }
$display .= "</table>";

        header("Content-Type:application/xls");
        header("content-Disposition: attachement; filename=download.xls");
        echo $display;
mysqli_close($conn);
?>