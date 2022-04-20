<?php 

$Requisition = array();
    if (!empty($Requisition_ID)) {
        $Requisition = Get_Requisition($Requisition_ID);
    }

    if(isset($_POST['items'])){
        $items = $_POST['items'];
        $filter = "AND Product_Name LIKE '%$items%'";
    }else{
        $filter = '';
    }
          session_start();
          include("./includes/connection.php");
          if(isset($_POST["add_item"])){ ?>
          <table width=100% style='border-style: none;'>
                <tr>
                    <td width=40%>
                        <table width=100% style='border-style: none;'>
                            <tr>
                                <td>
                                    <input type='text' id='Search_Value' class="form-control" name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='Search drug Name .....'>
                                </td>
                            </tr>
                            <tr>
                            <td>
                                <fieldset style='overflow-y: scroll; height: 450px;' >
                                    <table class="table" id='Items_Fieldset' style='border-style: none;'>
                                        <?php
                                            $nam=1;
                                            $result = mysqli_query($conn,"SELECT * FROM tbl_items WHERE Can_Be_Stocked ='yes' $filter limit 100") or die(mysqli_error($conn));
                                            while ($row = mysqli_fetch_array($result)) {
                                                $Item_ID = $row['Item_ID'];
                                                $Product_Name = $row['Product_Name'];
                                                $Unit_Of_Measure = $row['Unit_Of_Measure'];
                                                $Product_Code = $row['Product_Code'];
                                                $num++;
                                                echo "<tr  class='rows_list'><td>$num</td>";
                                                echo "<td onclick='add_maintanance($Item_ID)'>$Product_Name</td>";
                                                echo "<td onclick='add_maintanance($Item_ID)'>$Product_Code</td>";
                                                echo "<td onclick='add_maintanance($Item_ID)'>$Unit_Of_Measure</td></tr>";
                                                    
                                            }
                                        ?>
                                    </table>
                                </fieldset>
                            </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
      
<?php }


 
if(isset($_POST["add_maintanance"])){ ?>
          <table width=100% style='border-style: none;'>
                <tr>
                    <td width=40%>
                        <table width=100% style='border-style: none;'>
                            <tr>
                                <td>
                                    <input type='text' id='Search_Value_mantainance' class="form-control" name='Search_Value_mantainance' autocomplete='off' onkeyup='search_maintanance_item(this.value)' placeholder='Search Spare / Item Name .....'>
                                </td>
                            </tr>
                            <tr>
                            <td>
                                <fieldset style='overflow-y: scroll; height: 450px;' >
                                    <table class="table" id='Items_Fieldset' style='border-style: none;' >
                                        <?php
                                            $nam=1;
                                            $result = mysqli_query($conn,"SELECT * FROM tbl_items WHERE Can_Be_Stocked ='yes'  $filter limit 100") or die(mysqli_error($conn));
                                            while ($row = mysqli_fetch_array($result)) {
                                                $Item_ID = $row['Item_ID'];
                                                $Product_Name = $row['Product_Name'];
                                                $Unit_Of_Measure = $row['Unit_Of_Measure'];
                                                $Product_Code = $row['Product_Code'];
                                                $num++;
                                                echo "<tr  class='rows_list'><td>$num</td>";
                                                echo "<td onclick='add_maintanance($Item_ID)'>$Product_Name</td>";
                                                echo "<td onclick='add_maintanance($Item_ID)'>$Product_Code</td>";
                                                echo "<td onclick='add_maintanance($Item_ID)'>$Unit_Of_Measure</td></tr>";
                                            }
                                        ?>
                                    </table>
                                </fieldset>
                            </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
                    
      
<?php }
if(isset($_POST['search_maintanance_item'])){
    $items = $_POST['items'];
    $num=0;
    $result = mysqli_query($conn,"SELECT * FROM tbl_items WHERE Product_Name LIKE '%$items%' AND  Can_Be_Stocked ='yes'" ) or die(mysqli_error($conn));
    if(mysqli_num_rows($result)>0){
        while ($row = mysqli_fetch_array($result)) {
                                                $Item_ID = $row['Item_ID'];
                                                $Product_Name = $row['Product_Name'];
                                                $Unit_Of_Measure = $row['Unit_Of_Measure'];
                                                $Product_Code = $row['Product_Code'];
                                                $num++;
                                                echo "<tr class='rows_list'><td>$num</td>";
                                                echo "<td onclick='add_maintanance($Item_ID)'>$Product_Name</td>";
                                                echo "<td onclick='add_maintanance($Item_ID)'>$Product_Code</td>";
                                                echo "<td onclick='add_maintanance($Item_ID)'>$Unit_Of_Measure</td></tr>";
        }
    }else{
            echo "<tr><td colspan='4'><b>No result found</b></td></tr>";
    }
 } 
 if(isset($_POST['insert_maintanance'])){
    $Item_ID = $_POST['Item_ID'];
    $Requisition_ID = $_POST['Requisition_ID'];
    $Sub_Department_ID = $_POST['Sub_Department_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    
    if(!empty($Item_ID) && !empty($Requisition_ID) && !empty($Sub_Department_ID)){
        $Item_Balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Item_Balance FROM tbl_items_balance WHERE Sub_Department_ID = '$Sub_Department_ID' AND Item_ID = '$Item_ID'"))['Item_Balance'];
            if($Item_Balance > 0){
                $maintance_record = mysqli_query($conn, "SELECT Item_ID FROM tbl_mrv_items WHERE Requisition_ID = '$Requisition_ID' AND DATE(created_at)=CURDATE() AND Item_ID ='$Item_ID' ") or die(mysqli_error($conn));
                if((mysqli_num_rows($maintance_record))>0){
                            $Item_ID = mysqli_fetch_assoc($maintance_record);
                            echo "Spare/Device already added ";
                }else{
                    $insertmaintanance = mysqli_query($conn, "INSERT INTO tbl_mrv_items (Item_ID, Requisition_ID, Employee_ID, Sub_Department_ID, created_at) VALUES('$Item_ID','$Requisition_ID', '$Employee_ID', '$Sub_Department_ID', NOW()) ") or die(mysqli_error($conn));
            
                    if(!$insertmaintanance){
                            echo "Spare/Device didn't save ";
                    }else{
                            // echo "Spare/Device drug saved";
                    }
                }
            }else{
                $Sub_Department_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Sub_Department_ID'"))['Sub_Department_Name'];
                echo "The Selected Spare Has No Balance in ".$Sub_Department_Name;
            }
    }else{
        echo "Please Select Store You want to Consume from";
    }
}

if(isset($_POST['select_maintanance'])){
    $Requisition_ID = $_POST['Requisition_ID'];

    $select_maintanance = mysqli_query($conn, "SELECT Employee_ID, list_ID, Product_Name, Quantity, i.Unit_Of_Measure, i.Product_Code, created_at FROM  tbl_mrv_items ap, tbl_items i WHERE i.Item_ID=ap.Item_ID AND  Requisition_ID='$Requisition_ID' ORDER BY list_ID DESC  ") or die(mysqli_error($conn));
    $num=0;
    if(mysqli_num_rows($select_maintanance)>0){
        while($row = mysqli_fetch_assoc($select_maintanance)){
            $Employee_ID  = $row['Employee_ID'];
            $list_ID = $row['list_ID'];
            $Product_Name = $row['Product_Name'];
            $Quantity = $row['Quantity'];
            $created_at = $row['created_at'];
            $Unit_Of_Measure = $row['Unit_Of_Measure'];
            $Product_Code = $row['Product_Code'];
            $num++;
            echo "<tr><td>$num</td><td>$Product_Name</td>
            <td><input type='text' readonly='readonly' value='$Unit_Of_Measure'></td>
            <td><input type='text' readonly='readonly' value='$Product_Code'></td>
            <td><input type='text' id='quantity$list_ID' placeholder='Enter Quantity' value='$Quantity' onkeyup='update_maintanance_Quantity($list_ID)'></td>
            <td><input type='text' id='time_$list_ID' value='$created_at' placeholder='Enter time' onkeyup='update_maintanance_time($list_ID)'></td>
            <td><button class='btn btn-danger' type='button' name='removemaintanance' onclick='remove_maintanance($list_ID, $Employee_ID)'>X</button></td></tr>";
        }
    }
}

if(isset($_POST['removemaintanance'])){
    $Employee_ID = $_POST['Employee_ID'];
    $list_ID = $_POST['list_ID'];

    $delete_pre_med = mysqli_query($conn, "DELETE FROM tbl_mrv_items WHERE  Employee_ID='$Employee_ID' AND list_ID='$list_ID'") or die(mysqli_error($conn));
    if(!$delete_pre_med){
            echo "Not deleted. You have no access to delete this medication";
    }else{
            echo "Deleted successful";
    }
}

if(isset($_POST['updatetimemaintanance'])){
    $time = $_POST['time'];
    $list_ID = $_POST['list_ID'];
    $update_time = mysqli_query($conn, "UPDATE tbl_mrv_items SET  created_at='$time' WHERE list_ID='$list_ID'") or die(mysqli_error($conn));

    if(!$update_time){
            echo "Not updated";
    }else{
            echo "Updated successful";
    }
}

if(isset($_POST['updateQuantitymaintanance'])){
    $Quantity = $_POST['Quantity'];
    $list_ID = $_POST['list_ID'];
    if(!empty($list_ID)){

        $Select_SubDepartment = mysqli_query($conn, "SELECT Sub_Department_ID, Item_ID FROM tbl_mrv_items  WHERE list_ID='$list_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($Select_SubDepartment)>0){
            while($ID = mysqli_fetch_assoc($Select_SubDepartment)){
                $Sub_Department_ID = $ID['Sub_Department_ID'];
                $Item_ID = $ID['Item_ID'];

                $Item_Balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Item_Balance FROM tbl_items_balance WHERE Sub_Department_ID = '$Sub_Department_ID' AND Item_ID = '$Item_ID'"))['Item_Balance'];
                if($Quantity > $Item_Balance){
                    $Sub_Department_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Sub_Department_ID'"))['Sub_Department_Name'];
                    echo "The Selected Spare Has No Balance in ".$Sub_Department_Name;
                }else{
                    $update_Quantity = mysqli_query($conn, "UPDATE tbl_mrv_items SET  Quantity='$Quantity' WHERE list_ID='$list_ID'") or die(mysqli_error($conn));

                    if(!$update_Quantity){
                            echo "Not updated";
                    }else{
                            // echo "Updated successful";
                    }
                }
            }
        }
    }
}
if(isset($_POST['Check_Details'])){
    $result = array();
    $Requisition_ID = $_POST['Requisition_ID'];
    if(!empty($Requisition_ID)){
        $Select_SubDepartment = mysqli_query($conn, "SELECT Quantity, Item_ID FROM tbl_mrv_items  WHERE Requisition_ID='$Requisition_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($Select_SubDepartment)>0){
            while($details = mysqli_fetch_assoc($Select_SubDepartment)){
                array_push($result,$details);
            }
            echo json_encode($result);
        }

    }
}



