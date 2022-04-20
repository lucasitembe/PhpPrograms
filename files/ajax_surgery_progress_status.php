<?php
include("./includes/connection.php");
if(isset($_POST['Sub_Department_Name'])){
  $Sub_Department_Name=$_POST['Sub_Department_Name'];
  $filter = ' AND DATE(ilc.Service_Date_And_Time) = CURDATE()';

  $pendingtransaction = 0;
  $complete = 0;
//   AND ilc.theater_room_id IS NOT NULL AND em.Employee_ID = sap.Surgeon_filled 
  $query = "SELECT pc.Registration_ID, pr.Patient_Name, ilc.Transaction_Date_And_Time, sap.Surgeon_filled, pr.Date_Of_Birth, pr.Gender, sap.Anaesthesia_Type, 
            pc.consultation_ID, pr.Phone_Number, ilc.Consultant_ID, i.Product_Name, 
            TIME(ilc.Service_Date_And_Time) AS TIMES,ilc.Payment_Cache_ID,ilc.theater_room_id, ilc.Priority, 
            ilc.Payment_Item_Cache_List_ID, ilc.Sub_Department_ID FROM tbl_item_list_cache ilc, tbl_payment_cache pc, tbl_surgery_appointment sap, 
            tbl_patient_registration pr, tbl_items i WHERE pr.Registration_ID = pc.Registration_ID AND 
            pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Surgery' AND ilc.Status IN ('active','paid') AND
            i.Item_ID = ilc.Item_ID AND ilc.theater_room_id IS NOT NULL AND ilc.Payment_Item_Cache_List_ID = sap.Payment_Item_Cache_List_ID AND sap.Surgery_Status IN('active', 'on progress') $filter ORDER BY ilc.Service_Date_And_Time ASC";


  $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $Registration_ID=$patient_list_rows['Registration_ID'];
         $Patient_Name=$patient_list_rows['Patient_Name'];
         $Gender=$patient_list_rows['Gender'];
         $Surgeon_filled=$patient_list_rows['Surgeon_filled'];
         $Service_Date_And_Time=$patient_list_rows['Service_Date_And_Time'];   
         $Product_Name=$patient_list_rows['Product_Name'];
         $Sub_Department_ID=$patient_list_rows['Sub_Department_ID'];
        $my_theater_room_id = $patient_list_rows['theater_room_id'];
        $consultation_ID = $patient_list_rows['consultation_ID'];
        $Payment_Item_Cache_List_ID = $patient_list_rows['Payment_Item_Cache_List_ID'];
        $Priority = $patient_list_rows['Priority'];
        $TIMES =$patient_list_rows['TIMES'];
        $Anaesthesia_Type =$patient_list_rows['Anaesthesia_Type'];




        if(!empty($Surgeon_filled)){
            $Surgeon = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Surgeon_filled'"))['Employee_Name'];
        }else{
            $Surgeon = "<b>No Surgeon Dedicated</b>";
        }



    $Sub_Department_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Sub_Department_ID'"))['Sub_Department_Name'];
    $My_theater_Room = mysqli_fetch_assoc(mysqli_query($conn, "SELECT theater_room_name FROM tbl_theater_rooms WHERE theater_room_id = '$my_theater_room_id' AND room_status = 'active'"))['theater_room_name'];

    $Surgery_Status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Surgery_Status FROM tbl_surgery_appointment WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'"))['Surgery_Status'];

        $Select_Diagnosis = mysqli_query($conn, "SELECT disease_name, disease_code FROM tbl_disease td, tbl_disease_consultation tdc WHERE tdc.consultation_ID = '$consultation_ID' AND tdc.diagnosis_type = 'diagnosis' AND td.disease_ID = tdc.disease_ID");
            if(mysqli_num_rows($Select_Diagnosis)>0){
                $magonjwa = '';
                while($disease = mysqli_fetch_assoc($Select_Diagnosis)){
                    $disease_name = $disease['disease_name'];
                    $disease_code = $disease['disease_code'];

                    $magonjwa .= $disease_name." (<b>".$disease_code."</b>); ";
                }
            }

             $date1 = new DateTime($Today);
            $date2 = new DateTime($patient_list_rows['Date_Of_Birth']);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";

         if($Priority == 'Urgent' || $Priority == 'urgent'){
            if($Surgery_Status == "active" || $Surgery_Status == "Active"){
                $Status = 'Waiting..';
             }elseif($Surgery_Status == "on progress" || $Surgery_Status == "On Progress"){
                 $Status = 'On Progress..';
             }
            $change_color_style = "style='color: white; font-weight: bold;'";
            $style = "style='background: #bd0d0d; color: white; font-weight: bold;'";
         }else{
            if($Surgery_Status == "active" || $Surgery_Status == "Active"){
                $Status = 'Waiting..';
                 $change_color_style = "style='background:#ccc;color:black; font-weight: bold;'";
             }elseif($Surgery_Status == "on progress" || $Surgery_Status == "On Progress"){
                 $Status = 'On Progress..';
                 $change_color_style = "style='background:greenyellow; font-weight: bold;'";
             }
            $style = "";
         }
        //  if($Priority == 'Urgent'){
        //     $style = "style='background: #bd0d0d; color: white; font-weight: bold;'";
        // }else{
        //     $style = "";
        // }
         
         echo "
                <tr class='rows_list' $style >
                        <td>$count_sn.</td>
                        <td>$Patient_Name</td>
                        <td>$Registration_ID</td>
                        <td>$Gender</td>
                        <td>$age</td>
                        <td>$magonjwa</td>
                        <td>$Product_Name</td>
                        <td>$Surgeon</td>
                        <td>$Anaesthesia_Type</td>
                        <td>$TIMES</td>
                        <td>$Sub_Department_Name</td>
                        <td>$My_theater_Room</td>
                        <td $change_color_style>$Status</td>
                </tr>
                ";
        $count_sn++;
      }
      echo "<tr>
              <td colspan='13'><hr></td>
            </tr>";
  }else{
   echo "<tr class='rows_list'>
    <td colspan='13' style='font-size: 17px; color: #bd0d0d; font-weight: bold; text-align: center;'>NO PENDING SURGERY APPOINTED TODAY</td>
</tr> ";
 }
  
}else{
    // echo "mimi";
}

mysqli_close($conn);
?>