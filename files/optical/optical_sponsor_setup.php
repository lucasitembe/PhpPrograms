
<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    ?>
<a href="opticalworkspage.php" class="art-button-green">BACK</a>
<?php 
    if(isset($_POST['save_changes_btn'])){
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $approval_level_title=$_POST['sponsor_name'];
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
        $Sponsor_ID=mysqli_real_escape_string($conn,$_POST['Sponsor_ID']);
        $check_sponsor_if_exist=mysqli_query($conn,"SELECT Sponsor_ID FROM optical_sponsor_setup ") or die(mysqli_error($conn));
        if(mysqli_num_rows($check_sponsor_if_exist) > 0){
            echo "<script> alert('YOU CAN NOT ADD ANOTHER SPONSOR ONLY ONE SPONSOR IS REQUIRED');</script>";
        }else{
            $sql_add_sponsor=mysqli_query($conn,"INSERT INTO `optical_sponsor_setup`(`Sponsor_ID`, `Employee_ID`)  VALUES('$Sponsor_ID','$Employee_ID')") or die(mysqli_error($conn));
    
        if($sql_add_sponsor){
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
    }
if(isset($_POST['edit_btn'])){
        $document_approval_level_title= $_POST['reason'];
        $document_approval_level_title_id= $_POST['reason_id'];
}
?>
<br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>CASH BILLING TYPE SPONSOR SETUP</b></legend>

            <center>
            <h5 style="color:blue"><i>*** Price Of Item Of The Selected Sponsor  Will Be Used As the selling price Of Outpatient/Inpatient Cash Billing Type.Please Select Only one Sponsor***<i></h5>
            <h5 style="color:blue"><i>*** Applies Only For Outpatient/Inpatient Billing Type ***<i></h5>
                <form action="" method="POST">
                    <table style="width: 60%;" class="table">
                        <tr>
                            <td style="width: 15%; text-align:right;"><h4>Sponsor</h4></td>
                            <td style="width: 30%">
                                <!-- <input type="text" name="approval_level_title" placeholder="Enter selection name" value="<?= $document_approval_level_title ?>"class="form-control"/>
                                <input type="text" hidden="hidden" name="document_approval_level_title_id_edit"  value="<?= $document_approval_level_title_id ?>"/> -->

                                <select style='text-align: center;padding:4px; width:100%;' name="Sponsor_ID" id="Sponsor_ID">
                            <?php 
                                 //Sponsor list
                                 $query_sub_cat = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name,payment_method FROM tbl_sponsor") or die(mysqli_error($conn));
                                echo '<option value="All">Select Sponsor</option>';
                                 while ($row = mysqli_fetch_array($query_sub_cat)) {
                                     echo '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
                                 }
                            ?> 
                        </select>
                                <input type="text" hidden="hidden" name="document_approval_level_title_id_edit"  value="<?= $document_approval_level_title_id ?>"/>
                            </td>
                            <td style="width: 15%">
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
                <div style="width: 80%;overflow-y: scroll;overflow-x: hidden">
                <table style="width: 60%;background: #FFFFFF;" class="table table-bordered">
                    <tr>
                        <th style="width:15px">S/No.</th>
                        <th> SELECTED SPONSOR </th>
                        <th style="width:100px">EDIT</th>
                    </tr>
                    <tbody>
                    <?php 
                        $sql_select_added_result=mysqli_query($conn,"SELECT `optical_sponsor_setup_ID`, sp2.Guarantor_Name,Employee_ID FROM optical_sponsor_setup as sp1,tbl_sponsor as sp2 where sp1.Sponsor_ID=sp2.Sponsor_ID") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_added_result)>0){
                             $count=1;
                            while($row=mysqli_fetch_assoc($sql_select_added_result)){
                                $optical_sponsor_setup_ID=$row['optical_sponsor_setup_ID'];
                                $sponsor_name=$row['Guarantor_Name'];
                                echo "<tr>
                                        <td style='text-align:center;' onclick='edit_sponsor($optical_sponsor_setup_ID)'>$count</td>
                                        <td style='text-align:center;' onclick='edit_sponsor($optical_sponsor_setup_ID)'>$sponsor_name</td>
                                        <td>
                                            <form action='' method='POST'> 
                                                <input type='text' value='$optical_sponsor_setup_ID' name='reason_id' hidden='hidden'/>
                                                <input type='text' value='$sponsor_name' name='reason'hidden='hidden'/>
                                                <input type='button' name='edit_btn' class='art-button-green' value='EDIT'onclick='edit_sponsor($optical_sponsor_setup_ID)'/>
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
<div id="show"></div>
<script>
    function edit_sponsor(optical_sponsor_setup_ID){
        // alert(optical_sponsor_setup_ID);
        $.ajax({
                type:'post',
                url: 'edit_optical_sponsor.php',
                data : {
                    optical_sponsor_setup_ID:optical_sponsor_setup_ID
               },
               success : function(data){
                    $('#show').dialog({
                        autoOpen:true,
                        width:'50%',
                        position: ['center',200],
                        title:'EDIT SPONSOR',
                        modal:true
                    });  
                    $('#show').html(data);
               }
           });

    }
</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>