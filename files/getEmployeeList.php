<?php
    include("./includes/connection.php");
    if(isset($_GET['branchID'])){
        $branchID = $_GET['branchID'];
    }else{
        $branchID = 0;
    }
    
    $Select_employee = "SELECT * FROM tbl_employee emp,tbl_department dept,tbl_branches br
                        WHERE emp.Department_ID=dept.Department_ID
                        AND dept.Branch_ID=br.Branch_ID
                        AND br.Branch_ID='$branchID' ORDER BY emp.Employee_Name ASC ";
    $result = mysqli_query($conn,$Select_employee);
    ?> 
    <?php
             echo "<option selected='selected' value='0'>All</option>";
        while($row = mysqli_fetch_array($result)){
        //if($Region_ID == 0)
   
        ?>
        <option value="<?php echo $row['Employee_ID']?>"><?php echo $row['Employee_Name']; ?></option>
    <?php
    }
?> 