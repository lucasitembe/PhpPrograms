<?php 
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
                                    <table class="table" id='Items_Fieldset' style='border-style: none; '>
                                        <?php
                                            $nam=1;
                                            $result = mysqli_query($conn,"SELECT * FROM tbl_items limit 100") or die(mysqli_error($conn));
                                            while ($row = mysqli_fetch_array($result)) {
                                                $Item_ID = $row['Item_ID'];
                                                $Product_Name = $row['Product_Name'];
                                                $num++;
                                                echo "<tr><td>$num</td>";
                                                echo "<td class='rows_list' onclick='add_premedication($Item_ID)'>$Product_Name</td></tr>";
                                                    
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
                              $num++;
                              echo "<tr><td>$num</td>";
                              echo "<td class='rows_list' onclick='add_premedication($Item_ID)'>$Product_Name</td></tr>";
                    }
          }else{
                    echo "<tr><td colspan='2'>No result found</td></tr>";
          }
 } 

 if(isset($_POST['insert_premedication'])){
          $Item_ID = $_POST['Item_ID'];
          $Registration_ID= $_POST['Registration_ID'];
          $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
          
                    $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
                    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
                    if(mysqli_num_rows($anasthesia_record_result)>0){
                        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
                    }else{
                        $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                        $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$Employee_ID')") or die(mysqli_error($conn));
                        $anasthesia_record_id=mysqli_insert_id($conn);
                        
                    }
                    $premedicatio_record = mysqli_query($conn, "SELECT Item_ID FROM tbl_anaesthesia_premedicaton WHERE Registration_ID = '$Registration_ID' AND DATE(created_at)=CURDATE() AND Item_ID ='$Item_ID' ") or die(mysqli_error($conn));
                    if((mysqli_num_rows($premedicatio_record))>0){
                              $Item_ID = mysqli_fetch_assoc($premedicatio_record);
                              echo "Medication already added ";
                    }else{
                              $insertpremedication = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_premedicaton (Item_ID,anasthesia_record_id, Registration_ID, Employee_ID) VALUES('$Item_ID','$anasthesia_record_id', '$Registration_ID', '$Employee_ID' ) ") or die(mysqli_error($conn));
                    
                              if(!$insertpremedication){
                                        echo "Premedication didn't save ";
                              }else{
                                        echo "Premedication saved";
                              }
                    }
    
}
if(isset($_POST['select_premedication'])){
          $Registration_ID = $_POST['Registration_ID'];
            $anasthesia_record_id = $_POST['anasthesia_record_id'];
          $select_premedication = mysqli_query($conn, "SELECT Employee_ID, Premedication_ID, Product_Name, Dose, time FROM  tbl_anaesthesia_premedicaton ap, tbl_items i WHERE i.Item_ID=ap.Item_ID AND anasthesia_record_id='$anasthesia_record_id' AND  Registration_ID='$Registration_ID' ORDER BY anasthesia_record_id DESC  ") or die(mysqli_error($conn));
          $num=0;
          if(mysqli_num_rows($select_premedication)>0){
                    while($row = mysqli_fetch_assoc($select_premedication)){
                              $Employee_ID  = $row['Employee_ID'];
                              $Premedication_ID = $row['Premedication_ID'];
                              $Product_Name = $row['Product_Name'];
                              $Dose = $row['Dose'];
                              $time = $row['time'];
                              $num++;
                              echo "<tr><td>$num</td>";
                              echo "<td>$Product_Name</td><td><input type='text' id='dose_$Premedication_ID' placeholder='Enter Dose' value='$Dose' onkeyup='update_premedication_dose($Premedication_ID)'>
                                    </td><td><input type='text' id='time_$Premedication_ID' value='$time' placeholder='Enter time' onkeyup='update_premedication_time($Premedication_ID)'></td>
                                    <td><button class='btn btn-danger' type='button' name='removepremedication' onclick='remove_premedication($Premedication_ID, $Employee_ID)'>X</button>
                                    </td></tr>";

                    }
          }
}

if(isset($_POST['removepremedication'])){
          $Employee_ID = $_POST['Employee_ID'];
          $Premedication_ID = $_POST['Premedication_ID'];

          $delete_pre_med = mysqli_query($conn, "DELETE FROM tbl_anaesthesia_premedicaton WHERE  Employee_ID='$Employee_ID' AND Premedication_ID='$Premedication_ID'") or die(mysqli_error($conn));
          if(!$delete_pre_med){
                    echo "Not deleted. You have no access to delete this medication";
          }else{
                    echo "Deleted successful";
          }
}

if(isset($_POST['updatetimepremedication'])){
          $time = $_POST['time'];
          $Premedication_ID = $_POST['Premedication_ID'];

          $update_time = mysqli_query($conn, "UPDATE tbl_anaesthesia_premedicaton SET  time='$time' WHERE Premedication_ID='$Premedication_ID'") or die(mysqli_error($conn));

          if(!$update_time){
                    echo "Not updated";
          }else{
                    echo "Updated successful";
          }
}

if(isset($_POST['updatedosepremedication'])){
          $Dose = $_POST['dose'];
          $Premedication_ID = $_POST['Premedication_ID'];

          $update_Dose = mysqli_query($conn, "UPDATE tbl_anaesthesia_premedicaton SET  Dose='$Dose' WHERE Premedication_ID='$Premedication_ID'") or die(mysqli_error($conn));

          if(!$update_Dose){
                    echo "Not updated";
          }else{
                    echo "Updated successful";
          }
}
?>

<?php
if(isset($_POST["add_induction"])){ ?>
          <table width=100% style='border-style: none;'>
                    <tr>
                        <td width=40%>
                            <table width=100% style='border-style: none;'>
                                <tr>
                                    <td>
                                        <input type='text' id='Search_Value' class="form-control" name='Search_Value' autocomplete='off' onkeyup='search_induction_item(this.value)' placeholder='Search drug Name .....'>
                                    </td>
                                </tr>
                                <tr>
                                <td>
                                    <fieldset style='overflow-y: scroll; height: 450px;' >
                                            <table class="table" id='Items_Fieldset' style='border-style: none; '>
                                                <?php
                                                    $nam=1;
                                                    $result = mysqli_query($conn,"SELECT * FROM tbl_items limit 100") or die(mysqli_error($conn));
                                                    while ($row = mysqli_fetch_array($result)) {
                                                                $Item_ID = $row['Item_ID'];
                                                                $Product_Name = $row['Product_Name'];
                                                                $num++;
                                                                echo "<tr><td>$num</td>";
                                                                echo "<td class='rows_list' onclick='add_induction($Item_ID)'>$Product_Name</td></tr>";
                                                            
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
if(isset($_POST['search_item'])){
          $items = $_POST['items'];
          $num=0;
          $result = mysqli_query($conn,"SELECT * FROM tbl_items WHERE Product_Name LIKE '%$items%'" ) or die(mysqli_error($conn));
          if(mysqli_num_rows($result)>0){
                    while ($row = mysqli_fetch_array($result)) {
                              $Item_ID = $row['Item_ID'];
                              $Product_Name = $row['Product_Name'];
                              $num++;
                              echo "<tr><td>$num</td>";
                              echo "<td class='rows_list' onclick='add_induction($Item_ID)'>$Product_Name</td></tr>";
                    }
          }else{
                    echo "<tr><td colspan='2'>No result found</td></tr>";
          }
 } 

 if(isset($_POST['insert_induction'])){
          $Item_ID = $_POST['Item_ID'];
          $Registration_ID= $_POST['Registration_ID'];
          $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
          
                    $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
                    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
                    if(mysqli_num_rows($anasthesia_record_result)>0){
                        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
                    }else{
                        $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                        $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$Employee_ID')") or die(mysqli_error($conn));
                        $anasthesia_record_id=mysqli_insert_id($conn);
                        
                    }
                    $premedicatio_record = mysqli_query($conn, "SELECT Item_ID FROM tbl_anaesthesia_induction WHERE Registration_ID = '$Registration_ID' AND DATE(created_at)=CURDATE() AND Item_ID ='$Item_ID' ") or die(mysqli_error($conn));
                    if((mysqli_num_rows($premedicatio_record))>0){
                              $Item_ID = mysqli_fetch_assoc($premedicatio_record);
                              echo "Medication already added ";
                    }else{
                              $insertinduction = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_induction (Item_ID,anasthesia_record_id, Registration_ID, Employee_ID) VALUES('$Item_ID','$anasthesia_record_id', '$Registration_ID', '$Employee_ID' ) ") or die(mysqli_error($conn));
                    
                              if(!$insertinduction){
                                        echo "Induction didn't save ";
                              }else{
                                        echo "Induction saved";
                              }
                    }
    
}
    if(isset($_POST['select_induction'])){
          $Registration_ID = $_POST['Registration_ID'];
          $anasthesia_record_id = $_POST['anasthesia_record_id'];
          $select_induction = mysqli_query($conn, "SELECT Employee_ID, Induction_ID, Product_Name, Dose, time FROM  tbl_anaesthesia_induction ap, tbl_items i WHERE i.Item_ID=ap.Item_ID AND anasthesia_record_id='$anasthesia_record_id' AND   Registration_ID='$Registration_ID' ORDER BY anasthesia_record_id DESC  ") or die(mysqli_error($conn));
          $num=0;
            if(mysqli_num_rows($select_induction)>0){
                while($row = mysqli_fetch_assoc($select_induction)){
                            $Employee_ID  = $row['Employee_ID'];
                            $Induction_ID = $row['Induction_ID'];
                            $Product_Name = $row['Product_Name'];
                            $Dose = $row['Dose'];
                            $time = $row['time'];
                            $num++;
                            echo "<tr><td>$num</td>";
                            echo "<td>$Product_Name</td>
                                <td><input type='text' id='Induction_dose_$Induction_ID' placeholder='Enter Dose' value='$Dose' onkeyup='update_induction_dose($Induction_ID)'></td>
                                <td><input type='text' id='Induction_time_$Induction_ID' value='$time' placeholder='Enter time' onkeyup='update_induction_time($Induction_ID)'></td>
                                <td><button class='btn btn-danger' type='button' name='removeinduction' onclick='remove_induction($Induction_ID, $Employee_ID)'>X</button></td>
                            </tr>";

                }
            }
    }

    if(isset($_POST['removeinduction'])){
        $Employee_ID = $_POST['Employee_ID'];
        $Induction_ID = $_POST['Induction_ID'];

        $delete_pre_med = mysqli_query($conn, "DELETE FROM tbl_anaesthesia_induction WHERE  Employee_ID='$Employee_ID' AND Induction_ID='$Induction_ID'") or die(mysqli_error($conn));
        if(!$delete_pre_med){
                echo "Not deleted. You have no access to delete this medication";
        }else{
                echo "Deleted successful";
        }
    }

if(isset($_POST['updatetimeinduction'])){
    $time = $_POST['time'];
    $Induction_ID = $_POST['Induction_ID'];

    $update_time = mysqli_query($conn, "UPDATE tbl_anaesthesia_induction SET  time='$time' WHERE Induction_ID='$Induction_ID'") or die(mysqli_error($conn));

    if(!$update_time){
            echo "Not updated";
    }else{
            echo "Updated successful";
    }
}

if(isset($_POST['updatedoseinduction'])){
    $Dose = $_POST['dose'];
    $Induction_ID = $_POST['Induction_ID'];

    $update_Dose = mysqli_query($conn, "UPDATE tbl_anaesthesia_induction SET  Dose='$Dose' WHERE Induction_ID='$Induction_ID'") or die(mysqli_error($conn));

    if(!$update_Dose){
            echo "Not updated";
    }else{
            echo "Updated successful";
    }
}
?>



<?php
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
                                    <table class="table" id='Items_Fieldset' style='border-style: none; '>
                                        <?php
                                            $nam=1;
                                            $result = mysqli_query($conn,"SELECT * FROM tbl_items limit 100") or die(mysqli_error($conn));
                                            while ($row = mysqli_fetch_array($result)) {
                                                $Item_ID = $row['Item_ID'];
                                                $Product_Name = $row['Product_Name'];
                                                $num++;
                                                echo "<tr><td>$num</td>";
                                                echo "<td class='rows_list' onclick='add_maintanance($Item_ID)'>$Product_Name</td></tr>";
                                                    
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
            $num++;
            echo "<tr><td>$num</td>";
            echo "<td class='rows_list' onclick='add_maintanance($Item_ID)'>$Product_Name</td></tr>";
        }
    }else{
            echo "<tr><td colspan='2'>No result found</td></tr>";
    }
 } 

 if(isset($_POST['insert_maintanance'])){
        $Item_ID = $_POST['Item_ID'];
        $Registration_ID= $_POST['Registration_ID'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        
        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
        $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
        if(mysqli_num_rows($anasthesia_record_result)>0){
            $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
        }else{
            $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
            $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$Employee_ID')") or die(mysqli_error($conn));
            $anasthesia_record_id=mysqli_insert_id($conn);
            
        }
        $maintance_record = mysqli_query($conn, "SELECT Item_ID FROM tbl_anaesthesia_maintanance WHERE Registration_ID = '$Registration_ID' AND DATE(created_at)=CURDATE() AND Item_ID ='$Item_ID' ") or die(mysqli_error($conn));
        if((mysqli_num_rows($maintance_record))>0){
                    $Item_ID = mysqli_fetch_assoc($maintance_record);
                    echo "Maintanance already added ";
        }else{
            $insertmaintanance = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_maintanance (Item_ID,anasthesia_record_id, Registration_ID, Employee_ID) VALUES('$Item_ID','$anasthesia_record_id', '$Registration_ID', '$Employee_ID' ) ") or die(mysqli_error($conn));

            if(!$insertmaintanance){
                    echo "Maintanance drug didn't save ";
            }else{
                    echo "Maintanance drug saved";
            }
        }
    
}
if(isset($_POST['select_maintanance'])){
    $Registration_ID = $_POST['Registration_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];
    $select_maintanance = mysqli_query($conn, "SELECT Employee_ID, Maintanance_ID, Product_Name, Dose, time FROM  tbl_anaesthesia_maintanance ap, tbl_items i WHERE i.Item_ID=ap.Item_ID AND anasthesia_record_id='$anasthesia_record_id' AND   Registration_ID='$Registration_ID' ORDER BY anasthesia_record_id DESC  ") or die(mysqli_error($conn));
    $num=0;
    if(mysqli_num_rows($select_maintanance)>0){
        while($row = mysqli_fetch_assoc($select_maintanance)){
            $Employee_ID  = $row['Employee_ID'];
            $Maintanance_ID = $row['Maintanance_ID'];
            $Product_Name = $row['Product_Name'];
            $Dose = $row['Dose'];
            $time = $row['time'];
            $num++;
            echo "<tr><td>$num</td>";
            echo "<td>$Product_Name</td><td><input type='text' id='maintainance_dose_$Maintanance_ID' placeholder='Enter Dose' value='$Dose' onkeyup='update_maintanance_dose($Maintanance_ID)'></td><td><input type='text' id='maintainance_time_$Maintanance_ID' value='$time' placeholder='Enter time' onkeyup='update_maintanance_time($Maintanance_ID)'></td><td><button class='btn btn-danger' type='button' name='removemaintanance' onclick='remove_maintanance($Maintanance_ID, $Employee_ID)'>X</button></td></tr>";

        }
    }
}

if(isset($_POST['removemaintanance'])){
    $Employee_ID = $_POST['Employee_ID'];
    $Maintanance_ID = $_POST['Maintanance_ID'];

    $delete_pre_med = mysqli_query($conn, "DELETE FROM tbl_anaesthesia_maintanance WHERE  Employee_ID='$Employee_ID' AND Maintanance_ID='$Maintanance_ID'") or die(mysqli_error($conn));
    if(!$delete_pre_med){
            echo "Not deleted. You have no access to delete this medication";
    }else{
            echo "Deleted successful";
    }
}

if(isset($_POST['updatetimemaintanance'])){
    $time = $_POST['time'];
    $Maintanance_ID = $_POST['Maintanance_ID'];

    $update_time = mysqli_query($conn, "UPDATE tbl_anaesthesia_maintanance SET  time='$time' WHERE Maintanance_ID='$Maintanance_ID'") or die(mysqli_error($conn));

    if(!$update_time){
            echo "Not updated";
    }else{
            echo "Updated successful";
    }
}

if(isset($_POST['updatedosemaintanance'])){
    $Dose = $_POST['dose'];
    $Maintanance_ID = $_POST['Maintanance_ID'];

    $update_Dose = mysqli_query($conn, "UPDATE tbl_anaesthesia_maintanance SET  Dose='$Dose' WHERE Maintanance_ID='$Maintanance_ID'") or die(mysqli_error($conn));

    if(!$update_Dose){
            echo "Not updated";
    }else{
            echo "Updated successful";
    }
}


if(isset($_POST['BP'])){
    $Registration_ID = $_POST['Registration_ID'];
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];

    $fx = $_POST['fx'];
    $fy = $_POST['fy'];
    $fz = $_POST['fz'];
    $insert_BP = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_bp_readings(Registration_ID,anasthesia_record_id,Employee_ID, fx,fy, fz)
    VALUES('$Registration_ID','$anasthesia_record_id','$Employee_ID','$fx','$fy', '$fz')") or die(mysqli_errno($conn));
    if(!$insert_BP){
        echo "Could not insert BP readings";
    }else{
        echo "ok";
    }
 
}

if(isset($_POST['HR'])){
    $Registration_ID = $_POST['Registration_ID'];
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];
   
    $sx = $_POST['sx'];
    $sy = $_POST['sy'];    
    $insert_HR = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_hr_readings(Registration_ID,anasthesia_record_id,Employee_ID, sx,sy)VALUES('$Registration_ID','$anasthesia_record_id','$Employee_ID','$sx','$sy')") or die(mysqli_errno($conn));
    if(!$insert_HR){
        echo "Could not insert HR readings";
    }else{
        echo "ok";
    }
}

if(isset($_POST['MAP_insert'])){
    $Registration_ID = $_POST['Registration_ID'];    
    $anasthesia_record_id = $_POST['anasthesia_record_id'];
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $zx = $_POST['zx'];
    $zy = $_POST['zy'];  
    
    $insert_MAP = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_map_readings(Registration_ID,anasthesia_record_id,Employee_ID, zx,zy) VALUES('$Registration_ID','$anasthesia_record_id','$Employee_ID','$zx','$zy')") or die(mysqli_errno($conn));
    if(!$insert_MAP){
        echo "Could not insert MAP readings";
    }else{
        echo "ok";
    }
}


if(isset($_POST['HRreadings'])){
    $Registration_ID = $_POST['Registration_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];

    $select_hr_readings  = "SELECT sx,sy FROM tbl_anaesthesia_hr_readings WHERE
                               Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'";
                              $data  = array();

  if ($result=mysqli_query($conn,$select_hr_readings)) {

        if (($num = mysqli_num_rows($result)) > 0) {
            $d = array();
            while ($row = mysqli_fetch_assoc($result)) {
            $d['sx'] = $row['sx'];
            $d['sy'] = $row['sy'];
            array_push($data,$d);
        }    

    echo json_encode($data);
    }else{
      echo mysqli_error($conn);
    }

  }else {
    echo mysqli_error($conn);
  }
}

if(isset($_POST['MAPReading'])){
    $Registration_ID = $_POST['Registration_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];

    $select_hr_readings  = "SELECT zx,zy FROM tbl_anaesthesia_map_readings WHERE
                               Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'";
                              $data  = array();

  if ($result=mysqli_query($conn,$select_hr_readings)) {

        if (($num = mysqli_num_rows($result)) > 0) {
            $d = array();
            while ($row = mysqli_fetch_assoc($result)) {
            $d['zx'] = $row['zx'];
            $d['zy'] = $row['zy'];
            array_push($data,$d);
        }    

    echo json_encode($data);
    }else{
      echo mysqli_error($conn);
    }

  }else {
    echo mysqli_error($conn);
  }
}


if(isset($_POST['BPreadings'])){
    $Registration_ID = $_POST['Registration_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];
  
    $select_bp_readings  = "SELECT fy,fx, fz FROM tbl_anaesthesia_bp_readings WHERE Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'";
                              $data  = array();

  if ($resultsbp=mysqli_query($conn,$select_bp_readings)) {

        if (($num = mysqli_num_rows($resultsbp)) > 0) {
            $d = array();
            while ($rowbp = mysqli_fetch_assoc($resultsbp)) {
            $d['fx'] = $rowbp['fx'];
            $d['fy'] = $rowbp['fy'];
            $d['fz'] = $rowbp['fz'];

            array_push($data,$d);
        }    

    echo json_encode($data);
    }else{
      echo mysqli_error($conn);
    }

  }else {
    echo mysqli_error($conn);
  }
}


if(isset($_POST['view_mntainance_vitals'])){
    $Registration_ID = $_POST['Registration_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];
    
    $select_maintanance_vital = mysqli_query($conn, "SELECT * FROM tbl_anaesthesia_maintanance_vital av WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
    if((mysqli_num_rows($select_maintanance_vital))>0){
         while($Vitals_meaintanance_rw= mysqli_fetch_assoc($select_maintanance_vital)){ ?>
            <tr>
            <td> <input type="text"  class="form-control" readonly="readonly" value=" <?php echo $Vitals_meaintanance_rw['SPO2']; ?>"> </td>
            <td> <input type="text"   class="form-control" readonly="readonly" value=" <?php echo $Vitals_meaintanance_rw['ETCO2']; ?>"> </td>
            <td> <input type="text"  class="form-control" readonly="readonly" value=" <?php echo $Vitals_meaintanance_rw['ECG']; ?>"> </td>
            <td> <input type="text"   class="form-control" readonly="readonly" value=" <?php echo $Vitals_meaintanance_rw['Temp']; ?>"> </td>
            <td> <input type="text"   class="form-control" readonly="readonly" value=" <?php echo $Vitals_meaintanance_rw['Fluids_bt']; ?>"> </td>
            <td> <input type="text"   class="form-control" readonly="readonly" value=" <?php echo $Vitals_meaintanance_rw['MAC']; ?>"> </td>
            <td><button class="btn btn-success" type="submit" id='vent' disabled>Saved</button></td>
        </tr><?php
         }
    } ?>
        <tr>
            <td> <input type="text" id="SPO2"  class="form-control" value=""> </td>
            <td> <input type="text" id="ETCO2"  class="form-control" value=""> </td>
            <td> 
                <select name="" id="ECG" class="form-control">
                    <option value="NSR">Normal sinus Rhythm</option>
                    <option value="ARRHY">Arrhythmia ARRHY</option>
                </select>
            </td>
            <td> <input type="text" id="Temp"  class="form-control" value=""> </td>
            <td> <input type="text" id="Fluids_bt"  class="form-control" value=""> </td>
            <td> <input type="text" id="MAC"  class="form-control" value=""> </td>
            <td><button class="btn btn-info" type="button" id='Vitals_meaintanance' onclick="add_Vitals_meaintanance()">Save</button></td>
        </tr>
    <?php
}

//insert maintanance vitals
if(isset($_POST['Vitals_meaintanance_add'])){
    $SPO2 = mysqli_real_escape_string($conn,  $_POST["SPO2"]);
    $ETCO2 = mysqli_real_escape_string($conn,  $_POST["ETCO2"]);
    $ECG = mysqli_real_escape_string($conn,  $_POST["ECG"]);
    $Temp = mysqli_real_escape_string($conn,  $_POST["Temp"]);
    $Temp = mysqli_real_escape_string($conn,  $_POST["Temp"]);
    $Fluids_bt = mysqli_real_escape_string($conn,  $_POST["Fluids_bt"]);
    $MAC = mysqli_real_escape_string($conn,  $_POST["MAC"]);
    $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 
    $anasthesia_record_id_Post = $_POST['anasthesia_record_id'];
    $Registration_ID = $_POST['Registration_ID'];
    //select anesthesia record id
    $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress' ORDER BY anasthesia_record_id DESC LIMIT 1";
    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
    if(mysqli_num_rows($anasthesia_record_result)>0){
        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
    }else{
        $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
        $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
        $anasthesia_record_id=mysqli_insert_id($conn);
        
    }
    
    $renalto_medication = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_maintanance_vital ( SPO2, ETCO2, ECG,Temp,  Fluids_bt,   MAC, anasthesia_record_id, Registration_ID, Employee_ID) VALUES ( '$SPO2', '$ETCO2', '$ECG', '$Temp','$Fluids_bt',  '$MAC',  '$anasthesia_record_id_Post', '$Registration_ID', '$Employee_ID')") or die("Couldn't insert data ".mysqli_error($conn));
    if(!$renalto_medication){
        echo  "fail";
    } else{        
        echo "success";
    }
}

if(isset($_POST['vital_mantainance'])){?>
    <table width="100%" style="scroll:auto; height:auto;">
        <thead>
        <tr>
            <th>SPO2</th>
            <th>ETCO2</th>
            <th>ECG</th>
            <th>Temp</th>
            <th>Fluids/BT</th>
            <th>MAC</th>                
            <th>Action</th>
        </tr>
        </thead>
        <tbody id='vital_maintanance_body'>
        </bdody>
    </table>
    <?php
}

    if(isset($_POST['recovery_form_dialogy'])){?>
        <table class="table">
            <thead>
                <tr>
                    <th>Via Epidural</th>
                    <th>Crystalide</th>
                    <th>Colloid</th>
                    <th>Blood</th>
                    <th>Losses</th>
                    <th>Urine Output</th>
                    <th>Blood Loss</th>
                    <th>Others</th>
                    <th>OPIOID</th>
                    <th>Volatile Agent</th>
                </tr>
                <tr>
                    <td> <input type="text" id="Via_Epidural"  class="form-control" value=""> </td>
                    <td> <input type="text" id="Crystalide"  class="form-control" value=""> </td>
                    <td> <input type="text" id="Colloid"  class="form-control" value=""> </td>
                    <td> <input type="text" id="Blood"  class="form-control" value=""> </td>
                    <td> <input type="text" id="Losses"  class="form-control" value=""> </td>
                    <td> <input type="text" id="Urine_Output"  class="form-control" value=""> </td>
                    <td> <input type="text" id="Blood_Loss"  class="form-control" value=""> </td>
                    <td> <input type="text" id="Others"  class="form-control" value=""> </td>
                    <td> <input type="text" id="OPIOID"  class="form-control" value=""> </td>
                    <td> <input type="text" id="Volatile_Agent"  class="form-control" value=""> </td>


                    <td><button class="btn btn-info" type="button" id='Vitals_meaintanance' onclick="recovery_form_save()">Save</button></td>
                </tr>
            </thead>
            <tbody id="recoveryformdata">
                
            </tbody>
        </table>
    <?php
    }

    if(isset($_POST['insert_recovery_data'])){
        $Volatile_Agent = $_POST['Volatile_Agent'];
        $Via_Epidural = mysqli_real_escape_string($conn,  $_POST["Via_Epidural"]);
        $Crystalide = mysqli_real_escape_string($conn,  $_POST["Crystalide"]);
        $Colloid = mysqli_real_escape_string($conn,  $_POST["Colloid"]);
        $Blood = mysqli_real_escape_string($conn,  $_POST["Blood"]);
        $Losses = mysqli_real_escape_string($conn,  $_POST["Losses"]);
        $Urine_Output = mysqli_real_escape_string($conn,  $_POST["Urine_Output"]);
        $Blood_Loss = mysqli_real_escape_string($conn,  $_POST["Blood_Loss"]);
        $Others = mysqli_real_escape_string($conn,  $_POST["Others"]);
        $OPIOIDs = mysqli_real_escape_string($conn,  $_POST["OPIOIDs"]);
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $Registration_ID = $_POST['Registration_ID'];
        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
        $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
        if(mysqli_num_rows($anasthesia_record_result)>0){
            $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
        }else{
            $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
            $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
            $anasthesia_record_id=mysqli_insert_id($conn);
            
        }
        $recovery_data = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_recovery( Via_Epidural,Volatile_Agent, Crystalide, Colloid,Blood,  Losses,
        Urine_Output,Blood_Loss,Others,OPIOIDs, anasthesia_record_id, Registration_ID, Employee_ID) 
        VALUES ( '$Via_Epidural','$Volatile_Agent', '$Crystalide', '$Colloid', '$Blood','$Losses',  '$Urine_Output', '$Blood_Loss','$Others', '$OPIOIDs', '$anasthesia_record_id', '$Registration_ID', '$Employee_ID')") or die("Couldn't insert data ".mysqli_error($conn));
        if(!$recovery_data){
            echo  "fail";
        } else{
             echo "success";
        }
    }

    if(isset($_POST['view_recovery_form'])){
        $Registration_ID = $_POST['Registration_ID'];
        $anasthesia_record_id = $_POST['anasthesia_record_id'];
        $select_recovery = mysqli_query($conn, "SELECT * FROM tbl_anaesthesia_recovery WHERE Registration_ID='$Registration_ID' AND  anasthesia_record_id='$anasthesia_record_id' ORDER BY Recovery_ID DESC") or die(mysqli_error($conn)); 
        
        if(mysqli_num_rows($select_recovery)>0){
            while($rows = mysqli_fetch_assoc($select_recovery)){
                $Via_Epidural = $rows['Via_Epidural'];
                $Crystalide = $rows['Crystalide'];
                $Colloid = $rows['Colloid'];
                $Blood = $rows['Blood'];
                $Losses = $rows['Losses'];
                $Urine_Output = $rows['Urine_Output'];
                $Blood_Loss = $rows['Blood_Loss'];
                $Others = $rows['Others'];
                $OPIOIDs = $rows['OPIOIDs'];
                $created_at = $rows['created_at'];

                echo "<tr>
                    <td> <input type='text' value='$Via_Epidural'  class='form-control' readonly > </td>
                    <td> <input type='text' value='$Crystalide'  class='form-control' readonly > </td>
                    <td> <input type='text' value='$Colloid'  class='form-control' readonly > </td>
                    <td> <input type='text' value='$Blood'  class='form-control' readonly > </td>
                    <td> <input type='text' value='$Losses'  class='form-control' readonly > </td>
                    <td> <input type='text' value='$Urine_Output'  class='form-control' readonly > </td>
                    <td> <input type='text' value='$Blood_Loss'  class='form-control' readonly > </td>
                    <td> <input type='text' value='$Others'  class='form-control' readonly > </td>
                    <td> <input type='text' value='$OPIOIDs'  class='form-control' readonly > </td>
                    <td>$created_at</td>
                </tr>";

            }
        }else{
            echo "No data found";
        }
    }
    if(isset($_POST['icu_form'])){
        $Sub_Department_ID = mysqli_real_escape_string($conn,  $_POST["Sub_Department_ID"]);
        $Provisional_diagnosis = mysqli_real_escape_string($conn,  $_POST["Provisional_diagnosis"]);
        $Registration_ID = $_POST['Registration_ID'];
        $Reason_for_transfer = mysqli_real_escape_string($conn,  $_POST["Reason_for_transfer"]);
        $investigation_done = mysqli_real_escape_string($conn,  $_POST["investigation_done"]);
        $Spececialist_ID = mysqli_real_escape_string($conn,  $_POST["Spececialist_ID"]);
        $Infromed_date = mysqli_real_escape_string($conn,  $_POST["Infromed_date"]);
        $Referred_by = mysqli_real_escape_string($conn,  $_POST["Referred_by"]);
        $referred_date = mysqli_real_escape_string($conn,  $_POST["referred_date"]);
        $Following_treatment = $_POST['Following_treatment'];
        $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 

        //select anesthesia record id
        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
        $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
        if(mysqli_num_rows($anasthesia_record_result)>0){
            $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
        }else{
            $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
            $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
            $anasthesia_record_id=mysqli_insert_id($conn);
                
        }
        $Admission_ID  =mysqli_fetch_assoc(mysqli_query($conn, "SELECT Admission_ID FROM tbl_admission WHERE Registration_ID='$Registration_ID' ORDER BY Admission_ID DESC LIMIT 1"))['Admission_ID'];
        $send_to_icu = mysqli_query($conn, "INSERT INTO tbl_anasthesia_icuform (Sub_Department_ID,Following_treatment, Admission_ID,consultation_ID, Provisional_diagnosis, Registration_ID, Reason_for_transfer,investigation_done, Spececialist_ID, Infromed_date,
        Referred_by,referred_date, Saved_by, 
        anasthesia_record_id) 
        VALUES ('$Sub_Department_ID','$Following_treatment','$Admission_ID','$consultation_ID', '$Provisional_diagnosis', '$Registration_ID', '$Reason_for_transfer', '$investigation_done', '$Spececialist_ID',
         '$Infromed_date',  '$Referred_by', '$referred_date', 
        '$Employee_ID', '$anasthesia_record_id' )") or die(mysqli_error($conn));
        if(!$send_to_icu){
            die("Couldn't insert data ".mysqli_error($conn));
        } else{
            echo "Warrant Sent";       
         }
    }

    if(isset($_POST['icuformdialog'])){
        $Registration_ID = $_POST['Registration_ID'];
        $consultation_ID = $_POST['consultation_ID'];
        $select_icu_form = mysqli_query($conn, "SELECT * FROM  tbl_anasthesia_icuform WHERE Registration_ID='$Registration_ID' AND consultation_ID='$consultation_ID'") or die(mysqli_error($conn));
        while($row = mysqli_fetch_assoc($select_icu_form)){
            $Provisional_diagnosis = $row['Provisional_diagnosis'];
            $investigation_done = $row['investigation_done'];
            $Reason_for_transfer = $row['Reason_for_transfer'];
            $referred_date = $row['referred_date'];
            $Infromed_date = $row['Infromed_date'];
            $Spececialist_ID = $row['Spececialist_ID'];
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $Referred_by = $row['Referred_by'];
        
        ?>
        <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="panel">               
                <div class="panel-body">
                    <div class="row" style="text-align: center;">
                        <label for="">Purpose:</label>
                        <span>This form needs to be filled in and sent to ICU before referring this patient</span>
                    </div>
                    <div class="row" style="text-align: center;">
                        <label for="">Preferred:</label>
                        <span>This patient should be reviewed by ICU specialist/Anesthesiologist prior to admission </span>
                    </div>
                    <div class="row" style="text-align: center;">
                        <label for="">Emergency Patient:</label>
                        <span>Inform the ICU team, you can fill in the form upon arrival to ICU. </span>
                    </div>
                    <br>
                    <div class="row">
                        <label for="">Patient from Where:</label>
                        <span>                            
                            <?php 
                                $Sub_Department_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$Sub_Department_ID'"))['Sub_Department_Name'];

                                echo "<u>".$Sub_Department_Name ."</u>";                                
                            ?>
                        </span>
                    </div>
                    <br>
                    <div class="row">
                        <label for="">Provisional Diagnosis:</label>
                        <span> 
                            <?php 
                                echo "<u>". $Provisional_diagnosis ."</u>";
                            ?>
                        </span>
                    </div>
                    <br>
                    <div class="row">
                        <label for="">Reason for Transfer to ICU:</label>
                        <span> 
                            <u><?= $Reason_for_transfer ?></u>
                        </span>
                    </div>
                    <br>
                    <div class="row">
                        <label for="">Patient is under the following Treatment: </label>
                        <span> 
                          <u>  <?= $Following_treatment ?></u>
                        </span>
                    </div>
                    <br>
                    <div class="row">
                        <label for="">What investigation have been done, any need of followup of result?:</label>
                        <span> 
                            <u><?= $investigation_done ?></u>
                        </span>
                    </div>
                    <br>
                    <div class="row">
                        <p>If the patient is not reffered by the responsible specialists/Consultant himself/Consultant has been informed about the transfer.</p>                        
                    </div>
                    <br>
                    <div class="row">          
                        <label for="">Specialist/Consultant name:</label>                        
                            <?php 
                                $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Spececialist_ID'"))['Employee_Name'];
                                echo "<u>".$Employee_Name  ."</u>";
                            ?>
                           
                        <label for="">Date Informed: </label>
                        <u><?= $Infromed_date ?></u>
                    </div>
                    <br>
                    <div class="row">
                        <label for=""> Referred By:</label>
                        <span> 
                            <?php 
                                $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Referred_by'"))['Employee_Name'];
                                echo "<u>". $Employee_Name ."</u>";                            
                            ?>
                        </span>
                        <label for="">Date Referred: </label>
                        <span>
                            <u><?= $referred_date ?></u>
                            <!-- <input type="button" value="PRINT PDF" class="art-button-green" onclick="save_anaesthesia_icu_form()"> -->
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
        }
    }

