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
$filter = '';
$Current_Sub_Department_ID = $_GET['Sub_Department_ID'];
$Current_Employee_ID = $_GET['Current_Employee_ID'];

if($Current_Sub_Department_ID != 'All'){
    $filter .= " AND ilc.finance_department_id = '$Current_Sub_Department_ID'";
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
        <table width="100%" border=0  class="table table-collapse table-striped " style="border-collapse: collapse;border:1px solid black;">
            <tr style='background: #dedede; position: static !important;'>
                <th>S/No</th>
                <th>REGISTRATION #</th>
                <th>PATIENT NAME</th>
                <th>SURGERY NAME</th>
                <th>SERVICE DATE</th>
                <th>DEPARTMENT</th>
                <th>SURGEON</th>
                <th>PRIORITY</th>
                <th>ACTION</th>
            </tr>
            <?php
            
            $select_Filtered_Donors = mysqli_query($conn, "SELECT pc.Registration_ID, sd.Sub_Department_Name, pr.Patient_Name, ilc.Transaction_Date_And_Time, pr.Date_Of_Birth, pr.Gender, 
            pc.consultation_ID, pr.Phone_Number, em.Employee_Name, ilc.Consultant_ID, ilc.Priority,i.Product_Name, 
            ilc.Service_Date_And_Time,ilc.Payment_Cache_ID, 
            ilc.Payment_Item_Cache_List_ID, ilc.Sub_Department_ID FROM tbl_item_list_cache ilc, tbl_sub_department sd, tbl_payment_cache pc, tbl_surgery_appointment sap, 
            tbl_patient_registration pr, tbl_items i, tbl_employee em WHERE pr.Registration_ID = pc.Registration_ID AND sd.Sub_Department_ID = ilc.Sub_Department_ID AND
            pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Surgery' AND ilc.Status IN ('active','paid') AND 
            i.Item_ID = ilc.Item_ID AND em.Employee_ID = sap.Surgeon_filled $filter AND ilc.Payment_Item_Cache_List_ID = sap.Payment_Item_Cache_List_ID AND sap.Surgery_Status = 'pending' AND sap.Final_Decision = 'Accepted' ORDER BY ilc.Service_Date_And_Time") or die(mysqli_error($conn));

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


                    if($Priority == 'Urgent'){
                        $style = "style='background: #bd0d0d; color: white; font-weight: bold;'";
                    }else{
                        $style = "";
                    }
                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($rows['Date_Of_Birth']);
                    $diff = $date1->diff($date2);
                    $age = $diff->y . " Years, ";
                    $age .= $diff->m . " Months, ";
                    $age .= $diff->d . " Days";

                    echo "<tr $style>
                                <td>".$num."</td>
                                <td>".$Registration_ID."</td>
                                <td>".$Patient_Name."</td>
                                <td>".$Product_Name."</td>
                                <td onclick='Date_Time(" . $Payment_Item_Cache_List_ID . ")' title='Click Surgery Date incase you want to edit it.' id='Date_Time" . $Payment_Item_Cache_List_ID . "'>" . $Service_Date_And_Time . "</td>
                                <td>".$Sub_Department_Name."</td>
                                <td>".$Employee_Name."</td>
                                <td><select onchange='set_priority(".$Payment_Item_Cache_List_ID.")' id='priority".$Payment_Item_Cache_List_ID."' style='text-align: center;width:100%;display:inline; height: 32px; border-radius: 5px; font-size: 12px;'>
                                        <option "; if($Priority == 'Normal'){ echo "selected='selected'"; } echo " value='Normal'>Routine</option>
                                        <option "; if($Priority == 'Urgent'){ echo "selected='selected'"; } echo " value='Urgent'>Emergency</option>
                                    </select>
                                </td>
                                <td>
                                <select id='assign".$Payment_Item_Cache_List_ID."' $condition class='Mark_This_Item' onchange='add_reason(".$Payment_Item_Cache_List_ID.")' style='text-align: center;width:100%;display:inline; height: 32px; border-radius: 5px; font-size: 12px;'>
                                    <option value='Accept'>Accept</option>
                                    <option value='Reject'>Reject</option>
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
        <?php
            if(mysqli_num_rows($select_Filtered_Donors)>0){
            echo '<input type="button" name="Submit_Information" id="Submit_Information" value="SAVE SURGERY LIST"  style="font-weight: bold; padding: 10px; border-radius: 7px;" class="art-button-green" onclick="save_surgery_list()">
            <input type="button" name="Cancel" id="Cancel" style="font-weight: bold; padding: 10px; border-radius: 7px;" value="CANCEL" class="art-button-green" onclick="Close_Submit_Dialog()">  
            ';
            
            }
        
        ?>
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
