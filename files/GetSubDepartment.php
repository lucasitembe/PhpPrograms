<?php
    include("./includes/connection.php");
    if(isset($_GET['Department_ID'])){
        $Department_ID = $_GET['Department_ID']; 
    }else{
        $Department_ID = 0;
    }
    
   $Select_SubDepartment = "select * from tbl_sub_department
                            where department_id = '$Department_ID' and 
                            Sub_Department_Status = 'active'";
    $result = mysqli_query($conn,$Select_SubDepartment);
    ?> 
    <?php
    $count_sn=1;
    ?><table class="table"><tr><td>S/No.</td><td>Sub Department Name</td><td width="100px">Action</td><?php
    while($row = mysqli_fetch_array($result)){
        $Sub_Department_ID=$row['Sub_Department_ID'];
        ?>
        <tr>
            <td><?= $count_sn ?>.</td>
            <td><?= $row['Sub_Department_Name'] ?></td>
            <td>
                <input type="button" value="ADD SUB DEPARTMENT" class="art-button-green"onclick="add_subdepartment(<?= $Sub_Department_ID ?>,<?= $Department_ID ?>)"/>
            </td>
        </tr>
    <?php
         $count_sn++;
    }
?> 
    </tr></table>