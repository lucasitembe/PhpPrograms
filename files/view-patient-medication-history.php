<style>
    .table{
        border: 1px solid #ccc !important;
    }

    table tr td{
        border: 1px solid #cccd !important;
    }
</style>
<?php
    include './includes/connection.php';

    $Registration_ID = $_GET['Registration_ID'];
    $payment_cache_array = array();
    $output = "";

    $getPatientMedication = mysqli_query($conn,"SELECT tpc.Payment_Cache_ID,ti.Product_Name,tpc.consultation_id,tilc.Status,c.Consultation_Date_And_Time,Employee_Name,tilc.Doctor_Comment
                                                FROM tbl_payment_cache AS tpc,tbl_item_list_cache AS tilc, tbl_items AS ti, tbl_consultation AS c,tbl_employee AS e
                                                WHERE tpc.Registration_ID = $Registration_ID AND tilc.Check_In_Type = 'Pharmacy' AND tpc.Payment_Cache_ID = tilc.Payment_Cache_ID AND c.employee_ID = e.Employee_ID AND tpc.consultation_id = c.consultation_ID AND ti.Item_ID = tilc.Item_ID GROUP BY tpc.Payment_Cache_ID ORDER BY tpc.Payment_Cache_ID DESC");

    while($data = mysqli_fetch_assoc($getPatientMedication)){
        array_push($payment_cache_array,$data);
    }

    for($i = 0;$i < sizeof($payment_cache_array);$i++){
        $counter = 1;
        $output .="<table class='table'>";
        $output .="<tr style='background-color: rgba(34, 138, 170, 1);color:white'><td colspan=5'><b>Consultant : </b>".$payment_cache_array[$i]['Employee_Name']." <span style='margin-left:1em;margin-right:1em'>~</span> <b>Consultation Date And Time : </b>".$payment_cache_array[$i]['Consultation_Date_And_Time']."</td></tr>";
        $output .="<tr style='background-color: #eee;'>
                        <td><center><b>S/N</b></center></td>
                        <td><b>Medication Name</b></td>
                        <td><center><b>Quantity</b></center></td>
                        <td><b>Dosage</b></td>
                        <td><b>Status</b></td>
                    </tr>";
        $select_items = mysqli_query($conn,"SELECT tilc.Payment_Cache_ID,Product_Name,Quantity,edited_quantity,tilc.Status,tilc.Doctor_Comment FROM tbl_item_list_cache AS tilc,tbl_items AS ti WHERE tilc.Check_In_Type = 'Pharmacy' AND tilc.Item_ID = ti.Item_ID AND tilc.Payment_Cache_ID = ".$payment_cache_array[$i]['Payment_Cache_ID']."") or die(mysqli_errno($conn));
        while($row = mysqli_fetch_assoc($select_items)){
            $stat = "";
            if($row['Status'] == 'active' || $row['Status'] == 'free' || $row['Status'] == 'approved' || $row['Status'] == 'paid') { 
                $stat = 'Not Dispensed'; 
            }else if($row['Status'] == 'Out Of Stock'){
                $stat = 'Out Of Stock';

            }else if($row['Status'] == 'removed'){
                $stat = 'Removed';
            }else if($row['Status'] == 'partial dispensed'){
                $stat = 'Partial Dispensed';
            }else{ 
                $stat = 'Dispensed';
            }
            $output .= "
                <tr>
                    <td width='10%'><center>".$counter++."</center></td>
                    <td>".$row['Product_Name']."</td>
                    <td width='8%'><center>".$row['edited_quantity']."</center></td>
                    <td width='30%'>".$row['Doctor_Comment']."</td>
                    <td width='10%'>{$stat}</td>
                </tr>
            ";
            $stat = "";
        }
        $output .= "</table><br/>";
    }
    echo $output;
?>