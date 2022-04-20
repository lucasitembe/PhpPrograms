<?php
    include("./includes/connection.php");
    $Clinic_Name = $_GET['Clinic_Name'];
    $Type_Of_Check_In = $_GET['Type_Of_Check_In'];
    $Select_Consultant = "select * from tbl_sub_department sd,tbl_department d WHERE sd.Department_ID=d.Department_ID
        AND d.Department_Location='$Type_Of_Check_In'"; 
        
        $result = mysqli_query($conn,$Select_Consultant);
    ?><option></option><?php
    while($row = mysqli_fetch_array($result)){
        ?>
        <option value='<?php echo $row['Sub_Department_ID']; ?>'><?php echo $row['Sub_Department_Name']; ?></option>
    <?php
    }
?>