<?php 
             ///********************************changes made by gkcchief 17.11.2017 10:20 am*************************************************
    include("./includes/connection.php");
    
if(isset($_GET['Payment_Cache_ID'])){
    $Payment_Cache_ID=$_GET['Payment_Cache_ID'];
}else{
    $Payment_Cache_ID='';
} 
if(isset($_GET['Transaction_Type'])){
     $Transaction_Type=$_GET['Transaction_Type'];
}else{
    $Transaction_Type='';
}
if(isset($_GET['Registration_ID'])){
     $Registration_ID=$_GET['Registration_ID'];
}else{
    $Registration_ID='';
}

$count_dossage_medicine=0;
$dosage_detail="";

$count=1;
if(isset($_GET['drug_item_id'])){
    $Item_ID=$_GET['drug_item_id'];
    //for medical dosage control for credits patients....for those sponsor that support medical control....///////
      //the limit on the query is to check for the previous number of visit the patient has visit hospital and if he or she is currently on the douse previous given
      $sql_select_medicine_for_control_check="SELECT DATE_ADD(Dispense_Date_Time, INTERVAL dosage_duration DAY)as dourse_end_date,ilc.dosage_duration,pc.Payment_Cache_ID,ilc.Item_ID,ilc.Dispense_Date_Time,it.Product_Name FROM tbl_items it,tbl_payment_cache pc,tbl_item_list_cache ilc,tbl_sponsor sp"
                       . " WHERE it.Item_ID=ilc.Item_ID AND pc.Payment_Cache_ID=ilc.Payment_Cache_ID AND pc.Sponsor_ID=sp.Sponsor_ID AND sp.allow_dispense_control='yes' AND pc.Registration_ID='$Registration_ID' AND ilc.Item_ID='$Item_ID'"
                       ."AND DATEDIFF(CURDATE(),Dispense_Date_Time)<ilc.dosage_duration"
                       . " ORDER BY pc.Payment_Date_And_Time DESC";
                    
          $sql_select_medicine_for_control_check_result=mysqli_query($conn,$sql_select_medicine_for_control_check) or die(mysqli_error($conn));            
                if(mysqli_num_rows( $sql_select_medicine_for_control_check_result)>0){
                    while($items_rows=mysqli_fetch_assoc($sql_select_medicine_for_control_check_result)){
                        $dosage_duration=$items_rows['dosage_duration'];
                        $Dispense_Date_Time=$items_rows['Dispense_Date_Time'];
                        $Product_Name=$items_rows['Product_Name'];
                        $dourse_end_date=$items_rows['dourse_end_date'];
                        $count_dossage_medicine++;
                        
                        $dosage_detail.="<tr><td>$count</td><td>".$Product_Name."</td><td>".$dosage_duration." Days </td><td>".$Dispense_Date_Time."</td><td>$dourse_end_date</td></tr></tr><tr><td colspan='5'><hr style='border: .1px solid #ccc' /></td></tr>";
                   $count++;
                    }
                }
                
                
                
     if($count_dossage_medicine>0){
        ?>
            <table cellspacing="4" cellpadding="5" style="font-size:14px">
                <thead>
                    <tr>
                       <th colspan="5">THE PATIENT STILL HAVE THE SELECTED MEDICINE</th> 
                    </tr>
                    <tr>
                       <th colspan="5"><hr/></th> 
                    </tr>
                </thead>
                <tbody>
                    <tr>

                        <td>S/No.</td>
                        <td>Item Name</td>
                        <td>Dourse Duration</td>
                       
                        <td>Last Dispensed Date</td>
                        <td>Dourse End on</td>
                    </tr>
                    <tr>
                       <th colspan="5"><hr/></th> 
                    </tr>
                </tbody>
                <?php echo  $dosage_detail;
                    
                                ?>
                      <tr>
                           <td colspan="5">
                               <button style="float:right" class="art-button-green" onclick="close_dialog_drug_duration_alert()">OKEY</button>
                           </td>
                       </tr>
           
            
               
            </table>
            <?php
                 
    }else{
        echo 1;
    }
                
                
    
}else{
?>

    <?php
         //   echo $Payment_Cache_ID;echo "<br/>".$Transaction_Type;
    $sql_select="SELECT Item_ID,Edited_Quantity,status FROM tbl_item_list_cache WHERE Payment_Cache_ID = '$Payment_Cache_ID' and
                            Transaction_Type = '$Transaction_Type' AND status!='removed'";
    $sql_select_result=mysqli_query($conn,$sql_select) or die(myssql_error());
    $count=1;
  
    if(mysqli_num_rows($sql_select_result)>0){
        while($iterm_id_rows=mysqli_fetch_assoc($sql_select_result)){
                $Item_ID=$iterm_id_rows['Item_ID'];
             //   echo $Item_ID;
       //for medical dosage control for credits patients....for those sponsor that support medical control....///////
      //the limit on the query is to check for the previous number of visit the patient has visit hospital and if he or she is currently on the douse previous given
      $sql_select_medicine_for_control_check="SELECT DATE_ADD(Dispense_Date_Time, INTERVAL dosage_duration DAY)as dourse_end_date,ilc.dosage_duration,pc.Payment_Cache_ID,ilc.Item_ID,ilc.Dispense_Date_Time,it.Product_Name FROM tbl_items it,tbl_payment_cache pc,tbl_item_list_cache ilc,tbl_sponsor sp"
                       . " WHERE it.Item_ID=ilc.Item_ID AND pc.Payment_Cache_ID=ilc.Payment_Cache_ID AND pc.Sponsor_ID=sp.Sponsor_ID AND sp.allow_dispense_control='yes' AND pc.Registration_ID='$Registration_ID' AND ilc.Item_ID='$Item_ID'"
                       ."AND DATEDIFF(CURDATE(),Dispense_Date_Time)<ilc.dosage_duration"
                       . " ORDER BY pc.Payment_Date_And_Time DESC";
                    
          $sql_select_medicine_for_control_check_result=mysqli_query($conn,$sql_select_medicine_for_control_check) or die(mysqli_error($conn));            
                if(mysqli_num_rows( $sql_select_medicine_for_control_check_result)>0){
                    while($items_rows=mysqli_fetch_assoc($sql_select_medicine_for_control_check_result)){
                        $dosage_duration=$items_rows['dosage_duration'];
                        $Dispense_Date_Time=$items_rows['Dispense_Date_Time'];
                        $Product_Name=$items_rows['Product_Name'];
                        $dourse_end_date=$items_rows['dourse_end_date'];
                        $count_dossage_medicine++;
                        
                        $dosage_detail.="<tr><td>$count</td><td>".$Product_Name."</td><td>".$dosage_duration." Days </td><td>".$Dispense_Date_Time."</td><td>$dourse_end_date</td></tr></tr><tr><td colspan='5'><hr style='border: .1px solid #ccc' /></td></tr>";
                   $count++;
                    }
                } 
                       
        }
    }
   
    
    
    
   


     if($count_dossage_medicine>0){
        ?>
            <table cellspacing="4" cellpadding="5" style="font-size:14px">
                <thead>
                    <tr>
                       <th colspan="5">THE PATIENT STILL HAVE THE FOLLOWING MEDICINE</th> 
                    </tr>
                    <tr>
                       <th colspan="5"><hr/></th> 
                    </tr>
                </thead>
                <tbody>
                    <tr>

                        <td>S/No.</td>
                        <td>Item Name</td>
                        <td>Dourse Duration</td>
                       
                        <td>Last Dispensed Date</td>
                        <td>Dourse End on</td>
                    </tr>
                    <tr>
                       <th colspan="5"><hr/></th> 
                    </tr>
                </tbody>
                <?php echo  $dosage_detail;
                    if($_GET['after_bill']=="after_bill"){
                                ?>
                
                       <tr>
                           <td colspan="5">
                               <button style="float:right" class="art-button-green" onclick="close_dialog_drug_duration_alert()">CANCEL</button><button style="float:right" class="art-button-green" onclick="Dispense_Medication()">DISPENCE ANY WAY</button>
                           </td>
                       </tr>
           
            <?php
                    }else{
                ?>
               <tr>
                    <td colspan="5">
                        <button style="float:right" class="art-button-green" onclick="close_dialog_drug_duration_alert()">CANCEL</button><button style="float:right" class="art-button-green" onclick="Dispense_Credit_Medication()">DISPENCE ANY WAY</button>
                    </td>
                </tr>
                    <?php } ?>
            </table>
            <?php
                 
    }else{
        echo 1;
    }
}
?>