<?php
include("includes/connection.php");
//today function
$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    // $age ='';
    $This_date_today = $Today." 00:00";
}
//end
$filter = '';


$Patient_Name = $_GET['Patient_Name'];
$Patient_Number = $_GET['Patient_Number'];
$Sponsor_ID = $_GET['Sponsor_ID'];
// $Employee_ID = $_GET['Employee_ID'];
$Current_Employee_ID = $_GET['Current_Employee_ID'];
$date_From = $_GET['date_From'];
$date_To = $_GET['date_To'];
$Inp_Sub_Department_ID = $_GET['Sub_Department_ID'];
$Surgical_Type = $_GET['Surgical_Type'];

if (!empty($Patient_Number)) {
    $filter .= " AND pc.Registration_ID = '$Patient_Number'";
}

if (!empty($Patient_Name)) {
    $filter .= " AND pr.Patient_Name LIKE '%$Patient_Name%'";
}

if (($Sponsor_ID) != 'All') {
    $filter .= " AND pc.Sponsor_ID =  '$Sponsor_ID'";
}

if (($Current_Employee_ID) != 'All') {
    $filter .= " AND ilc.Consultant_ID =  '$Current_Employee_ID'";
}


if (!empty($date_From) && !empty($date_To)) {
    $filter .= " AND ilc.Service_Date_And_Time BETWEEN '$date_From' AND '$date_To'";
}else{
    $filter .= " AND ilc.Service_Date_And_Time >= '$This_date_today'";

}

if($Inp_Sub_Department_ID != 'All'){
    $filter .= " AND ilc.finance_department_id = '$Inp_Sub_Department_ID'";
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
            
            $select_Filtered_Donors = mysqli_query($conn, "SELECT pc.Registration_ID, sd.Sub_Department_Name, pr.Patient_Name, sap.Anaesthesia_Type, ilc.Transaction_Date_And_Time, pr.Date_Of_Birth, pr.Gender, 
            pc.consultation_ID, pr.Phone_Number, em.Employee_Name, ilc.Consultant_ID, ilc.Priority,i.Product_Name, 
            ilc.Service_Date_And_Time,ilc.Payment_Cache_ID,ilc.theater_room_id, 
            ilc.Payment_Item_Cache_List_ID, ilc.Sub_Department_ID FROM tbl_item_list_cache ilc, tbl_sub_department sd, tbl_payment_cache pc, tbl_surgery_appointment sap,
            tbl_patient_registration pr, tbl_items i, tbl_employee em WHERE pr.Registration_ID = pc.Registration_ID AND sd.Sub_Department_ID = ilc.Sub_Department_ID AND
            pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Surgery' AND ilc.Status IN ('active','paid') AND 
            i.Item_ID = ilc.Item_ID AND em.Employee_ID = sap.Surgeon_filled $filter AND ilc.Payment_Item_Cache_List_ID = sap.Payment_Item_Cache_List_ID AND sap.Surgery_Status = 'Submitted' AND sap.Final_Decision = 'Accepted' ORDER BY ilc.Service_Date_And_Time") or die(mysqli_error($conn));

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
                    $Anaesthesia_Type =$rows['Anaesthesia_Type'];


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
                                <td>".$Anaesthesia_Type."</td>";
                        echo "<td><a href='Patientfile_Record_Detail.php?Registration_ID=".$Registration_ID."&Patient_Payment_ID=&Patient_Payment_Item_List_ID=&PatientFile=PatientFileThisForm&position=out&this_page_from=patient_record' target='_blank' class='art-button-green'>PATIENT FILE</a></td>";
                        echo "
                                <td><select onchange='set_priority(".$Payment_Item_Cache_List_ID.")' id='priority".$Payment_Item_Cache_List_ID."' style='text-align: center;width:100%;display:inline; height: 32px; border-radius: 5px; font-size: 12px;'>
                                        <option "; if($Priority == 'Normal'){ echo "selected='selected'"; } echo " value='Normal'>Routine</option>
                                        <option "; if($Priority == 'Urgent'){ echo "selected='selected'"; } echo " value='Urgent'>Emergency</option>
                                    </select>
                                </td>";
                        // echo "<td></td>";
                        echo "<td>
                                <select id='assign".$Payment_Item_Cache_List_ID."' $condition class='Mark_This_Item' onchange='add_reason(".$Payment_Item_Cache_List_ID.")' style='text-align: center;width:100%;display:inline; height: 32px; border-radius: 5px; font-size: 12px;'>
                                    <option>Accept</option>
                                    <option>Reject</option>
                                </select>
                            </td>
                        </tr>";
                    
                    $num++;
                }
            }else{
                echo "<tr><td style='font-size: 17px; color: #bd0d0d; font-weight: bold; text-align: center;' colspan='10'>NO PATIENT SELECTED FOR THIS OPERATION, PLEASE SELECT TO PREPARE SURGERY LIST</td></tr>";
            }

            if(mysqli_num_rows($select_Filtered_Donors)>0){
                echo '<tr>
                            <td colspan="10" style="text-align: center;"><input type="button" name="Submit_Information" id="Submit_Information" value="APPROVE SURGERY LIST"  style="font-weight: bold; padding: 10px; border-radius: 7px;" class="art-button-green" onclick="save_surgery_list()"></td></tr>
                ';
                
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
