<?php

    include("./includes/connection.php");
    session_start();
    if(isset($_POST['searchitems'])){
        $Item_name = $_POST['Item_name'];
        if($Item_name !=''){
            $searchvalue = " AND Product_Name LIKE '%$Item_name%'";
        }else{
            $searchvalue =" ";
        }
        // die("SELECT Item_ID, Product_Name FROM tbl_items WHERE $searchvalue ");
        $slect_item = mysqli_query($conn, "SELECT Item_ID, Product_Name FROM tbl_items WHERE Consultation_Type IN ('Pharmacy', 'Laboratory', 'Others') $searchvalue ORDER BY  Item_ID ASC LIMIT 50 ") or die(mysqli_error($conn));

        if(mysqli_num_rows($slect_item)>0){
            $count_sn=1;
            while($irows=mysqli_fetch_assoc($slect_item)){
                $Item_ID=$irows['Item_ID'];
                $Product_Name=$irows['Product_Name'];
                echo "<tr  onclick='save_free_item($Item_ID)'>
                        <td class='rows_list'>$count_sn</td>
                        <td class='rows_list'>$Product_Name</td>" ?>
                        <td>
                        <select name='Sponsor_ID' id='Sponsor_ID_<?=$Item_ID?>' onchange="filterpatient()"class="form-control" style='text-align: center;width:100%;display:inline'>
                        <option value="">Select Sponsors</option>
                        <?php

                        $sponsor_results = mysqli_query($conn,"SELECT Sponsor_ID, Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
                        while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                            ?>
                            <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                            <?php
                        }
                        ?>
                    </select></td>
                        <?php echo "
                    </tr>";
                    $count_sn++;
            }
        }else{
            echo "<tr><td colspan='3'>No result found</td></tr>";
        }
    }

    if(isset($_POST['saveitem'])){
        $Item_ID = $_POST['Item_ID'];
        $Sponsor_ID = $_POST['Sponsor_ID'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $selecteifexist = mysqli_query($conn, "SELECT * FROM tbl_inclusive_service WHERE Sponsor_ID='$Sponsor_ID' AND Item_ID='$Item_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($selecteifexist)>0){
            echo "Exist";
        }else{
            $savefreeitem =  mysqli_query($conn, "INSERT INTO tbl_inclusive_service(Item_ID, Saved_by, Sponsor_ID) VALUES('$Item_ID', '$Employee_ID', '$Sponsor_ID')") or die(mysqli_error($conn));
            if($savefreeitem){
                echo "success";
            }else{
                echo "failed";
            }       
            
        }
    }

    if(isset($_POST['dispalysaved'])){
        $selected_item =mysqli_query($conn, "SELECT Product_Name, Inclusive_ID, si.Item_ID, Guarantor_Name FROM tbl_sponsor sp, tbl_inclusive_service si, tbl_items i WHERE si.Sponsor_ID=sp.Sponsor_ID AND si.Item_ID=i.Item_ID") or die(mysqli_error($conn)); 
        $num=1;
        if(mysqli_num_rows($selected_item)>0){
            while($rw =mysqli_fetch_assoc($selected_item)){
                $Product_Name =$rw['Product_Name'];
                $Guarantor_Name =$rw['Guarantor_Name'];
                $Item_ID =$rw['Item_ID'];
                $Inclusive_ID = $rw['Inclusive_ID'];
                echo "<tr>
                    <td>$num</td>
                    <td>$Product_Name</td>
                    <td>$Guarantor_Name</td>
                    <td ><input type='button' class='btn btn-danger btn-sm' onclick='remove_item($Inclusive_ID)' value='X'></td>
                </tr>";
                $num++;
            }
        }else{
            echo "<tr>
                    <td colspan='4'>No data Found</td>
            </tr>";
        }
    }

    if(isset($_POST['removeitem'])){
        $Inclusive_ID =$_POST['Inclusive_ID'];
        $rm = mysqli_query($conn, "DELETE  FROM tbl_inclusive_service WHERE Inclusive_ID='$Inclusive_ID'") or die(mysqli_error($conn));
        if($rm){
            echo 'Removed';
        }else{
            echo 'No';
        }
    }