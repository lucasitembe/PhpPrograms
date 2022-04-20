<?php
//Item_ID:Item_ID
include("./includes/connection.php");
if (isset($_POST['Item_ID'])) {
    $Item_ID = $_POST['Item_ID'];
} else {
    $Item_ID = "";
}


?>


<table width=100%>
    <?php
        if($Item_ID == 2){
            $select_result = mysqli_query($conn,"SELECT * FROM tbl_clinic");
            
                   while($row = mysqli_fetch_assoc($select_result)){
                      $Clinic_Name=$row['Clinic_Name'];
                      $Clinic_ID=$row['Clinic_ID'];
        echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left;'>";
        ?> 
        <input type='radio' name='selection' onclick="update_display('<?= $Clinic_Name ?>','<?= $Clinic_ID ?>','clinics')" id='<?php echo $row['Clinic_ID']; ?>' value='<?php echo $row['Clinic_ID']; ?>'>
        <?php
        echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Clinic_ID'] . "'>" . $row['Clinic_Name'] . "</label></td></tr>";
    }
        }else if($Item_ID == 1){
            $select_result = mysqli_query($conn,"SELECT * FROM  tbl_employee WHERE Employee_Type='Doctor'");
            
                   while($row = mysqli_fetch_assoc($select_result)){
                                    $Employee_Name =$row['Employee_Name'];
                                    $Employee_ID =$row['Employee_ID'];
        echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left;'>";
        ?> 
        <input type='radio' name='selection' onclick="update_display('<?= $Employee_Name ?>','<?= $Employee_ID ?>','doctors')" id='<?php echo $row['Employee_ID']; ?>' value='<?php echo $row['Employee_ID']; ?>'>
        <?php
        echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Employee_ID'] . "'>" . $row['Employee_Name'] . "</label></td></tr>";
    }
            
        }else {
            
            
//        $select_result = mysqli_query($conn,"SELECT * FROM tbl_clinic");
//            
//           while($row = mysqli_fetch_assoc($select_result)){
//                 $Clinic_Name=$row['Clinic_Name'];
//                 $Clinic_ID=$row['Clinic_ID'];
//        echo "<tr>
//        
//                    <td style='color:black; border:2px solid #ccc;text-align: left;'>";
//        ?> 
        <!--<input type='radio' name='selection' onclick="update_display('//<?= $Clinic_Name ?>','<?= $Clinic_ID ?>','clinics')" id='<?php echo $row['Clinic_ID']; ?>' value='<?php echo $row['Clinic_ID']; ?>'>-->
        <?php
//        echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Clinic_ID'] . "'>" . $row['Clinic_Name'] . "</label></td></tr>";
//        }
        }
  
    ?> 
</table>