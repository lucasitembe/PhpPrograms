<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['Disposal_ID'])){
        $Disposal_ID = $_SESSION['Disposal_ID'];
    }else{
        $Disposal_ID = 0;
    }
    
    if($Disposal_ID != 0 && $Disposal_ID != '' && $Disposal_ID != null){
        //check if there is at least one item
        $get_details = mysqli_query($conn,"select Disposal_Item_ID from tbl_disposal_items where
                                   Disposal_ID = '$Disposal_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($get_details);
        if($num > 0){
?>
            <table width=100%>
                <tr>
                        <td style='text-align: right;'>Supervisor Username</td>
                        <td>
                                <input type='text' name='Supervisor_Username' title='Supervisor Username' id='Supervisor_Username' autocomplete='off' placeholder='Supervisor Username' required='required'>
                        </td>
                        <td style='text-align: right;'>Supervisor Password</td>
                        <td>
                                <input type='password' title='Supervisor Password' name='Supervisor_Password' id='Supervisor_Password' autocomplete='off' placeholder='Supervisor Password' required='required'>
                        </td>
                        <td style='text-align: right;'>
                                <input type='button' class='art-button-green' value='SUBMIT DISPOSAL' onclick='Confirm_Submit_Disposal()'>
                        </td>
                </tr>
            </table>
<?php
        }
    }
?>