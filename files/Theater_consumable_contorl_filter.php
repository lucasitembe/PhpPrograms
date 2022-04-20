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


$Patient_Name = $_GET['Patient_Name'];
$Patient_Number = $_GET['Patient_Number'];
$Sponsor_ID = $_GET['Sponsor_ID'];
// $Employee_ID = $_GET['Employee_ID'];
$Current_Employee_ID = $_GET['Current_Employee_ID'];
$date_From = $_GET['date_From'];
$date_To = $_GET['date_To'];
$Inp_Sub_Department_ID = $_GET['Sub_Department_ID'];
$Surgical_Type = $_GET['Surgical_Type'];
$Current_Sub_Department_Name = $_GET['Current_Sub_Department_Name'];

$Sub_Department_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_ID FROM tbl_sub_department WHERE Sub_Department_Name = '$Current_Sub_Department_Name'"))['Sub_Department_ID'];



$filter = " AND ilc.Sub_Department_ID = '$Sub_Department_ID'";

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
    $filter .= " AND ilc.Service_Date_And_Time BETWEEN '$This_date_today' AND NOW()";

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
            
            $select_Filtered_Donors = mysqli_query($conn, "SELECT pc.Registration_ID, sd.finance_department_name, pc.Sponsor_ID, pr.Patient_Name, ilc.Transaction_Date_And_Time, pr.Date_Of_Birth, pr.Gender,  pr.Phone_Number, em.Employee_Name, i.Product_Name, ilc.Service_Date_And_Time,ilc.Payment_Cache_ID,ilc.theater_room_id, ilc.Payment_Item_Cache_List_ID FROM tbl_item_list_cache ilc, tbl_finance_department sd, tbl_payment_cache pc, tbl_patient_registration pr, tbl_items i, tbl_employee em WHERE pr.Registration_ID = pc.Registration_ID AND sd.finance_department_id = ilc.finance_department_id AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Surgery' AND ilc.Status = 'Served' AND i.Item_ID = ilc.Item_ID AND em.Employee_ID = ilc.ServedBy $filter AND ilc.Payment_Item_Cache_List_ID NOT IN(SELECT Payment_Item_Cache_List_ID FROM tbl_consumable_control WHERE Control_Status = 'Dispensed') ORDER BY ilc.Service_Date_And_Time") or die(mysqli_error($conn));

            if(mysqli_num_rows($select_Filtered_Donors)>0){
                $num = 1;
                while($rows = mysqli_fetch_assoc($select_Filtered_Donors)){
                    $finance_department_name = $rows['finance_department_name'];
                    $Registration_ID = $rows['Registration_ID'];
                    $Patient_Name = $rows['Patient_Name'];
                    $Date_Of_Birth = $rows['Date_Of_Birth'];
                    $Gender = $rows['Gender'];
                    $Service_Date_And_Time = $rows['Service_Date_And_Time'];
                    $Payment_Item_Cache_List_ID = $rows['Payment_Item_Cache_List_ID'];
                    $Employee_Name = $rows['Employee_Name'];
                    $Product_Name = $rows['Product_Name'];
                    $Sponsor_ID = $rows['Sponsor_ID'];

                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($rows['Date_Of_Birth']);
                    $diff = $date1->diff($date2);
                    $age = $diff->y . " Years, ";
                    $age .= $diff->m . " Months, ";
                    $age .= $diff->d . " Days";

                        echo "<tr>
                                <td><a href='Surgery_Consumable_Control_panel.php?RI=".$Registration_ID."&PLID=".$Payment_Item_Cache_List_ID."&Sp=".$Sponsor_ID."&Session_Type=Surgery' style='text-decoration: none;'>".$num."</a></td>
                                <td><a href='Surgery_Consumable_Control_panel.php?RI=".$Registration_ID."&PLID=".$Payment_Item_Cache_List_ID."&Sp=".$Sponsor_ID."&Session_Type=Surgery' style='text-decoration: none;'>".$Registration_ID."</a></td>
                                <td><a href='Surgery_Consumable_Control_panel.php?RI=".$Registration_ID."&PLID=".$Payment_Item_Cache_List_ID."&Sp=".$Sponsor_ID."&Session_Type=Surgery' style='text-decoration: none;'>".$Patient_Name."</a></td>
                                <td><a href='Surgery_Consumable_Control_panel.php?RI=".$Registration_ID."&PLID=".$Payment_Item_Cache_List_ID."&Sp=".$Sponsor_ID."&Session_Type=Surgery' style='text-decoration: none;'>".$Product_Name."</a></td>
                                <td><a href='Surgery_Consumable_Control_panel.php?RI=".$Registration_ID."&PLID=".$Payment_Item_Cache_List_ID."&Sp=".$Sponsor_ID."&Session_Type=Surgery' style='text-decoration: none;'>" . $Service_Date_And_Time . "</a></td>
                                <td><a href='Surgery_Consumable_Control_panel.php?RI=".$Registration_ID."&PLID=".$Payment_Item_Cache_List_ID."&Sp=".$Sponsor_ID."&Session_Type=Surgery' style='text-decoration: none;'>".$finance_department_name."</a></td>
                                <td><a href='Surgery_Consumable_Control_panel.php?RI=".$Registration_ID."&PLID=".$Payment_Item_Cache_List_ID."&Sp=".$Sponsor_ID."&Session_Type=Surgery' style='text-decoration: none;'>".$Employee_Name."</a></td>
                        </tr>";
                    
                    $num++;
                }
            }else{
                echo "<tr><td style='font-size: 17px; color: #bd0d0d; font-weight: bold; text-align: center;' colspan='7'>NO SURGERY PERFORMED IN ".strtoupper($Current_Sub_Department_Name)." FOR THE SPECIFIED PERIOD</td></tr>";
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
