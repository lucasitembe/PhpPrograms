<?php
//START CODE FOR SETUP MORGU BY MICHAEL YONGO CODING FROM GPITG-
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>
<a href='edititems.php?edititems=edititemsthispage' class='art-button-green'>
    BACK
</a>

<br/>
<?php 
    $feedback_message="";
    if(isset($_POST['save_btn'])){
       $item_id=$_POST['item_id'];
       $guarantor=$_POST['guarantor'];
       //$item_name=$_POST['Product_Name'];                                      //SICAI 
       $charges_type=$_POST['charges_type'];
       $inalala_bilakulala=$_POST['inalala_bilakulala'];
       $ageFrom=$_POST['ageFrom'];
       $ageTO=$_POST['ageTO'];
       $admitted_from=$_POST['admitted_from'];
       $charges_duration=$_POST['charges_duration'];
       $date_status=$_POST['date_status'];
       $price=$_POST['price'];
          // kutest catchup
       //echo 'id='.$item_id.'<br>'; 
       //echo 'type='.$charges_type.'<br>'; 
       //echo 'from='.$ageFrom.'<br>'; 
       //echo 'to='.$ageTo.'<br>'; 
       //echo 'stts='.$date_status.'<br>'; 
        //echo 'pricekunta='.$price.'<br>';
      // exit;
       
        $sql_save_result=mysqli_query($conn,"INSERT INTO `tbl_morgue_prices`(`item_id`, `Sponsor_ID`, `price`, `charges_type`,`inalala_bilakulala`, `ageFrom`, `ageTO`,`admitted_from`,`charges_duration`, `date_status`) VALUES ('$item_id','$guarantor','$price','$charges_type','$inalala_bilakulala','$ageFrom','$ageTO','$admitted_from','$charges_duration','$date_status')") or die(mysqli_error($conn));
        if($sql_save_result){
           $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> Saved Successfully
                              </div>"; 
        }else{
             $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Saving Fail Please Try Again
                              </div>"; 
        }
    }
    if(isset($_POST['save_changes_btn'])){
        $id=$_POST['id'];
        $item_id=$_POST['item_id'];
       $guarantor=$_POST['guarantor'];
       //$item_name=$_POST['Product_Name'];                                      //SICAI 
       $charges_type=$_POST['charges_type'];
       $inalala_bilakulala=$_POST['inalala_bilakulala'];
       $ageFrom=$_POST['ageFrom'];
       $admitted_from=$_POST['admitted_from'];
       $charges_duration=$_POST['charges_duration'];
       $ageTO=$_POST['ageTO'];
       $date_status=$_POST['date_status'];
       $price=$_POST['price'];
       
      // echo '$item_id='.$item_id.'<br>';
//echo '$guarantor='.$guarantor.'<br>';
//echo '$charges_type='.$charges_type.'<br>';
//echo '$ageFrom='.$ageFrom.'<br>';
//echo '$ageTo='.$ageTO.'<br>';
//echo '$date_status='.$date_status.'<br>';
//echo '$price='.$price.'<br>';
        
   //echo 'id='.$id.'<br>';
   //exit;
       
        //$sql_insert_result=mysqli_query($conn,"UPDATE tbl_morgue_prices SET charges_type='$charge_type',ageFrom='$ageFrom',ageTO='$ageTO',date_status='$date_status' WHERE item_id='$item_id'") or die(mysqli_error($conn));
   $sql_insert_result=mysqli_query($conn,"UPDATE tbl_morgue_prices SET  item_id='$item_id',Sponsor_ID='$guarantor', price='$price', charges_type='$charges_type',inalala_bilakulala='$inalala_bilakulala', ageFrom='$ageFrom', ageTO='$ageTO',admitted_from='$admitted_from',charges_duration='$charges_duration', date_status='$date_status' WHERE id='$id'") or die(mysqli_error($conn));
//INSERT INTO `tbl_morgue_prices`(`item_id`, `Sponsor_ID`, `price`, `charges_type`, `ageFrom`, `ageTO`, `date_status`) VALUES ('$item_id','$guarantor','$price','$charges_type','$// ////INSERT INTO `tbl_morgue_prices`(`item_id`, `Sponsor_ID`, `price`, `charges_type`, `ageFrom`, `ageTO`, `date_status`) VALUES ('$item_id','$guarantor','$price','$charges_type','$ageFrom','$ageTO','$date_status'
//KUNTA NABADILI VARIABLE ,MICHAEL YONGO CODING FROM GPITG-
 
   
       if($sql_insert_result){
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
    
    
        if($_POST['disable_btn']){
            $item_id=$_POST['item_id'];
            $id=$_POST['id'];
            
            $sql_disable_diagnosis=mysqli_query($conn,"UPDATE tbl_morgue_prices SET enabled_disabled='disabled' WHERE  id='$id'") or die(mysqli_error($conn));
            
            if($sql_disable_diagnosis){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> Fail to disable FINANCE department..please try again
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong>ou have disable and remove Item from Mortuary Bill  successfully 
                              </div>"; 
            }
        }
        if($_POST['enable_btn']){
             $id=$_POST['id']; 
         
//            $item_id=$_POST['item_id'];
            $sql_disable_FINANCE_department=mysqli_query($conn,"UPDATE tbl_morgue_prices SET enabled_disabled='enabled' WHERE id='$id'") or die(mysqli_error($conn));
            
            if($sql_disable_FINANCE_department){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> you have enable Item for Billing  successfully
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Fail to enable Item from the list..please try again
                              </div>"; 
            }
        }
        
        if(isset($_POST['edit_btn'])){
           $id=$_POST['id']; 
           $sql_select_idara_result=mysqli_query($conn,"SELECT * FROM tbl_morgue_prices WHERE id='$id'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_idara_result)>0){
                                while($idara_rows=mysqli_fetch_assoc($sql_select_idara_result)){
                                    //$count=$idara_rows['id'];
                                    $id=$idara_rows['id'];
                                     $item_id=$idara_rows['item_id'];
                                   
                                   $item_name= $idara_rows['Product_Name'];
                                   $guarantor=$idara_rows['Guarantor_Name'];
                                   $charges_type=$idara_rows['charges_type'];
                                   $inalala_bilakulala=$idara_rows['inalala_bilakulala'];
                                   $ageFrom=$idara_rows['ageFrom'];
                                   $admitted_from=$idara_rows['admitted_from'];
                                   $charges_duration=$idara_rows['charges_duration'];
                                   $ageTO=$idara_rows['ageTO'];
                                   $date_status=$idara_rows['date_status'];
                                   $price=$idara_rows['price'];
                                   $Sponsor_ID=$idara_rows['Sponsor_ID'];
                                   //$change_item=$idara_rows['change_item'];
                                   //$enabled_disabled=$idara_rows['enabled_disabled'];
                                   
                                   //$guarator=$idara_rows['Guarantor_Name'];
                                   //$age_days=$idara_rows['age_days'];
              } 
           }else{
              $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Process Fail..try again
                              </div>";  
           }
        }else{
           $item_id=""; 
           $item_name="";
           $guarantor=""; 
           $charges_type="";
           $inalala_bilakulala="";
           //$item_conditon="";
          // $item_name="";
           $ageFrom="";
           $ageTO="";
           $admitted_from="";
           $charges_duration="";
           $date_status="";
           //$enabled_disabled="";
           $price="";
        }
        //echo 'kunattest'.$item_id.'<br>';
//echo 'kunattest'.$guarantor.'<br>';
//echo 'kunattest'.$charges_type.'<br>';
//echo 'kunattest'.$ageFrom.'<br>';
//echo 'kunattest'.$ageTo.'<br>';
//echo 'kunattest'.$date_status.'<br>';
//MICHAEL YONGO CODING FROM GPITG-
//cho 'kunattest'.$price.'<br>';
        //echo 'kunattest'.$item_name.'<br>';
?>
<fieldset>  
    <legend align=center><b>ADD MORTUARY ITEM FOR BILLING PROCESS</b></legend>
    <div class="row">
        <div class="col-md-3"> </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <?= $feedback_message ?>
                </div>
                <div class="box-body">
                    <form action="" method="POST" class="form-horizontal">
                                              <div class="form-group">
                                                  
                            <label class="col-md-3">Sponsor type</label>
                            <div class="col-md-9">
                                <?php 
                                $filter_sponsor="";
                                
                    
                                ?>
     <select name='guarantor'>
                    <!--<option value="">You must choose Sponsor</option>-->
                    <?php
                    
                        $select= mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor WHERE add_sponsor_for_billing='yes'") or die(mysqli_error($conn));
                     if(mysqli_num_rows($select)>0){
                     while ($sponsor_rows = mysqli_fetch_assoc($select)) {
                         if(isset($_POST['edit_btn'])){
                      //$filter_sponsor="WHERE Sponsor_ID='$Sponsor_ID'"; 
                             $selected="";
                             if($sponsor_rows['Sponsor_ID']==$Sponsor_ID){
                                $selected="selected='selected'";
                                       }
                              }elseif($sponsor_rows['Sponsor_ID']==29793){
                                $selected="selected='selected'";
                                  
                              }
                         ?>
                            <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'  <?= $selected ?>><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                    <?php
                        } 
                     }
                    ?>     
                         
                         
                            <input type="number" hidden="hidden" value="<?= $id ?>" name="id"/>
                       <input type="text" hidden="hidden" value="<?= $item_id ?>" name="item_name"/>
                </select> 
                            </div>
                        </div>
                        <!--KUNTA CHANGES QUERY ITEM-->
                        <div class="form-group">
                            <label class="col-md-3">Item Name</label>
                            <div class="col-md-9">
                                <?php 
                                //$filter_sponsor="";
                                
                    
                                ?>
     <select name='item_id'>
                    <option value="ALL">Select Item to add</option>
                    
                    <?php
                    
                        $select= mysqli_query($conn,"SELECT Item_ID,Product_Name FROM `tbl_items` WHERE `Consultation_Type`='Mortuary'") or die(mysqli_error($conn));
                     if(mysqli_num_rows($select)>0){
                     while ($sponsor_rows = mysqli_fetch_assoc($select)) {
                         if(isset($_POST['edit_btn'])){
                      //$filter_sponsor="WHERE Sponsor_ID='$Sponsor_ID'"; 
                             $selected="";
                             if($sponsor_rows['Item_ID']==$item_id){
                                $selected="selected='selected'";
                                       }
                              }
                         ?>
                            <option value='<?php echo $sponsor_rows['Item_ID']; ?>'  <?= $selected ?>><?php echo $sponsor_rows['Product_Name']; ?></option>
                    <?php
                        } 
                     }
                    ?>     
                         
                         
                    
                       
                </select> 
                            </div>
                        </div>
                        
                    
                        
                        
                        
                       <!--add ITEM_CONDITION-->
                 <div class="form-group">
                            <label class="col-md-3">Charges Type</label>
                            <div class="col-md-9">
                                 <?php 
                                $filter_charges="";
                                $selected="";
                    if(isset($_POST['edit_btn'])){
                      $filter_charges="AND charges='$charges'"; 
                      $selected="selected='selected'";
                    }
                                ?>
     <select name="charges_type" id='Sponsor_ID'>
                    <option value="ALL"> Charges Type </option>
                    <option  value="services" <?=$selected?>>Services</option>
                    <option value="keeping"<?=$selected?>>Keeping</option>
                </select> 
                            </div>
                        </div>
                       <!--kuongeza mtu achague kama body ailali inatoka within 24hrs-->
                        <div class="form-group">
                            <label class="col-md-3">OUT BEFORE OR AFTER 24HRS?</label>
                            <div class="col-md-9">
                                 <?php 
                                $filter_charges="";
                                $selected="";
                    if(isset($_POST['edit_btn'])){
                      $filter_charges="AND charges='$charges'"; 
                      $selected="selected='selected'";
                    }
                                ?>
     <select name="inalala_bilakulala" id=' '>
                    <option value="ALL"> OUT BEFORE OR AFTER 24HRS? </option>
                    <option  value="inalala" <?=$selected?>>Maiti inayolala</option>
                    <option value="bilakulala"<?=$selected?>>Inachukuliwa bila kulala</option>
                </select> 
                            </div>
                        </div>
                       
                        <!--end of add-->
                        
                        <div class="form-group">
                            <label class="col-md-3">Age range</label>
                            <div class="col-md-9">
                                <div class="col-md-2">
                                <label class="">From</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" required="" value="<?= $ageFrom ?>" name="ageFrom">
                                </div>
                                <div class="col-md-1">
                                <label class="">To</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number"  class="form-control" required="" value="<?= $ageTO ?>" name="ageTO">
                                </div>
                                <div class="col-md-3">
                                <select required="" name="date_status" class="form-control">
                                    <?php 
                                $filter_kogajadate="";
                                $selected="";
                    if(isset($_POST['edit_btn'])){
                      $filter_kogajadate="AND charges='$kogajadate'"; 
                      $selected="selected='selected'";
                    }
                                ?>
                                    <option value="years"   name="date_status"<?=$selected?>> Years</option>
                                    <option value="months" name="date_status"<?=$selected?>>Months</option>
                                    <option value="days" name="date_status"<?=$selected?>>Days</option>
                                    <option value="selected" name="date_status">Select Range Status</option>
                                    <!--<input aligment="center" name="age_days">-->
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Item price</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" required=""  name="price" placeholder="Enter Price in Tsh" value="<?=$price?>"></input>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Body Admitted From</label>
                            <div class="col-md-9">
                                 <?php 
                                $filter_charges="";
                                $selected="";
                    if(isset($_POST['edit_btn'])){
                      $filter_charges="AND charges_duration='$charges_duration'"; 
                      $selected="selected='selected'";
                    }
                                ?>
     <select name="admitted_from" id='admitted_from'>
                    <!--<option value="ALL">Admitted From </option>-->
                    <option  value="from_ward" <?=$selected?>>INPATIENT-Admitted From ward</option>
                    <option value="from_outside"<?=$selected?>>OUTSIDE-Admitted as Dead body</option>
                </select> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">This Item or Services will be charged after :  </label>
                            <div class="col-md-9">
                                <input type="text" class="col-md-3"   name="charges_duration" placeholder="Enter charges hours" value="<?=$charges_duration?>"></input>
                            </div>
                            <label class="col-md-3"> Hours </label>
                        </div>
              
                        <div class="form-group">
                            <div class="col-md-12"> 
                                <?php 
                                     if(isset($_POST['edit_btn'])){
                                        ?>
                                <input type="submit" value="SAVE CHANGES FOR ITEM" name="save_changes_btn" onclick="return confirm('Are you sure you want to save changes?')"class="btn btn-success pull-right">
                                            <?php 
                                     }else{
                                       ?>
                                    <input type="submit" value="ADD ITEM FOR BILLING" name="save_btn" onclick="return confirm('Are you sure you want to Add this Mortuary Item?')"class="art-button-green pull-right">      
                                    <?php  
                                     }
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!--<div class="col-md-2"></div>-->
        <div class="col-md-10 col-md-offset-1">
            <div class="box box-primary">
                <div class="box-header">
                    <h4 class="box-title">
                        Mortuary Item and Services Charges List
                    </h4>
                </div>
                <div class="box-body"style="height:400px;overflow-y: scroll">
                    <table class="table table-bordered table-hover table-striped" >
                        <tr>
                            <th style="width: 50px">S/No.</th>
                            <th>Sponsor Type</th>
                            <th>Item Name</th>
                            <th>Charges Type</th>
                            <th>Inalala/</th>
                            <th>Age Range From</th>
                            <th>To</th>
                            <th>Age Status</th>
                             <th>Adimitted From</th>
                              <th>Charged Hours</th>
                            <th>Price</th>
                            
  
                            <th style="width: 50px">CHANGE ITEM</th>
                            <th style="width: 200px">DISABLE/ENABLE</th>
                        </tr>
                        <tbody>
                        <?php 
                            $count=1;
               $sql_select_idara_result=mysqli_query($conn,"SELECT tbl_morgue_prices.id,tbl_morgue_prices.item_id,tbl_morgue_prices.Sponsor_ID,Guarantor_Name,Product_Name,charges_type,inalala_bilakulala,ageFrom,admitted_from,charges_duration,ageTO,date_status,price,enabled_disabled FROM tbl_morgue_prices INNER JOIN tbl_items ON tbl_morgue_prices.item_id=tbl_items.Item_Id INNER JOIN tbl_sponsor ON tbl_morgue_prices.Sponsor_ID=tbl_sponsor.Sponsor_ID ORDER BY `tbl_morgue_prices`.`id` DESC
") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_idara_result)>0){
                                while($idara_rows=mysqli_fetch_assoc($sql_select_idara_result)){
                                    
                                   $item_id= $idara_rows['item_id'];
                                   $Sponsor_ID=$idara_rows['Sponsor_ID'];
                                   $guarantor=$idara_rows['Guarantor_Name'];
                                   $item_name=$idara_rows['Product_Name'];
                                   $charges_type=$idara_rows['charges_type'];
                                   $inalala_bilakulala=$idara_rows['inalala_bilakulala'];
                                   $ageFrom=$idara_rows['ageFrom'];
                                   $admitted_from=$idara_rows['admitted_from'];
                                   $charges_duration=$idara_rows['charges_duration'];
                                   $ageTO=$idara_rows['ageTO'];
                                   $date_status=$idara_rows['date_status'];
                                   $price=$idara_rows['price'];
                                   $enabled_disabled=$idara_rows['enabled_disabled'];
                                   $id=$idara_rows['id'];
                                           
                                  //<td>$$item_id</td>
                                   //<td>$age_days</td>
                                             //kuntaedit<td>$item_id</td>
//                                   KUNTACODE FORMAT NUMBER
                                   $priceDecimal=number_format($price,2);
                                  echo "
                                       <tr>
                                        <td>$count</td>
                              
                                        <td>$guarantor</td>
                                        <td>$item_name</td>
                                        <td>$charges_type</td>
                                            <td>$inalala_bilakulala</td>
                                          <td>$ageFrom</td>  
                                          <td> $ageTO</td>  
                                          <td>$date_status</td> 
                                              <td>$admitted_from</td>
                                               <td>$charges_duration</td>
                                              <td>$priceDecimal</td>
                                          
                                         
                                        <td>
                                            <form action='' method='POST'>
                                                <input type='text' value='$id'hidden='hidden' name='id'/>
                                                <input type='submit' value='EDIT' class='art-button-green' name='edit_btn'/>
                                            </form>
                                        </td>
                                        <td>
                                         <form action='' method='POST'>
                                                        <input type='text' value='$id' hidden='hidden' name='id'/>
                                                       <input type='text' name='item_id' value='$item_id' hidden='hidden'>";
                                    
                                                        if($enabled_disabled=="enabled"){
                                                           echo "<input type='submit' name='disable_btn' value='DISABLE ITEM BILLING' class='btn art-button btn-sm'>";  
                                                        }else{
                                                           echo "<input type='submit' name='enable_btn' value='ENABLE ITEM BILLING' class='btn btn-danger btn-block btn-sm'>"; 
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   
    
</fieldset>
<?php
include("./includes/footer.php");
//END OF CODE FOR SETUP BY MICHAEL YONGO CODING FROM GPITG-
?>