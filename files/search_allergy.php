
<?php
    include("./includes/connection.php");
    
    if(isset($_POST['Search_allergy'])){
        $Search_allergy = $_POST['Search_allergy'];
        $search_result= mysqli_query($conn,"SELECT *from allergies WHERE allergies_Name LIKE '%$Search_allergy%'") or die(mysqli_error($conn));
        if(mysqli_num_rows($search_result)>0){
        while($row = mysqli_fetch_array($search_result)){
            $allergies_Name=$row['allergies_Name'];
            // $temp++;
            ?>
            <tr>
            <td width="10%">
                <input style="width:30px;height:30px;pointer:cursor; margin-left:15px;"type="checkbox" name="select_allergy[]"  id="select_allergy" value="<?php echo strtoupper($row['allergies_Name']); ?>" >
            </td>
            <td>
                <label style="padding-left:20px;"> <?php echo strtoupper($row['allergies_Name']); ?></label>
            </td>
            </tr>
       <?php
        }
        } else{
            echo "<tr><td colspan='3'>No result found</td></tr>";
        }
}

if(isset($_POST['load_allergy'])){
    $search_result= mysqli_query($conn,"SELECT *from allergies ") or die(mysqli_error($conn));
    if(mysqli_num_rows($search_result)>0){
    while($row = mysqli_fetch_array($search_result)){
        $allergies_Name=$row['allergies_Name'];
        // $temp++;
        ?>
        <tr>
        <td width="10%">
            <input style="width:30px;height:30px;pointer:cursor; margin-left:15px;"type="checkbox" name="select_allergy[]"  id="select_allergy" value="<?php echo strtoupper($row['allergies_Name']); ?>" >
        </td>
        <td>
            <label style="padding-left:20px;"> <?php echo strtoupper($row['allergies_Name']); ?></label>
        </td>
        </tr>
   <?php
    }
    } else{
        echo "<tr><td colspan='3'>No result found</td></tr>";
    }
}
?>