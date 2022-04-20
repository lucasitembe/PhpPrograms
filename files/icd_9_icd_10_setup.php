<?php
include("./includes/header.php");
include("./includes/connection.php");
if (isset($_SESSION['userinfo'])) {
 
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$count_succes=0;
if(isset($_POST['map_btn'])){
    $disease_group_id=$_POST['disease_group_id'];
    $sql_select_all_icd_10_disease_result=mysqli_query($conn,"SELECT disease_ID FROM tbl_disease WHERE disease_version='icd_10'") or die(mysqli_error($conn));

    if(mysqli_num_rows($sql_select_all_icd_10_disease_result)>0){
       while($disease_rows=mysqli_fetch_assoc($sql_select_all_icd_10_disease_result)){
          $disease_ID=$disease_rows['disease_ID'];
          $sql_insert_result=mysqli_query($conn,"INSERT INTO tbl_disease_group_mapping(disease_group_id,disease_id) VALUES('$disease_group_id','$disease_ID')") or die(mysqli_error($conn));
            if($sql_insert_result){
$count_succes++;
            }
          } 
    }
    if($count_succes>0){
       echo "<script>
           alert('SUCCESSFULLY MAP $count_succes DISEASE')
        </script>"; 
    }
}
?>


<style>
    button{
        height: 27px!important;
        color: #FFFFFF!important;
    }
</style>
<fieldset>
    <legend>MAP ICD 10 TO GROUP</legend>
    <form action="" method="POST">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <select name="disease_group_id" required="">
                    <option value="">Select Group</option>
                    <?php 
                        $select_all_group_result=mysqli_query($conn,"SELECT disease_group_id,disease_group_name FROM tbl_disease_group") or die(mysqli_error($conn));
                        if(mysqli_num_rows($select_all_group_result)>0){
                           while($group_rows=mysqli_fetch_assoc($select_all_group_result)){
                               $disease_group_id=$group_rows['disease_group_id'];
                               $disease_group_name=$group_rows['disease_group_name'];
                               echo "<option value='$disease_group_id'>$disease_group_name</option>";
                           } 
                        }
                      ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" name="map_btn" class="art-button-green">Map to Icd 10 diseases</button>
            </div>
        </div>
    </form>
</fieldset>
<script>
    $(document).ready(function (){
        $('select').select2();
    });
</script>
<?php
include("./includes/footer.php");
?>

