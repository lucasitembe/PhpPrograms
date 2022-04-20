<?php 

if (isset($_POST['consultation_ID'])) {
    $consultation_ID = $_POST['consultation_ID'];
} else {
    $consultation_ID = 0;
}
if (isset($_POST['Registration_ID'])) {
    $Registration_ID = $_POST['Registration_ID'];
} else {
    $Registration_ID = 0;
}
if (isset($_POST['Payment_Item_Cache_List_ID'])){
    $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
} else {
    $Payment_Item_Cache_List_ID = 0;
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
                                    <input type='text' id='Search_Value_mantainance' class="form-control" name='Search_Value_mantainance' autocomplete='off' onkeyup='search_maintanance_item(this.value)' placeholder='Search drug Name .....'>
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
    $Registration_ID= $_POST['Registration_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
    $consultation_ID = $_POST['consultation_ID'];
    
    $anasthesia_record = "SELECT Bronchoscopy_Notes_ID FROM tbl_Bronchoscopy_notes WHERE Registration_ID = '$Registration_ID' AND status='Pending'";
    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
    if(mysqli_num_rows($anasthesia_record_result)>0){
        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
    }else{
        $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
        $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_Bronchoscopy_notes(Registration_ID, Payment_Item_Cache_List_ID, consultation_ID, Surgery_Date_Time, Employee_ID) VALUES('$Registration_ID', '$Payment_Item_Cache_List_ID', '$consultation_ID', NOW(), '$Employee_ID')") or die(mysqli_error($conn));
        $Bronchoscopy_Notes_ID=mysqli_insert_id($conn);
        
    }
    $maintance_record = mysqli_query($conn, "SELECT Item_ID FROM tbl_Bronchoscopy_Premedication WHERE Registration_ID = '$Registration_ID' AND DATE(created_at)=CURDATE() AND Item_ID ='$Item_ID' ") or die(mysqli_error($conn));
    if((mysqli_num_rows($maintance_record))>0){
                $Item_ID = mysqli_fetch_assoc($maintance_record);
                echo "Premedication already added ";
    }else{
        $insertmaintanance = mysqli_query($conn, "INSERT INTO tbl_Bronchoscopy_Premedication (Item_ID,Bronchoscopy_Notes_ID, Registration_ID, Employee_ID, created_at) VALUES('$Item_ID','$Bronchoscopy_Notes_ID', '$Registration_ID', '$Employee_ID', NOW() ") or die(mysqli_error($conn));

        if(!$insertmaintanance){
                echo "Premedication drug didn't save ";
        }else{
                echo "Premedication drug saved";
        }
    }

}


if(isset($_POST['select_maintanance'])){
    $Bronchoscopy_Notes_ID = $_POST['Bronchoscopy_Notes_ID'];

    $select_maintanance = mysqli_query($conn, "SELECT ap.Employee_ID, ap.Bronchoscopy_Notes_ID, i.Product_Name, ap.Amount, ap.Route, ap.Item_ID, ap.created_at FROM  tbl_Bronchoscopy_notes uk,tbl_Bronchoscopy_Premedication ap, tbl_items i WHERE i.Item_ID=ap.Item_ID AND ap.Bronchoscopy_Notes_ID='$Bronchoscopy_Notes_ID' AND ap.Registration_ID='$Registration_ID' ORDER BY Premedication_ID DESC  ") or die(mysqli_error($conn));

    
    $num=0;
    $no = mysqli_num_rows($select_maintanance);
    if($no > 0){
        while($row = mysqli_fetch_assoc($select_maintanance)){
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $Item_ID = $row['Item_ID'];
            $Premedication_ID = $row['Premedication_ID'];
            $Employee_ID  = $row['Employee_ID'];
            $Bronchoscopy_Notes_ID = $row['Bronchoscopy_Notes_ID'];
            $Product_Name = $row['Product_Name'];
            $Amount = $row['Amount'];
            $created_at = $row['created_at'];
            $Route = $row['Route'];
            $num++;


            echo "<tr><td>$num</td><td>$Product_Name</td>
            <td><input type='text' id='Amount$Item_ID' placeholder='Enter Amount' value='$Amount' onkeyup='update_maintanance_Amount($Bronchoscopy_Notes_ID, $Item_ID)'></td>
            <td><input type='text' id='Route$Item_ID' value='$Route' placeholder='Enter The Route' onkeyup='update_maintanance_time($Bronchoscopy_Notes_ID,$Item_ID)'></td>
            <td><button class='btn btn-danger' type='button' name='removemaintanance' onclick='remove_maintanance($Bronchoscopy_Notes_ID, $Item_ID, $Employee_ID)'>X</button></td></tr>";
                             
    }
}else{
    echo "<tr>
    <td colspan='5'><b>No Pre-Medication Prescribed for this Service</b></td></tr>";
}

if(isset($_POST['removemaintanance'])){
    $Employee_ID = $_POST['Employee_ID'];
    $Item_ID = $_POST['Item_ID'];
    $Bronchoscopy_Notes_ID = $_POST['Bronchoscopy_Notes_ID'];

    $delete_pre_med = mysqli_query($conn, "DELETE FROM tbl_Bronchoscopy_Premedication WHERE  Employee_ID = '$Employee_ID' AND  	Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID' AND Item_ID = '$Item_ID'") or die(mysqli_error($conn));
    if(!$delete_pre_med){
            echo "Not deleted. You have no access to delete this medication";
    }else{
            echo "Deleted successfully";
    }
}

if(isset($_POST['updatetimemaintanance'])){
    $Route = $_POST['Route'];
    $Item_ID = $_POST['Item_ID'];
    $Bronchoscopy_Notes_ID = $_POST['Bronchoscopy_Notes_ID'];

    $update_created_at = mysqli_query($conn,"UPDATE tbl_Bronchoscopy_Premedication SET  Route='$Route' WHERE Bronchoscopy_Notes_ID='$Bronchoscopy_Notes_ID' AND Item_ID='$Item_ID'") or die(mysqli_error($conn));

    if(!$update_created_at){
            echo "Not updated";
    }else{
            echo "Updated successful";
    }
}

if(isset($_POST['updateAmountmaintanance'])){
    $Amount = $_POST['Amount'];
    $Item_ID = $_POST['Item_ID'];
    $Bronchoscopy_Notes_ID = $_POST['Bronchoscopy_Notes_ID'];

    $update_Amount = mysqli_query($conn, "UPDATE tbl_Bronchoscopy_Premedication SET  Amount='$Amount' WHERE Bronchoscopy_Notes_ID='$Bronchoscopy_Notes_ID' AND Item_ID='$Item_ID'") or die(mysqli_error($conn));

    if(!$update_Amount){
            echo "Not updated";
    }else{
            echo "Updated successfully";
    }
}




