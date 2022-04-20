<?php
include("./includes/connection.php");
//
$filter="";
if(isset($_GET['procedure_dignosis_code'])){
   $procedure_dignosis_code=$_GET['procedure_dignosis_code'];
   $filter.=" AND procedure_dignosis_code LIKE '%$procedure_dignosis_code%'";
}
if(isset($_GET['procedure_dignosis_name'])){
   $procedure_dignosis_name=$_GET['procedure_dignosis_name'];
   $filter.=" AND procedure_dignosis_name LIKE '%$procedure_dignosis_name%'";
}
if(isset($_GET['Git_Post_operative_ID'])){
   $Git_Post_operative_ID=$_GET['Git_Post_operative_ID'];
}else{
   $Git_Post_operative_ID=0;  
}
$result = mysqli_query($conn,"SELECT * FROM tbl_procedure_diagnosis WHERE disable_enable='enabled' $filter LIMIT 100") or die(mysqli_error($conn));
while($row = mysqli_fetch_array($result)){
?>
            <tr>
                        <td><input type='radio' id="<?php echo $row['procedure_diagnosis_id']; ?>" name="Disease" onclick="Get_Selected_Diagnosis(<?php echo $row['procedure_diagnosis_id']; ?>,<?= $Git_Post_operative_ID ?>)"></td>
                        <td><label class="itemhoverlabl" for="<?php echo $row['procedure_diagnosis_id']; ?>" ><?php echo $row['procedure_dignosis_name'];?>(<b><?php echo $row['procedure_dignosis_code']; ?></b>)</label></td>
            </tr>
<?php
    }
?>