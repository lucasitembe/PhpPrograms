<?php
include("includes/connection.php");
//today function
$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $TodayDate = $Today.' 00:00';
    // $age ='';
}
//end
$filter = '';

$Sub_Department_ID = $_GET['Sub_Department_ID'];
$Current_Employee_ID = $_GET['Current_Employee_ID'];
$date_From = $_GET['date_From'];
$date_To = $_GET['date_To'];
$Patient_Number = $_GET['Patient_Number'];
$Patient_Name = $_GET['Patient_Name'];

if(!empty($Patient_Name)){
    $filter .= " AND pr.Patient_Name LIKE '%$Patient_Name%'";
}

if(!empty($Patient_Number)){
    $filter .= " AND pc.Registration_ID = '$Patient_Number'";
}
if(!empty($date_From) && !empty($date_To)){
    $filter .= " AND ilc.Service_Date_And_Time BETWEEN '$date_From' AND '$date_To'";
}else{
    $filter .= " AND ilc.Service_Date_And_Time >= '$TodayDate'";
}

if($Sub_Department_ID != 'All'){
    $filter .= " AND ilc.Sub_Department_ID = '$Sub_Department_ID'";
}
?>
        <style>
            table,tr,td{
                border-collapse:collapse !important;
                /* border:none !important; */

            }
            tr:hover{
                background-color:#eeeeee;
                cursor:pointer;
            }
        </style>

            <?php
            
            $select_Filtered_Donors = mysqli_query($conn, "SELECT pc.Registration_ID, sd.Sub_Department_Name, pr.Patient_Name, ilc.Transaction_Date_And_Time, pr.Date_Of_Birth, pr.Gender, pc.consultation_ID, pr.Phone_Number, ilc.Consultant_ID, ilc.Priority,i.Product_Name, ilc.Service_Date_And_Time,ilc.Payment_Cache_ID,ilc.theater_room_id, ilc.Payment_Item_Cache_List_ID, ilc.Sub_Department_ID FROM tbl_item_list_cache ilc, tbl_sub_department sd, tbl_payment_cache pc, tbl_patient_registration pr, tbl_items i WHERE pr.Registration_ID = pc.Registration_ID AND sd.Sub_Department_ID = ilc.Sub_Department_ID AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Surgery' AND ilc.Status IN('paid','active') AND i.Item_ID = ilc.Item_ID AND ilc.Payment_Item_Cache_List_ID IN(SELECT Payment_Item_Cache_List_ID FROM tbl_surgery_appointment WHERE Surgery_Status = 'active' AND Final_Decision = 'Accepted') $filter ORDER BY ilc.Service_Date_And_Time") or die(mysqli_error($conn));

            if(mysqli_num_rows($select_Filtered_Donors)>0){
                $num = 1;
                while($rows = mysqli_fetch_assoc($select_Filtered_Donors)){
                    $Sub_Department_Name = $rows['Sub_Department_Name'];
                    $Registration_ID = $rows['Registration_ID'];
                    $Patient_Name = $rows['Patient_Name'];
                    $Date_Of_Birth = $rows['Date_Of_Birth'];
                    $Gender = $rows['Gender'];
                    $Service_Date_And_Time = $rows['Service_Date_And_Time'];
                    $Payment_Item_Cache_List_ID = $rows['Payment_Item_Cache_List_ID'];
                    // $Employee_Name = $rows['Employee_Name'];
                    $Product_Name = $rows['Product_Name'];
                    $Priority = $rows['Priority'];
                    $my_theater_room_id = $rows['theater_room_id'];
                    $Sub_Department_ID = $rows['Sub_Department_ID'];

// die("SELECT em.Employee_Name FROM tbl_employee em, tbl_surgery_appointment sap WHERE sap.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND em.Employee_ID = sap.Surgeon_filled");
                    $Select_employee = mysqli_query($conn, "SELECT em.Employee_Name FROM tbl_employee em, tbl_surgery_appointment sap WHERE sap.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND em.Employee_ID = sap.Surgeon_filled");

                    if($Priority == 'Urgent'){
                        $style = "style='background: #bd0d0d; color: white; font-weight: bold;'";
                        $Status_Surgery = "Emergency";
                    }else{
                        $style = "";
                        $Status_Surgery = "Regular";
                    }
                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($rows['Date_Of_Birth']);
                    $diff = $date1->diff($date2);
                    $age = $diff->y . " Years, ";
                    $age .= $diff->m . " Months, ";
                    $age .= $diff->d . " Days";

                    if(mysqli_num_rows($Select_employee)>0){

                        $Surgeon = mysqli_fetch_assoc($Select_employee)['Employee_Name'];
                    }else{
                        $Surgeon = "<select name='Surgeon_filled' class='Surgeon_filled' id='Surgeon_filled".$Payment_Item_Cache_List_ID."' onchange='add_reason(" . $Payment_Item_Cache_List_ID . ");' required='required'>
                                        <option selected='selected' value=''>SELECT SURGEON</option>";

                        $data = mysqli_query($conn,"SELECT Employee_ID, Employee_Name from tbl_employee WHERE Employee_Job_Code = 'Surgeon' AND Account_Status = 'Active' AND employee_signature IS NOT NULL");
                            while($row = mysqli_fetch_array($data)){                
                                $Surgeon .= "<option value='".$row['Employee_ID']."'>".$row['Employee_Name']."</option>";
                            }
                        
                    $Surgeon .= "</select>";
                    }

                    echo "<tr $style>
                                <td>".$num."</td>
                                <td>".$Registration_ID."</td>
                                <td>".$Patient_Name."</td>
                                <td>".$Product_Name."</td>
                                <td>" . $Service_Date_And_Time . "</td>";
                                echo "<td>".$Sub_Department_Name."</td>
                                <td>".$Surgeon."</td>";
                                echo "<td style='text-align: center'>
                                    <select style='text-align: center;width:100%;display:inline; height: 32px; border-radius: 5px; font-size: 12px;' name='room_name_" . $Payment_Item_Cache_List_ID . "' class='room_select' id='room_name_" . $Payment_Item_Cache_List_ID . "' onchange='set_room(" . $Payment_Item_Cache_List_ID . ");'>
                                        <option value='0'>~~Select Room~~</option>";

                                $select_sub_dept = mysqli_query($conn, "SELECT theater_room_id,theater_room_name FROM tbl_theater_rooms WHERE room_status = 'active'");
                                while ($data = mysqli_fetch_array($select_sub_dept)) {
                                    if ($data['theater_room_id'] == $my_theater_room_id) {
                                        echo "<option selected='selected' style='text-align: center' value='" . $data['theater_room_id'] . "'>" . $data['theater_room_name'] . "</option>";
                                    } else {
                                        echo "<option style='text-align: center' value='" . $data['theater_room_id'] . "'>" . $data['theater_room_name'] . "</option>";
                                    }
                                }

                        echo "</select></td>";
                        echo "
                                <td>".$Status_Surgery."</td>
                        </tr>";
                    
                    $num++;
                }
            }else{
                echo "<tr><td style='font-size: 17px; color: #bd0d0d; font-weight: bold; text-align: center;' colspan='9'>NO PATIENT SELECTED FOR THIS OPERATION, PLEASE SELECT TO PREPARE SURGERY LIST</td></tr>";
            }

            ?>
        </table>
        <!-- <?php

        
        ?> -->
<script>
    $('#date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_From').datetimepicker({value: '', step: 1});
    $('#date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 1});
    
         $('#date_Fromx').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_Fromx').datetimepicker({value: '', step: 1});

    $(document).ready(function (e){
        $("#Sponsor_ID").select2();
        $("#Employee_ID").select2();
        $("#Sub_Department_ID").select2();
        $(".room_select").select2();
    });

</script>
