<?php
    include("./includes/connection.php");
    if(isset($_GET['direction'])){
        if($_GET['direction']=='Direct To Clinic'){
          $Select_Consultant = "select Clinic_Name,Clinic_ID from tbl_clinic where Clinic_Status = 'Available'";
            $result = mysqli_query($conn,$Select_Consultant);
            ?>
            <option></option>
            <?php
            while($row = mysqli_fetch_array($result)){
                ?>
                <option value="<?php echo $row['Clinic_ID']; ?>"><?php echo $row['Clinic_Name']; ?></option>
            <?php
            }
            
        }elseif($_GET['direction']=='Direct To Doctor'){
            $Select_Consultant = "select Employee_Name,Employee_ID from tbl_employee e
                                    where e.Employee_Type = 'Doctor' and Account_Status = 'active'";
                $result = mysqli_query($conn,$Select_Consultant);
                ?>
                <option></option>
                <?php
                while($row = mysqli_fetch_array($result)){
                    ?>
                <option value="<?php echo $row['Employee_ID']; ?>"><?php echo $row['Employee_Name']; ?></option>
                <?php
                }
        }
    }
    
?>