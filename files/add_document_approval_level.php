<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    ?>
<a href="employee_approval_configuration.php" class="art-button-green">BACK</a>
<?php 
    if(isset($_POST['save_changes_btn'])){
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $approval_level_title=$_POST['approval_level_title'];
        $approval_level_title=mysqli_real_escape_string($conn,$approval_level_title);
        $document_approval_level_title_id=$_POST['document_approval_level_title_id_edit'];
        $sql_update_approval_level_tittle_result=mysqli_query($conn,"UPDATE tbl_document_approval_level_title SET document_approval_level_title='$approval_level_title',last_edited_by='$Employee_ID' WHERE document_approval_level_title_id='$document_approval_level_title_id'") or die(mysqli_error($conn));
    
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
        
        $sql_add_approval_level_tittle_result=mysqli_query($conn,"INSERT INTO tbl_document_approval_level_title (document_approval_level_title,added_by) VALUES('$approval_level_title','$Employee_ID')") or die(mysqli_error($conn));
    
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
        $document_approval_level_title= $_POST['document_approval_level_title'];
        $document_approval_level_title_id= $_POST['document_approval_level_title_id'];
}
?>
<br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>ADD DOCUMENT APPROVAL LEVEL TITLE</b></legend>
            <center>
                <form action="" method="POST">
                    <table style="width: 50%;" class="table">
                        <tr>
                            <td style="width: 40%">Add Approval Level Title</td>
                            <td style="width: 50%">
                                <input type="text" name="approval_level_title" placeholder="Enter Approval Level Title" value="<?= $document_approval_level_title ?>"class="form-control"/>
                                <input type="text" hidden="hidden" name="document_approval_level_title_id_edit"  value="<?= $document_approval_level_title_id ?>"/>
                            </td>
                            <td style="width: 10%">
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
                        <th colspan="3">
                            DOCUMENT APPROVAL LEVEL TITLE
                        </th>
                    </tr>
                    <tr>
                        <th style="width:50px">S/No.</th>
                        <th>APPROVAL LEVEL TITLE</th>
                        <th style="width:100px">EDIT</th>
                    </tr>
                    <tbody>
                    <?php 
                        $sql_select_added_title_result=mysqli_query($conn,"SELECT document_approval_level_title_id,document_approval_level_title FROM  tbl_document_approval_level_title") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_added_title_result)>0){
                             $count=1;
                            while($added_level_rows=mysqli_fetch_assoc($sql_select_added_title_result)){
                                $document_approval_level_title_id=$added_level_rows['document_approval_level_title_id'];
                                $document_approval_level_title=$added_level_rows['document_approval_level_title'];
                                echo "<tr>
                                        <td>$count</td>
                                        <td>$document_approval_level_title</td>
                                        <td>
                                            <form action='' method='POST'> 
                                                <input type='text' value='$document_approval_level_title_id' name='document_approval_level_title_id' hidden='hidden'/>
                                                <input type='text' value='$document_approval_level_title' name='document_approval_level_title'hidden='hidden'/>
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