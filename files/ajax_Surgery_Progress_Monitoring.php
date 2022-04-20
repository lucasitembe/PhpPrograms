<?php
include("includes/connection.php");
//today function
$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    // $age ='';
}
//end
$filter = ' AND DATE(ilc.Service_Date_And_Time) = CURDATE()';


$Current_Sub_Department_ID = $_GET['Sub_Department_ID'];
$Current_Employee_ID = $_GET['Current_Employee_ID'];
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

            $select_Filtered_Donors = mysqli_query($conn, "SELECT pc.Registration_ID, pr.Patient_Name, ilc.Transaction_Date_And_Time, pr.Date_Of_Birth, pr.Gender, 
            pc.consultation_ID, pr.Phone_Number, em.Employee_Name, ilc.Consultant_ID, i.Product_Name, 
            ilc.Service_Date_And_Time,ilc.Payment_Cache_ID,ilc.theater_room_id, ilc.Priority, 
            ilc.Payment_Item_Cache_List_ID, ilc.Sub_Department_ID FROM tbl_item_list_cache ilc, tbl_payment_cache pc, 
            tbl_patient_registration pr, tbl_items i, tbl_employee em WHERE pr.Registration_ID = pc.Registration_ID AND 
            pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Surgery' AND ilc.Status IN ('active','paid') AND 
            i.Item_ID = ilc.Item_ID AND em.Employee_ID = ilc.Consultant_ID AND ilc.Payment_Item_Cache_List_ID IN(SELECT Payment_Item_Cache_List_ID FROM tbl_surgery_appointment WHERE Surgery_Status IN('active', 'on progress')) $filter ORDER BY ilc.Service_Date_And_Time ASC") or die(mysqli_error($conn));

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
                    $Employee_Name = $rows['Employee_Name'];
                    $Product_Name = $rows['Product_Name'];
                    $Priority = $rows['Priority'];
                    $my_theater_room_id = $rows['theater_room_id'];
                    $Sub_Department_ID = $rows['Sub_Department_ID'];
                    
                    $Sub_Department_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Sub_Department_ID'"))['Sub_Department_Name'];


                    if($Priority == 'Urgent'){
                        $style = "style='background: #bd0d0d; color: white; font-weight: bold;'";
                        $Priority = "Emergency";
                    }else{
                        $style = "";
                        $Priority = "Routine";
                    }
                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($rows['Date_Of_Birth']);
                    $diff = $date1->diff($date2);
                    $age = $diff->y . " Years, ";
                    $age .= $diff->m . " Months, ";
                    $age .= $diff->d . " Days";

                    $Status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Surgery_Status FROM tbl_surgery_appointment WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'"))['Surgery_Status'];

                    echo "<tr $style>
                                <td>".$num."</td>
                                <td>".$Registration_ID."</td>
                                <td>".$Patient_Name."</td>
                                <td>".$Product_Name."</td>
                                <td onclick='Date_Time(" . $Payment_Item_Cache_List_ID . ")' title='Click Surgery Date incase you want to edit it.' id='Date_Time" . $Payment_Item_Cache_List_ID . "'>" . $Service_Date_And_Time . "</td>
                                <td>".$Sub_Department_Name."</td>
                                <td>".$Employee_Name."</td>";
                        echo "
                                <td>$Priority</td>";
                        echo "<td>
                                <select id='assign".$Payment_Item_Cache_List_ID."' $condition class='Mark_This_Item' onchange='add_reason(".$Payment_Item_Cache_List_ID.")' style='text-align: center;width:100%;display:inline; height: 32px; border-radius: 5px; font-size: 12px;'>
                                    <option "; if($Status == 'Active') { echo "selected='selected'"; } echo">Waiting</option>
                                    <option "; if($Status == 'On Progress') { echo "selected='selected'"; } echo">On Progress</option>
                                    <option "; if($Status == 'Completed') { echo "selected='selected'"; } echo">Completed</option>
                                </select>
                            </td>
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
