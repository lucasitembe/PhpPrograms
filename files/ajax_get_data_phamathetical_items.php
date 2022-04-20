<?php
include("./includes/connection.php");
if(isset($_POST['phamathetical_dataelement_id'])){
$phamathetical_dataelement_id=$_POST['phamathetical_dataelement_id'];
$sql_select_phamathetical_element_id_result=mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Consultation_Type='Pharmacy' AND Item_ID='$phamathetical_dataelement_id'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_phamathetical_element_id_result)>0){
        $count=1;
        while($data_elemetn_id_rows=mysqli_fetch_assoc($sql_select_phamathetical_element_id_result)){
            $phamathetical_dataelement_id=$data_elemetn_id_rows['Item_ID'];
            $displayname=$data_elemetn_id_rows['Product_Name'];
            echo "<tr>
                    <td>$displayname</td>
                 </tr>";
            $count++;
        }
    }
     echo "<tr><td><input type='text' value='$phamathetical_dataelement_id' class='hide' placeholder='data element id' id='selected_Phamathetical_dataelement_id'/></td></tr>";
}
?>
