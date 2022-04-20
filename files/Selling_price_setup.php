<?php
include_once("./includes/header.php");
include_once("./includes/connection.php");
?>
<a href="storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage" class="art-button-green">BACK</a>
<br/>
<br/>
<?php 
    if(isset($_POST['remove_btn'])){
        $store_selling_price_id=$_POST['store_selling_price_id'];
        $sql_remove_settings_result=mysqli_query($conn,"DELETE FROM tbl_store_selling_price_setup WHERE store_selling_price_id='$store_selling_price_id'") or die(mysqli_error($conn));
    
        if($sql_remove_settings_result){
            ?>
                <script>
                    alert('Removed Successfully');
                </script>
            <?php
        }else{
          ?>
                <script>
                    alert('Fail To remove...try again later');
                </script>
            <?php  
        }
    }
    if(isset($_POST['save_btn'])){
       $Sub_Department_ID=$_POST['Sub_Department_ID'];
       $Sponsor_ID=$_POST['Sponsor_ID'];
       //check if exist
       $sql_check_if_aleardy_set_result=mysqli_query($conn,"SELECT Sub_Department_ID FROM tbl_store_selling_price_setup WHERE Sub_Department_ID='$Sub_Department_ID'") or die(mysqli_error($conn));
       if(mysqli_num_rows($sql_check_if_aleardy_set_result)>0){
           ?>
               <script>
                   alert("The Cost Center already exit in setup");
               </script>
               <?php
       }else{
           $sql_insert_cost_center_result=mysqli_query($conn,"INSERT INTO tbl_store_selling_price_setup(Sub_Department_ID,Sponsor_ID) VALUES('$Sub_Department_ID','$Sponsor_ID')") or die(mysqli_error($conn));
       
           if($sql_insert_cost_center_result){
               ?>
               <script>
                   alert("Cost Center Saved Successfully");
               </script>
               <?php
           }else{
               ?>
               <script>
                   alert("Saving Fail..please try again");
               </script>
               <?php
           }
       }
    }
?>
<fieldset>
        <legend align='center'><b>Store Selling Price Setup</b></legend>
        <div class="row">
            <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="" method="POST">
                <table class="table">
                <tr>
                    <td style="width: 50%">Select Cost Center You want To set selling Price</td>
                    <td style="width: 50%">
                        <select style="width: 100%" name="Sub_Department_ID" required="">
                            <option></option>
                            <?php 
                                $sql_select_costcenter_result=mysqli_query($conn,"SELECT Sub_Department_ID,Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_Status='active'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_costcenter_result)>0){
                                   while($cost_center_rows=mysqli_fetch_assoc($sql_select_costcenter_result)){
                                       $Sub_Department_ID=$cost_center_rows['Sub_Department_ID'];
                                       $Sub_Department_Name=$cost_center_rows['Sub_Department_Name'];
                                       echo "<option value='$Sub_Department_ID'>$Sub_Department_Name</option>";
                                   } 
                                }
                             ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Select Sponsor to use its price as the selling price</td>
                    <td>
                        <select style="width: 100%" required="" name="Sponsor_ID">
                            <option></option>
                            <?php 
                                $sql_select_sponsor_result=mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_sponsor_result)>0){
                                    while($ponsor_rows=mysqli_fetch_assoc($sql_select_sponsor_result)){
                                       $Guarantor_Name=$ponsor_rows['Guarantor_Name'];
                                       $Sponsor_ID=$ponsor_rows['Sponsor_ID'];
                                       echo "<option value='$Sponsor_ID'>$Guarantor_Name</option>";
                                    }
                                }
                              ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="SAVE" name="save_btn" class="art-button-green pull-right"/>
                    </td>
                </tr>
            </table>
            </form>
        </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h4 class="box-title">Configured Cost Center</h4>
                    </div>
                    <div class="box-body">
                        <table class="table">
                            <tr>
                                <td style="width:50px">S/No.</td>
                                <td>Cost Center</td>
                                <td>Sponsor</td>
                                <td style="width:50px">Remove</td>
                            </tr>
                            <?php 
                                $sql_select_configured_result=mysqli_query($conn,"SELECT store_selling_price_id,Guarantor_Name,Sub_Department_Name FROM tbl_store_selling_price_setup slps INNER JOIN tbl_sponsor sp ON slps.Sponsor_ID=sp.Sponsor_ID INNER JOIN tbl_sub_department sd ON slps.Sub_Department_ID=sd.Sub_Department_ID") or die(mysql);
                            
                                if(mysqli_num_rows($sql_select_configured_result)>0){
                                    $count=1;
                                    while($configured_rows=mysqli_fetch_assoc($sql_select_configured_result)){
                                        $store_selling_price_id=$configured_rows['store_selling_price_id'];
                                        $Guarantor_Name=$configured_rows['Guarantor_Name'];
                                        $Sub_Department_Name=$configured_rows['Sub_Department_Name'];
                                        echo "<tr>
                                                <td>$count</td>
                                                <td>$Sub_Department_Name</td>
                                                <td>$Guarantor_Name</td>
                                                <td>
                                                    <form action='' method='POST'>
                                                        <input type='text' name='store_selling_price_id' hidden='hidden' value='$store_selling_price_id'/>
                                                        <input type='submit' value='REMOVE'name='remove_btn' class='art-button-green'>
                                                    </form>
                                                </td>
                                                
                                             </tr>";
                                    }
                                }
                             ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</fieldset>
<script>
    $(document).ready(function (){
        $('select').select2();
    });
</script>

<?php
include("./includes/footer.php");
?>
