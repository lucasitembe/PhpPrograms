<?php
    include("./includes/connection.php");
    if(isset($_GET['temp'])){
        $temp = $_GET['temp'];
    }else{
        $temp = '';
    }


    //get the last value
    $select = mysqli_query($conn,"select * from tbl_approval_level where Approval_ID = '$temp'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Approval_Title =$data['Approval_Title'];
        }
    }else{
        $Approval_Title = '';
    }
?>
<select name="<?php echo $temp; ?>" id="<?php echo $temp; ?>" onchange="Update_Approval_Level(<?php echo $temp; ?>)">
    <option selected="selected" value="">~~~ Select Approval Title ~~~</option>
    <option <?php if($Approval_Title =='Accountant'){ echo "selected='selected'"; } ?>>Accountant</option>
    <option <?php if($Approval_Title =='Billing Personnel'){ echo "selected='selected'"; } ?>>Billing Personnel</option>
    <option <?php if($Approval_Title =='Cashier'){ echo "selected='selected'"; } ?>>Cashier</option>
    <option <?php if($Approval_Title =='Doctor'){ echo "selected='selected'"; } ?>>Doctor</option>
    <option <?php if($Approval_Title =='Food Personnel'){ echo "selected='selected'"; } ?>>Food Personnel</option>
    <option <?php if($Approval_Title =='IT Personnel'){ echo "selected='selected'"; } ?>>IT Personnel</option>
    <option <?php if($Approval_Title =='Laboratory Technician'){ echo "selected='selected'"; } ?>>Laboratory Technician</option>
    <option <?php if($Approval_Title =='Laundry Personnel'){ echo "selected='selected'"; } ?>>Laundry Personnel</option>
    <option <?php if($Approval_Title =='Nurse'){ echo "selected='selected'"; } ?>>Nurse</option>
    <option <?php if($Approval_Title =='Pharmacist'){ echo "selected='selected'"; } ?>>Pharmacist</option>
    <option <?php if($Approval_Title =='Radiologist'){ echo "selected='selected'"; } ?>>Radiologist</option>
    <option <?php if($Approval_Title =='Receptionist'){ echo "selected='selected'"; } ?>>Receptionist</option>
    <option <?php if($Approval_Title =='Record Personnel'){ echo "selected='selected'"; } ?>>Record Personnel</option>
    <option <?php if($Approval_Title =='Security Personnel'){ echo "selected='selected'"; } ?>>Security Personnel</option>
    <option <?php if($Approval_Title =='Store Keeper'){ echo "selected='selected'"; } ?>>Store Keeper</option>
    <option <?php if($Approval_Title =='Others<'){ echo "selected='selected'"; } ?>>Others</option> 
</select>