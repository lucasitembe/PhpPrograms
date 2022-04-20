<?php
include("./includes/connection.php");
include("./includes/header.php");
?>
<a href='otherconfigurations.php?OtherConfigurations=OtherConfigurationsThisForm' class='art-button-green'>
    BACK
 </a>
<?php 
$feddback_message="";
    if(isset($_POST['save_btn'])){
       $military_unit_name= $_POST['military_unit_name'];
        $sql_insert_reslut=mysqli_query($conn,"INSERT INTO tbl_military_units(military_unit_name) VALUES('$military_unit_name')") or die(mysqli_error($conn));
        if($sql_insert_reslut){
            $feddback_message= "<div class='alert alert-success'>Unit Saved Successfully</div>";
        }else{
           $feddback_message=  "<div class='alert alert-danger'>Fail to save...please try again</div>"; 
        }
    }
    if(isset($_POST['delete_btn'])){
        $military_unit_id=$_POST['military_unit_id'];
        $sql_delete_result=mysqli_query($conn,"DELETE FROM tbl_military_units WHERE military_unit_id='$military_unit_id'") or die(mysqli_error($conn));
        if($sql_delete_result){
            $feddback_message= "<div class='alert alert-success'>Unit Deleted Successfully</div>";
        }else{
           $feddback_message=  "<div class='alert alert-danger'>Fail to Delete...please try again</div>"; 
        }
    }
?>
<fieldset>
    <legend align="center">ADD UNIT</legend>
    <?= $feddback_message ?>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form action="" method="POST">
                <div class="form-group">
                    <div class="col-md-2"> Unit</div>
                    <div class="col-md-8">
                        <input type="text" name="military_unit_name" placeholder="Enter Unit" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <input type="submit" name="save_btn" value="SAVE" class="art-button-green">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            <br/>
            <table class="table table-bordered table-striped table-contents" style="background: #FFFFFF">
                <tr>
                    <th style="width:50px">S/No.</th>
                    <th>Rank</th>
                    <th style="width:50px">Delete</th>
                </tr>
                <?php 
                    $count=1;
                    $sql_selct_rank_result=mysqli_query($conn,"SELECT *FROM tbl_military_units") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_selct_rank_result)>0){
                        while($rank_rows=mysqli_fetch_assoc($sql_selct_rank_result)){
                           $military_unit_name=$rank_rows['military_unit_name'];
                           $military_unit_id=$rank_rows['military_unit_id'];
                           echo " 
                                    <tr>
                                        <td>$count</td>
                                        <td>$military_unit_name</td>
                                        <td>
                                            <form action='' method='POST'>
                                                <input type='text' hidden='hidden' name='military_unit_id' value='$military_unit_id'>
                                                <input type='submit' name='delete_btn' onclick='return confirm(\" Are you sure you want to delete this unit?\")' value='Delete' class='btn btn-danger btn-sm'>
                                            </form>
                                        </td>
                                    </tr>
                                ";
                           $count++;
                        }
                    }
                ?>
            </table>
        </div>
    </div>
</fieldset>


<?php
include("./includes/footer.php");
?>
