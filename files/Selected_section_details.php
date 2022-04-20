<?php
    include("./includes/connection.php");

    if(isset($_GET['option'])){
        $option = $_GET['option'];
    }else{
        $option = 0;
    }
    // die("SELECT COUNT(requisition_ID) as requisition_ID FROM tbl_engineering_requisition WHERE section_required = '$option'");
    $engineers = mysqli_query($conn,"SELECT assigned_engineer, section_required FROM tbl_engineering_requisition where section_required='$option' Group by assigned_engineer");


    $temp = 1;
    while ($row = mysqli_fetch_array($engineers)) {
        $name = $row['assigned_engineer'];
        $open_jobs_count = mysqli_query($conn,"SELECT COUNT(requisition_ID) as requisition_ID_Count FROM tbl_engineering_requisition WHERE section_required = '$option' AND assigned_engineer = '$name' AND completed='no'") or die(mysqli_error($conn));
        while($count = mysqli_fetch_array($open_jobs_count)){
            $yes = $count['requisition_ID_Count'];
        }
        $closed_jobs_count = mysqli_query($conn,"SELECT COUNT(requisition_ID) as requisition_ID_Count FROM tbl_engineering_requisition WHERE section_required = '$option' AND assigned_engineer = '$name' AND completed='completed'") or die(mysqli_error($conn));
        while($count = mysqli_fetch_array($closed_jobs_count)){
            $completed = $count['requisition_ID_Count'];
        }
            "<input id='name_$name' type='text' value='{$name} style='display:none;'>";
        "<input id='option_name' type='text' value='{$option} style='display:none;'>";
        echo "<tr>
            <td style='text-align: center;'>".$temp++."</td>
            <td>".$row['assigned_engineer']."</td>";?>
            <td style='color: red; font-weight: bold; text-align:center; padding:10px;'>
                <a href='#' id='menu' onClick='throw_something("<?=$name?>")' style='text-decoration: none; color: red;'><?= $yes ?>
            </td>
            <td style='color: green; font-weight: bold; text-align:center; padding:10px;'><?= $completed ?></td>
            </tr>
            <?php
        ;
    }

?>
<div id="loadddddd"></div>

<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>

        function throw_something(name){
            var option_name = $('#section_required').val();
            $.ajax({
                type:'POST',
                url:'open_load.php',
                data:{loaddialog:'', name:name, option_name:option_name},
                success:function(responce){
                    $("#loadddddd").dialog({
                        title: "OPEN JOBS FOR "+ name,
                        width: "90%",
                        height: 400,
                        modal: true
                    });
                    $("#loadddddd").html(responce);                    
                }
            })
        }
    </script>
     <script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">