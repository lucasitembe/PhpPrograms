<?php
include ("./includes/connection.php")
?> 
 <style>
    .section_table{
        padding: 10px;
    }
    #section_table tr th{
        font-weight: bold;
        background: grey;
        border: 1px solid #000;
        padding: 10px;
    }
    #section_table tr th{
        border: 1px solid #fff;
    }
    #section_table tr:nth-child(even){
        background-color: #eee;
    }
    #section_table tr:nth-child(odd){
        background-color: #fff;
    }
    #section_table tr:hover{
        cursor: pointer;
        font-size: 15px;
        background: grey;
        color: white;
    }
</style>
<table id='section_table' style='padding: 20px; font-weight: bold; padding: 30px; width:100%;'>
    <tr style='padding: 10px; font-weight: bold; padding: 10px; background: grey;'>
        <td style='width: 3%'>SN</td>
        <td style='width: 25%'>ASSIGNED ENGINEER</td>
        <td style='width: 20%'>SECTION REQUIRED</td>
        <td style='width: 20%'>EQUIPMENT NAME</td>
        <td style='width: 20%'>FLOOR/LOCATION/PLACE</td>
        <td style='width: 12%'>NO. OF DAYS</td>
    </tr>
<?php 

    if(isset($_POST['option_name'])){
        $option_name = $_POST['option_name'];
    }else{
        $option_name = 0;
    }
    if(isset($_POST['name'])){
        $name = $_POST['name'];
    }else{
        $name = 0;
    }

    $engineers = mysqli_query($conn,"SELECT assigned_engineer, section_required, date_of_requisition, equipment_name, floor FROM tbl_engineering_requisition where assigned_engineer='$name' and section_required = '$option_name' AND completed = 'no' ORDER BY requisition_ID DESC") or die(mysqli_error($conn));


    $temp = 1;
    while ($row = mysqli_fetch_array($engineers)) {
        $name = $row['assigned_engineer'];
        $date1 = new DateTime($Today);
        $date2 = new DateTime($row['date_of_requisition']);
        $diff = $date1->diff($date2);
        $no_of_days = $diff->d . " Days";
        

        echo "<tr>
            <td style='text-align: center;'>".$temp++."</td>
            <td>".$row['assigned_engineer']."</td>
            <td>".$row['section_required']."</td>
            <td>".$row['equipment_name']."</td>
            <td>".$row['floor']."</td>
            <td>".$no_of_days."</td>
            </tr>";
    }

?>
</table>

<script src="css/jquery.js"></script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">