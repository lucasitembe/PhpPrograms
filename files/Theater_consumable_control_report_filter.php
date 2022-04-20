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
    $filter_date = " AND Date_Time BETWEEN '$date_From' AND '$date_To'";
}else{
    $filter_date = " AND Date_Time BETWEEN '$This_date_today' AND NOW()";

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
            

            $select_Filtered_Donors = mysqli_query($conn, "SELECT pc.Registration_ID, sp.Guarantor_Name, sd.finance_department_name, pr.Patient_Name, ilc.Transaction_Date_And_Time, pr.Date_Of_Birth, pr.Gender,  pr.Phone_Number, em.Employee_Name, i.Product_Name, ilc.Service_Date_And_Time,ilc.Payment_Cache_ID,ilc.theater_room_id, ilc.Payment_Item_Cache_List_ID FROM tbl_item_list_cache ilc, tbl_finance_department sd, tbl_payment_cache pc, tbl_sponsor sp, tbl_patient_registration pr, tbl_items i, tbl_employee em WHERE pr.Registration_ID = pc.Registration_ID AND sd.finance_department_id = ilc.finance_department_id AND sp.Sponsor_ID = pc.Sponsor_ID AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Surgery' AND ilc.Status = 'Served' AND i.Item_ID = ilc.Item_ID AND em.Employee_ID = ilc.ServedBy $filter AND ilc.Payment_Item_Cache_List_ID IN(SELECT Payment_Item_Cache_List_ID FROM tbl_consumable_control WHERE Control_Status = 'Dispensed' $filter_date) ORDER BY ilc.Service_Date_And_Time") or die(mysqli_error($conn));

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
                    $Guarantor_Name = $rows['Guarantor_Name'];


                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($rows['Date_Of_Birth']);
                    $diff = $date1->diff($date2);
                    $age = $diff->y . " Years, ";
                    $age .= $diff->m . " Months, ";
                    $age .= $diff->d . " Days";

                        echo "<tr>
                                <td>".$num."</td>
                                <td>".$Patient_Name."</td>
                                <td>".$Registration_ID."</td>
                                <td>".$Guarantor_Name."</td>
                                <td>".$age."</td>
                                <td>".$Gender."</td>
                                <td>".$Product_Name."</td>
                                <td>" . $Service_Date_And_Time . "</td>
                                <td>".$finance_department_name."</td><td>";

                    $Get_Cost = mysqli_query($conn, "SELECT Product_Name AS Pharmaceutical, cci.Quantity, (cci.Quantity*cci.Price) AS TotalCost FROM tbl_consumable_control cc, tbl_consumable_control_items cci, tbl_items it WHERE cc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND it.Item_ID = cci.Item_ID AND cc.Control_ID = cci.Control_ID") or die(mysqli_error($conn));
                    if(mysqli_num_rows($Get_Cost)>0){
                        $Sub_Cost = '';
                        $Sub_Num = 1;
                            echo "
                            <table class='table table-collapse table-striped'>
                                <tr>
                                    <th>S/No</th>
                                    <th style='text-align: left;'>PHARMACEUTICAL</th>
                                    <th style='text-align: left;'>QTY</th>
                                    <th style='text-align: left;'>COST</th>
                                </tr>";
                        while($dt =mysqli_fetch_assoc($Get_Cost)){
                            $Pharmaceutical = $dt['Pharmaceutical'];
                            $Quantity = $dt['Quantity'];
                            $TotalCost = $dt['TotalCost'];

                            echo "<tr>
                                        <td>".$Sub_Num."</td>
                                        <td>".$Pharmaceutical."</td>
                                        <td style='text-align: right;'>".$Quantity."</td>
                                        <td style='text-align: right;'>".number_format($TotalCost)."</td>
                                    <tr>";
                            $Sub_Cost += $TotalCost;
                            $Sub_Num++;
                        }
                        echo "<tr style='background: #dedede;'>
                                <td colspan='3' style='text-align: right;'><b>SUB TOTAL</b></td>
                                <td colspan='3' style='text-align: right;'><b>".number_format($Sub_Cost)."</b></td></tr>
                            </table>";
                    }


                        echo "</td></tr>";
                    
                    $num++;
                    $Full_Cost += $Sub_Cost;
                }
                echo "<tr>
                        <td style='text-align: right;' colspan='9'><h4>TOTAL PHARMACEUTICAL COST</td>
                        <td style='text-align: right;'><h4>".number_format($Full_Cost)."</td>
                ";
            }else{
                echo "<tr><td style='font-size: 17px; color: #bd0d0d; font-weight: bold; text-align: center;' colspan='10'>NO PHARMACEUTICAL DOCUMENTED IN ".strtoupper($Current_Sub_Department_Name)." FOR THE SPECIFIED PERIOD</td></tr>";
            }
            ?>
        </table>