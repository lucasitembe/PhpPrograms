<?php
include("./includes/connection.php");
if(isset($_POST['dhis2_dataelement_id'])){
$dhis2_dataelement_id=$_POST['dhis2_dataelement_id'];
$dataset_id=$_POST['dataset_id'];
$sql_select_data_element_id_result=mysqli_query($conn,"SELECT dhis2_dataelement_id,displayname FROM tbl_dhis2_dataelements WHERE dataset_id='$dataset_id' AND dhis2_dataelement_id='$dhis2_dataelement_id'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_data_element_id_result)>0){
        $count=1;
        while($data_elemetn_id_rows=mysqli_fetch_assoc($sql_select_data_element_id_result)){
            $dhis2_dataelement_id=$data_elemetn_id_rows['dhis2_dataelement_id'];
            $displayname=$data_elemetn_id_rows['displayname'];
            echo "<tr>
                    <td>$count.</td>
                    <td>$displayname</td>
                 </tr>";
            $count++;
        }
    }
     echo "<tr><td><input type='text' value='$dhis2_dataelement_id' class='hide' placeholder='data element id' id='selected_dhis2_dataelement_id'/></td></tr>";
}
?>