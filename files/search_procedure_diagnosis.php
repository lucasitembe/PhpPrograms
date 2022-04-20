<?php 
include("./includes/connection.php");

if(isset($_GET['search_diagnosis'])){
  $search_diagnosis=$_GET['search_diagnosis'];
}else{
  $search_diagnosis=''; 
}
?>
<table class="table table-bordered">
    <tr>
        <th style="width:50px">S/No.</th>
        <th>DIAGNOSIS NAME</th>
        <th>DIAGNOSIS CODE</th>
        <th style="width: 50px">EDIT</th>
        <th style="width: 50px">DISABLE/ENABLE</th>
    </tr>
    <?php 
        $count=1;
        $sql_select_saved_diagnosis_result=mysqli_query($conn,"SELECT *FROM tbl_procedure_diagnosis WHERE procedure_dignosis_name LIKE '%$search_diagnosis%' OR procedure_dignosis_code LIKE '%$search_diagnosis%'  ORDER BY procedure_dignosis_name DESC LIMIT 20") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_saved_diagnosis_result)>0){
            while($diagnosis_rows=mysqli_fetch_assoc($sql_select_saved_diagnosis_result)){
                $procedure_diagnosis_id=$diagnosis_rows['procedure_diagnosis_id'];
                $procedure_dignosis_name=$diagnosis_rows['procedure_dignosis_name'];
                $procedure_dignosis_code=$diagnosis_rows['procedure_dignosis_code'];
                $disable_enable=$diagnosis_rows['disable_enable'];
                echo "
                        <tr>
                            <td>$count</td>
                            <td>$procedure_dignosis_name</td>
                            <td>$procedure_dignosis_code</td>
                            <td>
                                <form action='' method='POST'>
                                    <input type='text' name='procedure_diagnosis_id' value='$procedure_diagnosis_id' hidden='hidden'>
                                    <input type='submit' name='edit_btn' value='Edit' class='art-button-green'>
                                </form>
                            </td>
                            <td>
                                <form action='' method='POST'>
                                    <input type='text' name='procedure_diagnosis_id' value='$procedure_diagnosis_id' hidden='hidden'>";

                                    if($disable_enable=="enabled"){
                                       echo "<input type='submit' name='disable_btn' value='Enabled' class='btn btn-success btn-sm'>";  
                                    }else{
                                       echo "<input type='submit' name='enable_btn' value='Disabled' class='btn btn-danger btn-sm'>"; 
                                    }

                                            echo "
                                </form>
                            </td>
                        </tr>
                    ";
                $count++;
            }
        }
    ?>
</table>