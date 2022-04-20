
<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    ?>
<a href="laboratory_setup.php?LaboratorySetup=LaboratorySetupThisPage" class="art-button-green">BACK</a>
<?php 
    if(isset($_POST['save_changes_btn'])){
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $approval_level_title=$_POST['approval_level_title'];
        $approval_level_title=mysqli_real_escape_string($conn,$approval_level_title);
        $document_approval_level_title_id=$_POST['document_approval_level_title_id_edit'];
        $sql_update_approval_level_tittle_result=mysqli_query($conn,"UPDATE tbl_rejection_reasons SET reason='$approval_level_title',last_edited_by='$Employee_ID' WHERE reason_id='$document_approval_level_title_id'") or die(mysqli_error($conn));
    
        if($sql_update_approval_level_tittle_result){
            ?>
                <script>
                    alert("UPDATED SUCCESSFULLY");
                </script>    
            <?php
            $approval_level_title="";
        }else{
            ?>
                <script>
                    alert("UPDATING FAIL...TRY AGAIN");
                </script>    
            <?php
        }
    }
    if(isset($_POST['save_btn'])){
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $approval_level_title=mysqli_real_escape_string($conn,$_POST['approval_level_title']);
        
        $sql_add_approval_level_tittle_result=mysqli_query($conn,"INSERT INTO `tbl_rejection_reasons`(`reason`, `added_by`)  VALUES('$approval_level_title','$Employee_ID')") or die(mysqli_error($conn));
    
        if($sql_add_approval_level_tittle_result){
            ?>
                <script>
                    alert("ADDED SUCCESSFULLY");
                </script>    
            <?php
        }else{
            ?>
                <script>
                    alert("ADDING FAIL...TRY AGAIN");
                </script>    
            <?php
        }
    }
if(isset($_POST['edit_btn'])){
        $document_approval_level_title= $_POST['reason'];
        $document_approval_level_title_id= $_POST['reason_id'];
}
?>
<br/><br/><br/><br/>
<fieldset style="width:'50%'">  
            <legend align=center><b>REJECTION REASON CONFIGURATION</b></legend>
            <center>
                <form action="" method="POST">
                    <table style="width: 50%;" class="table">
                        <tr>
                            <!-- <td style="width: 40%; text-align:right;"><h4>Reason</h4></td> -->
                            <td style="width: 50%">
                                <input type="text" name="approval_level_title" placeholder="Enter selection name" value="<?= $document_approval_level_title ?>"class="form-control"/>
                                <input type="text" hidden="hidden" name="document_approval_level_title_id_edit"  value="<?= $document_approval_level_title_id ?>"/>
                            </td>
                            <td style="width: 4%">
                                <?php 
                                    if(isset($_POST['edit_btn'])){
                                      ?>
                                        <input type="submit" name="save_changes_btn" value="SAVE CHANGES" class="art-button-green"/>
                                <?php
                                    }else{
                                ?>
                                <input type="submit" name="save_btn" value="ADD" class="art-button-green"/>
                                <?php
                                    }
                                ?>
                            </td>
                        </tr>
                    </table>
                </form>
                <div style="height:350px;width: 50%;overflow-y: scroll;overflow-x: hidden">
                <table style="width: 100%;background: #FFFFFF;" class="table table-bordered">
                    <tr>
                        <th style="width:50px">S/No.</th>
                        <th>SELECTION NAME</th>
                        <th style="width:100px">EDIT</th>
                    </tr>
                    <tbody>
                    <?php 
                        $sql_select_added_title_result=mysqli_query($conn,"SELECT `reason_id`, `reason` FROM `tbl_rejection_reasons`") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_added_title_result)>0){
                             $count=1;
                            while($added_level_rows=mysqli_fetch_assoc($sql_select_added_title_result)){
                                $document_approval_level_title_id=$added_level_rows['reason_id'];
                                $document_approval_level_title=$added_level_rows['reason'];
                                echo "<tr>
                                        <td>$count</td>
                                        <td>$document_approval_level_title</td>
                                        <td>
                                            <form action='' method='POST'> 
                                                <input type='text' value='$document_approval_level_title_id' name='reason_id' hidden='hidden'/>
                                                <input type='text' value='$document_approval_level_title' name='reason'hidden='hidden'/>
                                                <input type='submit' name='edit_btn' class='art-button-green' value='EDIT'/>
                                            </form>
                                        </td>
                                      </tr>";
                                $count++;
                            }
                        }
                    ?>
                    </tbody>
                </table>
                </div>
            </center>
</fieldset>