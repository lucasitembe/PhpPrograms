<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	//    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Reception_Works'] != 'yes' && $_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
	//	header("Location: ./index.php?InvalidPrivilege=yes");
	//    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>


<?php
    if(isset($_SESSION['userinfo'])){
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Pharmacy'] == 'yes'){
?>
    <a href='grnopenbalance.php?ReasonConfiguration=ReasonsConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } //} ?>

<br/><br/><br/><br/><br/><br/><br/><br/>

<?php 

  $employee_Name="";
  $employee_enable="";
  $employee_diable="";
  $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
  
   $employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
    $feedback_message="";
  

        if($_POST['disable_btn']){
            $reason_id=$_POST['reason_id'];
              $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
  
//           $employee_diable = mysqli_fetch_assoc(mysqli_query($conn,"SELECT emp.Employee_Name FROM tbl_employee emp,tbl_reasons_adjustment ad WHERE  emp.Employee_ID=ad.Employee_ID AND ad.reason_id='$reason_id'"))['Employee_Name'];
            
            $sql_disable_reasons=mysqli_query($conn,"UPDATE  tbl_reasons_adjustment SET Status='disabled',DISABLE_BY='$Employee_ID' WHERE reason_id='$reason_id'") or die(mysqli_error($conn));
            
            if($sql_disable_reasons){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> you have disable Reason successfully
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Fail to disable Reason..please try again
                              </div>"; 
            }
        }
        if($_POST['enable_btn']){
            
              $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
  
//   $employee_enable = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
            
                $reason_id=$_POST['reason_id'];
            $sql_disable_reasons=mysqli_query($conn,"UPDATE  tbl_reasons_adjustment SET Status='enabled',ENABLED_BY='$Employee_ID' WHERE reason_id='$reason_id'") or die(mysqli_error($conn));
            
            if($sql_disable_reasons){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> you have enable Reason successfully
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Fail to enable Reason..please try again
                              </div>"; 
            }
        }
        
//         echo "ndio sana";
        $reason_id=0;
        if(isset($_POST['edit_btn'])){
          $reason_id=$_POST['reason_id']; 
          
           $sql_select_reason_result=mysqli_query($conn,"SELECT reason_id,reasons FROM tbl_reasons_adjustment WHERE reason_id='$reason_id'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_reason_result)>0){
                                while($reason_rows=mysqli_fetch_assoc($sql_select_reason_result)){
                                   $reasons= $reason_rows['reasons'];
                                   $reason_id=$reason_rows['reason_id'];
                                   $Status_reasons=$reason_rows['Status'];
                                
                                  
              } 
           }else{
              $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Process Fail..try again
                              </div>";  
           }
        }else{
            $reasons=""; 
//           $finance_department_code=""; 
            $reason_id="";
            $Status_reasons="";
        }
  
        if(isset($_POST['save_changes_btn'])){
         $reasonvalue=$_POST['reason_id'];
//            echo $reason_id;
        $reason_Name=$_POST['reason_Name']; 
        $sql_insert_result=mysqli_query($conn,"UPDATE tbl_reasons_adjustment SET reasons='$reason_Name',Employee_ID='$Employee_ID' WHERE reason_id='$reasonvalue'") or die(mysqli_error($conn));
        
           
           if($sql_insert_result){
//               echo "hapa";
             $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> Changes Saved Successfully
                              </div>";  
           }else{
               
             $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong>Changes Saving Fail Please Try Again
                              </div>"; 
           }
        }
?>
<center>
<table width=60%>
    <tr>
      <td>
        <fieldset>
        <legend align=center><b>NEW REASONS</b></legend>
        <!--<form action='#' method='post' name='myForm' id='myForm' enctype="multipart/form-data">-->
        
          <?= $feedback_message ?>
        <form action="" method="POST" class="form-horizontal">
            <table width=100%>
        
                <tr>
                    <td style='text-align: right;' width=15%>Reason Name</td>
                    <td>
                        <input type='text' name='reason_Name' id='reason_Name' value="<?=  $reasons ?>" required='required' placeholder='Enter Reason Name'>
                        <input type='text' name='reason_id' id='reason_id' hidden='hidden' value="<?=  $reason_id ?>" required='required' placeholder='Enter Reason Name'>
                        
                    </td>
                </tr>
          
                <tr>
                    <td colspan=4 style='text-align: right;'>
                        <!--<button  onclick="grn_reason_adjustements()" style="width:27px;" class='art-button-green'>SAVE </button>-->
                        
                        
                           <?php 
                                     if(isset($_POST['edit_btn'])){
                                        ?>
                                <input type="submit" value="SAVE CHANGES" name="save_changes_btn" onclick="return confirm('Are you sure you want to save changes?')"class="btn btn-success pull-right">
                                            <?php 
                                     }else{
                                       ?>
                                    <button  onclick="grn_reason_adjustements()" style="width:27px;" class='art-button-green'>SAVE </button>      
                                    <?php  
                                     }
                                ?>
                    
                    </td>
                </tr>
            </table>
            </form>
	<!--</form>-->
</fieldset>
        </td>
    </tr>
</table>
        </center>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h4 class="box-title">
                         List OF Reasons 
                    </h4>
                </div>
                <div class="box-body" style="height:300px;overflow: auto">
                    <table class="table table-bordered table-hover table-striped">
                        <tr>
                            <th style="width: 50px">S/No.</th>
                            <th>REASON</th>
                            <th>SAVED BY</th>
                            <th style="width: 50px">ENABLED BY</th>
                            <th style="width: 200px">DISABLE BY</th>
                            <th style="width: 50px">EDIT</th>
                            <th style="width: 200px">DISABLE/ENABLE</th>
                            
                        </tr>
                        <?php 
                            $count=1;
                            $sql_select_reasons_result=mysqli_query($conn,"SELECT reason_id,reasons,Status,Employee_ID,ENABLED_BY,DISABLE_BY FROM tbl_reasons_adjustment") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_reasons_result)>0){
                                while($reasons_rows=mysqli_fetch_assoc($sql_select_reasons_result)){
                                   $reasons= $reasons_rows['reasons'];
                                   $reason_id= $reasons_rows['reason_id'];
                                 $Status_reasons=$reasons_rows['Status'];
                                 $ENABLED_BY=$reasons_rows['ENABLED_BY'];
                                 $DISABLE_BY =$reasons_rows['DISABLE_BY'];
                                 $Employee_ID =$reasons_rows['Employee_ID'];
                                 $employee_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
                                 $employee_ENABLE=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$ENABLED_BY'"))['Employee_Name'];
                                 $employee_DISABLE=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$DISABLE_BY'"))['Employee_Name'];
                                
                                   echo "
                                       <tr>
                                        <td>$count</td>
                                        <td>$reasons</td>
                                        <td> $employee_Name</td>
                                     
                                         <td>
                                        $employee_ENABLE
                                         </td>
                                         <td>
                                        $employee_DISABLE
                                         </td>
                                            <td>
                                            <form action='' method='POST'>
                                                <input type='text' value='$reason_id'hidden='hidden' name='reason_id'/>
                                                <input type='submit' value='EDIT' class='art-button-green' name='edit_btn'/>
                                            </form>
                                        </td>
                                         </td>
                                        <td>
                                      
                                         <form action='' method='POST'>
                                                        <input type='text' name='reason_id' value='$reason_id' hidden='hidden'>";
                                    
                                                        if($Status_reasons=="enabled"){
                                                           echo "<input type='submit' name='disable_btn' value='DISABLE REASONS' class='btn art-button btn-sm'>";  
                                                        }else{
                                                           echo "<input type='submit' name='enable_btn' value='ENABLE REASONS' class='btn btn-danger btn-block btn-sm'>"; 
                                                        }
                                                        
                                                                echo "
                                                    </form>
                                        </td>
                                          <td>
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
        </div>
    </div>
<br/>
<script>
    
  function grn_reason_adjustements(){
    var reason_Name = $("#reason_Name").val();
    var Employee_ID = '<?=  $Employee_ID ?>';
    
       if(reason_Name==""){
           alert("Fill reason Name");
           exit();
       }
     $.ajax({
      
             type:'POST',
             url:'Grn_reason_adjustemet.php',
             data:{reason_Name:reason_Name,Employee_ID:Employee_ID},
             success:function(data){
                 console.log(data);
                alert("sussfully reason inserted");
                 $("#reason_Name").val('');
             }
         });
}


</script>
<?php
    include("./includes/footer.php");
?>
