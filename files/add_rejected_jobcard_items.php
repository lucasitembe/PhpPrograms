<?php 

    $jobcard = array();
    if (!empty($jobcard_ID)) {
        $jobcard = Get_Jobcard($jobcard_ID);
    }
    

    $Sub_Department_ID = array();
    if (!empty($Sub_Department_ID)) {
        $Sub_Department_ID = Get_Sub_Department_ID($Sub_Department_ID);
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
                                    <input type='text' id='Search_Value' class="form-control" name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='Search Spare/Item Name .....'>
                                </td>
                            </tr>
                            <tr>
                            <td>
                                <fieldset style='overflow-y: scroll; height: 450px;' >
                                    <table class="table" id='Items_Fieldset' style='border-style: none;'>
                                        <?php
                                            $nam=1;
                                            $result = mysqli_query($conn,"SELECT * FROM tbl_items limit 100") or die(mysqli_error($conn));
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
if(isset($_POST['items'])){
          $items = $_POST['items'];
          $num=0;
          $result = mysqli_query($conn,"SELECT * FROM tbl_items WHERE Product_Name LIKE '%$items%'" ) or die(mysqli_error($conn));
          if(mysqli_num_rows($result)>0){
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
          }else{
                    echo "<tr><td colspan='4'><b>No result found</b></td></tr>";
          }
 } 

 
if(isset($_POST["add_maintanance"])){ ?>
          <table width=100% style='border-style: none;'>
                <tr>
                    <td width=40%>
                        <table width=100% style='border-style: none;'>
                            <tr>
                                <td>
                                    <input type='text' id='Search_Value_mantainance' class="form-control" name='Search_Value_mantainance' autocomplete='off' onkeyup='search_maintanance_item(this.value)' placeholder='Search for Spare Name .....'>
                                </td>
                            </tr>
                            <tr>
                            <td>
                                <fieldset style='overflow-y: scroll; height: 450px;' >
                                    <table class="table" id='Items_Fieldset' style='border-style: none;' >
                                        <?php
                                            $nam=1;
                                            $result = mysqli_query($conn,"SELECT * FROM tbl_items limit 100") or die(mysqli_error($conn));
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
    $result = mysqli_query($conn,"SELECT * FROM tbl_items WHERE Product_Name LIKE '%$items%'" ) or die(mysqli_error($conn));
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
    $jobcard_ID = $_POST['jobcard_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Sub_Department_ID = $_POST['Sub_Department_ID'];
    
    $anasthesia_record = "SELECT jobcard_ID FROM tbl_jobcards WHERE  jobcard_ID = '$jobcard_ID' AND status = 'Rejected'";
    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
    if(mysqli_num_rows($anasthesia_record_result)>0){
        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['jobcard_ID'];
    }
    $maintance_record = mysqli_query($conn, "SELECT Item_ID FROM tbl_jobcard_orders WHERE Jobcard_ID = '$jobcard_ID' AND Item_ID ='$Item_ID' ") or die(mysqli_error($conn));
    if((mysqli_num_rows($maintance_record))>0){
                $Item_ID = mysqli_fetch_assoc($maintance_record);
                echo "Spare/Device already added ";
    }else{
        $insertmaintanance = mysqli_query($conn, "INSERT INTO tbl_jobcard_orders (Item_ID, Jobcard_ID, Employee_ID) VALUES('$Item_ID','$jobcard_ID', '$Employee_ID') ") or die(mysqli_error($conn));

        if(!$insertmaintanance){
                echo "Spare/Device didn't save ";
        }else{
                echo "Spare/Device drug saved";
        }
    }

}

if(isset($_POST['select_maintanance'])){
    $Jobcard_ID = $_POST['Jobcard_ID'];

    $select_maintanance = mysqli_query($conn, "SELECT uk.Sub_Department_ID, ap.Employee_ID, ap.Jobcard_ID, Product_Name, ap.Quantity, ap.Price, ap.Item_ID, i.Unit_Of_Measure, i.Product_Code, ap.created_at FROM  tbl_jobcards uk,tbl_jobcard_orders ap, tbl_items i WHERE i.Item_ID=ap.Item_ID AND ap.Jobcard_ID='$Jobcard_ID' AND uk.jobcard_ID='$Jobcard_ID' ORDER BY Jobcard_ID DESC  ") or die(mysqli_error($conn));
  
    $num=0;
    $no = mysqli_num_rows($select_maintanance);
    if($no > 0){
        while($row = mysqli_fetch_assoc($select_maintanance)){
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $Item_Balance = $row['Item_Balance'];
            $Item_ID = $row['Item_ID'];
            $Jobcard_Order_ID = $row['Jobcard_Order_ID'];
            $Employee_ID  = $row['Employee_ID'];
            $Jobcard_ID = $row['Jobcard_ID'];
            $Product_Name = $row['Product_Name'];
            $Quantity = $row['Quantity'];
            $created_at = $row['created_at'];
            $Price = $row['Price'];
            $Unit_Of_Measure = $row['Unit_Of_Measure'];
            $Product_Code = $row['Product_Code'];
            $num++;

            $subtotal = $Quantity * $Price;
            $Grand_total = $Grand_total + $subtotal;

            $Item_Balance = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_Balance FROM tbl_items_balance WHERE Sub_Department_ID='$Sub_Department_ID' AND Item_ID='$Item_ID'"))['Item_Balance'];


            echo "<tr><td>$num</td><td>$Product_Name</td>
            <td><input type='text' readonly='readonly' value='$Unit_Of_Measure'></td>
            <td><input type='text' readonly='readonly' value='$Product_Code'></td>
            <td><input type='text' readonly='readonly' value='$Item_Balance'></td>
            <td><input type='text' id='Quantity$Item_ID' placeholder='Enter Quantity' value='$Quantity' onkeyup='update_maintanance_Quantity($Jobcard_ID, $Item_ID)'></td>
            <td><input type='text' id='Price$Item_ID' value='$Price' placeholder='Enter The Price' onkeyup='update_maintanance_time($Jobcard_ID,$Item_ID)'></td>
            <td><input type='text' style='text-align:right; 'readonly='readonly' id='total_$Jobcard_Order_ID' value='".number_format($subtotal)."' placeholder='Total Price'></td>
            <td><button class='btn btn-danger' type='button' name='removemaintanance' onclick='remove_maintanance($Jobcard_ID, $Item_ID, $Employee_ID)'>X</button></td></tr>";
        }
        echo "<tr>
                    <td colspan='6'><p style='font-size: 17; font-weight: bold;'>GRAND TOTAL</td>
                    <td colspan='2'><input type='text' name='total' readonly='readonly' style='text-align:right; font-weight: bold; font-size: 18; border-style: none;' value='Tshs. ".number_format($Grand_total)."/=' placeholder='Total Price'></td>
                                    </tr>";
    }
}

if(isset($_POST['removemaintanance'])){
    $Employee_ID = $_POST['Employee_ID'];
    $Item_ID = $_POST['Item_ID'];
    $Jobcard_ID = $_POST['Jobcard_ID'];

    $delete_pre_med = mysqli_query($conn, "DELETE FROM tbl_jobcard_orders WHERE  Employee_ID = '$Employee_ID' AND  Jobcard_ID = '$Jobcard_ID' AND Item_ID = '$Item_ID'") or die(mysqli_error($conn));
    if(!$delete_pre_med){
            echo "Not deleted. You have no access to delete this medication";
    }else{
            echo "Deleted successful";
    }
}

if(isset($_POST['updatetimemaintanance'])){
    $Price = $_POST['Price'];
    $Item_ID = $_POST['Item_ID'];
    $Jobcard_ID = $_POST['Jobcard_ID'];

    $update_created_at = mysqli_query($conn,"UPDATE tbl_jobcard_orders SET  Price = '$Price' WHERE Jobcard_ID = '$Jobcard_ID' AND Item_ID = '$Item_ID'") or die(mysqli_error($conn));

    if(!$update_created_at){
            echo "Not updated";
    }else{
            echo "Updated successful";
    }
}

if(isset($_POST['updateQuantitymaintanance'])){
    $Quantity = $_POST['Quantity'];
    $Item_ID = $_POST['Item_ID'];
    $Jobcard_ID = $_POST['Jobcard_ID'];

    $update_Quantity = mysqli_query($conn, "UPDATE tbl_jobcard_orders SET  Quantity='$Quantity' WHERE Jobcard_ID='$Jobcard_ID' AND Item_ID='$Item_ID'") or die(mysqli_error($conn));

    if(!$update_Quantity){
            echo "Not updated";
    }else{
            echo "Updated successful";
    }
}




