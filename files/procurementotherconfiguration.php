<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    } 
    
    //get branch id
	if(isset($_SESSION['userinfo']['Branch_ID'])){
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
		$Branch_ID = 0;
	}
	
?>
<?php
    if(isset($_SESSION['userinfo'])){ ?>
        <a href='procurementconfiguration.php?ProcurementConfigurations=ProcurementConfigurationsThisForm' class='art-button-green'>BACK</a>
<?php } ?>


<?php
    $select = mysqli_query($conn,"select Departmental_Stock_Movement from tbl_system_configuration") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Departmental_Stock_Movement = $data['Departmental_Stock_Movement'];
        }
    }else{
        $Departmental_Stock_Movement = 'yes';
    }
    
    $select1 = mysqli_query($conn,"select Editable_Quantity_Received,enable_receive_by_package from tbl_system_configuration WHERE Branch_ID='$Branch_ID'") or die(mysqli_error($conn));
    $row=  mysqli_fetch_assoc($select1);
    $Editable=$row['Editable_Quantity_Received'];
    $enable_receive_by_package=$row['enable_receive_by_package'];
?>


<br/><br/><br/><br/>
<fieldset>  
    <legend align=center><b>STOCK MOVEMENT CONFIGURATIONS</b></legend>
        <center>
            <table width = "75%">
                <tr>
                    <td style='text-align: center;'>
                        <input type="radio" name="Dept_Stock_Movement" id="Dept_Stock_Movement" value="yes" <?php if(strtolower($Departmental_Stock_Movement) == 'yes'){ echo "checked='checked'"; } ?> disabled="disabled" title="Stock movement within department">
                        <label for="Dept_Stock_Movement" title="Stock movement within department"><?php if(strtolower($Departmental_Stock_Movement) == 'yes'){ echo "<b>"; } ?>DEPARTMENTAL STOCK MOVEMENT</label>
                    </td>
                    <td style='text-align: center;'>
                        <input type="radio" name="Dept_Stock_Movement" id="Dept_Stock_Movement" value="yes" <?php if(strtolower($Departmental_Stock_Movement) == 'no'){ echo "checked='checked'"; } ?> disabled="disabled" title="Centralized Stock movement">
                        <label for="Dept_Stock_Movement" title="Centralized Stock movement"><?php if(strtolower($Departmental_Stock_Movement) == 'no'){ echo "<b>"; } ?>CENTRALIZED STOCK MOVEMENT</label>
                    </td>
                    
                    <td style='text-align: center;'>
                        <span id="editable">
                            <?php
                                if($Editable=='no'){
                                   echo'<input type="checkbox" name="Dept_Stock_Movement" disabled="true" id="Quantity_Received">'; 
                                }  else {
                                    echo '<input type="checkbox" checked="true" disabled="true" name="Dept_Stock_Movement" id="Quantity_Received" >'; 
                                }
                            ?>
                           
                            <label for="Quantity_received">EDITABLE QUANTITY RECEIVED</label>
                        </span>
                    </td>
                    <td style='text-align: center;'>
                        <span id="editable">
                            <?php
                                if($enable_receive_by_package=='no'){
                                   echo'<input type="checkbox" name="enable_receive_by_package" disabled="true" id="enable_receive_by_package">'; 
                                }  else {
                                    echo '<input type="checkbox" checked="true" disabled="true" name="enable_receive_by_package" id="enable_receive_by_package" >'; 
                                }
                            ?>
                           
                            <label for="enable_receive_by_package">ENABLE RECEIVE BY PACKAGE</label>
                        </span>
                    </td>
                    <td width="15%" style="text-align: center;">
                        <a href="procurementotherconfsetting.php?ProcurementOtherConfSetting=ProcurementOtherConfSettingThisPage" class="art-button-green">CHANGE</a>
                    </td>
                </tr>
            </table>
        </center>
</fieldset><br/>

<?php
    include("./includes/footer.php");
?>