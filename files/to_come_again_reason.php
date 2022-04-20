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
       $to_come_again_reason= $_POST['to_come_again_reason'];
        $sql_insert_reslut=mysqli_query($conn,"INSERT INTO tbl_to_come_again_reason(to_come_again_reason) VALUES('$to_come_again_reason')") or die(mysqli_error($conn));
        if($sql_insert_reslut){
            $feddback_message= "<div class='alert alert-success'>Saved Successfully</div>";
        }else{
           $feddback_message=  "<div class='alert alert-danger'>Fail to save...please try again</div>"; 
        }
    }
    if(isset($_POST['delete_btn'])){
        $to_come_again_id=$_POST['to_come_again_id'];
        $sql_delete_result=mysqli_query($conn,"DELETE FROM tbl_to_come_again_reason WHERE to_come_again_id='$to_come_again_id'") or die(mysqli_error($conn));
        if($sql_delete_result){
            $feddback_message= "<div class='alert alert-success'>Deleted Successfully</div>";
        }else{
           $feddback_message=  "<div class='alert alert-danger'>Fail to Delete...please try again</div>"; 
        }
    }
?>
<fieldset>
    <legend align="center"><b>TO COME AGAIN REASON</b></legend>
    <?= $feddback_message ?>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="" method="POST">
                <div class="form-group">
                    <div class="col-md-5"> To come again reason</div>
                    <div class="col-md-5">
                        <input type="text" name="to_come_again_reason" placeholder="Enter to come again reason" class="form-control">
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
                    <th>To come again reason</th>
                    <th style="width:50px">Delete</th>
                </tr>
                <?php 
                    $count=1;
                    $sql_selct_rank_result=mysqli_query($conn,"SELECT *FROM tbl_to_come_again_reason") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_selct_rank_result)>0){
                        while($rank_rows=mysqli_fetch_assoc($sql_selct_rank_result)){
                           $to_come_again_reason=$rank_rows['to_come_again_reason'];
                           $to_come_again_id=$rank_rows['to_come_again_id'];
                           echo " 
                                    <tr>
                                        <td>$count</td>
                                        <td>$to_come_again_reason</td>
                                        <td>
                                            <form action='' method='POST'>
                                                <input type='text' hidden='hidden' name='to_come_again_id' value='$to_come_again_id'>
                                                <input type='submit' name='delete_btn' onclick='return confirm(\" Are you sure you want to delete this rank?\")' value='Delete' class='btn btn-danger btn-sm'>
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