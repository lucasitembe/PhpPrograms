<?php
    include("./includes/connection.php");
    if(isset($_GET['Batch_Name'])){
        $Batch_Name = $_GET['Batch_Name'];
    }else{
        $Batch_Name = "";
    }
    
    $Select_Batch = "select Blood_ID from tbl_patient_blood_data d
                            where d.Blood_Batch = '$Batch_Name'";
    $result = mysqli_query($conn,$Select_Batch);
    ?>
		<option selected='selected'></option>
    <?php
    while($row = mysqli_fetch_array($result)){
        ?>
        <option><?php echo $row['Blood_ID']; ?></option>
    <?php
    }
?> 