<?php
    include("./includes/connection.php");
    if(isset($_GET['direction'])){
        if($_GET['direction']=='clinic'){
          $Select_Consultant = "select * from tbl_clinic";
            $result = mysqli_query($conn,$Select_Consultant);
            ?>
            <option></option>
            <?php
            while($row = mysqli_fetch_array($result)){
                ?>
                <option value='<?php echo $row['Clinic_ID']; ?>'><?php echo $row['Clinic_Name']; ?></option>
            <?php
            }
            
        }else{
            $Select_Consultant = "select * from tbl_employee e
                                    where e.Employee_Type = 'Doctor' ";
                $result = mysqli_query($conn,$Select_Consultant);
                ?>
                <option></option>
                <?php
                while($row = mysqli_fetch_array($result)){
                    ?>
                    <option value='<?php echo $row['Employee_ID']; ?>'><?php echo $row['Employee_Name']; ?></option>
                <?php
                }
            }
    }
    
?>