<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';
if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


//save information
$feedback_message="";
if(isset($_POST['save_btn'])){
    
     $Item_ID=$_POST['Item_ID'];  
     $Sponsor_ID=$_POST['Sponsor_ID'];  
     $kada=$_POST['kada'];
   
   $sql_insert="INSERT INTO tbl_consultation_items_configuration(Item_ID,Sponsor_ID,kada) VALUES('$Item_ID','$Sponsor_ID','$kada')";
   $sql_insert_result=mysqli_query($conn,$sql_insert) or die(mysqli_error($conn));
   if($sql_insert_result){
      $feedback_message="<div class='alert alert-success'>SAVED SUCCESSFULLY...</div>"; 
   }else{
       $feedback_message="<div class='alert alert-danger'>SAVING FAIL!PLEASE TRY AGAIN...</div>"; 
   }
}
//for deletion of configured item
if(isset($_POST['delete_btn'])){
    $consultation_items_configuration_id=$_POST['consultation_items_configuration_id'];
    $sql_delete_configured_item_result=mysqli_query($conn,"DELETE FROM tbl_consultation_items_configuration WHERE consultation_items_configuration_id='$consultation_items_configuration_id'") or die(mysqli_error($conn));

    if($sql_delete_configured_item_result){
      $feedback_message="<div class='alert alert-success'>DELETED SUCCESSFULLY...</div>"; 
   }else{
       $feedback_message="<div class='alert alert-danger'>DELETING FAIL!PLEASE TRY AGAIN...</div>"; 
   }   
}
?>
<br>
<br>


    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h4 class="box-tittle">CONSULTATION ITEMS CONFIGURATION</h4>
                </div>
                <div class="box-body">
                    <?= $feedback_message ?>
                    <form action="" class="form-horizontal" method="POST">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label>SELECT ITEM</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="select2" style="width:100%" name="Item_ID">
                                    <option value=""></option>
                                    <?php 
                                        $sql_select_items_result=mysqli_query($conn,"SELECT Item_ID,Product_Name FROM tbl_items") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_items_result)>0){
                                            while($items_rows=mysqli_fetch_assoc($sql_select_items_result)){
                                               $Item_ID=$items_rows['Item_ID'];
                                               $Product_Name=$items_rows['Product_Name'];
                                               echo "<option value='$Item_ID'>$Product_Name</option>";
                                            }
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label>SELECT SPONSOR</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="select2" style="width:100%" name="Sponsor_ID">
                                    <option value=""></option>
                                    <?php 
                                        $sql_select_sponsor_result=mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
                                   if(mysqli_num_rows($sql_select_sponsor_result)>0){
                                       while($sponsor_rows=mysqli_fetch_assoc($sql_select_sponsor_result)){
                                           $Sponsor_ID=$sponsor_rows['Sponsor_ID'];
                                           $Guarantor_Name=$sponsor_rows['Guarantor_Name'];
                                          echo "<option value='$Sponsor_ID'>$Guarantor_Name</option>";
                                       }
                                   }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label>SELECT KADA</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="select2" style="width:100%" name="kada">
                                    <option value=""></option>
                                    <option value="general_practitioner">General Practitioner</option>
                                    <option value="specialist">Specialist</option>
                                    <option value="super_specialist">Super Specialist</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="submit" name="save_btn" onclick="return confirm('Are You Sure You Want To Save This Information?')" class="art-button-green pull-right"value="SAVE" />
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-sm-12">
                            <div clbass="table-responsive">
                            <table class="table table-bordered table-striped ">
                                <tr>
                                    <th colspan="5">LIST OF CONSULTATION ITEMS ALREADY CONFIGURED</th>
                                </tr>
                                <tr>
                                    <th style="width: 50px">S/No.</th>
                                    <th>ITEM NAME</th>
                                    <th>SPONSOR NAME</th>
                                    <th>KADA</th>
                                    <th style="width: 50px">DELETE</th>
                                </tr>
                                <?php 
                                    $count=1;
                                    $sql_select_configured_item="SELECT consultation_items_configuration_id,kada,Guarantor_Name,Product_Name FROM tbl_consultation_items_configuration tcic,tbl_sponsor ts,tbl_items ti WHERE tcic.Item_ID=ti.Item_ID AND tcic.Sponsor_ID=ts.Sponsor_ID";
                                    $sql_select_configured_item_result=mysqli_query($conn,$sql_select_configured_item) or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_select_configured_item_result)>0){
                                       while($configuration_rows=mysqli_fetch_assoc($sql_select_configured_item_result)){
                                           $consultation_items_configuration_id=$configuration_rows['consultation_items_configuration_id'];
                                           $kada=$configuration_rows['kada'];
                                           $Guarantor_Name=$configuration_rows['Guarantor_Name'];
                                           $Product_Name=$configuration_rows['Product_Name'];
                                            if($kada=="general_practitioner"){
                                                           $kada_name="General Practitioner"; 
                                                        }else if($kada=="specialist"){
                                                           $kada_name="Specialist"; 
                                                        }else if($kada=="super_specialist"){
                                                           $kada_name="Super Specialist";  
                                                        }else{
                                                           $kada_name= $kada;
                                                        }
                                                       
                                           echo "<tr>
                                               <td>$count</td>
                                               <td>$Product_Name</td>
                                               <td>$Guarantor_Name</td>
                                               <td>$kada_name</td>
                                               <td>
                                                    <form action='' method='POST'>
                                                        <input type='text' hidden='hidden' name='consultation_items_configuration_id' value='$consultation_items_configuration_id'/>
                                                        <input type='submit'name='delete_btn' value='DELETE'class='btn btn-danger btn-xs' onclick='return confirm(\" Are You Sure You Want To delete this Item\")'>
                                                    </form>
                                               </td>
                                           </tr>";
                                           $count++;
                                       } 
                                    }
                                ?>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    $(document).ready(function() { 
    $(".select2").select2();
   });
</script>

<?php
    include("./includes/footer.php");
?>