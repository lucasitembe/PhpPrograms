<?php
    session_start();
    include("./includes/connection.php");

    if(isset($_GET['Payment_Item_Cache_List_ID'])){
        $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    }else{
        $Payment_Item_Cache_List_ID = '';
    }

    if(isset($_GET['Participant_ID'])){
        $Participant_ID = $_GET['Participant_ID'];
    }else{
        $Participant_ID = 0;
    }


    //delete selected surgeon
    $delete = mysqli_query($conn,"delete from tbl_post_operative_participant where Participant_ID = '$Participant_ID'") or die(mysqli_error($conn));
    //Select Post_operative_ID
    $select = mysqli_query($conn,"select Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Post_operative_ID = $data['Post_operative_ID'];
        }
    }else{
        $Post_operative_ID = 0;
    }

?>
<table width="100%">
    <tr>
        <td width="4%"><b>SN</b></td>
        <td><b>&nbsp;&nbsp;&nbsp;SURGEON NAME</b></td>
        <td width="12%" style="text-align: center;"><b>ACTION</b></td>
    </tr>                                            
    <tr><td colspan="3"><hr></td></tr>
<?php
    //select selected surgeons
    $selected_surgeon = mysqli_query($conn,"select emp.Employee_Name, Participant_ID from tbl_post_operative_participant pop, tbl_employee emp where
                                        pop.Post_operative_ID = '$Post_operative_ID' and
                                        pop.Employee_Type = 'Surgeon' and
                                        pop.Employee_ID = emp.Employee_ID") or die(mysqli_error($conn));
    $num_selected_surgeons = mysqli_num_rows($selected_surgeon);
    if($num > 0){
        $temp = 0;
        while ($dtz = mysqli_fetch_array($selected_surgeon)) {
    ?>
        <tr>
            <td><?php echo ++$temp; ?></td>
            <td>&nbsp;&nbsp;&nbsp;<?php echo strtoupper($dtz['Employee_Name']); ?></td>
            <td width="12%" style="text-align: center;">
                <input type="button" name="SelectedSurgeon" id="SelectedSurgeon" value="REMOVE" class="art-button-green" onclick="Remove_Surgeon(<?php echo $dtz['Participant_ID']; ?>)">
            </td>
        </tr>
    <?php 
        }
    }
?>
</table>