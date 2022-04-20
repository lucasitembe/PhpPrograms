<?php
include("includes/connection.php");

// $Select_Admission = mysqli_query($conn, "SELECT ad.Registration_ID, pr.Patient_Name, ad.Admission_Date_Time, hw.Hospital_Ward_Name FROM tbl_admission ad, tbl_hospital_ward hw, tbl_patient_registration pr WHERE ad.Hospital_Ward_ID NOT IN('1','12') AND ad.Discharge_Employee_ID IS NULL AND ad.Discharge_Date_Time IS NULL AND pr.Registration_ID = ad.Registration_ID AND hw.Hospital_Ward_ID = ad.Hospital_Ward_ID AND DATE(ad.Admission_Date_Time) <= '2021-08-17'") or die(mysqli_error($conn));
// $num = 1;
// $display = '<table class="table">';
// if(mysqli_num_rows($Select_Admission) > 0){
//     while($data = mysqli_fetch_assoc($Select_Admission)){
//         $Admission_Date_Time = $data['Admission_Date_Time'];
//         $Registration_ID = $data['Registration_ID'];
//         $Patient_Name = $data['Patient_Name'];
//         $Hospital_Ward_Name = $data['Hospital_Ward_Name'];

//         $display .= "<tr>
//                         <td>".$num."</td>
//                         <td>".$Registration_ID."</td>
//                         <td>".$Patient_Name."</td>
//                         <td>".$Hospital_Ward_Name."</td>
//                         <td>".$Admission_Date_Time."</td></tr>";

//                     $num++;

//     }
// }

//     header("Content-Type:application/xls");
//     header("content-Disposition: attachement; filename=download.xls");
//     echo $display;


$Snumber = 1;
// die("SELECT Patient_Payment_ID, Item_ID, Check_In_Type FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID > 0 AND Item_ID > 0 AND (Sub_Department_ID = 0 OR Sub_Department_ID IS NULL)  ORDER BY Patient_Payment_Item_List_ID DESC LIMIT 20");
// $onesha ='';
// $onesha = "<table>";
$onesha = '<table class="table">';


// die("SELECT Patient_Payment_ID, Item_ID, Check_In_Type FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID > 0 AND Item_ID > 0 AND (Sub_Department_ID = 0 OR Sub_Department_ID IS NULL)  ORDER BY Patient_Payment_Item_List_ID ASC LIMIT 10000");
$Select_Lists = mysqli_query($conn, "SELECT Patient_Payment_ID, Transaction_Date_And_Time, Item_ID, Check_In_Type FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID > 0 AND Item_ID > 0 AND (Sub_Department_ID = 0 OR Sub_Department_ID IS NOT NULL)  ORDER BY Patient_Payment_Item_List_ID desc LIMIT 50000") or die(mysqli_error($conn));
        if(mysqli_num_rows($Select_Lists)>0){


            while($dts = mysqli_fetch_assoc($Select_Lists)){
                $Patient_Payment_ID = $dts['Patient_Payment_ID'];
                $Item_ID = $dts['Item_ID'];
                $Check_In_Type = $dts['Check_In_Type'];
                $Transaction_Date_And_Time = $dts['Transaction_Date_And_Time'];

// die("SELECT Sub_Department_ID FROM tbl_item_list_cache WHERE Patient_Payment_ID = '$Patient_Payment_ID' AND Item_ID = '$Item_ID' AND Check_In_Type = '$Check_In_Type' AND (Sub_Department_ID > 0 OR Sub_Department_ID IS NULL)");
                $Select_Depts = mysqli_query($conn, "SELECT Sub_Department_ID FROM tbl_item_list_cache WHERE Patient_Payment_ID = '$Patient_Payment_ID' AND Item_ID = '$Item_ID' AND Check_In_Type = '$Check_In_Type' AND (Sub_Department_ID > 0 OR Sub_Department_ID IS NOT NULL)");
                    if(mysqli_num_rows($Select_Depts) > 0){
                        while($data = mysqli_fetch_assoc($Select_Depts)){
                            $Sub_Department_ID = $data['Sub_Department_ID'];

// die("UPDATE tbl_patient_payment_item_list SET Sub_Department_ID = '$Sub_Department_ID' WHERE Patient_Payment_ID = '$Patient_Payment_ID' AND Item_ID = '$Item_ID' AND Check_In_Type = '$Check_In_Type'");

                            $Update_receipt = mysqli_query($conn, "UPDATE tbl_patient_payment_item_list SET Sub_Department_ID = '$Sub_Department_ID' WHERE Patient_Payment_ID = '$Patient_Payment_ID' AND Item_ID = '$Item_ID' AND Check_In_Type = '$Check_In_Type'") or die(mysqli_error($conn));

                                // if($Update_receipt){
                                    $onesha .= "<tr>
                                                <td>".$Snumber."</td>
                                                <td>".$Patient_Payment_ID."</td>
                                                <td>".$Check_In_Type."</td>
                                                <td>".$Transaction_Date_And_Time."</td>
                                                <td>".$Item_ID."</td>
                                                <td>".$Sub_Department_ID."</td></tr>";
                                // }
                                $Snumber++;
                        }
                    }else{
            // $onesha .= "HAIPOOOOOOOOO77777777777777";
                        if($Check_In_Type == 'Free Round'){

                            $Update_receipt = mysqli_query($conn, "UPDATE tbl_patient_payment_item_list SET Sub_Department_ID = '$Sub_Department_ID' WHERE Patient_Payment_ID = '$Patient_Payment_ID' AND Item_ID = '$Item_ID' AND Check_In_Type = '$Check_In_Type'") or die(mysqli_error($conn));

                        }

                    }
            }
        }else{
            // $onesha .= "HAIPOOOOOOOOO";
        }

        $onesha .= "</table>";


        // header("Content-Type:application/xls");
        // header("content-Disposition: attachement; filename=download.xls");
        echo $onesha;

        mysqli_close($conn);
?>


<script>

var time = new Date().getTime();
     $(document.body).bind("mousemove keypress", function(e) {
         time = new Date().getTime();
     });
function refresh() {
         if(new Date().getTime() - time >= 30000) 
             window.location.reload(true);
            //  filter_list_of_patient_sent_to_cashier();
         else 
             setTimeout(refresh, 30000);
     }
     setTimeout(refresh, 30000);
</script>